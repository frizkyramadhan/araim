<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = "Brands";
        $subtitle = "List of Brands";
        $brands = Brand::orderBy('brand_name', 'asc')->get();
        return view('brands.index', compact('title', 'subtitle', 'brands'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = "Brands";
        $subtitle = "Add Brand";

        return view('brands.create', compact('title', 'subtitle'));
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
            'brand_name' => 'required|unique:brands',
        ], [
            'brand_name.required' => 'Brand name is required',
            'brand_name.unique' => 'Brand name already exists',
        ]);

        $brand = new Brand();
        $brand->brand_name = $request->brand_name;
        $brand->brand_status = 1;
        $brand->save();

        return redirect()->route('brands.index')->with('success', 'Brand added successfully');
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
        $title = "Brands";
        $subtitle = "Edit Brand";
        $brand = Brand::find($id);
        return view('brands.edit', compact('title', 'subtitle', 'brand'));
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
                'brand_name' => 'required',
                'brand_status' => 'required',
            ],
            [
                'brand_name.required' => 'Brand name is required',
                'brand_status.required' => 'Brand status is required',
            ]
        );

        Brand::find($id)->update([
            'brand_name' => $request->brand_name,
            'brand_status' => $request->brand_status,
        ]);

        return redirect()->route('brands.index')->with('success', 'Brand updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Brand::find($id)->delete();
        return redirect()->route('brands.index')->with('success', 'Brand deleted successfully');
    }
}
