<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Component;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Inventory;
use App\Models\Project;
use App\Models\Specification;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Yajra\DataTables\DataTables;

class InventoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = 'Inventories';
        $subtitle = 'List of inventories';

        return view('inventories.index', compact('title', 'subtitle'));
    }

    public function getInventories(Request $request)
    {  
        if($request->ajax()){
            $inventories = Inventory::leftJoin('projects', 'inventories.project_id', '=', 'projects.id')
                        ->leftJoin('employees', 'inventories.employee_id', '=', 'employees.id')
                        ->leftJoin('assets', 'inventories.asset_id', '=', 'assets.id')
                        ->leftJoin('departments', 'inventories.department_id', '=', 'departments.id')
                        ->leftJoin('users', 'inventories.created_by', '=', 'users.id')
                        ->select(['inventories.id','inventories.inventory_no', 'inventories.input_date', 'inventories.brand', 'inventories.model_asset', 'inventories.serial_no','inventories.inventory_status', 'projects.project_code', 'departments.dept_name', 'assets.asset_name' ,'employees.fullname','users.name'])
                        ->orderBy('inventories.id', 'desc');
                
            return DataTables::of($inventories)
                ->addIndexColumn()
                ->addColumn('inventory_no', function($inventories){
                    return $inventories->inventory_no;
                })
                ->addColumn('input_date', function($inventories){
                    return date('d-M-Y', strtotime($inventories->input_date));
                })
                ->addColumn('asset_name', function($inventories){
                    return $inventories->asset_name;
                })
                ->addColumn('brand', function($inventories){
                    return $inventories->brand;
                })
                ->addColumn('model_asset', function($inventories){
                    return $inventories->model_asset;
                })
                ->addColumn('serial_no', function($inventories){
                    return $inventories->serial_no;
                })
                ->addColumn('fullname', function($inventories){
                    return $inventories->fullname;
                })
                ->addColumn('project_code', function($inventories){
                    return $inventories->project_code;
                })
                ->addColumn('inventory_status', function($inventories){
                    if ($inventories->inventory_status == 'Good') {
                        return '<span class="badge badge-primary">Good</span>';
                    } elseif ($inventories->inventory_status == 'Broken'){
                        return '<span class="badge badge-danger">Broken</span>';
                    } elseif ($inventories->inventory_status == 'Mutated'){
                        return '<span class="badge badge-warning">Mutated</span>';
                    } elseif ($inventories->inventory_status == 'Discarded'){
                        return '<span class="badge badge-secondary">Discarded</span>';
                    }
                })
                ->filter(function ($instance) use ($request) {
                    if (!empty($request->get('search'))) {
                            $instance->where(function($w) use($request){
                            $search = $request->get('search');
                            $w->orWhere('asset_name', 'LIKE', "%$search%")
                            ->orWhere('fullname', 'LIKE', "%$search%")
                            ->orWhere('project_code', 'LIKE', "%$search%")
                            ->orWhere('inventory_status', 'LIKE', "%$search%")
                            ->orWhere('brand', 'LIKE', "%$search%")
                            ->orWhere('inventory_no', 'LIKE', "%$search%")
                            ->orWhere('model_asset', 'LIKE', "%$search%");
                        });
                    }
                })
                ->addColumn('action', 'inventories.action')
                ->rawColumns(['inventory_status','action'])
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
        $title = 'Inventories';
        $subtitle = 'Add Inventory';
        $employees = Employee::where('status', '1')->orderBy('fullname', 'asc')->get();
        $assets = Asset::where('asset_status', '1')->orderBy('asset_name', 'asc')->get();
        $projects = Project::where('project_status', '1')->orderBy('project_code', 'asc')->get();
        $departments = Department::where('dept_status', '1')->orderBy('dept_name', 'asc')->get();
        $components = Component::where('component_status', '1')->orderBy('component_name', 'asc')->get();

        // generate inventory no
        $year = date('y');
        $month = date('m');
        $number = Inventory::max('id') + 1;
        $inv_no = str_pad($number, 6, '0', STR_PAD_LEFT);

        return view('inventories.create', compact('title', 'subtitle','employees', 'assets', 'projects', 'departments','components','inv_no','year','month'));
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
            'inventory_no' => 'required|unique:inventories',
            'input_date' => 'required',
            'asset_id' => 'required',
            'employee_id' => 'required',
            'project_id' => 'required',
            'department_id' => 'required',
            'inventory_status' => 'required',
            'brand' => 'required',
            'model_asset' => 'required',
        ]);

        $data = $request->all();
                
        $inventory = new Inventory();
        $inventory->inventory_no = $data['inventory_no'];
        $inventory->input_date = $data['input_date'];
        $inventory->asset_id = $data['asset_id'];
        $inventory->employee_id = $data['employee_id'];
        $inventory->project_id = $data['project_id'];
        $inventory->department_id = $data['department_id'];
        $inventory->brand = $data['brand'];
        $inventory->model_asset = $data['model_asset'];
        $inventory->serial_no = $data['serial_no'];
        $inventory->part_no = $data['part_no'];
        $inventory->po_no = $data['po_no'];
        $inventory->quantity = $data['quantity'];
        $inventory->remarks = $data['remarks'];
        $inventory->created_by = auth()->user()->id;
        $inventory->reference_no = $data['reference_no'];
        $inventory->reference_date = $data['reference_date'];
        $inventory->location = $data['location'];
        $inventory->inventory_status = $data['inventory_status'];
        $inventory->save();
        
        $check = Arr::exists($data, 'component_id');
        if($check == true){
            foreach($data['component_id'] as $component => $value){
                $components = array(
                    'inventory_id' => $inventory->id,
                    'component_id' => $data['component_id'][$component],
                    'specification' => $data['specification'][$component],
                    'spec_remarks' => $data['spec_remarks'][$component],
                );
                Specification::create($components);
            }
        }

        return redirect()->route('inventories.index')->with('success', 'Inventory successfully added!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Inventory  $inventory
     * @return \Illuminate\Http\Response
     */
    public function show(Inventory $inventory)
    {
        $title = 'Inventories';
        $subtitle = 'Detail Inventory';
        $inventory = Inventory::with('employee', 'asset', 'project', 'department')->find($inventory->id);
        $specifications = Specification::with('component')->where('inventory_id', $inventory->id)->get();
        // dd($specifications->toArray());
        
        return view('inventories.show', compact('title', 'subtitle', 'inventory','specifications'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Inventory  $inventory
     * @return \Illuminate\Http\Response
     */
    public function edit(Inventory $inventory)
    {
        $title = 'Inventories';
        $subtitle = 'Edit Inventory';
        $employees = Employee::where('status', '1')->orderBy('fullname', 'asc')->get();
        $assets = Asset::where('asset_status', '1')->orderBy('asset_name', 'asc')->get();
        $projects = Project::where('project_status', '1')->orderBy('project_code', 'asc')->get();
        $departments = Department::where('dept_status', '1')->orderBy('dept_name', 'asc')->get();
        $components = Component::where('component_status', '1')->orderBy('component_name', 'asc')->get();

        $specifications = Specification::with('component')->where('inventory_id', $inventory->id)->get();

        return view('inventories.edit', compact('title', 'subtitle','employees', 'assets', 'projects', 'departments','components','inventory','specifications'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Inventory  $inventory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Inventory $inventory)
    {
        $specifications = Specification::where('inventory_id', $inventory->id)->get();
        foreach($specifications as $specification){
            if($request->has('deleteRow'.$specification->id)){
                Specification::where('id', $specification->id)->delete();
                return redirect('inventories/'.$inventory->id.'/edit')->with('success', 'Specification has been deleted!');
            }
        }

        $request->validate([
            'inventory_no' => 'required',
            'input_date' => 'required',
            'asset_id' => 'required',
            'employee_id' => 'required',
            'project_id' => 'required',
            'department_id' => 'required',
            'inventory_status' => 'required',
            'brand' => 'required',
            'model_asset' => 'required',
        ]);

        Inventory::where('id', $inventory->id)->update([
            'inventory_no' => $request->inventory_no,
            'input_date' => $request->input_date,
            'asset_id' => $request->asset_id,
            'employee_id' => $request->employee_id,
            'project_id' => $request->project_id,
            'department_id' => $request->department_id,
            'brand' => $request->brand,
            'model_asset' => $request->model_asset,
            'serial_no' => $request->serial_no,
            'part_no' => $request->part_no,
            'po_no' => $request->po_no,
            'quantity' => $request->quantity,
            'remarks' => $request->remarks,
            'created_by' => auth()->user()->id,
            'reference_no' => $request->reference_no,
            'reference_date' => $request->reference_date,
            'location' => $request->location,
            'inventory_status' => $request->inventory_status,
        ]);

        $data = $request->all();
        $check = Arr::exists($data, 'component_id');
        if($check == true){
            foreach($data['component_id'] as $component => $value){
                $components = array(
                    'inventory_id' => $inventory->id,
                    'component_id' => $data['component_id'][$component],
                    'specification' => $data['specification'][$component],
                    'spec_remarks' => $data['spec_remarks'][$component],
                );
                Specification::create($components);
            }
        }
        return redirect('inventories/'.$inventory->id)->with('success', 'Inventory successfully updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Inventory  $inventory
     * @return \Illuminate\Http\Response
     */
    public function destroy(Inventory $inventory)
    {
        Specification::where('inventory_id', $inventory->id)->delete();
        Inventory::where('id', $inventory->id)->delete();
        return redirect()->route('inventories.index')->with('success', 'Inventory successfully deleted!');
    }

    public function json(Request $request)
    {  
        $inventories = Inventory::leftJoin('projects', 'inventories.project_id', '=', 'projects.id')
                        ->leftJoin('employees', 'inventories.employee_id', '=', 'employees.id')
                        ->leftJoin('assets', 'inventories.asset_id', '=', 'assets.id')
                        ->leftJoin('departments', 'inventories.department_id', '=', 'departments.id')
                        ->leftJoin('users', 'inventories.created_by', '=', 'users.id')
                        ->select(['inventories.id','inventories.inventory_no', 'inventories.input_date', 'inventories.brand', 'inventories.model_asset', 'inventories.serial_no','inventories.inventory_status', 'projects.project_code', 'departments.dept_name', 'assets.asset_name' ,'employees.fullname','users.name'])
                        ->orderBy('inventories.id', 'desc');
                
            return DataTables::of($inventories)
                ->addIndexColumn()
                ->addColumn('inventory_no', function($inventories){
                    return $inventories->inventory_no;
                })
                ->addColumn('input_date', function($inventories){
                    return date('d-M-Y', strtotime($inventories->input_date));
                })
                ->addColumn('asset_name', function($inventories){
                    return $inventories->asset_name;
                })
                ->addColumn('brand', function($inventories){
                    return $inventories->brand;
                })
                ->addColumn('model_asset', function($inventories){
                    return $inventories->model_asset;
                })
                ->addColumn('serial_no', function($inventories){
                    return $inventories->serial_no;
                })
                ->addColumn('fullname', function($inventories){
                    return $inventories->fullname;
                })
                ->addColumn('project_code', function($inventories){
                    return $inventories->project_code;
                })
                ->addColumn('inventory_status', function($inventories){
                    if ($inventories->inventory_status == 'Available') {
                        return '<span class="badge badge-success">Available</span>';
                    } elseif ($inventories->inventory_status == 'Broken'){
                        return '<span class="badge badge-danger">Broken</span>';
                    } elseif ($inventories->inventory_status == 'Mutated'){
                        return '<span class="badge badge-warning">Mutated</span>';
                    } elseif ($inventories->inventory_status == 'Discarded'){
                        return '<span class="badge badge-secondary">Discarded</span>';
                    }
                })
                ->filter(function ($instance) use ($request) {
                    if (!empty($request->get('search'))) {
                            $instance->where(function($w) use($request){
                            $search = $request->get('search');
                            $w->orWhere('asset_name', 'LIKE', "%$search%")
                            ->orWhere('fullname', 'LIKE', "%$search%")
                            ->orWhere('project_code', 'LIKE', "%$search%")
                            ->orWhere('inventory_status', 'LIKE', "%$search%")
                            ->orWhere('brand', 'LIKE', "%$search%")
                            ->orWhere('model_asset', 'LIKE', "%$search%");
                        });
                    }
                })
                ->addColumn('action', 'inventories.action')
                ->rawColumns(['inventory_status','action'])
                ->toJson();
    }
}
