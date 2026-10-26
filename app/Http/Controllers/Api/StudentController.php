<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class StudentController extends Controller
{
    // GET /api/students
    public function index()
    {
        return response()->json(Student::latest()->get());
    }

    // POST /api/students
    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_number' => 'required|string|max:50|unique:students,student_number',
            'first_name'     => 'required|string|max:100',
            'last_name'      => 'required|string|max:100',
            'email'          => 'required|email|max:255|unique:students,email',
            'course'         => 'required|string|max:100',
            'year_level'     => 'required|integer|between:1,6',
        ]);

        $student = Student::create($validated);

        return response()->json($student, 201);
    }

    // GET /api/students/{student}
    public function show(Student $student)
    {
        return response()->json($student);
    }

    // PUT /api/students/{student}
    public function update(Request $request, Student $student)
    {
        $validated = $request->validate([
            'student_number' => [
                'required', 'string', 'max:50',
                Rule::unique('students', 'student_number')->ignore($student->id),
            ],
            'first_name' => 'required|string|max:100',
            'last_name'  => 'required|string|max:100',
            'email'      => [
                'required', 'email', 'max:255',
                Rule::unique('students', 'email')->ignore($student->id),
            ],
            'course'     => 'required|string|max:100',
            'year_level' => 'required|integer|between:1,6',
        ]);

        $student->update($validated);

        return response()->json($student);
    }

    // DELETE /api/students/{student}
    public function destroy(Student $student)
    {
        $student->delete();

        return response()->json([
            'message' => 'Student deleted successfully.',
        ]);
    }
}