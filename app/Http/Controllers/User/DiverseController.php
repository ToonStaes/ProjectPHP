<?php

namespace App\Http\Controllers\User;

use App\Cost_center;
use App\Diverse_reimbursement_evidence;
use App\Diverse_reimbursement_line;
use App\Diverse_reimbursement_request;
use App\Http\Controllers\Controller;
use DateTime;
use Facades\App\Helpers\Json;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DiverseController extends Controller
{
    public function diverseindex()
    {
        $kostenplaatsen = Cost_center::all();
        $result = compact('kostenplaatsen');
        Json::dump($result);
        return view('user.diverse', $result);
    }

    public function store(Request $request)
    {
        $cterID = $request->kostenplaats;
        $cter = Cost_center::whereid($cterID)->first();

        $iserror = false;
        $errormessage = "";

        $savenewreq = true;

        for ($x = 1; $x <= $request->aantalkosten; $x++) {
            $currFileUpCountName = "aantalbestanden".$x;
            $currdatename = "datum".$x;
            $currswitchname = "AutoSwitch".$x;
            $currafstandname = "afstand".$x;
            $currbedragname = "bedrag".$x;
            $currbestandname = "UploadBestand".$x;


            $date_current = new DateTime();
            $date_given   = new DateTime($request->$currdatename);

            if ($request->$currswitchname){
                if ($request->$currafstandname <= 0){
                    $errormessage = $errormessage . "Ongeldige afstand(en). ";
                    $iserror = true;
                }
            }

            else{
                if ($date_current < $date_given) {
                    $errormessage = $errormessage . "Ongeldige aankoopdatum(s)";
                    $iserror = true;
                }

                if ($request->$currbedragname < 1) {
                    $errormessage = $errormessage . "Ongeldig(e) aankoopbedrag(en)";
                    $iserror = true;
                }
            }

            if ($iserror){
                session()->flash('danger', $errormessage);
                return back();
            }
            else{
                if ($request->$currswitchname)
                {
                    if ($savenewreq){
                        $NewRequest = new Diverse_reimbursement_request();
                        $NewRequest->user_id = Auth::user()->id;
                        $NewRequest->description = $request->reden;
                        $NewRequest->request_date = $date_current;
                        $NewRequest->user_id_CC_manager = $cter->user_id_Cost_center_manager;
                        $NewRequest->cost_center_id = $cterID;
                        $NewRequest->save();
                        $savenewreq = false;
                    }

                    $NewLine = new Diverse_reimbursement_line();
                    $NewLine->DR_request_id = $NewRequest->id;
                    $NewLine->number_of_km = $request->$currafstandname;
                    $NewLine->parameter_id = 2;
                    $NewLine->save();
                }
                else{

                    if ($savenewreq){
                        $NewRequest = new Diverse_reimbursement_request();
                        $NewRequest->user_id = Auth::user()->id;
                        $NewRequest->description = $request->reden;
                        $NewRequest->request_date = $request->$currdatename;
                        $NewRequest->user_id_CC_manager = $cter->user_id_Cost_center_manager;
                        $NewRequest->cost_center_id = $cterID;
                        $NewRequest->save();
                        $savenewreq = false;
                    }


                    $NewLine = new Diverse_reimbursement_line();
                    $NewLine->DR_request_id = $NewRequest->id;
                    $NewLine->amount = $request->$currbedragname;
                    $NewLine->save();

                    for ($y = 1; $y <= $request->$currFileUpCountName; $y++){
                        $currbestandname = 'UploadBestand'.$x.'-'.$y;
                        $FileName = date('YzHis') . $request->$currbestandname->getClientOriginalName();
                        $request->$currbestandname->storeAs('public/DiverseBewijzen', $FileName);

                        $NewEvidence= new Diverse_reimbursement_evidence();
                        $NewEvidence->filepath = $FileName;
                        $NewEvidence->DR_line_id = $NewLine->id;
                        $NewEvidence->save();
                    }
                }
            }
        }
        session()->flash('success', 'De aanvraag is goed ontvangen.');
        return back();
    }
}
