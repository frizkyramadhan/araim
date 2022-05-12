<?php

namespace App\Http\Controllers;

use App\Models\Component;
use Illuminate\Http\Request;

class ComponentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = 'Components';
        $subtitle = 'List of components';
        $components = Component::orderBy('component_name', 'asc')->get();

        return view('components.index', compact('title', 'subtitle', 'components'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = 'Components';
        $subtitle = 'Add Components';

        return view('components.create', compact('title', 'subtitle'));
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
            'component_name' => 'required|unique:components'
        ]);

        $component = new Component;
        $component->component_name = $request->component_name;
        $component->save();

        return redirect()->route('components.index')->with('success', 'Component added successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Component  $component
     * @return \Illuminate\Http\Response
     */
    public function show(Component $component)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Component  $component
     * @return \Illuminate\Http\Response
     */
    public function edit(Component $component)
    {
        $title = 'Components';
        $subtitle = 'Edit Components';

        return view('components.edit', compact('title', 'subtitle' ,'component'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Component  $component
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Component $component)
    {
        $request->validate([
            'component_name' => 'required'
        ]);

        Component::where('id', $component->id)->update([
            'component_name' => $request->component_name,
            'component_status' => $request->component_status
        ]);

        return redirect()->route('components.index')->with('success', 'Component updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Component  $component
     * @return \Illuminate\Http\Response
     */
    public function destroy(Component $component)
    {
        $component->delete();

        return redirect()->route('components.index')->with('success', 'Component deleted successfully');
    }
}
