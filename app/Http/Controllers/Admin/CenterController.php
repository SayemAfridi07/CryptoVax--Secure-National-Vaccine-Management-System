<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCenterRequest;
use App\Models\Center;
use App\Models\Vaccine;
use App\Models\VaccineStock;
use Illuminate\Http\Request;

class CenterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $centers = Center::all();
        return view('admin.centers.index', ['centers' => $centers]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.centers.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCenterRequest $request)
    {
        $validated = $request->validated();

        $center = new Center($validated);

        $saved = $center->save();

        if ($saved)
            return 1;

        return response('Center couldn\'t be created!', 500);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Center $center)
    {
        return view('admin.centers.partials.show', ['center' => $center]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Center $center)
    {
        return view('admin.centers.edit', ['center' => $center]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreCenterRequest $request, Center $center)
    {
        $validated = $request->validated();

        $center->fill($validated);

        $saved = $center->update();

        if ($saved)
            return 1;

        return response('Center Data Couldn\'t be Updated!', 500);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Center $center)
    {
        if($center->registrations()->exists())
        {
            return 'vaccine application exists in this center. cannot delete.';
        }

        foreach($center->operators as $user)
        {
            $user->center_id = null;
            $user->update();
        }

        $isDeleted = $center->delete();

        if($isDeleted) return 1;
        else return response()->json(['message' => 'failed deteling the center'], 500);
    }

    public function sendVaccine(Center $center)
    {
        $vaccines = Vaccine::all();
        return view('admin.centers.send-vaccine', compact('center', 'vaccines'));
    }

    public function sendVaccineStore(Request $request, Center $center)
    {
        $vaccineId = $request->input('vaccine_id', 0);
        $quantity = $request->input('quantity', 0);

        $vaccine = Vaccine::findOrFail($vaccineId);

        $centralVaccineStock = VaccineStock::where('vaccine_id', $vaccine->id)
            ->whereNull('center_id')
            ->firstOrFail();

        if($centralVaccineStock->quantity < $quantity)
        {
            return 2;
        }

        $centerVaccineStock = VaccineStock::where('vaccine_id', $vaccine->id)
            ->where('center_id', $center->id)
            ->first();

        if ($centerVaccineStock)
        {
            $centerVaccineStock->quantity += $quantity;
            $centerVaccineStock = $centerVaccineStock->update();

        }
        else
        {
            $centerVaccineStock = VaccineStock::create([
                'vaccine_id' => $vaccine->id,
                'center_id' => $center->id,
                'quantity' => $quantity,
            ]);
        }

        if (!$centerVaccineStock)
            return response('Updating Failed!', 500);
        else
        {
            $centralVaccineStock->quantity -= $quantity;
            $centralVaccineStock->update();
            
            return 1;
        }
    }
}
