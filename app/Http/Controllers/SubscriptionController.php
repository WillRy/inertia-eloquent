<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUpdateSubscriptionRequest;
use App\Models\Plan;
use App\Models\Subscription;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Inertia\Inertia;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function index()
    {
        return Inertia::render("Subscriptions/Index");
    }

    public function url(Request $request)
    {
        $plan = Plan::find($request->input("plan"));
        $filters = [
            "searchURL" => $request->input("search"),
            "planURL" => $plan
        ];

        return Inertia::render("Subscriptions/Url", $filters);
    }

    public function list(Request $request)
    {
        try {
            $subs = (new Subscription())->searchSubscription(
                $request->input("search"),
                $request->input("plan")
            );

            return $this->responseJSON($subs);
        } catch (\Exception $e) {
            return $this->errorJSON($e->getMessage());
        }
    }

    public function show($id)
    {
        try {
            $subs = (new Subscription())->getSubscription($id);

            return $this->responseJSON($subs);
        } catch (\Exception $e) {
            return $this->errorJSON($e->getMessage());
        }
    }

    public function store(StoreUpdateSubscriptionRequest $request)
    {
        try {

            $exists = (new Subscription())->subscriptionExists(
                $request->input("student_id"),
                $request->input("plan_id"),
            );

            if($exists) {
                throw new \Exception("Aluno já possui uma matrícula ativa");
            }

            $data = $request->all();

            $plan = Plan::findOrFail($data["plan_id"]);

            $data["dt_start"] = Carbon::make($data["dt_start"])->format("Y-m-d");
            $data["dt_end"] = Carbon::make($data["dt_start"])->addMonths($plan->duration)->format("Y-m-d");

            $sub = Subscription::create($data);

            $returnData = (new Subscription())->getSubscription($sub->id);

            return $this->successInertia('Matrícula criada com sucesso!',null, [], $returnData);

        } catch (\Exception $e) {
            return $this->errorInertia($e->getMessage());
        }
    }

    public function update(StoreUpdateSubscriptionRequest $request, Subscription $subscription)
    {
        try {
            $data = $request->all();

            $data["dt_start"] = Carbon::make($data["dt_start"])->format("Y-m-d");
            $data["dt_end"] = Carbon::make($data["dt_start"])->addMonths($subscription->plan->duration)->format("Y-m-d");

            $subscription->fill($data);
            $subscription->save();


            $returnData = (new Subscription())->getSubscription($subscription->id);

            return $this->successInertia("Matrícula editada com sucesso!",null, [], $returnData);
        } catch (\Exception $e) {
            return $this->errorInertia($e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $sub = Subscription::query()
                ->tenant()
                ->with(["student","plan"])
                ->where("id","=", $id)
                ->firstOrFail();

            $sub->delete();

            return $this->successInertia("Matrícula excluída com sucesso!");
        } catch (\Exception $e) {
            return $this->errorInertia($e->getMessage());
        }
    }
}
