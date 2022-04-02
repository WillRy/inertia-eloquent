<?php

namespace App\Http\Controllers;

use App\Http\Requests\PlanStoreUpdateRequest;
use App\Models\Plan;
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
            $student = Plan::tenant()->where("id", "=", $id)->first();

            if(empty($student)) {
                return response()->json([
                    "error" => "Not found"
                ], 404);
            }

            return response()->json([
                "data" => $student
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
            $student = Plan::tenant()->where("id", "=", $id)->first();

            if(empty($student)) {
                return response()->json([
                    "error" => "Not found"
                ], 404);
            }

            $student->fill($request->all());
            $student->save();


            return response()->json([
                "data" => $student
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
            $student = Plan::tenant()->where("id", "=", $id)->first();

            if(empty($student)) {
                return response()->json([
                    "error" => "Not found"
                ], 404);
            }

            $student->delete();


            return response()->json([], 204);
        } catch (\Exception $e) {
            return response()->json([
                "error" => $e->getMessage()
            ], 500);
        }
    }

}
