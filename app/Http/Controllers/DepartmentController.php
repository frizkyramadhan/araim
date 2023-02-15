<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('check_role:admin,superuser');
    }

    public function index()
    {
        $title = "Departments";
        $subtitle = "List of Departments";
        $departments = Department::orderBy('dept_name', 'asc')->get();
        return view('departments.index', compact('title', 'subtitle', 'departments'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = "Departments";
        $subtitle = "Add Department";

        return view('departments.create', compact('title', 'subtitle'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'dept_name' => 'required|unique:departments',
        ], [
            'dept_name.required' => 'Department name is required',
            'dept_name.unique' => 'Department name already exists',
        ]);

        $department = new Department;
        $department->dept_name = $request->dept_name;
        $department->dept_status = 1;
        $department->save();

        return redirect()->route('departments.index')->with('success', 'Department added successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function show(Department $department)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function edit(Department $department)
    {
        $title = "Departments";
        $subtitle = "Edit Department";
        $department = Department::find($department->id);
        return view('departments.edit', compact('title', 'subtitle', 'department'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Department $department)
    {
        $request->validate(
            [
                'dept_name' => 'required',
                'dept_status' => 'required',
            ],
            [
                'dept_name.required' => 'Department name is required',
                'dept_status.required' => 'Department status is required',
            ]
        );

        Department::where('id', $department->id)->update([
            'dept_name' => $request->dept_name,
            'dept_status' => $request->dept_status,
        ]);

        return redirect()->route('departments.index')->with('success', 'Department updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function destroy(Department $department)
    {
        // if delete success
        if ($department->delete()) {
            return redirect()->route('departments.index')->with('success', 'Department deleted successfully');
        } else {
            return redirect()->route('departments.index')->with('error', 'Department could not be deleted');
        }
    }
}
