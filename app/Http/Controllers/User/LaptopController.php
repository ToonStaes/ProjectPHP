<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Laptop_invoice;
use App\Laptop_reimbursement;
use App\Laptop_reimbursement_parameter;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class LaptopController extends Controller
{
    public function store(Request $request)
    {
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
            $request->UploadBestand->storeAs('public/LaptopBewijzen', $FileName);
            $NewInvoice = new Laptop_invoice();
            $NewInvoice->filepath = $FileName;
            $NewInvoice->user_id = Auth::user()->id;
            $NewInvoice->amount = $request->bedrag;
            $NewInvoice->invoice_description = $request->reden;
            $NewInvoice->purchase_date = $request->datum;
            $NewInvoice->save();

            $NewLapReimb = new Laptop_reimbursement();
            $NewLapReimb->laptop_invoice_id = $NewInvoice->id;
            $NewLapReimb->user_id_Cost_center_manager = 2;
            $NewLapReimb->payment_date = null;
            $NewLapReimb->save();

            //Maximum schijfgroote meegeven
            $maxpaymentlaptop = new Laptop_reimbursement_parameter();
            $maxpaymentlaptop->laptop_reimbursement_id = $NewLapReimb->id;
            $maxpaymentlaptop->parameter_id = 3;
            $maxpaymentlaptop->save();

            //Standaard kostenplaats meegeven
            $defaultCC = new Laptop_reimbursement_parameter();
            $defaultCC->laptop_reimbursement_id = $NewLapReimb->id;
            $defaultCC->parameter_id = 4;
            $defaultCC->save();


            session()->flash('success', 'De aanvraag is goed ontvangen.');
            return back();
        }
    }
}
