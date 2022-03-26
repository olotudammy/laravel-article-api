<?php

namespace App\Http\Controllers;

use App\Models\CourseRegistration;
use App\Models\Student;
use App\Traits\ApiResponder;
use Illuminate\Http\Request;

class StudentsController extends Controller
{
    use ApiResponder;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index($id)
    {
        try {
            $student = Student::query()
                ->with(["profile", "courseRegistrations"])
                ->where("id", $id)
                ->first();
            if (empty($student)) {
                return $this->failed("Student not found");
            }
            return $this->success($student, "created");
        }catch (\Exception $exception) {
            return $this->failed($exception->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $departments = config("larasoft.departments");
        $allowedDepartments = implode(",",$departments);
        $input = $request->validate([
            "first_name" => "required",
            "last_name" => "required",
            "matric_no" => "required|unique:students,matric_no",
            "department" => "required|in:$allowedDepartments"
        ]);

        try {
            $student = Student::create($input);
            return $this->success($student, "created");
        }catch (\Exception $exception) {
            return $this->failed($exception->getMessage());
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


    public function courseRegistration(Request $request)
    {
        $input = $request->validate([
            "student_id" => ["required", "exists:students,id"],
            "semester" => ["required", "in:1,2"],
            "session" => ["required", "in:2022"],
            "course_code" => ["required", "in:CSC 419,CSC 422"],
            "course_title" => ["required", "string"],
        ]);

        try {
            $courseRegistration = CourseRegistration::firstOrCreate([
                "student_id" => $input["student_id"],
                "course_code" => $input["course_code"]
            ], $input);
            return $this->success($courseRegistration, "created");
        }catch (\Exception $exception) {
            return $this->failed($exception->getMessage());
        }

    }
}
