<?php

namespace App\Http\Controllers;

use App\Models\Project;

class ProjectController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Project::class, 'project');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //return view('projects.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //return view('projects.create');
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {
        //return view('projects.show', ['project' => $project]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project)
    {
        //return view('projects.edit', ['project' => $project]);
    }

    /**
     * Show the form for copying a project.
     */
    public function copy()
    {
        $this->authorize('create', Project::class);

        //return view('projects.copy');
    }

    /**
     * Display registration form for project
     */
    public function register(Project $project)
    {
        //return view('projects.register', ['project' => $project]);
    }
}
