<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUpdateSubscriptionRequest;
use App\Models\Plan;
use App\Models\Subscription;
use Carbon\Carbon;
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

            return response()->json([
                "data" => $subs
            ]);
        } catch (\Exception $e) {
            return response()->json([
                "error" => $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $subs = (new Subscription())->getSubscription($id);

            return response()->json([
                "data" => $subs
            ]);
        } catch (\Exception $e) {
            return response()->json([
                "error" => $e->getMessage()
            ], 500);
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

            return response()->json([
                "data" => (new Subscription())->getSubscription($sub->id),
                "success" => "Matrícula criada com sucesso!"
            ]);
        } catch (\Exception $e) {
            return response()->json([
                "error" => $e->getMessage()
            ], 500);
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

            return response()->json([
                "data" => (new Subscription())->getSubscription($subscription->id),
                "success" => "Matrícula editada com sucesso!"
            ]);
        } catch (\Exception $e) {
            return response()->json([
                "error" => $e->getMessage()
            ], 500);
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

            return response()->json([], 204);
        } catch (\Exception $e) {
            return response()->json([
                "error" => $e->getMessage()
            ], 500);
        }
    }
}
