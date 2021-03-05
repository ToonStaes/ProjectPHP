<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Laptop_invoice;
use Illuminate\Http\Request;

class LaptopController extends Controller
{
    public function store(Request $request)
    {
        $FileName = $request->UploadBestand->getClientOriginalName();
        $request->UploadBestand->storeAs('LaptopBewijzen', $FileName);

        $NewInvoice = new Laptop_invoice();
        $NewInvoice->filepath = $FileName;
        $NewInvoice->user_id = \Auth::user()->id;
        $NewInvoice->amount = $request->bedrag;
        $NewInvoice->invoice_description = $request->reden;
        $NewInvoice->purchase_date = $request->datum;
        $NewInvoice->user_id_Cost_center_manager = 2;
        $NewInvoice->save();
        return back();
    }
}
