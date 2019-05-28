<?php

namespace App\Http\Controllers;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Http\Requests\Bossdata;
use App\Boss;

class BossController extends Controller
{
    public function store(Bossdata $request)
    {
        $data=$request->validated();
        $data['password']=Hash::make($data['password']);
        return Boss::create($data);
    }
}
