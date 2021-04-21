<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Laptop_invoice;
use App\Mailcontent;
use DateTime;
use Facades\App\Helpers\Json;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use phpDocumentor\Reflection\Types\Integer;

class LaptopController extends Controller
{
    public function store(Request $request)
    {
        $date_current = new DateTime();
        $date_given    = new DateTime($request->datum);

        $iserror = false;
        $errormessage = "";

        if ($date_current < $date_given) {
            $errormessage = $errormessage . "De aankoopdatum is ongeldig. ";
            $iserror = true;
        }

        if ($request->bedrag < 1) {
            $errormessage = $errormessage . "Het aankoopbedrag is ongeldig. ";
            $iserror = true;
        }

        if ($iserror){
            session()->flash('danger', $errormessage);
            return back();
        }
        else{
            $FileName = date('YzHis') . $request->UploadBestand->getClientOriginalName();
            $request->UploadBestand->storeAs('LaptopBewijzen', $FileName);
            $NewInvoice = new Laptop_invoice();
            $NewInvoice->filepath = $FileName;
            $NewInvoice->user_id = \Auth::user()->id;
            $NewInvoice->amount = $request->bedrag;
            $NewInvoice->invoice_description = $request->reden;
            $NewInvoice->purchase_date = $request->datum;
            $NewInvoice->save();
            session()->flash('success', 'De aanvraag is goed ontvangen.');
            return back();
        }
    }

    public function update(Request $request, $id)
    {
        $laptopInvoice = Laptop_invoice::find($id);
        $date_current = new DateTime();
        $date_given    = new DateTime($request->datum);

        $iserror = false;
        $errormessage = "";
        if ($date_current < $date_given) {
            $errormessage = $errormessage . "De aankoopdatum is ongeldig. ";
            $iserror = true;
        }

        if ($request->bedrag < 1) {
            $errormessage = $errormessage . "Het aankoopbedrag is ongeldig. ";
            $iserror = true;
        }

        if ($iserror){
            session()->flash('danger', $errormessage);
            return back();
        }
        elseif ($request->UploadBestand == null){
            $laptopInvoice->amount = $request->bedrag;
            $laptopInvoice->invoice_description = $request->reden;
            $laptopInvoice->purchase_date = $request->datum;
            $laptopInvoice->save();
            return back();
        }

        else{
            $FileName = date('YzHis') . $request->UploadBestand->getClientOriginalName();
            $request->UploadBestand->storeAs('LaptopBewijzen', $FileName);
            $laptopInvoice->amount = $request->bedrag;
            $laptopInvoice->invoice_description = $request->reden;
            $laptopInvoice->purchase_date = $request->datum;
            $laptopInvoice->filepath = $FileName;
            $laptopInvoice->save();
            session()->flash('success', 'Uw aanvraag is aangepast.');
            return back();
        }
    }
}
