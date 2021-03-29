<?php

namespace App\Http\Controllers\user;

use App\Cost_center;
use App\Diverse_reimbursement_evidence;
use App\Diverse_reimbursement_line;
use App\Diverse_reimbursement_request;
use App\Helpers\Json;
use App\Http\Controllers\Controller;
use App\Laptop_invoice;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DiverseController extends Controller
{

    public function diverseindex()
    {
        $kostenplaatsen = Cost_center::get();
        $result = compact('kostenplaatsen');
        \Facades\App\Helpers\Json::dump($result);
        return view('user.diverse', $result);
    }

    public function store(Request $request)
    {
        $cterID = $request->kostenplaats;
        $cter = Cost_center::whereid($cterID)->first();

        $date_current = new DateTime();
        $date_given    = new DateTime($request->datum);

        $iserror = false;
        $errormessage = "";

        if ($request->AutoSwitch){
            if ($request->afstand <= 0){
                $errormessage = $errormessage . "De afstand is ongeldig. ";
                $iserror = true;
            }
        }
        else{
            if ($date_current < $date_given) {
                $errormessage = $errormessage . "De aankoopdatum is ongeldig. ";
                $iserror = true;
            }

            if ($request->bedrag < 1) {
                $errormessage = $errormessage . "Het aankoopbedrag is ongeldig. ";
                $iserror = true;
            }
        }

        if ($iserror){
            session()->flash('danger', $errormessage);
            return back();
        }
        else{
            if ($request->AutoSwitch)
            {
                $NewRequest = new Diverse_reimbursement_request();
                $NewRequest->user_id = \Auth::user()->id;
                $NewRequest->invoice_description = $request->reden;
                $NewRequest->request_date = $date_current;
                $NewRequest->user_id_CC_manager = $cter->user_id_Cost_center_manager; //NOG AANPASSEN ADHV DROPDOWN
                $NewRequest->cost_center_id = $cterID; //NOG AANPASSEN ADHV DROPDOW
                $NewRequest->save();

                $NewLine = new Diverse_reimbursement_line();
                $NewLine->DR_request_id = $NewRequest->id;
                $NewLine->number_of_km = $request->afstand;
                $NewLine->description = $request->reden;
                $NewLine->save();
            }
            else{
                $FileName = date('YzHis') . $request->UploadBestand->getClientOriginalName();
                $request->UploadBestand->storeAs('DiverseBewijzen', $FileName); //MEERDERE IMPLEMENTEREN!

                $NewRequest = new Diverse_reimbursement_request();
                $NewRequest->user_id = \Auth::user()->id;
                $NewRequest->invoice_description = $request->reden;
                $NewRequest->request_date = $request->datum;
                $NewRequest->user_id_CC_manager = $cter->user_id_Cost_center_manager; //NOG AANPASSEN ADHV DROPDOWN
                $NewRequest->cost_center_id = $cterID; //NOG AANPASSEN ADHV DROPDOW
                $NewRequest->save();

                $NewLine = new Diverse_reimbursement_line();
                $NewLine->DR_request_id = $NewRequest->id;
                $NewLine->description = $request->reden;
                $NewLine->save();

                $NewEvidence= new Diverse_reimbursement_evidence();
                $NewEvidence->filepath = $FileName;
                $NewEvidence->DR_line_id =$NewLine->id;
                $NewEvidence->save();
            }

            session()->flash('success', 'De aanvraag is goed ontvangen.');
            return back();
        }
    }
}
