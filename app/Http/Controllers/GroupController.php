<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Group;
use App\Boss;

class GroupController extends Controller
{
    public function store()
    {
        $group=Group::create(request()->validate([
            'name'=>['required', 'string', 'unique:groups,name']
        ]));
        return $group;
    }

    public function index()
    {
        return Group::all();
    }
}
