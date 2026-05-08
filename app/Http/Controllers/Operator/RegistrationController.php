<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use App\Models\Dose;
use App\Models\Registration;
use App\Models\Vaccine;
use App\Models\VaccineStock;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
class RegistrationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $registrations = auth()->user()?->center?->registrations;

        return view('operator.registrations.index', compact('registrations'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Registration $registration)
    {
        return view('operator.registrations.show', compact('registration'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Registration $registration)
    {
        $user = $registration->user;

        $user->phone = $request->input('phone', $user->phone);
        $user->dob = $request->input('dob', $user->dob);
        $user->save();

        return 1;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function getDoses(Registration $registration)
    {
        return view('operator.registrations.doses', compact('registration'));
    }

    public function doseCreate(Registration $registration)
    {
        $vaccines = Vaccine::all();

        $selectedVaccine = $registration->doses()->whereHas('vaccine')->first()?->vaccine;

        return view('operator.registrations.doses.create', compact('registration', 'vaccines', 'selectedVaccine'));
    }

    public function doseStore(Request $request, Registration $registration)
    {
        $validOperator = auth()->user()->center == $registration->center;

        if (!$validOperator)
            abort(401);

        $doseType = $request->input('dose_type');

        if ($registration->doses()->where('dose_type', $doseType)->exists()) {
            return response()->json(['message' => ucfirst($doseType) . ' dose already assigned'], 500);
        }

        if ($registration->doses()->whereNull('taken_date')->exists()) {
            return response()->json(['message' => 'Assigned dose need to be taken first'], 500);
        }

        if ($doseType == 'second' && $registration->doses()->where('dose_type', 'first')->doesntExist()) {
            return response()->json(['message' => 'First dose not taken yet.'], 500);
        }

        if ($doseType == 'booster' && $registration->doses()->where('dose_type', 'second')->doesntExist()) {
            return response()->json(['message' => 'Second dose not taken yet.'], 500);
        }

        $dose = Dose::create([
            'registration_id' => $registration->id,
            'vaccine_id' => $request->input('vaccine'),
            'dose_type' => $request->input('dose_type'),
            'scheduled_date' => $request->input('scheduled_date'),
            'given_by' => null,
        ]);

        if (!$dose) {
            return 2;
        }
        $this->sendVaccineScheduleEmail($dose, $registration);
        return 1;
    }
    public function sendVaccineScheduleEmail($dose, $registration)
    {

        try {
            $userEmail = $registration->user->email;
            $message = "Your " . ucfirst($dose->dose_type) . " Dose Is Scheduled at " . $dose->scheduled_date;

            Mail::raw($message, function ($mail) use ($userEmail) {
                $mail->to($userEmail)
                    ->subject('Vaccine Dose Notification');
            });

            return redirect()->back()->with('success', 'Email sent successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to send email.');
        }
    }

    public function markDoseAsTaken(Registration $registration, Dose $dose)
    {
        $validOperator = auth()->user()->center == $registration->center;
        $validDose = $dose->registration->id == $registration->id;
        $validRequest = $validOperator && $validDose;

        $vaccineStock = VaccineStock::where('center_id', $registration->center->id)->where('vaccine_id', $dose->vaccine->id)->first();

        if (!$vaccineStock || $vaccineStock->quantity < 1) {
            return redirect(route('operator.registrations.doses', $registration->id))->with(['error' => 'No vial available in stock for ' . $dose->vaccine->name]);
        }

        if (!$validRequest)
            return redirect(route('operator.registrations.doses', $registration->id))->with(['error' => 'Unauthorized request']);

        if ($dose->taken_date)
            return redirect(route('operator.registrations.doses', $registration->id))->with(['error' => 'Already taken']);

        if (!$dose->scheduled_date)
            return redirect(route('operator.registrations.doses', $registration->id))->with(['error' => 'Not yet scheduled']);

        if (!$dose->taken_date) {
            $dose->taken_date = now();
            $dose->given_by = auth()->user()->id;
            $dose->update();

            $vaccineStock->quantity -= 1;
            $vaccineStock->update();
        }

        $this->sendVaccineReadyEmail($dose, $registration);

        return redirect(route('operator.registrations.doses', $registration->id))->with(['success' => 'Vaccined marked taken']);
    }
    public function sendVaccineReadyEmail($dose, $registration)
    {

        try {
            $userEmail = $registration->user->email;
            $message = "Your " . ucfirst($dose->dose_type) . " Dose Is Done!";

            Mail::raw($message, function ($mail) use ($userEmail) {
                $mail->to($userEmail)
                    ->subject('Vaccine Dose Notification');
            });

            return redirect()->back()->with('success', 'Email sent successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to send email.');
        }
    }
}
