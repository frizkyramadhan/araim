<?php

namespace App\Http\Controllers;

use App\Models\Bapb;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BapbController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = 'BAPB';
        $subtitle = 'Berita Acara Peminjaman Barang';
        $bapbs = DB::table('bapbs')
            ->leftJoin('employees as submit', 'bapbs.bapb_submit', '=', 'submit.id')
            ->leftJoin('employees as receive', 'bapbs.bapb_receive', '=', 'receive.id')
            ->select('bapbs.bapb_no', 'bapbs.bapb_reg', 'bapb_date', 'receive.fullname as receive_name', 'submit.fullname as submit_name')
            ->distinct()->orderBy('bapb_no', 'desc')->get();
        // dd($bapbs);
        return view('bapbs.index', compact('title', 'subtitle', 'bapbs'));
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
        $title = 'BAPB';
        $subtitle = 'Berita Acara Peminjaman Barang';
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
        $number = Bapb::max('bapb_no') + 1;
        $bapb_no = str_pad($number, 6, '0', STR_PAD_LEFT);

        return view('bapbs.create', compact('title', 'subtitle', 'submits', 'receives', 'bapb_no', 'year', 'month'));
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
            'bapb_no' => 'required',
            'bapb_reg' => 'required',
            'bapb_date' => 'required',
            'bapb_submit' => 'required',
            'bapb_receive' => 'required',
            'duration' => 'required',
            'inventory_id' => 'required',
        ]);

        $data = $request->all();
        foreach ($data['inventory_id'] as $inventory => $value) {
            $inventories = array(
                'bapb_no' => $data['bapb_no'],
                'bapb_reg' => $data['bapb_reg'],
                'bapb_date' => $data['bapb_date'],
                'bapb_submit' => $data['bapb_submit'],
                'bapb_receive' => $data['bapb_receive'],
                'duration' => $data['duration'],
                'inventory_id' => $data['inventory_id'][$inventory],
            );
            // dd($inventories);
            Bapb::create($inventories);
        }

        return redirect()->route('bapbs.index')->with('success', 'Data berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Bapb  $bapb
     * @return \Illuminate\Http\Response
     */
    public function show($bapb_no)
    {
        $title = 'BAPB';
        $subtitle = 'Berita Acara Peminjaman Barang';
        $bapb = DB::table('bapbs')
            ->leftJoin('employees as submit', 'bapbs.bapb_submit', '=', 'submit.id')
            ->leftJoin('positions as pos_submit', 'submit.position_id', '=', 'pos_submit.id')
            ->leftJoin('employees as receive', 'bapbs.bapb_receive', '=', 'receive.id')
            ->leftJoin('positions as pos_receive', 'receive.position_id', '=', 'pos_receive.id')
            ->select('bapbs.*', 'receive.fullname as receive_name', 'receive.nik as receive_nik', 'pos_receive.position_name as receive_pos', 'submit.fullname as submit_name', 'submit.nik as submit_nik', 'pos_submit.position_name as submit_pos')
            ->where('bapb_no', '=', $bapb_no)
            ->orderBy('bapb_no', 'desc')->first();
        $bapb_row = DB::table('bapbs')
            ->leftJoin('inventories', 'bapbs.inventory_id', '=', 'inventories.id')
            ->leftJoin('assets', 'inventories.asset_id', '=', 'assets.id')
            ->select('bapbs.bapb_no', 'inventories.*', 'assets.asset_name')
            ->where('bapb_no', '=', $bapb_no)
            ->get();
        // dd($bapb, $bapb_row);
        return view('bapbs.show', compact('title', 'subtitle', 'bapb', 'bapb_row'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Bapb  $bapb
     * @return \Illuminate\Http\Response
     */
    public function edit($bapb_no)
    {
        $title = 'BAPB';
        $subtitle = 'Berita Acara Peminjaman Barang';

        $submits = DB::table('employees')
            ->join('positions', 'employees.position_id', '=', 'positions.id')
            ->join('departments', 'positions.department_id', '=', 'departments.id')
            ->select('employees.*', 'departments.dept_name')
            ->where('departments.dept_name', '=', 'Information Technology')
            ->where('status', '1')->orderBy('fullname', 'asc')->get();
        $receives = Employee::where('status', '1')->orderBy('fullname', 'asc')->get();

        $bapb = DB::table('bapbs')
            ->leftJoin('employees as submit', 'bapbs.bapb_submit', '=', 'submit.id')
            ->leftJoin('positions as pos_submit', 'submit.position_id', '=', 'pos_submit.id')
            ->leftJoin('employees as receive', 'bapbs.bapb_receive', '=', 'receive.id')
            ->leftJoin('positions as pos_receive', 'receive.position_id', '=', 'pos_receive.id')
            ->select('bapbs.*', 'receive.fullname as receive_name', 'receive.nik as receive_nik', 'pos_receive.position_name as receive_pos', 'submit.fullname as submit_name', 'submit.nik as submit_nik', 'pos_submit.position_name as submit_pos')
            ->where('bapb_no', '=', $bapb_no)
            ->orderBy('bapb_no', 'desc')->first();

        if (empty($bapb)) {
            return redirect('bapbs');
        }

        $inventories = DB::table('inventories')
            ->leftJoin('assets', 'inventories.asset_id', '=', 'assets.id')
            ->leftJoin('employees', 'inventories.employee_id', '=', 'employees.id')
            ->leftJoin('bapbs', 'bapbs.inventory_id', '=', 'inventories.id')
            ->select('inventories.*', 'assets.asset_name', 'bapbs.inventory_id')
            ->where('employees.id', '=', $bapb->bapb_receive)
            ->orderBy('inventories.id', 'desc')
            ->distinct()->get();

        $bapb_row = DB::table('inventories')
            ->leftJoin('assets', 'inventories.asset_id', '=', 'assets.id')
            ->leftJoin('employees', 'inventories.employee_id', '=', 'employees.id')
            ->leftJoin('bapbs', 'bapbs.inventory_id', '=', 'inventories.id')
            ->select('inventories.*', 'assets.asset_name', 'bapbs.inventory_id', 'bapbs.bapb_no', 'bapbs.id as bapb_id')
            ->where('employees.id', '=', $bapb->bapb_receive)
            ->where('bapbs.bapb_no', '=', $bapb_no)
            ->orderBy('inventories.id', 'desc')
            ->distinct()
            ->get();

        // dd($bapb_row);

        return view('bapbs.edit', compact('title', 'subtitle', 'bapb', 'receives', 'bapb_no', 'submits', 'inventories', 'bapb_row'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Bapb  $bapb
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $bapb_no)
    {
        $bapb_detail = DB::table('bapbs')->where('bapb_no', $bapb_no)->get();
        foreach ($bapb_detail as $detail) {
            if ($request->has('deleteRow' . $detail->id)) {
                DB::table('bapbs')->where('id', '=', $detail->id)->delete();
                return redirect('bapbs/' . $bapb_no . '/edit')->with('success', 'Item deleted successfully');
            }
        }

        $request->validate([
            'bapb_no' => 'required',
            'bapb_reg' => 'required',
            'bapb_date' => 'required',
            'bapb_submit' => 'required',
            'bapb_receive' => 'required',
            'duration' => 'required',
        ]);

        $data = $request->all();
        if (!empty($request->inventory_id)) {
            foreach ($data['inventory_id'] as $inventory => $value) {
                DB::table('bapbs')->where('bapb_no', $bapb_no)->update([
                    'bapb_no' => $data['bapb_no'],
                    'bapb_reg' => $data['bapb_reg'],
                    'bapb_date' => $data['bapb_date'],
                    'bapb_submit' => $data['bapb_submit'],
                    'bapb_receive' => $data['bapb_receive'],
                    'duration' => $data['duration']
                ]);
                $inventories = array(
                    'bapb_no' => $data['bapb_no'],
                    'bapb_reg' => $data['bapb_reg'],
                    'bapb_date' => $data['bapb_date'],
                    'bapb_submit' => $data['bapb_submit'],
                    'bapb_receive' => $data['bapb_receive'],
                    'duration' => $data['duration'],
                    'inventory_id' => $data['inventory_id'][$inventory],
                );
                // dd($inventories);
                Bapb::create($inventories);
                return redirect('bapbs/' . $bapb_no)->with('success', 'BAPB updated successfully');
            }
        } else {
            DB::table('bapbs')->where('bapb_no', $bapb_no)->update([
                'bapb_no' => $data['bapb_no'],
                'bapb_reg' => $data['bapb_reg'],
                'bapb_date' => $data['bapb_date'],
                'bapb_submit' => $data['bapb_submit'],
                'bapb_receive' => $data['bapb_receive'],
                'duration' => $data['duration']
            ]);
            return redirect('bapbs/' . $bapb_no)->with('success', 'BAPB updated successfully');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Bapb  $bapb
     * @return \Illuminate\Http\Response
     */
    public function destroy($bapb_no)
    {
        DB::table('bapbs')->where('bapb_no', $bapb_no)->delete();
        return back()->with('success', 'BAPB deleted successfully');
    }

    public function print($bapb_no)
    {
        $title = 'BAPB';
        $subtitle = 'Berita Acara Peminjaman Barang';
        $bapb = DB::table('bapbs')
            ->leftJoin('employees as submit', 'bapbs.bapb_submit', '=', 'submit.id')
            ->leftJoin('positions as pos_submit', 'submit.position_id', '=', 'pos_submit.id')
            ->leftJoin('employees as receive', 'bapbs.bapb_receive', '=', 'receive.id')
            ->leftJoin('positions as pos_receive', 'receive.position_id', '=', 'pos_receive.id')
            ->select('bapbs.*', 'receive.fullname as receive_name', 'receive.nik as receive_nik', 'pos_receive.position_name as receive_pos', 'submit.fullname as submit_name', 'submit.nik as submit_nik', 'pos_submit.position_name as submit_pos')
            ->where('bapb_no', '=', $bapb_no)
            ->orderBy('bapb_no', 'desc')->first();
        $bapb_row = DB::table('bapbs')
            ->leftJoin('inventories', 'bapbs.inventory_id', '=', 'inventories.id')
            ->leftJoin('assets', 'inventories.asset_id', '=', 'assets.id')
            ->select('bapbs.bapb_no', 'inventories.*', 'assets.asset_name')
            ->where('bapb_no', '=', $bapb_no)
            ->get();
        // dd($bapb, $bapb_row);
        return view('bapbs.print', compact('title', 'subtitle', 'bapb', 'bapb_row'));
    }
}
