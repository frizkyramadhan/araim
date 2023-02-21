<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function __construct()
    {
        $this->middleware('check_role:admin,superuser');
    }

    public function index()
    {
        $title = "Locations";
        $subtitle = "List of Locations";
        $locations = Location::orderBy('location_name', 'asc')->get();
        return view('locations.index', compact('title', 'subtitle', 'locations'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = "Locations";
        $subtitle = "Add Location";

        return view('locations.create', compact('title', 'subtitle'));
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
            'location_name' => 'required|unique:locations',
        ], [
            'location_name.required' => 'Location name is required',
            'location_name.unique' => 'Location name already exists',
        ]);

        $location = new Location();
        $location->location_name = $request->location_name;
        $location->location_status = 1;
        $location->save();

        return redirect()->route('locations.index')->with('success', 'Location added successfully');
    }

    public function storeFromInventory(Request $request)
    {
        $request->validate([
            'location_name' => 'required|unique:locations',
        ], [
            'location_name.required' => 'Location name is required',
            'location_name.unique' => 'Location name already exists',
        ]);

        $location = new Location();
        $location->location_name = $request->location_name;
        $location->location_status = 1;
        $location->save();

        return redirect()->back()->with('success', 'Location added successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $title = "Locations";
        $subtitle = "Edit Location";
        $location = Location::find($id);
        return view('locations.edit', compact('title', 'subtitle', 'location'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate(
            [
                'location_name' => 'required',
                'location_status' => 'required',
            ],
            [
                'location_name.required' => 'Location name is required',
                'location_status.required' => 'Location status is required',
            ]
        );

        Location::find($id)->update([
            'location_name' => $request->location_name,
            'location_status' => $request->location_status,
        ]);

        return redirect()->route('locations.index')->with('success', 'Location updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Location::find($id)->delete();
        return redirect()->route('locations.index')->with('success', 'Location deleted successfully');
    }
}
