<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Employee;
use App\Models\Inventory;
use App\Models\Position;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = 'Employees';
        $subtitle = 'List of employees';
        // $employees = Employee::with(['position', 'project'])->orderBy('nik', 'asc')->get();

        return view('employees.index', compact('title', 'subtitle'));
    }

    public function getEmployees(Request $request)
    {
        if ($request->ajax()) {
            $employees = Employee::leftJoin('projects', 'employees.project_id', '=', 'projects.id')
                ->leftJoin('positions', 'employees.position_id', '=', 'positions.id')
                ->leftJoin('departments', 'positions.department_id', '=', 'departments.id')
                ->select(['employees.*', 'projects.project_code', 'projects.project_name', 'positions.position_name', 'departments.dept_name'])
                ->orderBy('employees.nik', 'asc');

            return DataTables::of($employees)
                ->addIndexColumn()
                ->addColumn('nik', function ($employees) {
                    return $employees->nik;
                })
                ->addColumn('fullname', function ($employees) {
                    return $employees->fullname;
                })
                ->addColumn('position_name', function ($employees) {
                    return $employees->position->position_name ?? 'null';
                })
                ->addColumn('project_code', function ($employees) {
                    return $employees->project->project_code;
                })
                ->addColumn('status', function ($employees) {
                    if ($employees->status == '1') {
                        return '<span class="badge badge-success">Active</span>';
                    } elseif ($employees->status == '0') {
                        return '<span class="badge badge-danger">Inactive</span>';
                    }
                })
                ->filter(function ($instance) use ($request) {
                    if (!empty($request->get('search'))) {
                        $instance->where(function ($w) use ($request) {
                            $search = $request->get('search');
                            $w->orWhere('nik', 'LIKE', "%$search%")
                                ->orWhere('fullname', 'LIKE', "%$search%")
                                ->orWhere('positions.position_name', 'LIKE', "%$search%")
                                ->orWhere('projects.project_code', 'LIKE', "%$search%")
                                ->orWhere('status', 'LIKE', "%$search%");
                        });
                    }
                })
                ->addColumn('action', 'employees.action')
                ->addColumn('total', function ($employees) {
                    $total = Inventory::where('employee_id', '=', $employees->id)->where('inventory_status', '=', 'Good')->count('id');
                    return $total;
                })
                ->rawColumns(['status', 'action'])
                ->toJson();
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = 'Employees';
        $subtitle = 'Add Employees';
        $projects = Project::where('project_status', '=', '1')->orderBy('project_code', 'asc')->get();
        $positions = Position::where('position_status', '=', '1')->orderBy('position_name', 'asc')->get();

        return view('employees.create', compact('title', 'subtitle', 'projects', 'positions'));
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
            'nik' => 'required|unique:employees',
            'fullname' => 'required',
            'project_id' => 'required',
            'position_id' => 'required',
            'email' => 'required|ends_with:@arka.co.id'
        ], [
            'nik.required' => 'NIK is required',
            'nik.unique' => 'NIK is already taken',
            'fullname.required' => 'Fullname is required',
            'project_id.required' => 'Project is required',
            'position_id.required' => 'Position is required'
        ]);

        $employee = new Employee;
        $employee->nik = $request->nik;
        $employee->fullname = $request->fullname;
        $employee->project_id = $request->project_id;
        $employee->position_id = $request->position_id;
        $employee->email = $request->email;
        $employee->save();

        return redirect()->route('employees.index')->with('success', 'Employee has been added');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function show(Employee $employee)
    {
        $title = 'Employees';
        $subtitle = 'Detail Employee';
        $projects = Project::where('project_status', '=', '1')->orderBy('project_code', 'asc')->get();
        $positions = Position::where('position_status', '=', '1')->orderBy('position_name', 'asc')->get();
        $inventories = Inventory::with('asset')->where('employee_id', '=', $employee->id)->orderBy('inventory_no', 'desc')->get();

        return view('employees.show', compact('title', 'subtitle', 'employee', 'projects', 'positions', 'inventories'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function edit(Employee $employee)
    {
        $title = 'Employees';
        $subtitle = 'Edit Employees';
        $projects = Project::where('project_status', '=', '1')->orderBy('project_code', 'asc')->get();
        $positions = Position::where('position_status', '=', '1')->orderBy('position_name', 'asc')->get();

        return view('employees.edit', compact('title', 'subtitle', 'projects', 'positions', 'employee'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Employee $employee)
    {
        $this->validate($request, [
            'fullname' => 'required',
            'project_id' => 'required',
            'position_id' => 'required'
        ], [
            'fullname.required' => 'Fullname is required',
            'project_id.required' => 'Project is required',
            'position_id.required' => 'Position is required'
        ]);

        if ($request->nik != $employee->nik) {
            $request->validate([
                'nik' => 'required|unique:employees'
            ], [
                'nik.required' => 'NIK is required',
                'nik.unique' => 'NIK is already taken'
            ]);
        }

        Employee::where('id', $employee->id)
            ->update([
                'nik' => $request->nik,
                'fullname' => $request->fullname,
                'project_id' => $request->project_id,
                'position_id' => $request->position_id,
                'email' => $request->email,
                'status' => $request->status
            ]);

        return redirect()->route('employees.index')->with('success', 'Employee has been updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function destroy(Employee $employee)
    {
        Employee::destroy($employee->id);

        return redirect()->route('employees.index')->with('success', 'Employee has been deleted');
    }

    public function json(Request $request)
    {

        $employees = Employee::leftJoin('projects', 'employees.project_id', '=', 'projects.id')
            ->leftJoin('positions', 'employees.position_id', '=', 'positions.id')
            ->leftJoin('departments', 'positions.department_id', '=', 'departments.id')
            ->select(['employees.*', 'projects.project_code', 'projects.project_name', 'positions.position_name', 'departments.dept_name'])
            ->orderBy('employees.nik', 'asc');

        return DataTables::of($employees)
            ->addIndexColumn()
            ->addColumn('nik', function ($employees) {
                return $employees->nik;
            })
            ->addColumn('fullname', function ($employees) {
                return $employees->fullname;
            })
            ->addColumn('position_name', function ($employees) {
                return $employees->position->position_name ?? null;
            })
            ->addColumn('project_code', function ($employees) {
                return $employees->project->project_code;
            })
            ->addColumn('status', function ($employees) {
                if ($employees->status == '1') {
                    return '<span class="badge badge-success">Active</span>';
                } elseif ($employees->status == '0') {
                    return '<span class="badge badge-danger">Inactive</span>';
                }
            })
            ->filter(function ($instance) use ($request) {
                if (!empty($request->get('search'))) {
                    $instance->where(function ($w) use ($request) {
                        $search = $request->get('search');
                        $w->orWhere('nik', 'LIKE', "%$search%")
                            ->orWhere('fullname', 'LIKE', "%$search%")
                            ->orWhere('positions.position_name', 'LIKE', "%$search%")
                            ->orWhere('projects.project_code', 'LIKE', "%$search%")
                            ->orWhere('status', 'LIKE', "%$search%");
                    });
                }
            })
            ->addColumn('action', 'employees.action')
            ->rawColumns(['status', 'action'])
            ->toJson();
    }
}
