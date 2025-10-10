<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProjectManageController extends Controller
{
    public function create()
    {
        return view('pages.projects.create');
    }

    public function list()
    {
        return view('pages.projects.list');
    }
    public function show()
    {
        return view('pages.projects.show');
    }
}
