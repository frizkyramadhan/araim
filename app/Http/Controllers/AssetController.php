<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Category;
use Illuminate\Http\Request;

class AssetController extends Controller
{
    public function __construct()
    {
        $this->middleware('check_role:admin,superuser');
    }

    public function index()
    {
        $title = "Assets";
        $subtitle = "List of assets";
        $assets = Asset::with('category')
            ->orderBy(Category::select('category_name')->whereColumn('categories.id', 'assets.category_id'))
            ->orderBy('asset_name', 'asc')
            ->get();

        return view('assets.index', compact('title', 'subtitle', 'assets'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = "Assets";
        $subtitle = "Add Asset";
        $categories = Category::where('category_status', '=', '1')->orderBy('category_name', 'asc')->get();

        return view('assets.create', compact('title', 'subtitle', 'categories'));
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
            'asset_name' => 'required|unique:assets',
            'category_id' => 'required',
        ]);

        $asset = new Asset;
        $asset->asset_name = $request->asset_name;
        $asset->category_id = $request->category_id;
        $asset->save();

        return redirect()->route('assets.index')->with('success', 'Asset added successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Asset  $assets
     * @return \Illuminate\Http\Response
     */
    public function show(Asset $asset)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Asset  $assets
     * @return \Illuminate\Http\Response
     */
    public function edit(Asset $asset)
    {
        $title = "Assets";
        $subtitle = "Edit Asset";
        $categories = Category::where('category_status', '=', '1')->orderBy('category_name', 'asc')->get();

        return view('assets.edit', compact('title', 'subtitle', 'asset', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Asset  $assets
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Asset $asset)
    {
        $request->validate([
            'asset_name' => 'required',
            'category_id' => 'required',
        ], [
            'asset_name.required' => 'Asset name is required',
            'category_id.required' => 'Category is required',
        ]);

        Asset::where('id', $asset->id)
            ->update([
                'asset_name' => $request->asset_name,
                'category_id' => $request->category_id,
                'asset_status' => $request->asset_status,
            ]);

        return redirect()->route('assets.index')->with('success', 'Asset updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Asset  $assets
     * @return \Illuminate\Http\Response
     */
    public function destroy(Asset $asset)
    {
        $asset->delete();

        return redirect()->route('assets.index')->with('success', 'Asset deleted successfully');
    }
}
