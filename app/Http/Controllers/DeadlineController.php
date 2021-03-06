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
            return response(['error' => 'This data has existed.'], 422);
        }
        return Deadline::create($data);
    }

    public function timeIndex()
    {
        $startDate=request()->get('start_date');
        $endDate=request()->get('end_date');
        $groupId=request()->get('group_id');
        if (!isset($startDate) || !isset($endDate)) {
            return response(['error' => 'Date error'], 422);
        }
        return Deadline::where('group_id', $groupId)
            ->whereDate('which_date', '>=', $startDate)
            ->whereDate('which_date', '<=', $endDate)
            ->get();
    }

    public function timeUpdate(Deadline $deadline)
    {
        $deadline->update(request()->validate([
            'deadline_time' => ['required', 'date_format:H:i']
        ]));
        return $deadline;
    }

    public function timeDestroy(Deadline $deadline)
    {
        $deadline->delete();
        return 'already deleted this data';
    }
}
