<?php

namespace App\Http\Controllers;

use App\Http\Requests\SetDeadline;
use App\Deadline;
use Illuminate\Http\Request;

class DeadlineController extends Controller
{
    public function timeStore(SetDeadline $request)
    {
        $data=$request->validated();
        if (Deadline::where('which_date', $data['which_date'])->where('group_id', $data['group_id'])->exists()) {
            return 'This data has existed.';
        }
        return Deadline::create($data);
    }
}
