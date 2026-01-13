<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Brand;
use App\Models\Project;
use App\Models\Employee;
use App\Models\Location;
use App\Models\Inventory;
use App\Models\Department;
use App\Models\Category;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Spatie\Activitylog\Models\Activity;

class DashboardController extends Controller
{
    public function index()
    {
        $title = 'Dashboard';
        $subtitle = 'Dashboard';

        $total_inv = DB::table('inventories')->select(DB::raw('SUM(quantity) as sum'))->where('transfer_status', '!=', 'Mutated')->where('is_active', '=', '1')->first();
        $good_inv = DB::table('inventories')->select(DB::raw('SUM(quantity) as sum'))->where('inventory_status', '=', 'Good')->where('transfer_status', '!=', 'Mutated')->where('is_active', '=', '1')->first();
        $broken_inv = DB::table('inventories')->select(DB::raw('SUM(quantity) as sum'))->where('inventory_status', '=', 'Broken')->where('transfer_status', '!=', 'Mutated')->where('is_active', '=', '1')->first();
        $asset_sum = DB::table('inventories')->join('assets', 'inventories.asset_id', '=', 'assets.id')
            ->select('assets.id', 'assets.asset_name', DB::raw('SUM(quantity) as asset_sum'))
            ->where('inventory_status', '=', 'Good')
            ->where('transfer_status', '!=', 'Mutated')
            ->where('is_active', '=', '1')
            ->groupBy('asset_name', 'assets.id')
            ->get();
        $total_emp = Employee::get()->count();

        $projects = DB::table('inventories')
            ->join('projects', 'inventories.project_id', '=', 'projects.id')
            ->select('projects.id', 'projects.project_code', 'projects.project_name', DB::raw('COUNT(inventories.id) as count'))
            ->where('inventories.transfer_status', '!=', 'Mutated')
            ->where('is_active', '=', '1')
            ->groupBy('project_code', 'projects.id', 'project_name')
            ->orderBy('project_code', 'asc')
            ->get();

        $projectAssets = DB::table('inventories')
            ->join('projects', 'inventories.project_id', '=', 'projects.id')
            ->join('assets', 'inventories.asset_id', '=', 'assets.id')
            ->select('projects.id', 'projects.project_code', 'assets.asset_name', DB::raw('COUNT(inventories.id) as count'))
            ->where('inventories.transfer_status', '!=', 'Mutated')
            ->where('is_active', '=', '1')
            ->groupBy('project_code', 'projects.id', 'asset_name')
            ->orderBy('count', 'desc')
            ->get();

        // Get IT Equipment category
        $itEquipmentCategory = Category::where('category_name', 'IT Equipment')->first();

        // Get inventories IT Equipment without BAST (only for admin)
        $inventoriesWithoutBast = collect();
        if ($itEquipmentCategory) {
            $inventoriesWithoutBast = DB::table('inventories')
                ->leftJoin('assets', 'inventories.asset_id', '=', 'assets.id')
                ->leftJoin('categories', 'assets.category_id', '=', 'categories.id')
                ->leftJoin('basts', 'inventories.id', '=', 'basts.inventory_id')
                ->leftJoin('employees', 'inventories.employee_id', '=', 'employees.id')
                ->leftJoin('projects', 'inventories.project_id', '=', 'projects.id')
                ->select('inventories.id', 'inventories.inventory_no', 'inventories.model_asset', 'inventories.serial_no', 'employees.fullname', 'projects.project_code', 'assets.asset_name')
                ->where('categories.id', $itEquipmentCategory->id)
                ->where('inventories.is_active', '=', '1')
                ->whereNull('basts.id')
                ->orderBy('inventories.created_at', 'desc')
                ->get();
        }

        // Get data for filters (only for admin)
        $assets = Asset::where('asset_status', '1')->orderBy('asset_name', 'asc')->get();
        $brands = Brand::where('brand_status', '1')->orderBy('brand_name', 'asc')->get();
        $departments = Department::where('dept_status', '1')->orderBy('dept_name', 'asc')->get();

        return view('dashboard.dashboard', compact('title', 'subtitle', 'total_inv', 'good_inv', 'broken_inv', 'total_emp', 'asset_sum', 'projects', 'projectAssets', 'inventoriesWithoutBast', 'assets', 'brands', 'departments'));
    }

    public function getInventoriesWithoutBast(Request $request)
    {
        if ($request->ajax()) {
            // Get IT Equipment category
            $itEquipmentCategory = Category::where('category_name', 'IT Equipment')->first();

            if (!$itEquipmentCategory) {
                return DataTables::of(collect())->toJson();
            }

            $query = DB::table('inventories')
                ->leftJoin('assets', 'inventories.asset_id', '=', 'assets.id')
                ->leftJoin('categories', 'assets.category_id', '=', 'categories.id')
                ->leftJoin('basts', 'inventories.id', '=', 'basts.inventory_id')
                ->leftJoin('employees', 'inventories.employee_id', '=', 'employees.id')
                ->leftJoin('projects', 'inventories.project_id', '=', 'projects.id')
                ->leftJoin('brands', 'inventories.brand_id', '=', 'brands.id')
                ->leftJoin('departments', 'inventories.department_id', '=', 'departments.id')
                ->select('inventories.id', 'inventories.inventory_no', 'inventories.model_asset', 'inventories.serial_no', 'inventories.input_date', 'inventories.inventory_status', 'inventories.transfer_status', 'employees.fullname', 'projects.project_code', 'assets.asset_name', 'brands.brand_name', 'departments.dept_name')
                ->where('categories.id', $itEquipmentCategory->id)
                ->where('inventories.is_active', '=', '1')
                ->whereNull('basts.id')
                ->orderBy('inventories.created_at', 'desc');

            // Apply filters
            if ($request->get('date1') && $request->get('date2')) {
                $query->whereBetween('inventories.input_date', [$request->get('date1'), $request->get('date2')]);
            }

            if ($request->get('inventory_no')) {
                $query->where('inventories.inventory_no', 'LIKE', "%{$request->get('inventory_no')}%");
            }

            if ($request->get('asset_name')) {
                $query->where('assets.asset_name', 'LIKE', "%{$request->get('asset_name')}%");
            }

            if ($request->get('brand_name')) {
                $query->where('brands.brand_name', 'LIKE', "%{$request->get('brand_name')}%");
            }

            if ($request->get('model_asset')) {
                $query->where('inventories.model_asset', 'LIKE', "%{$request->get('model_asset')}%");
            }

            if ($request->get('serial_no')) {
                $query->where('inventories.serial_no', 'LIKE', "%{$request->get('serial_no')}%");
            }

            if ($request->get('fullname')) {
                $query->where('employees.fullname', 'LIKE', "%{$request->get('fullname')}%");
            }

            if ($request->get('project_code')) {
                $query->where('projects.project_code', 'LIKE', "%{$request->get('project_code')}%");
            }

            if ($request->get('dept_name')) {
                $query->where('departments.dept_name', 'LIKE', "%{$request->get('dept_name')}%");
            }

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('inventory_no', function ($inventory) {
                    return '<b>' . $inventory->inventory_no . '</b>';
                })
                ->addColumn('input_date', function ($inventory) {
                    return $inventory->input_date ? date('d-M-Y', strtotime($inventory->input_date)) : '-';
                })
                ->addColumn('brand_name', function ($inventory) {
                    return $inventory->brand_name ?? '-';
                })
                ->addColumn('dept_name', function ($inventory) {
                    return $inventory->dept_name ?? '-';
                })
                ->addColumn('action', function ($inventory) {
                    $viewBtn = '<a href="' . url('inventories/' . $inventory->id) . '" class="btn btn-sm btn-info" target="_blank"><i class="fas fa-eye"></i> View</a>';
                    $addBastBtn = '<a href="' . url('basts/create?inventory_id=' . $inventory->id) . '" class="btn btn-sm btn-primary"><i class="fas fa-plus"></i> Add BAST</a>';
                    return $viewBtn . ' ' . $addBastBtn;
                })
                ->rawColumns(['inventory_no', 'action'])
                ->toJson();
        }
    }

    public function summary($id)
    {
        $asset = Asset::find($id);
        $title = 'Summary - ' . $asset->asset_name;
        $subtitle = 'Summary - ' . $asset->asset_name;
        $summary = Inventory::with('asset', 'project', 'department', 'employee', 'brand')
            ->where('inventories.asset_id', '=', $id)
            ->where('inventories.inventory_status', '=', 'Good')
            ->where('inventories.transfer_status', '=', 'Available')
            ->orderBy('inventories.id', 'desc')
            ->get();

        return view('dashboard.summary', compact('title', 'subtitle', 'summary'));
    }

    public function logs()
    {
        $title = 'Activity Logs';
        $subtitle = 'Activity Logs';

        $logs = Activity::join('users', 'activity_log.causer_id', '=', 'users.id')
            ->select('activity_log.*', 'users.name')
            ->orderBy('activity_log.id', 'desc')
            ->get();

        return view('dashboard.log', compact('title', 'subtitle', 'logs'));
    }

    public function json()
    {
        $logs = Activity::join('users', 'activity_log.causer_id', '=', 'users.id')
            ->select('activity_log.*', 'users.name')
            ->orderBy('activity_log.id', 'desc')
            ->get();

        return response()->json($logs);
    }

    public function getLogs(Request $request)
    {
        if ($request->ajax()) {
            $logs = Activity::join('users', 'activity_log.causer_id', '=', 'users.id')
                ->select('activity_log.*', 'users.name')
                ->orderBy('activity_log.id', 'desc');

            return DataTables::of($logs)
                ->addIndexColumn()
                ->addColumn('created_at', function ($logs) {
                    return date('d-M-Y', strtotime($logs->created_at));
                })
                ->addColumn('log_name', function ($logs) {
                    return $logs->log_name;
                })
                ->addColumn('description', function ($logs) {
                    if ($logs->description == 'created') {
                        return '<span class="badge badge-primary">created</span>';
                    } elseif ($logs->description == 'updated') {
                        return '<span class="badge badge-success">updated</span>';
                    } elseif ($logs->description == 'deleted') {
                        return '<span class="badge badge-danger">deleted</span>';
                    }
                })
                ->editColumn('properties', function ($logs) {
                    $output = '';
                    foreach ($logs->properties as $property) {
                        $output .= 'Inventory No: <b>' . ($property['inventory_no'] ?? '-') . '</b><br>';
                        $output .= 'Employee: ' . ($property['employee.fullname'] ?? '-') . '<br>';
                        $output .= 'Project: ' . ($property['project.project_code'] ?? '-') . '<br>';
                        $output .= 'Department: ' . ($property['department.dept_name'] ?? '-') . '<br>';
                        $output .= 'Asset: ' . ($property['asset.asset_name'] ?? '-') . '<br>';
                        $output .= 'Brand: ' . ($property['brand.brand_name'] ?? '-') . '<br>';
                        $output .= 'Quantity: ' . ($property['quantity'] ?? '-') . '<br>';
                        $output .= 'Location: ' . ($property['location.location_name'] ?? '-') . '<br>';
                        $output .= 'Inventory Status: ' . ($property['inventory_status'] ?? '-') . '<br>';
                        $output .= 'Transfer Status: ' . ($property['transfer_status'] ?? '-') . '<br><br>';
                    }
                    return $output;
                })
                ->addColumn('name', function ($logs) {
                    return $logs->name;
                })
                ->addColumn('action', 'dashboard.action')
                ->filter(function ($instance) use ($request) {
                    if (!empty($request->get('date1') && !empty($request->get('date2')))) {
                        $instance->where(function ($w) use ($request) {
                            $date1 = $request->get('date1');
                            $date2 = $request->get('date2');
                            $w->whereBetween('created_at', array($date1, $date2));
                        });
                    }
                    if (!empty($request->get('log_name'))) {
                        $instance->where(function ($w) use ($request) {
                            $log_name = $request->get('log_name');
                            $w->orWhere('log_name', 'LIKE', '%' . $log_name . '%');
                        });
                    }
                    if (!empty($request->get('description'))) {
                        $instance->where(function ($w) use ($request) {
                            $description = $request->get('description');
                            $w->orWhere('description', 'LIKE', '%' . $description . '%');
                        });
                    }
                    if (!empty($request->get('properties'))) {
                        $instance->where(function ($w) use ($request) {
                            $properties = $request->get('properties');
                            $w->orWhere('properties', 'LIKE', '%' . $properties . '%');
                        });
                    }
                    if (!empty($request->get('name'))) {
                        $instance->where(function ($w) use ($request) {
                            $name = $request->get('name');
                            $w->orWhere('name', 'LIKE', '%' . $name . '%');
                        });
                    }
                    if (!empty($request->get('search'))) {
                        $instance->where(function ($w) use ($request) {
                            $search = $request->get('search');
                            $w->orWhere('created_at', 'LIKE', "%$search%")
                                ->orWhere('log_name', 'LIKE', "%$search%")
                                ->orWhere('description', 'LIKE', "%$search%")
                                ->orWhere('properties', 'LIKE', "%$search%")
                                ->orWhere('name', 'LIKE', "%$search%");
                        });
                    }
                }, true)
                ->rawColumns(['description', 'properties', 'action'])
                ->toJson();
        }
    }
}
