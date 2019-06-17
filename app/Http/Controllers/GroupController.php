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

    public function update(Group $group)
    {
        $group->update(request()->validate([
            'name' => ['string','unique:groups,name'],
            'time_limit' => ['boolean'],
            'preset_time' => ['date_format:H:i', 'required_if:time_limit,1']
        ]));
        return Group::findorfail($group);
    }

    public function destroy(Group $group)
    {
        $group->delete();
        return 'already delete';
    }
}
