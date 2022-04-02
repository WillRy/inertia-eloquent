<?php

namespace App\Http\Controllers;


use App\Http\Requests\StudentStoreUpdateRequest;
use App\Models\Student;
use Illuminate\Http\Request;
use Inertia\Inertia;

class StudentController extends Controller
{
    public function index(Request $request)
    {

        $filters = [
            "gender" => $request->input("gender"),
            "search" => $request->input("search")
        ];

        $students = (new Student())->searchStudents($filters);

        return Inertia::render("Students/Index", [
            "students" => $students,
            "filters" => $filters,
            "page" => $request->input("page",1)
        ]);
    }

    public function store(StudentStoreUpdateRequest $request)
    {
        try {
            $student = Student::create($request->all());

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
            $student = Student::tenant()->where("id", "=", $id)->first();

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

    public function update(StudentStoreUpdateRequest $request, $id)
    {
        try {
            $student = Student::tenant()->where("id", "=", $id)->first();

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
            $student = Student::tenant()->where("id", "=", $id)->first();

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
