<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function getEvents(Request $request)
    {
        try {
            $event = $request->id;
            if($event){
                $data =  Event::find($event);
            } else {
                $data = Event::with('business')->get();
            }

            return api_success('Events', $data);

        } catch(\Exception $ex) {

            return api_error('message: ' . $ex->getMessage(), 500);

        }

    }

}
