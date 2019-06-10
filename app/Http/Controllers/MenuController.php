<?php

namespace App\Http\Controllers;

use App\Menu;
use App\Group;
use App\Boss;
use App\Http\Requests\Addmenu;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function store(Addmenu $request)
    {
        $token=request()->bearerToken();
        $data=$request->validated();
        return Menu::create($data);
    }
}
