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

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = 'BAST';
        $subtitle = 'Berita Acara Serah Terima';
        $submits = Employee::where('status', '1')->orderBy('fullname', 'asc')->get();
        $receives = Employee::where('status', '1')->orderBy('fullname', 'asc')->get();

        return view('basts.create', compact('title', 'subtitle', 'submits', 'receives'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Bast  $bast
     * @return \Illuminate\Http\Response
     */
    public function show(Bast $bast)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Bast  $bast
     * @return \Illuminate\Http\Response
     */
    public function edit(Bast $bast)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Bast  $bast
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Bast $bast)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Bast  $bast
     * @return \Illuminate\Http\Response
     */
    public function destroy(Bast $bast)
    {
        //
    }
}
