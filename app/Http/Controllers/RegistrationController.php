<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRegistrationRequest;
use App\Models\Center;
use App\Models\Dose;
use App\Models\Registration;
use Illuminate\Http\Request;

class RegistrationController extends Controller
{
    function create() 
    {
        return view('front.registration.create', ['centers' => Center::all()]);
    }

    function store(StoreRegistrationRequest $request) 
    {
        $validated = $request->validated();

        $registration = new Registration;

        $registration->center_id = $validated['center_id'];
        $registration->user_id = auth()->user()->id;
        
        if($registration->save())
        {
            return redirect(route('front.registration.status'))->with(['success' => 'Registration Successful!']);
        }
        else {
            return redirect(route('front.registration.create'))->with(['error' => 'Registration Failed! Something went wrong while adding data to the database.']);
        }
    }

    function status(Request $request)
    {
        $registration = auth()->user()->registration;

        return view('front.registration.show', ['registration' => $registration]);
    }

    function update_date(Request $request)
    {
        $dose = Dose::findOrFail($request->dose_id);

        if($dose->registration->user_id == auth()->user()->id)
        {
            $dose->scheduled_date = $request->date;
            $dose->save();

            return response()->json(['message' => 'Date updated']);
        }
        else{
            abort(403);
        }
    }

    function cancel_appointment(Request $request)
    {
        $dose = Dose::findOrFail($request->dose_id);

        if($dose->registration->user_id == auth()->user()->id)
        {
            $dose->scheduled_date = null;
            $dose->save();

            return response()->json(['message' => 'Appointment cancelled.']);
        }
        else{
            abort(403);
        }
    }
}
