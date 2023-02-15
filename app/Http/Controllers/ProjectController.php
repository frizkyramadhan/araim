<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function __construct()
    {
        $this->middleware('check_role:admin,superuser');
    }

    public function index()
    {
        $title = 'Projects';
        $subtitle = 'List of Projects';
        $projects = Project::orderBy('project_code', 'asc')->get();

        return view('projects.index', compact('title', 'subtitle', 'projects'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = 'Projects';
        $subtitle = 'Add Projects';

        return view('projects.create', compact('title', 'subtitle'));
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
            'project_code' => 'required|unique:projects',
            'project_name' => 'required'
        ]);

        $project = new Project;
        $project->project_code = $request->project_code;
        $project->project_name = $request->project_name;
        $project->save();

        return redirect()->route('projects.index')->with('success', 'Project created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function show(Project $project)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function edit(Project $project)
    {
        $title = 'Projects';
        $subtitle = 'Edit Projects';

        return view('projects.edit', compact('title', 'subtitle', 'project'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Project $project)
    {
        $request->validate([
            'project_code' => 'required',
            'project_name' => 'required'
        ]);

        Project::where('id', $project->id)->update([
            'project_code' => $request->project_code,
            'project_name' => $request->project_name,
            'project_status' => $request->project_status
        ]);

        return redirect()->route('projects.index')->with('success', 'Project updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function destroy(Project $project)
    {
        Project::destroy($project->id);
        return redirect()->route('projects.index')->with('success', 'Project deleted successfully');
    }
}
