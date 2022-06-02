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
        $filters = ["search" => $request->input("search")];
        $plans = (new Plan())->searchPlans($filters);
        return response()->json($plans);
    }

    public function store(PlanStoreUpdateRequest $request)
    {
        try {
            $student = Plan::create($request->all());

            return response()->json([
                "data" => $student
            ]);
        } catch (\Exception $e) {
            return response()->json([
                "error" => $e->getMessage()
            ], 500);
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

            return response()->json([
                "data" => $plan
            ]);
        } catch (\Exception $e) {
            return response()->json([
                "error" => $e->getMessage()
            ], 500);
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


            return response()->json([
                "data" => $plan
            ]);
        } catch (\Exception $e) {
            return response()->json([
                "error" => $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Request $request, $id)
    {
        try {
            $plan = Plan::tenant()->where("id", "=", $id)->first();

            if(empty($plan)) {
                return response()->json([
                    "error" => "Not found"
                ], 404);
            }

            if ((new Subscription())->subscriptionExistsByPlan($id)) {
                return response()->json([
                    "error" => "Plano usado em alguma matrícula"
                ], 422);
            }

            $plan->delete();


            return response()->json([], 204);
        } catch (\Exception $e) {
            return response()->json([
                "error" => $e->getMessage()
            ], 500);
        }
    }

}
