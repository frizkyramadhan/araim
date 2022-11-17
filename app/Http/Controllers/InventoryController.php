<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Project;
use App\Models\Employee;
use App\Models\Component;
use App\Models\Inventory;
use App\Models\Department;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Models\Specification;
use App\Exports\InventoryExport;
use App\Imports\InventoryImport;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;
use Maatwebsite\Excel\Facades\Excel;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\RoundBlockSizeMode\RoundBlockSizeModeMargin;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;

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
        $assets = Asset::where('asset_status', '1')->orderBy('asset_name', 'asc')->get();
        $projects = Project::where('project_status', '1')->orderBy('project_code', 'asc')->get();
        $departments = Department::where('dept_status', '1')->orderBy('dept_name', 'asc')->get();

        return view('inventories.index', compact('title', 'subtitle', 'assets', 'projects', 'departments'));
    }

    public function getInventories(Request $request)
    {
        if ($request->ajax()) {
            $inventories = Inventory::leftJoin('projects', 'inventories.project_id', '=', 'projects.id')
                ->leftJoin('employees', 'inventories.employee_id', '=', 'employees.id')
                ->leftJoin('assets', 'inventories.asset_id', '=', 'assets.id')
                ->leftJoin('departments', 'inventories.department_id', '=', 'departments.id')
                ->leftJoin('users', 'inventories.created_by', '=', 'users.id')
                ->select(['inventories.id', 'inventories.inventory_no', 'inventories.input_date', 'inventories.brand', 'inventories.model_asset', 'inventories.serial_no', 'inventories.location', 'inventories.quantity', 'inventories.inventory_status', 'inventories.transfer_status', 'projects.project_code', 'departments.dept_name', 'assets.asset_name', 'employees.fullname', 'users.name'])
                ->orderBy('inventories.id', 'desc');

            return DataTables::of($inventories)
                ->addIndexColumn()
                ->addColumn('inventory_no', function ($inventories) {
                    return $inventories->inventory_no;
                })
                ->addColumn('input_date', function ($inventories) {
                    return date('d-M-Y', strtotime($inventories->input_date));
                })
                ->addColumn('asset_name', function ($inventories) {
                    return $inventories->asset_name;
                })
                ->addColumn('brand', function ($inventories) {
                    return $inventories->brand;
                })
                ->addColumn('model_asset', function ($inventories) {
                    return $inventories->model_asset;
                })
                ->addColumn('serial_no', function ($inventories) {
                    return $inventories->serial_no;
                })
                ->addColumn('fullname', function ($inventories) {
                    return $inventories->fullname;
                })
                ->addColumn('project_code', function ($inventories) {
                    return $inventories->project_code;
                })
                ->addColumn('location', function ($inventories) {
                    return $inventories->location;
                })
                ->addColumn('quantity', function ($inventories) {
                    return $inventories->quantity;
                })
                ->addColumn('inventory_status', function ($inventories) {
                    if ($inventories->inventory_status == 'Good') {
                        return '<span class="badge badge-primary">Good</span>';
                    } elseif ($inventories->inventory_status == 'Broken') {
                        return '<span class="badge badge-danger">Broken</span>';
                    }
                })
                ->addColumn('transfer_status', function ($inventories) {
                    if ($inventories->transfer_status == 'Available') {
                        return '<span class="badge badge-success">Available</span>';
                    } elseif ($inventories->transfer_status == 'Discarded') {
                        return '<span class="badge badge-secondary">Discarded</span>';
                    } elseif ($inventories->transfer_status == 'Mutated') {
                        return '<span class="badge badge-warning">Mutated</span>';
                    }
                })
                ->filter(function ($instance) use ($request) {
                    if (!empty($request->get('date1') && !empty($request->get('date2')))) {
                        $instance->where(function ($w) use ($request) {
                            $date1 = $request->get('date1');
                            $date2 = $request->get('date2');
                            $w->whereBetween('input_date', array($date1, $date2));
                        });
                    }
                    if (!empty($request->get('inventory_no'))) {
                        $instance->where(function ($w) use ($request) {
                            $inventory_no = $request->get('inventory_no');
                            $w->orWhere('inventory_no', 'LIKE', '%' . $inventory_no . '%');
                        });
                    }
                    if (!empty($request->get('asset_name'))) {
                        $instance->where(function ($w) use ($request) {
                            $asset_name = $request->get('asset_name');
                            $w->orWhere('asset_name', 'LIKE', '%' . $asset_name . '%');
                        });
                    }
                    if (!empty($request->get('brand'))) {
                        $instance->where(function ($w) use ($request) {
                            $brand = $request->get('brand');
                            $w->orWhere('brand', 'LIKE', '%' . $brand . '%');
                        });
                    }
                    if (!empty($request->get('model_asset'))) {
                        $instance->where(function ($w) use ($request) {
                            $model_asset = $request->get('model_asset');
                            $w->orWhere('model_asset', 'LIKE', '%' . $model_asset . '%');
                        });
                    }
                    if (!empty($request->get('serial_no'))) {
                        $instance->where(function ($w) use ($request) {
                            $serial_no = $request->get('serial_no');
                            $w->orWhere('serial_no', 'LIKE', '%' . $serial_no . '%');
                        });
                    }
                    if (!empty($request->get('fullname'))) {
                        $instance->where(function ($w) use ($request) {
                            $fullname = $request->get('fullname');
                            $w->orWhere('fullname', 'LIKE', '%' . $fullname . '%');
                        });
                    }
                    if (!empty($request->get('project_code'))) {
                        $instance->where(function ($w) use ($request) {
                            $project_code = $request->get('project_code');
                            $w->orWhere('project_code', 'LIKE', '%' . $project_code . '%');
                        });
                    }
                    if (!empty($request->get('dept_name'))) {
                        $instance->where(function ($w) use ($request) {
                            $dept_name = $request->get('dept_name');
                            $w->orWhere('dept_name', 'LIKE', '%' . $dept_name . '%');
                        });
                    }
                    if (!empty($request->get('inventory_status'))) {
                        $instance->where(function ($w) use ($request) {
                            $inventory_status = $request->get('inventory_status');
                            $w->orWhere('inventory_status', 'LIKE', '%' . $inventory_status . '%');
                        });
                    }
                    if (!empty($request->get('transfer_status'))) {
                        $instance->where(function ($w) use ($request) {
                            $transfer_status = $request->get('transfer_status');
                            $w->orWhere('transfer_status', 'LIKE', '%' . $transfer_status . '%');
                        });
                    }
                    if (!empty($request->get('search'))) {
                        $instance->where(function ($w) use ($request) {
                            $search = $request->get('search');
                            $w->orWhere('asset_name', 'LIKE', "%$search%")
                                ->orWhere('fullname', 'LIKE', "%$search%")
                                ->orWhere('project_code', 'LIKE', "%$search%")
                                ->orWhere('inventory_status', 'LIKE', "%$search%")
                                ->orWhere('transfer_status', 'LIKE', "%$search%")
                                ->orWhere('brand', 'LIKE', "%$search%")
                                ->orWhere('inventory_no', 'LIKE', "%$search%")
                                ->orWhere('model_asset', 'LIKE', "%$search%");
                        });
                    }
                }, true)
                ->addColumn('action', 'inventories.action')
                ->rawColumns(['inventory_status', 'transfer_status', 'action'])
                ->toJson();
        }
    }

    public function json(Request $request)
    {
        $inventories = Inventory::leftJoin('projects', 'inventories.project_id', '=', 'projects.id')
            ->leftJoin('employees', 'inventories.employee_id', '=', 'employees.id')
            ->leftJoin('assets', 'inventories.asset_id', '=', 'assets.id')
            ->leftJoin('departments', 'inventories.department_id', '=', 'departments.id')
            ->leftJoin('users', 'inventories.created_by', '=', 'users.id')
            ->select(['inventories.id', 'inventories.inventory_no', 'inventories.input_date', 'inventories.brand', 'inventories.model_asset', 'inventories.serial_no', 'inventories.location', 'inventories.quantity', 'inventories.inventory_status', 'inventories.transfer_status', 'projects.project_code', 'departments.dept_name', 'assets.asset_name', 'employees.fullname', 'users.name'])
            ->orderBy('inventories.id', 'desc');

        return DataTables::of($inventories)
            ->addIndexColumn()
            ->addColumn('inventory_no', function ($inventories) {
                return $inventories->inventory_no;
            })
            ->addColumn('input_date', function ($inventories) {
                return date('d-M-Y', strtotime($inventories->input_date));
            })
            ->addColumn('asset_name', function ($inventories) {
                return $inventories->asset_name;
            })
            ->addColumn('brand', function ($inventories) {
                return $inventories->brand;
            })
            ->addColumn('model_asset', function ($inventories) {
                return $inventories->model_asset;
            })
            ->addColumn('serial_no', function ($inventories) {
                return $inventories->serial_no;
            })
            ->addColumn('fullname', function ($inventories) {
                return $inventories->fullname;
            })
            ->addColumn('project_code', function ($inventories) {
                return $inventories->project_code;
            })
            ->addColumn('location', function ($inventories) {
                return $inventories->location;
            })
            ->addColumn('quantity', function ($inventories) {
                return $inventories->quantity;
            })
            ->addColumn('inventory_status', function ($inventories) {
                if ($inventories->inventory_status == 'Good') {
                    return '<span class="badge badge-primary">Good</span>';
                } elseif ($inventories->inventory_status == 'Broken') {
                    return '<span class="badge badge-danger">Broken</span>';
                }
            })
            ->addColumn('transfer_status', function ($inventories) {
                if ($inventories->transfer_status == 'Available') {
                    return '<span class="badge badge-success">Available</span>';
                } elseif ($inventories->transfer_status == 'Discarded') {
                    return '<span class="badge badge-secondary">Discarded</span>';
                } elseif ($inventories->transfer_status == 'Mutated') {
                    return '<span class="badge badge-warning">Mutated</span>';
                }
            })
            ->filter(function ($instance) use ($request) {
                if (!empty($request->get('date1') && !empty($request->get('date2')))) {
                    $instance->where(function ($w) use ($request) {
                        $date1 = $request->get('date1');
                        $date2 = $request->get('date2');
                        $w->whereBetween('input_date', array($date1, $date2));
                    });
                }
                if (!empty($request->get('inventory_no'))) {
                    $instance->where(function ($w) use ($request) {
                        $inventory_no = $request->get('inventory_no');
                        $w->orWhere('inventory_no', 'LIKE', '%' . $inventory_no . '%');
                    });
                }
                if (!empty($request->get('asset_name'))) {
                    $instance->where(function ($w) use ($request) {
                        $asset_name = $request->get('asset_name');
                        $w->orWhere('asset_name', 'LIKE', '%' . $asset_name . '%');
                    });
                }
                if (!empty($request->get('brand'))) {
                    $instance->where(function ($w) use ($request) {
                        $brand = $request->get('brand');
                        $w->orWhere('brand', 'LIKE', '%' . $brand . '%');
                    });
                }
                if (!empty($request->get('model_asset'))) {
                    $instance->where(function ($w) use ($request) {
                        $model_asset = $request->get('model_asset');
                        $w->orWhere('model_asset', 'LIKE', '%' . $model_asset . '%');
                    });
                }
                if (!empty($request->get('serial_no'))) {
                    $instance->where(function ($w) use ($request) {
                        $serial_no = $request->get('serial_no');
                        $w->orWhere('serial_no', 'LIKE', '%' . $serial_no . '%');
                    });
                }
                if (!empty($request->get('fullname'))) {
                    $instance->where(function ($w) use ($request) {
                        $fullname = $request->get('fullname');
                        $w->orWhere('fullname', 'LIKE', '%' . $fullname . '%');
                    });
                }
                if (!empty($request->get('project_code'))) {
                    $instance->where(function ($w) use ($request) {
                        $project_code = $request->get('project_code');
                        $w->orWhere('project_code', 'LIKE', '%' . $project_code . '%');
                    });
                }
                if (!empty($request->get('inventory_status'))) {
                    $instance->where(function ($w) use ($request) {
                        $inventory_status = $request->get('inventory_status');
                        $w->orWhere('inventory_status', 'LIKE', '%' . $inventory_status . '%');
                    });
                }
                if (!empty($request->get('transfer_status'))) {
                    $instance->where(function ($w) use ($request) {
                        $transfer_status = $request->get('transfer_status');
                        $w->orWhere('transfer_status', 'LIKE', '%' . $transfer_status . '%');
                    });
                }
                if (!empty($request->get('search'))) {
                    $instance->where(function ($w) use ($request) {
                        $search = $request->get('search');
                        $w->orWhere('asset_name', 'LIKE', "%$search%")
                            ->orWhere('fullname', 'LIKE', "%$search%")
                            ->orWhere('project_code', 'LIKE', "%$search%")
                            ->orWhere('inventory_status', 'LIKE', "%$search%")
                            ->orWhere('transfer_status', 'LIKE', "%$search%")
                            ->orWhere('brand', 'LIKE', "%$search%")
                            ->orWhere('inventory_no', 'LIKE', "%$search%")
                            ->orWhere('model_asset', 'LIKE', "%$search%");
                    });
                }
            }, true)
            ->addColumn('action', 'inventories.action')
            ->rawColumns(['inventory_status', 'transfer_status', 'action'])
            ->toJson();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($employee_id = null)
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

        return view('inventories.create', compact('title', 'subtitle', 'employees', 'assets', 'projects', 'departments', 'components', 'inv_no', 'year', 'month', 'employee_id'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $id_employee = $request->input('id_employee');

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
            'quantity' => 'required'
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
        $inventory->transfer_status = "Available";
        $inventory->is_active = "1";

        $inventory->save();

        $check = Arr::exists($data, 'component_id');
        if ($check == true) {
            foreach ($data['component_id'] as $component => $value) {
                $components = array(
                    'inventory_id' => $inventory->id,
                    'component_id' => $data['component_id'][$component],
                    'specification' => $data['specification'][$component],
                    'spec_remarks' => $data['spec_remarks'][$component],
                    'spec_status' => "Available",
                );
                Specification::create($components);
            }
        }

        if ($id_employee) {
            return redirect('employees/' . $id_employee)->with('success', 'Inventory successfully added!');
        } else {
            return redirect()->route('inventories.index')->with('success', 'Inventory successfully added!');
        }
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

        return view('inventories.show', compact('title', 'subtitle', 'inventory', 'specifications'));
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

        return view('inventories.edit', compact('title', 'subtitle', 'employees', 'assets', 'projects', 'departments', 'components', 'inventory', 'specifications'));
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
        foreach ($specifications as $specification) {
            if ($request->has('deleteRow' . $specification->id)) {
                Specification::where('id', $specification->id)->delete();
                return redirect('inventories/' . $inventory->id . '/edit')->with('success', 'Specification has been deleted!');
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
            'is_active' => $request->is_active,
        ]);

        $data = $request->all();
        $check = Arr::exists($data, 'component_id');
        if ($check == true) {
            foreach ($data['component_id'] as $component => $value) {
                $components = array(
                    'inventory_id' => $inventory->id,
                    'component_id' => $data['component_id'][$component],
                    'specification' => $data['specification'][$component],
                    'spec_remarks' => $data['spec_remarks'][$component],
                    'spec_status' => "Available",
                );
                Specification::create($components);
            }
        }
        return redirect('inventories/' . $inventory->id)->with('success', 'Inventory successfully updated!');
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

    public function transfer($id)
    {
        $title = 'Inventories';
        $subtitle = 'Inventory Transfer';
        $employees = Employee::where('status', '1')->orderBy('fullname', 'asc')->get();
        $projects = Project::where('project_status', '1')->orderBy('project_code', 'asc')->get();
        $departments = Department::where('dept_status', '1')->orderBy('dept_name', 'asc')->get();

        $inventory = Inventory::with('employee', 'asset', 'project', 'department')->find($id);
        $specifications = Specification::with('component')->where('inventory_id', $id)->get();

        return view('inventories.transfer', compact('title', 'subtitle', 'employees', 'inventory', 'specifications', 'projects', 'departments'));
    }

    public function transferProcess($id, Request $request)
    {
        $inventory = Inventory::find($id);
        // dd($inventory->quantity);
        $qty = $inventory->quantity;
        $qty_new = $qty - $request->quantity;

        if ($qty_new > 0) {
            $inventory->update([
                'quantity' => $qty_new,
            ]);
        } elseif ($qty_new == 0) {
            $inventory->update([
                'quantity' => $qty_new,
                'transfer_status' => 'Mutated',
            ]);
            $specification = Specification::where('inventory_id', $id)->get();
            foreach ($specification as $spec) {
                $spec->update([
                    'spec_status' => 'Mutated',
                ]);
            }
        }

        $request->validate([
            'input_date' => 'required',
            'employee_id' => 'required',
            'remarks' => 'required',
        ]);
        // store new data
        $data = $request->all();
        $new_inventory = new Inventory();
        $new_inventory->inventory_no = $data['inventory_no'];
        $new_inventory->input_date = $data['input_date'];
        $new_inventory->asset_id = $data['asset_id'];
        $new_inventory->employee_id = $data['employee_id'];
        $new_inventory->project_id = $data['project_id'];
        $new_inventory->department_id = $data['department_id'];
        $new_inventory->brand = $data['brand'];
        $new_inventory->model_asset = $data['model_asset'];
        $new_inventory->serial_no = $data['serial_no'];
        $new_inventory->part_no = $data['part_no'];
        $new_inventory->po_no = $data['po_no'];
        $new_inventory->quantity = $data['quantity'];
        $new_inventory->remarks = $data['remarks'];
        $new_inventory->created_by = auth()->user()->id;
        $new_inventory->reference_no = $data['reference_no'];
        $new_inventory->reference_date = $data['reference_date'];
        $new_inventory->location = $data['location'];
        $new_inventory->inventory_status = $data['inventory_status'];
        $new_inventory->transfer_status = 'Available';
        $new_inventory->save();

        $check = Arr::exists($data, 'component_id');
        if ($check == true) {
            foreach ($data['component_id'] as $component => $value) {
                $components = array(
                    'inventory_id' => $new_inventory->id,
                    'component_id' => $data['component_id'][$component],
                    'specification' => $data['specification'][$component],
                    'spec_remarks' => $data['spec_remarks'][$component],
                    'spec_status' => 'Available',
                );
                Specification::create($components);
            }
        }

        return redirect('inventories/' . $new_inventory->id)->with('success', 'Inventory successfully transferred!');
    }

    public function export()
    {
        return Excel::download(new InventoryExport, 'inventories.xlsx');
    }

    public function import()
    {
        $title = 'Inventories';
        $subtitle = 'Import Inventories';

        return view('inventories.import', compact('title', 'subtitle'));
    }

    public function importProcess(Request $request)
    {
        Excel::import(new InventoryImport, request()->file('import_file'));

        return back()->with('success', 'Inventory successfully imported!');
    }

    public function qrcode($id)
    {
        $inventory = Inventory::with('asset')->find($id);
        $content = "No. Aset = " . $inventory->inventory_no . "\n";
        $content .= "Nama Aset = " . $inventory->asset->asset_name . "\n";
        $content .= "Merk = " . $inventory->brand . "\n";
        $content .= "Lokasi = " . $inventory->location . "\n";

        $result = Builder::create()
            ->writer(new PngWriter())
            ->writerOptions([])
            ->data($content)
            ->encoding(new Encoding('ISO-8859-1'))
            ->errorCorrectionLevel(new ErrorCorrectionLevelHigh())
            ->size(400)
            ->margin(15)
            ->roundBlockSizeMode(new RoundBlockSizeModeMargin())
            // ->labelText('This is the label')
            // ->labelFont(new NotoSans(20))
            // ->labelAlignment(new LabelAlignmentCenter())
            ->build();

        $result->saveToFile(public_path('../storage/app/public/qrcode/qr-' . $inventory->id . '.png'));

        $inventory->update([
            'qrcode' => 'qr-' . $inventory->id . '.png',
        ]);

        // return redirect('inventories/' . $inventory->id)->with('success', 'QR Code successfully generated!');
        return back()->with('success', 'QR Code successfully generated!');
    }

    public function delete_qrcode($id)
    {
        $inventory = Inventory::find($id);

        // delete qrcode
        $qrcode = public_path('../storage/app/public/qrcode/' . $inventory->qrcode);
        if (file_exists($qrcode)) {
            unlink($qrcode);
            $inventory->update([
                'qrcode' => null,
            ]);
        }

        return back()->with('success', 'QR Code successfully deleted!');
        // return redirect('inventories/' . $inventory->id)->with('success', 'QR Code successfully deleted!');
    }

    public function print_qrcode($id)
    {
        $title = 'QR Code Inventory Data';
        $tag = 'qrcode';
        $inventory = Inventory::with('asset', 'employee')->find($id);
        return view('inventories.qrcode', compact('title', 'inventory', 'tag'));
    }

    public function print_qrcode_employee($id)
    {
        $title = 'QR Code Inventory Data';
        $tag = 'qrcodes';
        $inventories = DB::table('inventories')
            ->join('assets', 'inventories.asset_id', '=', 'assets.id')
            ->join('employees', 'inventories.employee_id', '=', 'employees.id')
            ->where('employees.id', '=', $id)
            ->where('inventories.qrcode', '!=', null)
            ->orderBy('inventories.id', 'desc')
            ->get();
        return view('inventories.qrcode', compact('title', 'inventories', 'tag'));
    }
}
