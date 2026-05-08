<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vaccine;
use App\Models\VaccineStock;
use Illuminate\Http\Request;

class VaccineStockController extends Controller
{
    public function index(Request $request)
    {
        $vaccineStocks = VaccineStock::doesntHave('center')->get() ?? [];

        return view('admin.vaccine-stock.index', ['vaccineStocks' => $vaccineStocks]);
    }

    public function create(Request $request)
    {
        $vaccines = Vaccine::all();

        return view('admin.vaccine-stock.create', ['vaccines' => $vaccines]);
    }

    public function store(Request $request)
    {
        $vaccine = Vaccine::findOrFail($request->input('vaccine_id', 0));
        $quantity = request('quantity', 0);

        $vaccineStock = VaccineStock::where('vaccine_id', $vaccine->id)
            ->whereNull('center_id')
            ->first();

        if ($vaccineStock)
        {
            $vaccineStock->quantity += $quantity;
            $vaccineStock = $vaccineStock->update();

        } else {
            $vaccineStock = VaccineStock::create([
                'vaccine_id' => $vaccine->id,
                'center_id' => null,
                'quantity' => $quantity,
            ]);
        }

        if (!$vaccineStock)
            return response('Updating Failed!', 500);
        else
            return 1;
    }
}
