<?php

namespace App\Http\Controllers;

use App\Menu;
use App\Group;
use App\Boss;
use App\Flavor;
use App\Http\Requests\Addmenu;
use App\Http\Requests\Addflavor;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function store(Addmenu $request)
    {
        $token=request()->bearerToken();
        $data=$request->validated();
        return Menu::create($data);
    }

    public function flavorstore(Addflavor $request)
    {
        $token=request()->bearerToken();
        $data=$request->validated();
        return Flavor::create($data);
    }
}
