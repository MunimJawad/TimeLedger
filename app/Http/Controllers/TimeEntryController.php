<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Task;
use App\Models\TimeEntry;
use Carbon\Carbon;

class TimeEntryController extends Controller
{
    public function start($taskId){
        $task=Task::findOrFail($taskId);

        $running=$task->runningEntryFor(Auth::id());

        if($running){
            return back()->with('error','You already have a running timer for this task');
        }

        TimeEntry::create([
            'task_id'=>$task->id,
            'user_id'=>Auth::id(),
            'start_time'=>Carbon::now()
        ]);

        return back()->with('success','Timer Added');
    }

    public function stop($taskId){
        $task=Task::findOrFail($taskId);
        $running=$task->runningEntryFor(Auth::id());
        
        if(!$running){
            return back()->with('error','No running timer found for this task.');
        }
        $running->end_time=Carbon::now();
        $running->duration=$running->end_time->diffInSeconds($running->start_time);
        $running->save();
        return back()->with('success','Timer stopped');        
    }
}
