<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Laptop_invoice;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LaptopController extends Controller
{
    public function store(Request $request)
    {
        $FileName = date('YzHis') . $request->UploadBestand->getClientOriginalName();
        $request->UploadBestand->storeAs('LaptopBewijzen', $FileName);

        $date_current = new DateTime();
        $date_given    = new DateTime($request->datum);

        if ($date_current < $date_given) {
            session()->flash('danger', 'De aankoopdatum is ongeldig.');
            return back();
        }

        if ($request->bedrag < 1) {
            session()->flash('danger', 'Het aankoopbedrag is ongeldig.');
            return back();
        }

        $NewInvoice = new Laptop_invoice();
        $NewInvoice->filepath = $FileName;
        $NewInvoice->user_id = \Auth::user()->id;
        $NewInvoice->amount = $request->bedrag;
        $NewInvoice->invoice_description = $request->reden;
        $NewInvoice->purchase_date = $request->datum;
        $NewInvoice->user_id_Cost_center_manager = 2;
        $NewInvoice->save();
        session()->flash('success', 'De aanvraag is goed ontvangen.');
        return back();
    }
}
