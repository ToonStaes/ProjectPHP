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
use phpDocumentor\Reflection\Types\Array_;
use function Sodium\add;

class DiverseController extends Controller
{
    public function diverseindex()
    {
        $kostenplaatsen = Cost_center::all();
        $result = compact('kostenplaatsen');
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
                    $NewLine->purchase_date = $date_current;
                    $NewLine->amount = $request->$currbedragname;
                    $NewLine->save();

                    for ($y = 1; $y <= $request->$currFileUpCountName; $y++){
                        $currbestandname = 'UploadBestand'.$x.'-'.$y;
                        $FileName = date('YzHis') . $request->$currbestandname->getClientOriginalName();
                        $request->$currbestandname->storeAs('public/DiverseBewijzen', $FileName);
                        $request->$currbestandname->move(base_path('public_html/storage/DiverseBewijzen'), $FileName);

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

    public function edit($id)
    {
        $Req = Diverse_reimbursement_request::findOrFail($id);
        $Lines = Diverse_reimbursement_line::where('DR_request_id', $Req->id)->get();
        $Evidarr = [];
        foreach ($Lines as $line) {
            $Temp = Diverse_reimbursement_evidence::where('DR_line_id', $line->id)->get();
            foreach ($Temp as $current){
                array_push($Evidarr, $current);
            }
        }
        $Evid = collect($Evidarr);
        $kostenplaatsen = Cost_center::all();
        $result = compact("Req", "Lines", "Evid", "kostenplaatsen");
        return view('user.MijnAanvragen.diversedit', $result);
    }

    public function update($id, Request $request)
    {
        $deletethis = Diverse_reimbursement_request::findOrFail($id);
        $deletethis->delete();


        $cterID = $request->kostenplaats;
        $cter = Cost_center::whereid($cterID)->first();

        $iserror = false;
        $errormessage = "";

        $savenewreq = true;

        for ($x = 1; $x <= $request->aantalkosten; $x++) {
            $currFileUpCountName = "aantalbestanden".$x;
            $currExFileUpCountName = "aantalbestaandebestanden".$x;
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
                        $NewRequest->id = $id;
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
                        $NewRequest->id = $id;
                        $NewRequest->save();
                        $savenewreq = false;
                    }


                    $NewLine = new Diverse_reimbursement_line();
                    $NewLine->DR_request_id = $NewRequest->id;
                    $NewLine->purchase_date = $date_current;
                    $NewLine->amount = $request->$currbedragname;
                    $NewLine->save();

                        for ($y = 1; $y <= $request->$currFileUpCountName; $y++) {
                            $currbestandname = 'UploadBestand' . $x . '-' . $y;
                            if ($request->$currbestandname != null){
                                $FileName = date('YzHis') . $request->$currbestandname->getClientOriginalName();
                                $request->$currbestandname->storeAs('public/DiverseBewijzen', $FileName);

                                $NewEvidence = new Diverse_reimbursement_evidence();
                                $NewEvidence->filepath = $FileName;
                                $NewEvidence->DR_line_id = $NewLine->id;
                                $NewEvidence->save();
                            }
                        }

                    for ($y = 1; $y <= $request->$currExFileUpCountName; $y++) {
                        $currbestandname = 'ExistBestand' . $x . '-' . $y;
                        if ($request->$currbestandname != null){
                            $NewEvidence = new Diverse_reimbursement_evidence();
                            $FileName = $request->$currbestandname;
                            $NewEvidence->filepath = $FileName;
                            $NewEvidence->DR_line_id = $NewLine->id;
                            $NewEvidence->save();
                        }
                    }
                }
            }
        }
        session()->flash('success', 'De aanvraag is aangepast.');
        return redirect("/user/mijnaanvragen");
    }
}
