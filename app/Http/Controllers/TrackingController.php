<?php

namespace App\Http\Controllers;

use App\Models\Specification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TrackingController extends Controller
{
    public function index(Request $request)
    {
        $title = 'Tracking';
        $subtitle = 'Inventory Tracking';

        $trackings = DB::table('inventories')
            ->leftJoin('projects as p1', 'inventories.project_id', '=', 'p1.id')
            ->leftJoin('employees', 'inventories.employee_id', '=', 'employees.id')
            ->leftJoin('projects as p2', 'employees.project_id', '=', 'p2.id')
            ->leftJoin('departments', 'inventories.department_id', '=', 'departments.id')
            ->leftJoin('assets', 'inventories.asset_id', '=', 'assets.id')
            ->leftJoin('categories', 'assets.category_id', '=', 'categories.id')
            ->select('inventories.*', 'assets.asset_name', 'categories.category_name', 'p1.project_code as p_code_asset', 'p1.project_name as p_name_asset', 'p2.project_code as p_code_emp', 'p2.project_name as p_name_emp', 'employees.nik', 'employees.fullname', 'departments.dept_name')
            ->when($request->search, function($query) use ($request){
            return $query->where('inventories.inventory_no', 'like', '%'.$request->search.'%')->orWhere('inventories.serial_no', 'like', '%'.$request->search.'%');
            })
            ->orderBy('inventories.id', 'desc')->get();

        return view('trackings.index', compact('title', 'subtitle','trackings','request'));
    }
}
