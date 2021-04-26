<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Laptop_invoice;
use App\Laptop_reimbursement;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LaptopController extends Controller
{
    public function store(Request $request)
    {
        $this->validate($request,[
            'bedrag' => 'required|numeric|min:0',
            'reden' => 'required',
            'datum' => 'required|before_or_equal:today',
            'UploadBestand' => 'required',
        ], [
            'bedrag.required' => 'Het bedrag voor de laptopvergoeding moet ingevuld zijn.',
            'bedrag.numeric' => 'Het bedrag voor de laptopvergoeding moet een getal zijn.',
            'bedrag.min' => 'Het bedrag voor de laptopvergoeding moet groter of gelijk zijn aan 0.',
            'reden.required' => 'De verkaring voor de aanvraag moet ingevuld zijn.',
            'datum.required' => 'De aankoopdatum moet ingevuld zijn.',
            'datum.before_or_equal' => 'De aankoopdatum moet een dag voor vandaag of vandaag zijn.',
            'UploadBestand.required' => 'Er moet een bestand geüpload zijn.',
        ]);

        $date_current = new DateTime();
        $date_given = new DateTime($request->datum);

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

            $NewLapReimb = new Laptop_reimbursement();
            $NewLapReimb->laptop_invoice_id = $NewInvoice->id;
            $NewLapReimb->user_id_Cost_center_manager = 2;
            $NewLapReimb->payment_date = null;
            $NewLapReimb->save();


            session()->flash('success', 'De aanvraag is goed ontvangen.');
            return back();
    }
}
