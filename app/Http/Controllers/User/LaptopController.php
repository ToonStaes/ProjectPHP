<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Laptop_invoice;
use App\Mailcontent;
use App\Laptop_reimbursement;
use App\Laptop_reimbursement_parameter;
use App\Parameter;
use DateTime;
use Facades\App\Helpers\Json;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use phpDocumentor\Reflection\Types\Integer;
use View;

class LaptopController extends Controller
{
    public function store(Request $request)
    {
        $this->validate($request, [
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

        if ($iserror) {
            session()->flash('danger', $errormessage);
            return back();
        }
        else{
            $FileName = date('YzHis') . $request->UploadBestand->getClientOriginalName();
            $request->UploadBestand->storeAs('public/LaptopBewijzen', $FileName);
            $request->UploadBestand->move(base_path('public_html/storage/LaptopBewijzen'), $FileName);
            $NewInvoice = new Laptop_invoice();
            $NewInvoice->filepath = $FileName;
            $NewInvoice->user_id = Auth::user()->id;
            $NewInvoice->amount = $request->bedrag;
            $NewInvoice->invoice_description = $request->reden;
            $NewInvoice->purchase_date = $request->datum;
            $NewInvoice->save();

            $NewLapReimb = new Laptop_reimbursement();
            $NewLapReimb->laptop_invoice_id = $NewInvoice->id;
            $NewLapReimb->user_id_Cost_center_manager = Parameter::with('cost_center.user')->findOrFail(4)->cost_center->user->id;
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
            $laptopReimbursements = Laptop_reimbursement::where('laptop_invoice_id', '=', $id)->WhereIn('status_FE', [1, 3])->Where('status_CC_manager', '!=', 2)->get();
            foreach ($laptopReimbursements as $item) {
                $item->status_FE = 1;
                $item->status_CC_manager = 1;
                $item->save();
            }
            session()->flash('success', 'Uw aanvraag is aangepast.');
        }
        else{
            $FileName = date('YzHis') . $request->UploadBestand->getClientOriginalName();
            $request->UploadBestand->storeAs('LaptopBewijzen', $FileName);
            $request->UploadBestand->move(base_path('public_html/storage/LaptopBewijzen'), $FileName);
            $laptopInvoice->amount = $request->bedrag;
            $laptopInvoice->invoice_description = $request->reden;
            $laptopInvoice->purchase_date = $request->datum;
            $laptopInvoice->filepath = $FileName;
            $laptopInvoice->save();
            $laptopReimbursements = Laptop_reimbursement::where('laptop_invoice_id', '=', $id)->WhereIn('status_FE', [1, 3])->get();
            foreach ($laptopReimbursements as $item) {
                $item->status_FE = 1;
                $item->status_CC_manager = 1;
                $item->save();
            }
            session()->flash('success', 'Uw aanvraag is aangepast.');
        }
    }
}
