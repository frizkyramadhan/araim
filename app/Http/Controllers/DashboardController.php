<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Employee;
use App\Models\Inventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Activitylog\Models\Activity;

class DashboardController extends Controller
{
    public function index()
    {
        $title = 'Dashboard';
        $subtitle = 'Dashboard';

        $total_inv = DB::table('inventories')->select(DB::raw('SUM(quantity) as sum'))->where('transfer_status', '!=', 'Mutated')->first();
        $good_inv = DB::table('inventories')->select(DB::raw('SUM(quantity) as sum'))->where('inventory_status', '=', 'Good')->where('transfer_status', '!=', 'Mutated')->first();
        $broken_inv = DB::table('inventories')->select(DB::raw('SUM(quantity) as sum'))->where('inventory_status', '=', 'Broken')->where('transfer_status', '!=', 'Mutated')->first();
        $asset_sum = DB::table('inventories')->join('assets', 'inventories.asset_id', '=', 'assets.id')
            ->select('assets.id', 'assets.asset_name', DB::raw('SUM(quantity) as asset_sum'))
            ->where('inventory_status', '=', 'Good')
            ->where('transfer_status', '!=', 'Mutated')
            ->groupBy('asset_name', 'assets.id')
            ->get();
        $total_emp = Employee::get()->count();

        $projects = DB::table('inventories')
            ->join('projects', 'inventories.project_id', '=', 'projects.id')
            ->select('projects.id', 'projects.project_code', 'projects.project_name', DB::raw('COUNT(inventories.id) as count'))
            ->where('inventories.transfer_status', '!=', 'Mutated')
            ->groupBy('project_code', 'projects.id', 'project_name')
            ->get();

        $projectAssets = DB::table('inventories')
            ->join('projects', 'inventories.project_id', '=', 'projects.id')
            ->join('assets', 'inventories.asset_id', '=', 'assets.id')
            ->select('projects.id', 'projects.project_code', 'assets.asset_name', DB::raw('COUNT(inventories.id) as count'))
            ->where('inventories.transfer_status', '!=', 'Mutated')
            ->groupBy('project_code', 'projects.id', 'asset_name')
            ->orderBy('count', 'desc')
            ->get();

        return view('dashboard.dashboard', compact('title', 'subtitle', 'total_inv', 'good_inv', 'broken_inv', 'total_emp', 'asset_sum', 'projects', 'projectAssets'));
    }

    public function summary($id)
    {
        $asset = Asset::find($id);
        $title = 'Summary - ' . $asset->asset_name;
        $subtitle = 'Summary - ' . $asset->asset_name;
        $summary = Inventory::with('asset', 'project', 'department', 'employee')
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
        return $logs;
    }
}
