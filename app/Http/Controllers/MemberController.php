<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Member;
use App\Http\Requests\Memberdata;

class MemberController extends Controller
{
    public function store (Memberdata $request)
    {
        $data=$request->validated();
        $data['password']=Hash::make($data['password']);
        return Member::create($data);
    }
}
