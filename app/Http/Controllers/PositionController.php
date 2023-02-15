<?php

namespace App\Http\Controllers;

use App\Models\Position;
use App\Models\Department;
use Illuminate\Http\Request;

class PositionController extends Controller
{
    public function __construct()
    {
        $this->middleware('check_role:admin,superuser');
    }

    public function index()
    {
        $title = "Positions";
        $subtitle = "List of positions";
        $positions = Position::with('department')
            ->orderBy(Department::select('dept_name')->whereColumn('departments.id', 'positions.department_id'))
            ->orderBy('position_name', 'asc')
            ->get();

        return view('positions.index', compact('title', 'subtitle', 'positions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = "Positions";
        $subtitle = "Add Positions";
        $departments = Department::where('dept_status', '=', '1')->orderBy('dept_name', 'asc')->get();

        return view('positions.create', compact('title', 'subtitle', 'departments'));
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
            'position_name' => 'required|unique:positions',
            'department_id' => 'required',
        ], [
            'position_name.required' => 'Position name is required',
            'position_name.unique' => 'Position name already exists',
            'department_id.required' => 'Department is required',
        ]);

        $position = new Position;
        $position->position_name = $request->position_name;
        $position->department_id = $request->department_id;
        $position->save();

        return redirect()->route('positions.index')->with('success', 'Position added successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Position  $position
     * @return \Illuminate\Http\Response
     */
    public function show(Position $position)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Position  $position
     * @return \Illuminate\Http\Response
     */
    public function edit(Position $position)
    {
        $title = "Positions";
        $subtitle = "Edit Positions";
        $departments = Department::where('dept_status', '=', '1')->orderBy('dept_name', 'asc')->get();

        return view('positions.edit', compact('title', 'subtitle', 'departments', 'position'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Position  $position
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Position $position)
    {
        $rules = [
            'department_id' => 'required',
            'position_status' => 'required',
        ];

        if ($request->position_name != $position->position_name) {
            $rules['position_name'] = 'required|unique:positions';
        }

        $validatedData = $request->validate($rules);

        Position::where('id', $position->id)->update($validatedData);

        return redirect()->route('positions.index')->with('success', 'Position updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Position  $position
     * @return \Illuminate\Http\Response
     */
    public function destroy(Position $position)
    {
        Position::where('id', $position->id)->delete();

        return redirect()->route('positions.index')->with('success', 'Position deleted successfully');
    }
}
