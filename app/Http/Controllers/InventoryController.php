<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Brand;
use App\Models\Image;
use App\Models\User;
use SimpleXMLElement;
use App\Models\Project;
use App\Models\Employee;
use App\Models\Location;
use App\Models\Component;
use App\Models\Inventory;
use App\Models\Department;
use Illuminate\Support\Arr;
use App\Imports\BrandImport;
use Illuminate\Http\Request;
use App\Models\Specification;
use App\Imports\LocationImport;
use App\Exports\InventoryExport;
use App\Imports\InventoryImport;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Support\Facades\File;
use Endroid\QrCode\Encoding\Encoding;
use Illuminate\Support\Facades\Storage;
use Endroid\QrCode\RoundBlockSizeMode\RoundBlockSizeModeMargin;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;

class InventoryController extends Controller
{

    public function __construct()
    {
        // set middleware for admin and superuser that can access this controller but for user just can access index
        $this->middleware('guest')->only('qrcodeJson');
        $this->middleware('auth');
        $this->middleware('check_role:admin,superuser', ['except' => ['index', 'getInventories', 'show']]);
        $this->middleware('check_role:admin', ['only' => ['destroy']]);
    }

    private function getAuthUser()
    {
        $userId = auth()->id();
        return $userId ? User::with('categories')->find($userId) : null;
    }

    public function index()
    {
        $title = 'Inventories';
        $subtitle = 'List of inventories';
        $assets = Asset::where('asset_status', '1')->orderBy('asset_name', 'asc')->get();
        $brands = Brand::where('brand_status', '1')->orderBy('brand_name', 'asc')->get();
        $locations = Location::where('location_status', '1')->orderBy('location_name', 'asc')->get();
        $projects = Project::where('project_status', '1')->orderBy('project_code', 'asc')->get();
        $departments = Department::where('dept_status', '1')->orderBy('dept_name', 'asc')->get();

        return view('inventories.index', compact('title', 'subtitle', 'assets', 'projects', 'departments', 'brands', 'locations'));
    }

    public function getInventories(Request $request)
    {
        if ($request->ajax()) {
            $authUser = $this->getAuthUser();
            $where = '1';
            if ($authUser->level == 'user' || $authUser->level == 'superuser') {
                $where = 'inventories.project_id = ' . $authUser->project_id;
            }

            $categories = $authUser->categories()->pluck('categories.id')->implode(',');

            if (empty($categories)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'You do not have access to any category. Please contact the Administrator to set up your access.'
                ]);
            }

            $query = Inventory::leftJoin('projects', 'inventories.project_id', '=', 'projects.id')
                ->leftJoin('employees', 'inventories.employee_id', '=', 'employees.id')
                ->leftJoin('assets', 'inventories.asset_id', '=', 'assets.id')
                ->leftJoin('brands', 'inventories.brand_id', '=', 'brands.id')
                ->leftJoin('locations', 'inventories.location_id', '=', 'locations.id')
                ->leftJoin('departments', 'inventories.department_id', '=', 'departments.id')
                ->select(['inventories.*', 'projects.project_code', 'departments.dept_name', 'assets.asset_name', 'assets.category_id', 'employees.fullname', 'brands.brand_name', 'locations.location_name'])
                ->whereRaw($where)
                ->whereRaw('assets.category_id IN (' . $categories . ')')
                ->orderBy('inventories.created_at', 'desc');

            if ($request->get('date1') && $request->get('date2')) {
                $query->whereBetween('input_date', [$request->get('date1'), $request->get('date2')]);
            }

            if ($request->get('inventory_no')) {
                $query->where('inventory_no', 'LIKE', "%{$request->get('inventory_no')}%");
            }

            if ($request->get('asset_name')) {
                $query->where('asset_name', 'LIKE', "%{$request->get('asset_name')}%");
            }

            if ($request->get('brand_name')) {
                $query->where('brand_name', 'LIKE', "%{$request->get('brand_name')}%");
            }

            if ($request->get('model_asset')) {
                $query->where('model_asset', 'LIKE', "%{$request->get('model_asset')}%");
            }

            if ($request->get('serial_no')) {
                $query->where('serial_no', 'LIKE', "%{$request->get('serial_no')}%");
            }

            if ($request->get('fullname')) {
                $query->where('fullname', 'LIKE', "%{$request->get('fullname')}%");
            }

            if ($request->get('project_code')) {
                $query->where('project_code', 'LIKE', "%{$request->get('project_code')}%");
            }

            if ($request->get('dept_name')) {
                $query->where('dept_name', 'LIKE', "%{$request->get('dept_name')}%");
            }

            if ($request->get('inventory_status')) {
                $query->where('inventory_status', 'LIKE', "%{$request->get('inventory_status')}%");
            }

            if ($request->get('transfer_status')) {
                $query->where('transfer_status', 'LIKE', "%{$request->get('transfer_status')}%");
            }

            return DataTables::of($query)
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
                ->addColumn('brand_name', function ($inventories) {
                    return $inventories->brand_name ? $inventories->brand_name : $inventories->brand;
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
                    return $inventories->location_name ? $inventories->location_name : $inventories->location;
                })
                ->addColumn('quantity', function ($inventories) {
                    return $inventories->quantity;
                })
                ->addColumn('inventory_status', function ($inventories) {
                    if ($inventories->inventory_status == 'Good') {
                        return '<span class="badge badge-primary">Good</span>';
                    } elseif ($inventories->inventory_status == 'Broken') {
                        return '<span class="badge badge-danger">Broken</span>';
                    } elseif ($inventories->inventory_status == 'Lost') {
                        return '<span class="badge badge-dark">Lost</span>';
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
                ->addColumn('action', 'inventories.action')
                ->rawColumns(['inventory_status', 'transfer_status', 'action'])
                ->toJson();
        }
    }

    public function json(Request $request)
    {
        $authUser = $this->getAuthUser();
        $where = '1';
        if ($authUser->level == 'user' || $authUser->level == 'superuser') {
            $where = 'inventories.project_id = ' . $authUser->project_id;
        }

        $categories = $authUser->categories()->pluck('categories.id')->implode(',');

        $query = Inventory::leftJoin('projects', 'inventories.project_id', '=', 'projects.id')
            ->leftJoin('employees', 'inventories.employee_id', '=', 'employees.id')
            ->leftJoin('assets', 'inventories.asset_id', '=', 'assets.id')
            ->leftJoin('brands', 'inventories.brand_id', '=', 'brands.id')
            ->leftJoin('locations', 'inventories.location_id', '=', 'locations.id')
            ->leftJoin('departments', 'inventories.department_id', '=', 'departments.id')
            ->select(['inventories.*', 'projects.project_code', 'departments.dept_name', 'assets.asset_name', 'assets.category_id', 'employees.fullname', 'brands.brand_name', 'locations.location_name'])
            ->whereRaw($where)
            ->whereRaw('assets.category_id IN (' . $categories . ')')
            ->orderBy('inventories.created_at', 'desc');

        if ($request->get('date1') && $request->get('date2')) {
            $query->whereBetween('input_date', [$request->get('date1'), $request->get('date2')]);
        }

        if ($request->get('inventory_no')) {
            $query->where('inventory_no', 'LIKE', "%{$request->get('inventory_no')}%");
        }

        if ($request->get('asset_name')) {
            $query->where('asset_name', 'LIKE', "%{$request->get('asset_name')}%");
        }

        if ($request->get('brand_name')) {
            $query->where('brand_name', 'LIKE', "%{$request->get('brand_name')}%");
        }

        if ($request->get('model_asset')) {
            $query->where('model_asset', 'LIKE', "%{$request->get('model_asset')}%");
        }

        if ($request->get('serial_no')) {
            $query->where('serial_no', 'LIKE', "%{$request->get('serial_no')}%");
        }

        if ($request->get('fullname')) {
            $query->where('fullname', 'LIKE', "%{$request->get('fullname')}%");
        }

        if ($request->get('project_code')) {
            $query->where('project_code', 'LIKE', "%{$request->get('project_code')}%");
        }

        if ($request->get('dept_name')) {
            $query->where('dept_name', 'LIKE', "%{$request->get('dept_name')}%");
        }

        if ($request->get('inventory_status')) {
            $query->where('inventory_status', 'LIKE', "%{$request->get('inventory_status')}%");
        }

        if ($request->get('transfer_status')) {
            $query->where('transfer_status', 'LIKE', "%{$request->get('transfer_status')}%");
        }

        return DataTables::of($query)
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
            ->addColumn('brand_name', function ($inventories) {
                return $inventories->brand_name ? $inventories->brand_name : $inventories->brand;
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
                return $inventories->location_name ? $inventories->location_name : $inventories->location;
            })
            ->addColumn('quantity', function ($inventories) {
                return $inventories->quantity;
            })
            ->addColumn('inventory_status', function ($inventories) {
                if ($inventories->inventory_status == 'Good') {
                    return '<span class="badge badge-primary">Good</span>';
                } elseif ($inventories->inventory_status == 'Broken') {
                    return '<span class="badge badge-danger">Broken</span>';
                } elseif ($inventories->inventory_status == 'Lost') {
                    return '<span class="badge badge-dark">Lost</span>';
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
            ->addColumn('action', 'inventories.action')
            ->rawColumns(['inventory_status', 'transfer_status', 'action'])
            ->toJson();
    }

    function generateInvNum()
    {
        // generate inventory no
        $year = date('y');
        $month = date('m');
        $number = Inventory::max('id') + 1;
        $inv_no = str_pad($number, 6, '0', STR_PAD_LEFT);
        return $year . $month . $inv_no;
    }

    public function create($employee_id = null)
    {
        $title = 'Inventories';
        $subtitle = 'Add Inventory';
        $employees = Employee::where('status', '1')->orderBy('fullname', 'asc')->get();
        $employee = Employee::where('id', $employee_id)->first();
        $assets = Asset::join('categories', 'categories.id', '=', 'assets.category_id')
            ->select('assets.*', 'categories.category_name')
            ->where('asset_status', '1')
            ->orderBy('category_name', 'asc')
            ->orderBy('asset_name', 'asc')
            ->get();
        $brands = Brand::where('brand_status', '1')->orderBy('brand_name', 'asc')->get();
        $projects = Project::where('project_status', '1')->orderBy('project_code', 'asc')->get();
        $departments = Department::where('dept_status', '1')->orderBy('dept_name', 'asc')->get();
        $locations = Location::where('location_status', '1')->orderBy('location_name', 'asc')->get();
        $components = Component::where('component_status', '1')->orderBy('component_name', 'asc')->get();

        // generate inventory no
        $inv_no = static::generateInvNum();

        return view('inventories.create', compact('title', 'subtitle', 'employees', 'employee', 'assets', 'brands', 'projects', 'departments', 'locations', 'components', 'inv_no', 'employee_id'));
    }

    public function store(Request $request)
    {
        $id_employee = $request->input('id_employee');

        $request->validate([
            'inventory_no' => 'required',
            'input_date' => 'required',
            'asset_id' => 'required',
            'employee_id' => 'required',
            'project_id' => 'required',
            'department_id' => 'required',
            'inventory_status' => 'required',
            'brand_id' => 'required',
            'location_id' => 'required',
            'model_asset' => 'required',
            'quantity' => 'required'
        ]);

        $docNum = $request->input('inventory_no');
        // check if inventory number exist
        if (Inventory::where('inventory_no', $docNum)->exists()) {
            // generate new inventory no
            $docNum = static::generateInvNum();
            $request->request->add(['inventory_no' => $docNum]);
        }

        $data = $request->all();
        $inventory = new Inventory();
        $inventory->inventory_no = $data['inventory_no'];
        $inventory->input_date = $data['input_date'];
        $inventory->asset_id = $data['asset_id'];
        $inventory->employee_id = $data['employee_id'];
        $inventory->project_id = $data['project_id'];
        $inventory->department_id = $data['department_id'];
        $inventory->brand_id = $data['brand_id'];
        // $inventory->brand = $data['brand'];
        $inventory->model_asset = $data['model_asset'];
        $inventory->serial_no = $data['serial_no'];
        $inventory->part_no = $data['part_no'];
        $inventory->po_no = $data['po_no'];
        $inventory->quantity = $data['quantity'];
        $inventory->remarks = $data['remarks'];
        $inventory->created_by = auth()->user()->id;
        $inventory->reference_no = $data['reference_no'];
        $inventory->reference_date = $data['reference_date'];
        $inventory->location_id = $data['location_id'];
        // $inventory->location = $data['location'];
        if ($data['inventory_status'] == 'Lost') {
            $inventory->inventory_status = "Lost";
            $inventory->transfer_status = "Discarded";
        } else {
            $inventory->inventory_status = $data['inventory_status'];
            $inventory->transfer_status = "Available";
        }
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

    public function show(Inventory $inventory, $employee_id = null)
    {
        $title = 'Inventories';
        $subtitle = 'Detail Inventory';
        $inventory = Inventory::leftJoin('employees', 'inventories.employee_id', '=', 'employees.id')
            ->leftJoin('assets', 'inventories.asset_id', '=', 'assets.id')
            ->leftJoin('brands', 'inventories.brand_id', '=', 'brands.id')
            ->leftJoin('projects', 'inventories.project_id', '=', 'projects.id')
            ->leftJoin('departments', 'inventories.department_id', '=', 'departments.id')
            ->leftJoin('locations', 'inventories.location_id', '=', 'locations.id')
            ->select('inventories.*', 'employees.nik', 'employees.fullname', 'assets.asset_name', 'brands.brand_name', 'projects.project_code', 'projects.project_name', 'departments.dept_name', 'locations.location_name')
            ->find($inventory->id);

        $specifications = Specification::with('component')->where('inventory_id', $inventory->id)->get();
        $images = DB::table('images')->where('inventory_no', $inventory->inventory_no)->get();
        $basts = DB::table('basts')
            ->where('inventory_id', $inventory->id)
            ->select('basts.*')
            ->get();
        // dd($inventory->asset);

        return view('inventories.show', compact('title', 'subtitle', 'inventory', 'specifications', 'images', 'basts', 'employee_id'));
    }


    public function edit($inventory_id, $employee_id = null)
    {
        $title = 'Inventories';
        $subtitle = 'Edit Inventory';
        $inventory = Inventory::find($inventory_id);
        $employees = Employee::where('status', '1')->orderBy('fullname', 'asc')->get();
        $employee = Employee::where('id', $employee_id)->first();
        $assets = Asset::join('categories', 'categories.id', '=', 'assets.category_id')
            ->select('assets.*', 'categories.category_name')
            ->where('asset_status', '1')
            ->orderBy('category_name', 'asc')
            ->orderBy('asset_name', 'asc')
            ->get();
        $brands = Brand::where('brand_status', '1')->orderBy('brand_name', 'asc')->get();
        $projects = Project::where('project_status', '1')->orderBy('project_code', 'asc')->get();
        $departments = Department::where('dept_status', '1')->orderBy('dept_name', 'asc')->get();
        $locations = Location::where('location_status', '1')->orderBy('location_name', 'asc')->get();
        $components = Component::where('component_status', '1')->orderBy('component_name', 'asc')->get();

        $specifications = Specification::with('component')->where('inventory_id', $inventory_id)->get();

        return view('inventories.edit', compact('title', 'subtitle', 'employees', 'employee', 'employee_id', 'assets', 'brands', 'projects', 'departments', 'locations', 'components', 'inventory', 'specifications'));
    }

    public function update(Request $request, Inventory $inventory)
    {
        $id_employee = $request->input('id_employee');

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
            'brand_id' => 'required',
            'location_id' => 'required',
            'model_asset' => 'required',
        ]);

        $inventory = Inventory::find($inventory->id);
        $inventory->inventory_no = $request->inventory_no;
        $inventory->input_date = $request->input_date;
        $inventory->asset_id = $request->asset_id;
        $inventory->employee_id = $request->employee_id;
        $inventory->project_id = $request->project_id;
        $inventory->department_id = $request->department_id;
        $inventory->brand_id = $request->brand_id;
        // $inventory->brand = $request->brand;
        $inventory->model_asset = $request->model_asset;
        $inventory->serial_no = $request->serial_no;
        $inventory->part_no = $request->part_no;
        $inventory->po_no = $request->po_no;
        $inventory->quantity = $request->quantity;
        $inventory->remarks = $request->remarks;
        $inventory->created_by = auth()->user()->id;
        $inventory->reference_no = $request->reference_no;
        $inventory->reference_date = $request->reference_date;
        $inventory->location_id = $request->location_id;
        // $inventory->location = $request->location;
        $inventory->inventory_status = $request->inventory_status;
        $inventory->transfer_status = $request->transfer_status;
        $inventory->is_active = $request->is_active;
        $inventory->save();

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

        if ($id_employee) {
            return redirect('employees/' . $id_employee)->with('success', 'Inventory successfully updated!');
        } else {
            return redirect('inventories/' . $inventory->id)->with('success', 'Inventory successfully updated!');
        }
    }

    public function destroy(Inventory $inventory, $employee_id = null)
    {
        $images = Image::where('inventory_no', $inventory->inventory_no)->get();
        foreach ($images as $image) {
            // delete image
            $img = public_path('images/' . $image->inventory_no . '/' . $image->filename);
            if (file_exists($img)) {
                unlink($img);
                Image::where('id', $image->id)->delete();
            }
        }
        Specification::where('inventory_id', $inventory->id)->delete();
        Inventory::find($inventory->id)->delete();

        if ($employee_id) {
            return redirect('employees/' . $employee_id)->with('success', 'Inventory successfully deleted!');
        } else {
            return redirect()->route('inventories.index')->with('success', 'Inventory successfully deleted!');
        }
    }

    public function transfer($id)
    {
        $title = 'Inventories';
        $subtitle = 'Inventory Transfer';
        $employees = Employee::where('status', '1')->orderBy('fullname', 'asc')->get();
        $projects = Project::where('project_status', '1')->orderBy('project_code', 'asc')->get();
        $departments = Department::where('dept_status', '1')->orderBy('dept_name', 'asc')->get();
        $locations = Location::where('location_status', '1')->orderBy('location_name', 'asc')->get();

        $inventory = Inventory::with('employee', 'asset', 'project', 'department', 'brand', 'location')->find($id);
        $specifications = Specification::with('component')->where('inventory_id', $id)->get();

        return view('inventories.transfer', compact('title', 'subtitle', 'employees', 'inventory', 'specifications', 'projects', 'departments', 'locations'));
    }

    public function transferProcess($id, Request $request)
    {
        $request->validate([
            'input_date' => 'required',
            'employee_id' => 'required',
            'remarks' => 'required',
        ]);

        $data = $request->all();

        $inventory = Inventory::find($id);
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


        // store new data

        $new_inventory = new Inventory();
        $new_inventory->inventory_no = $data['inventory_no'];
        $new_inventory->input_date = $data['input_date'];
        $new_inventory->asset_id = $data['asset_id'];
        $new_inventory->employee_id = $data['employee_id'];
        $new_inventory->project_id = $data['project_id'];
        $new_inventory->department_id = $data['department_id'];
        $new_inventory->brand_id = $data['brand_id'];
        // $new_inventory->brand = $data['brand'];
        $new_inventory->model_asset = $data['model_asset'];
        $new_inventory->serial_no = $data['serial_no'];
        $new_inventory->part_no = $data['part_no'];
        $new_inventory->po_no = $data['po_no'];
        $new_inventory->quantity = $data['quantity'];
        $new_inventory->remarks = $data['remarks'];
        $new_inventory->created_by = auth()->user()->id;
        $new_inventory->reference_no = $data['reference_no'];
        $new_inventory->reference_date = $data['reference_date'];
        $new_inventory->location_id = $data['location_id'];
        // $new_inventory->location = $data['location'];
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
        return (new InventoryExport)->download('inventories.xlsx');
    }

    public function import()
    {
        $title = 'Inventories';
        $subtitle = 'Import Inventories';

        return view('inventories.import', compact('title', 'subtitle'));
    }

    public function importProcess(Request $request)
    {
        // dd($request->all());
        $this->validate($request, [
            'brand' => 'mimes:xls,xlsx',
            'location' => 'mimes:xls,xlsx',
            'inventory' => 'mimes:xls,xlsx',
        ]);
        $brand = $request->file('brand');
        $location = $request->file('location');
        $inventory = $request->file('inventory');

        if ($request->hasFile('brand')) {
            $import_brand = new BrandImport;
            $import_brand->import($brand);

            if ($import_brand->failures()->isNotEmpty()) {
                return back()->withFailures($import_brand->failures());
            }
        }

        if ($request->hasFile('location')) {
            $import_location = new LocationImport;
            $import_location->import($location);

            if ($import_location->failures()->isNotEmpty()) {
                return back()->withFailures($import_location->failures());
            }
        }

        if ($request->hasFile('inventory')) {
            $import_inventory = new InventoryImport;
            $import_inventory->import($inventory);

            if ($import_inventory->failures()->isNotEmpty()) {
                return back()->withFailures($import_inventory->failures());
            }
        }

        return redirect('inventories')->with('success', 'Data successfully imported!');
    }

    public function qrcode($id)
    {
        $inventory = Inventory::leftJoin('assets', 'inventories.asset_id', '=', 'assets.id')
            ->leftJoin('categories', 'assets.category_id', '=', 'categories.id')
            ->leftJoin('brands', 'inventories.brand_id', '=', 'brands.id')
            ->leftJoin('locations', 'inventories.location_id', '=', 'locations.id')
            ->select('inventories.*', 'assets.asset_name', 'categories.category_name', 'brands.brand_name', 'locations.location_name')
            ->find($id);

        // $content = "No. = " . $inventory->inventory_no . "\n" .
        //     "Asset = " . $inventory->asset_name . "\n" .
        //     "Merk = " . $inventory->brand_name . "\n" .
        //     "Lokasi = " . $inventory->location_name . "\n";

        // $content = URL::route('inventories.qrcodeJson', $inventory->id);
        $content = 'http://10.10.110.97/arka-api/api/inventories/qrcode/' . $inventory->id;

        $result = Builder::create()
            ->writer(new PngWriter())
            ->writerOptions([])
            ->data($content)
            ->encoding(new Encoding('UTF-8'))
            ->errorCorrectionLevel(new ErrorCorrectionLevelHigh())
            ->size(400)
            ->margin(0)
            ->roundBlockSizeMode(new RoundBlockSizeModeMargin())
            // ->labelText('This is the label')
            // ->labelFont(new NotoSans(20))
            // ->labelAlignment(new LabelAlignmentCenter())
            ->build();

        $result->saveToFile(public_path('../storage/app/public/qrcode/qr-' . $inventory->id . '.png'));

        // set dissableLogging() to prevent log
        $inventory->disableLogging();
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
            $inventory->disableLogging();
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

        $inventory = Inventory::leftJoin('assets', 'inventories.asset_id', '=', 'assets.id')
            ->leftJoin('categories', 'assets.category_id', '=', 'categories.id')
            ->leftJoin('brands', 'inventories.brand_id', '=', 'brands.id')
            ->leftJoin('projects', 'inventories.project_id', '=', 'projects.id')
            ->leftJoin('locations', 'inventories.location_id', '=', 'locations.id')
            ->leftJoin('employees', 'inventories.employee_id', '=', 'employees.id')
            ->select('inventories.*', 'assets.asset_name', 'categories.category_name', 'brands.brand_name', 'locations.location_name', 'employees.fullname', 'employees.nik', 'projects.project_code')
            ->find($id);
        return view('inventories.qrcode', compact('title', 'inventory', 'tag'));
    }

    public function print_qrcode_employee($id)
    {
        $title = 'QR Code Inventory Data';
        $tag = 'qrcodes';

        $inventories = Inventory::leftJoin('assets', 'inventories.asset_id', '=', 'assets.id')
            ->leftJoin('categories', 'assets.category_id', '=', 'categories.id')
            ->leftJoin('brands', 'inventories.brand_id', '=', 'brands.id')
            ->leftJoin('projects', 'inventories.project_id', '=', 'projects.id')
            ->leftJoin('locations', 'inventories.location_id', '=', 'locations.id')
            ->leftJoin('employees', 'inventories.employee_id', '=', 'employees.id')
            ->select('inventories.*', 'assets.asset_name', 'categories.category_name', 'brands.brand_name', 'locations.location_name', 'employees.fullname', 'employees.nik', 'projects.project_code')
            ->where('employees.id', '=', $id)
            ->where('inventories.qrcode', '!=', null)
            ->orderBy('inventories.id', 'desc')
            ->get();
        return view('inventories.qrcode', compact('title', 'inventories', 'tag'));
    }

    public function addImages($id, Request $request)
    {
        $inventory = Inventory::find($id);

        $this->validate($request, [
            'filename' => 'required',
            'filename.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        if ($request->hasfile('filename')) {
            $directories = Storage::directories('public/images/' . $inventory->inventory_no);
            if (count($directories) == 0) {
                $path = public_path() . '/images/' . $inventory->inventory_no;
                File::makeDirectory($path, $mode = 0777, true, true);
            }
            foreach ($request->file('filename') as $image) {
                $name = $image->getClientOriginalName();
                $image->move(public_path() . '/images/' . $inventory->inventory_no, $name);

                $image = new Image();
                $image->filename = $name;
                $image->inventory_no = $inventory->inventory_no;

                $image->save();
            }

            return back()->with('success', 'Images uploaded successfully');
        } else {
            return back()->with('error', 'Images failed to upload');
        }
    }

    public function deleteImage($id)
    {
        $image = Image::find($id);
        // delete image
        $img = public_path('images/' . $image->inventory_no . '/' . $image->filename);
        if (file_exists($img)) {
            unlink($img);
            Image::where('id', $image->id)->delete();
        }

        return back()->with('success', 'Image successfully deleted!');
    }

    public function deleteImages($inventory_no)
    {
        $images = Image::where('inventory_no', $inventory_no)->get();
        foreach ($images as $image) {
            // delete image
            $img = public_path('images/' . $image->inventory_no . '/' . $image->filename);
            if (file_exists($img)) {
                unlink($img);
                Image::where('id', $image->id)->delete();
            }
        }
        return back()->with('success', 'All images successfully deleted!');
    }

    public function qrcodeJson($id)
    {
        // $qrcode = Inventory::find($id);

        $qrcode = Inventory::leftJoin('employees', 'inventories.employee_id', '=', 'employees.id')
            ->leftJoin('assets', 'inventories.asset_id', '=', 'assets.id')
            ->leftJoin('categories', 'assets.category_id', '=', 'categories.id')
            ->leftJoin('brands', 'inventories.brand_id', '=', 'brands.id')
            ->leftJoin('projects', 'inventories.project_id', '=', 'projects.id')
            ->leftJoin('departments', 'inventories.department_id', '=', 'departments.id')
            ->leftJoin('locations', 'inventories.location_id', '=', 'locations.id')
            ->select('inventories.*', 'employees.nik', 'employees.fullname', 'assets.asset_name', 'brands.brand_name', 'projects.project_code', 'projects.project_name', 'departments.dept_name', 'locations.location_name', 'categories.category_name')
            ->where('inventories.id', $id)
            ->first();

        // make validation if the query is null
        if ($qrcode == null) {
            return response()->json([
                'message' => 'Data not found'
            ], 404);
        } else {
            return response()->json(
                [
                    'message' => 'Data found',
                    'data' => $qrcode
                ],
                200
            );
        }


        // $xml = new SimpleXMLElement('<inventory/>');
        // $item = $xml->addChild('item');
        // $item->addChild('inventory_no', $inventory->inventory_no);
        // $item->addChild('asset', $inventory->asset_name);
        // $item->addChild('category', $inventory->category_name);
        // $item->addChild('brand', $inventory->brand_name);
        // $item->addChild('model', $inventory->model_asset);
        // $item->addChild('sn', $inventory->serial_no);
        // $item->addChild('pn', $inventory->part_no);
        // $item->addChild('qty', $inventory->quantity);
        // $item->addChild('inventory_status', $inventory->inventory_status);
        // $item->addChild('transfer_status', $inventory->transfer_status);
        // $item->addChild('project_code', $inventory->project_code);
        // $item->addChild('project_name', $inventory->project_name);
        // $item->addChild('department', $inventory->dept_name);
        // $item->addChild('location_name', $inventory->location_name);
        // $item->addChild('pic', $inventory->fullname);
        // $item->addChild('nik', $inventory->nik);
        // $item->addChild('input_date', date('d-M-Y', strtotime($inventory->input_date)));

        // // return response()->json($inventory);
        // return response($xml->asXML(), 200)
        //     ->header('Content-Type', 'application/xml');
    }
}
