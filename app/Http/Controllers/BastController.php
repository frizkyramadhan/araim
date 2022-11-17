<?php

namespace App\Http\Controllers;

use App\Models\Bast;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BastController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = 'BAST';
        $subtitle = 'Berita Acara Serah Terima';
        $basts = DB::table('basts')
            ->leftJoin('employees as submit', 'basts.bast_submit', '=', 'submit.id')
            ->leftJoin('employees as receive', 'basts.bast_receive', '=', 'receive.id')
            ->select('basts.bast_no', 'basts.bast_reg', 'bast_date', 'receive.fullname as receive_name', 'submit.fullname as submit_name')
            ->distinct()->orderBy('bast_no', 'desc')->get();
        // dd($basts);
        return view('basts.index', compact('title', 'subtitle', 'basts'));
    }

    public function getInventories(Request $request)
    {
        if ($request->ajax()) {
            // get inevntories based on employee id get from request
            $employee_id = $request->get('employee_id');
            $inventories = DB::table('inventories')
                ->leftJoin('employees', 'inventories.employee_id', '=', 'employees.id')
                ->leftJoin('assets', 'inventories.asset_id', '=', 'assets.id')
                ->select('inventories.*', 'employees.nik', 'employees.fullname', 'assets.asset_name')
                ->where('employees.id', $employee_id)
                ->orderBy('inventories.id', 'desc')
                ->get();

            return $inventories;
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = 'BAST';
        $subtitle = 'Berita Acara Serah Terima';
        $submits = DB::table('employees')
            ->join('positions', 'employees.position_id', '=', 'positions.id')
            ->join('departments', 'positions.department_id', '=', 'departments.id')
            ->select('employees.*', 'departments.dept_name')
            ->where('departments.dept_name', '=', 'Information Technology')
            ->where('status', '1')->orderBy('fullname', 'asc')->get();
        $receives = Employee::where('status', '1')->orderBy('fullname', 'asc')->get();

        // generate inventory no
        $year = date('Y');
        $month = date('m');
        $number = Bast::max('bast_no') + 1;
        $bast_no = str_pad($number, 6, '0', STR_PAD_LEFT);

        return view('basts.create', compact('title', 'subtitle', 'submits', 'receives', 'bast_no', 'year', 'month'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // validate
        $request->validate([
            'bast_no' => 'required',
            'bast_reg' => 'required',
            'bast_date' => 'required',
            'bast_submit' => 'required',
            'bast_receive' => 'required',
            'inventory_id' => 'required',
        ]);

        $data = $request->all();
        foreach ($data['inventory_id'] as $inventory => $value) {
            $inventories = array(
                'bast_no' => $data['bast_no'],
                'bast_reg' => $data['bast_reg'],
                'bast_date' => $data['bast_date'],
                'bast_submit' => $data['bast_submit'],
                'bast_receive' => $data['bast_receive'],
                'inventory_id' => $data['inventory_id'][$inventory],
            );
            // dd($inventories);
            Bast::create($inventories);
        }

        return redirect()->route('basts.index')->with('success', 'Data berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Bast  $bast
     * @return \Illuminate\Http\Response
     */
    public function show($bast_no)
    {
        $title = 'BAST';
        $subtitle = 'Berita Acara Serah Terima';
        $bast = DB::table('basts')
            ->leftJoin('employees as submit', 'basts.bast_submit', '=', 'submit.id')
            ->leftJoin('positions as pos_submit', 'submit.position_id', '=', 'pos_submit.id')
            ->leftJoin('employees as receive', 'basts.bast_receive', '=', 'receive.id')
            ->leftJoin('positions as pos_receive', 'receive.position_id', '=', 'pos_receive.id')
            ->select('basts.bast_no', 'basts.bast_reg', 'bast_date', 'receive.fullname as receive_name', 'receive.nik as receive_nik', 'pos_receive.position_name as receive_pos', 'submit.fullname as submit_name', 'submit.nik as submit_nik', 'pos_submit.position_name as submit_pos')
            ->where('bast_no', '=', $bast_no)
            ->orderBy('bast_no', 'desc')->first();
        $bast_row = DB::table('basts')
            ->leftJoin('inventories', 'basts.inventory_id', '=', 'inventories.id')
            ->leftJoin('assets', 'inventories.asset_id', '=', 'assets.id')
            ->select('basts.bast_no', 'inventories.*', 'assets.asset_name')
            ->where('bast_no', '=', $bast_no)
            ->get();
        // dd($bast, $bast_row);
        return view('basts.show', compact('title', 'subtitle', 'bast', 'bast_row'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Bast  $bast
     * @return \Illuminate\Http\Response
     */
    public function edit($bast_no)
    {
        $title = 'BAST';
        $subtitle = 'Berita Acara Serah Terima';

        $submits = DB::table('employees')
            ->join('positions', 'employees.position_id', '=', 'positions.id')
            ->join('departments', 'positions.department_id', '=', 'departments.id')
            ->select('employees.*', 'departments.dept_name')
            ->where('departments.dept_name', '=', 'Information Technology')
            ->where('status', '1')->orderBy('fullname', 'asc')->get();
        $receives = Employee::where('status', '1')->orderBy('fullname', 'asc')->get();

        $bast = DB::table('basts')
            ->leftJoin('employees as submit', 'basts.bast_submit', '=', 'submit.id')
            ->leftJoin('positions as pos_submit', 'submit.position_id', '=', 'pos_submit.id')
            ->leftJoin('employees as receive', 'basts.bast_receive', '=', 'receive.id')
            ->leftJoin('positions as pos_receive', 'receive.position_id', '=', 'pos_receive.id')
            ->select('basts.*', 'receive.fullname as receive_name', 'receive.nik as receive_nik', 'pos_receive.position_name as receive_pos', 'submit.fullname as submit_name', 'submit.nik as submit_nik', 'pos_submit.position_name as submit_pos')
            ->where('bast_no', '=', $bast_no)
            ->orderBy('bast_no', 'desc')->first();

        if (empty($bast)) {
            return redirect('basts');
        }

        $inventories = DB::table('inventories')
            ->leftJoin('assets', 'inventories.asset_id', '=', 'assets.id')
            ->leftJoin('employees', 'inventories.employee_id', '=', 'employees.id')
            ->leftJoin('basts', 'basts.inventory_id', '=', 'inventories.id')
            ->select('inventories.*', 'assets.asset_name', 'basts.inventory_id')
            ->where('employees.id', '=', $bast->bast_receive)
            ->orderBy('inventories.id', 'desc')
            ->distinct()->get();

        $bast_row = DB::table('inventories')
            ->leftJoin('assets', 'inventories.asset_id', '=', 'assets.id')
            ->leftJoin('employees', 'inventories.employee_id', '=', 'employees.id')
            ->leftJoin('basts', 'basts.inventory_id', '=', 'inventories.id')
            ->select('inventories.*', 'assets.asset_name', 'basts.inventory_id', 'basts.bast_no', 'basts.id as bast_id')
            ->where('employees.id', '=', $bast->bast_receive)
            ->where('basts.bast_no', '=', $bast_no)
            ->orderBy('inventories.id', 'desc')
            ->distinct()
            ->get();

        // dd($bast_row);

        return view('basts.edit', compact('title', 'subtitle', 'bast', 'receives', 'bast_no', 'submits', 'inventories', 'bast_row'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Bast  $bast
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $bast_no)
    {
        $bast_detail = DB::table('basts')->where('bast_no', $bast_no)->get();
        foreach ($bast_detail as $detail) {
            if ($request->has('deleteRow' . $detail->id)) {
                DB::table('basts')->where('id', '=', $detail->id)->delete();
                return redirect('basts/' . $bast_no . '/edit')->with('success', 'Item deleted successfully');
            }
        }

        $request->validate([
            'bast_no' => 'required',
            'bast_reg' => 'required',
            'bast_date' => 'required',
            'bast_submit' => 'required',
            'bast_receive' => 'required',
        ]);

        $data = $request->all();
        if (!empty($request->inventory_id)) {
            foreach ($data['inventory_id'] as $inventory => $value) {
                DB::table('basts')->where('bast_no', $bast_no)->update([
                    'bast_no' => $data['bast_no'],
                    'bast_reg' => $data['bast_reg'],
                    'bast_date' => $data['bast_date'],
                    'bast_submit' => $data['bast_submit'],
                    'bast_receive' => $data['bast_receive']
                ]);
                $inventories = array(
                    'bast_no' => $data['bast_no'],
                    'bast_reg' => $data['bast_reg'],
                    'bast_date' => $data['bast_date'],
                    'bast_submit' => $data['bast_submit'],
                    'bast_receive' => $data['bast_receive'],
                    'inventory_id' => $data['inventory_id'][$inventory],
                );
                // dd($inventories);
                Bast::create($inventories);
                return redirect('basts/' . $bast_no)->with('success', 'BAST updated successfully');
            }
        } else {
            DB::table('basts')->where('bast_no', $bast_no)->update([
                'bast_no' => $data['bast_no'],
                'bast_reg' => $data['bast_reg'],
                'bast_date' => $data['bast_date'],
                'bast_submit' => $data['bast_submit'],
                'bast_receive' => $data['bast_receive']
            ]);
            return redirect('basts/' . $bast_no)->with('success', 'BAST updated successfully');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Bast  $bast
     * @return \Illuminate\Http\Response
     */
    public function destroy($bast_no)
    {
        DB::table('basts')->where('bast_no', $bast_no)->delete();
        return back()->with('success', 'BAST deleted successfully');
    }

    public function print($bast_no)
    {
        $title = 'BAST';
        $subtitle = 'Berita Acara Serah Terima';
        $bast = DB::table('basts')
            ->leftJoin('employees as submit', 'basts.bast_submit', '=', 'submit.id')
            ->leftJoin('positions as pos_submit', 'submit.position_id', '=', 'pos_submit.id')
            ->leftJoin('employees as receive', 'basts.bast_receive', '=', 'receive.id')
            ->leftJoin('positions as pos_receive', 'receive.position_id', '=', 'pos_receive.id')
            ->select('basts.bast_no', 'basts.bast_reg', 'bast_date', 'receive.fullname as receive_name', 'receive.nik as receive_nik', 'pos_receive.position_name as receive_pos', 'submit.fullname as submit_name', 'submit.nik as submit_nik', 'pos_submit.position_name as submit_pos')
            ->where('bast_no', '=', $bast_no)
            ->orderBy('bast_no', 'desc')->first();
        $bast_row = DB::table('basts')
            ->leftJoin('inventories', 'basts.inventory_id', '=', 'inventories.id')
            ->leftJoin('assets', 'inventories.asset_id', '=', 'assets.id')
            ->select('basts.bast_no', 'inventories.*', 'assets.asset_name')
            ->where('bast_no', '=', $bast_no)
            ->get();
        // dd($bast, $bast_row);
        return view('basts.print', compact('title', 'subtitle', 'bast', 'bast_row'));
    }
}
