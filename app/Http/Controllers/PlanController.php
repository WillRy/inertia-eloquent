<?php

namespace App\Http\Controllers;

use App\Http\Requests\PlanStoreUpdateRequest;
use App\Models\Plan;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PlanController extends Controller
{
    public function index(Request $request)
    {
        return Inertia::render("Plans/Index");
    }

    public function list(Request $request)
    {
        $filters = [
            "search" => $request->input("search"),
            "sortName" => $request->input("sortName", "id"),
            "sortOrder" => $request->input("sortOrder", "asc")
        ];
        $plans = (new Plan())->searchPlans($filters);
        return $this->responseJSON($plans);
    }

    public function store(PlanStoreUpdateRequest $request)
    {
        try {
            Plan::create($request->all());

            return $this->successInertia("Plano criado com sucesso!");
        } catch (\Exception $e) {
            return $this->errorInertia($e->getMessage());
        }
    }

    public function show(Request $request, $id)
    {
        try {
            $plan = Plan::tenant()->where("id", "=", $id)->first();

            if(empty($plan)) {
                return response()->json([
                    "error" => "Not found"
                ], 404);
            }

            return $this->responseJSON($plan);
        } catch (\Exception $e) {
            return $this->errorJSON($e->getMessage());
        }
    }

    public function update(PlanStoreUpdateRequest $request, $id)
    {
        try {
            $plan = Plan::tenant()->where("id", "=", $id)->first();

            if(empty($plan)) {
                return response()->json([
                    "error" => "Not found"
                ], 404);
            }

            $plan->fill($request->all());
            $plan->save();


            return $this->successInertia("Plano editado com sucesso!");
        } catch (\Exception $e) {
            return $this->errorInertia($e->getMessage());
        }
    }

    public function destroy(Request $request, $id)
    {
        try {
            $plan = Plan::tenant()->where("id", "=", $id)->first();

            if(empty($plan)) {
                return $this->errorInertia("Plano não encontrado");
            }

            if ((new Subscription())->subscriptionExistsByPlan($id)) {
                return $this->errorInertia("Plano usado em alguma matrícula");
            }

            $plan->delete();


            return $this->successInertia("Plano excluído com sucesso!");
        } catch (\Exception $e) {
            return $this->errorInertia($e->getMessage());
        }
    }

}
