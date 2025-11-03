<?php

namespace App\Http\Controllers\Reminder;

use App\Http\Controllers\Controller;
use App\Models\Reminder;
use Illuminate\Http\Request;

class ReminderController extends Controller
{

    public function store(Request $request)
    {
        return $request;
        /*$newReminder = Reminder::firstOrNew(['ref_id' => $request->ref_id]);*/
        $newReminder = new Reminder();
        $newReminder->title  = $request->title;
        $newReminder->note = $request->note;
        $newReminder->date = $request->date;
        $newReminder->time = $request->time;
        $newReminder->save();

        return [
            'type'=>000,
        ];

    }
}
