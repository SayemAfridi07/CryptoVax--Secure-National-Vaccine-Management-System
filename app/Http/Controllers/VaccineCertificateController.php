<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class VaccineCertificateController extends Controller
{
    public function index(Request $request)
    {
        $registration = auth()->user()->registration;
        
        return view('front.vaccine-certificate', ['registration' => $registration]);
    }

    function download(Request $request) {

        $registration = auth()->user()->registration;

        $totalTaken = 0;

        foreach($registration->doses as $dose)
        {
            if($dose)
            {
                if($dose->taken_date)
                {
                    $totalTaken++;
                }
            }
        }

        if($totalTaken < 2)
        {
            return redirect()->back()->with('warning', 'Minimum first two doses are needed for certification');
        }

        $pdf = Pdf::loadView('front.pdf.vaccine-certificate', [
            'user' => auth()->user(),
        ]);

        $rand = rand(1, 500);
        return $pdf->download("vaccine-certificate-" . auth()->user()->name . "-{$rand}.pdf");
    }
}
