<?php

namespace App\Http\Controllers\financial_employee;

use App\Bike_reimbursement;
use App\Cost_center;
use App\Diverse_reimbursement_line;
use App\Diverse_reimbursement_request;
use App\Http\Controllers\Controller;
use App\Laptop_reimbursement;
use App\Mail\SendRequestDenied;
use App\Mailcontent;
use App\Parameter;
use App\Status;
use Facades\App\Helpers\Json;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class RequestController extends Controller
{
    public function index()
    {
        return view('financial_employee.aanvragen_beheren');
    }

    public function getRequests()
    {
        $total_open_payments = 0;

        $diverse_requests = Diverse_reimbursement_request::whereHas('status_cc_manager', function ($innerQuery){
            $innerQuery->where('id', '=', '2');
        })
            ->with(['user', 'cost_center', 'diverse_reimbursement_lines.parameter', 'diverse_reimbursement_lines.diverse_reimbursement_evidences', 'status_fe', 'financial_employee', 'cost_center_manager'])
            ->get()
            ->transform(function ($item, $key) use (&$total_open_payments){
                $item->description = htmlspecialchars($item->description);

                unset($item->user_id, $item->cost_center_id);

                $item->username = $item->user->first_name . ' ' . $item->user->last_name;
                unset($item->user);

                $item->cost_center_name = $item->cost_center->name;
                unset($item->cost_center);

                $item->status_CC_manager = $item->status_cc_manager->name;
                unset($item->status_cc_manager);

                $item->status_FE = $item->status_fe->name;
                unset($item->status_fe);

                $item->fe_name = $item->financial_employee->first_name . " " . $item->financial_employee->last_name;
                unset($item->financial_employee);

                $item->ccm_name = $item->cost_center_manager->first_name . " " . $item->cost_center_manager->last_name;
                unset($item->cost_center_manager);

                if ($item->review_date_Cost_center_manager != null){
                    $item->review_date_Cost_center_manager = date("d/m/Y", strtotime($item->review_date_Cost_center_manager));
                }
                if ($item->review_date_Financial_employee != null){
                    $item->review_date_Financial_employee = date("d/m/Y", strtotime($item->review_date_Financial_employee));
                }
                if ($item->request_date != null){
                    $item->request_date = date("d/m/Y", strtotime($item->request_date));
                }

                $item->amount = 0;
                foreach ($item->diverse_reimbursement_lines as $line){

                    unset($line->created_at, $line->updated_at);
                    if ($line->amount == null){
                        $item->amount += $line->number_of_km * $line->parameter->amount_per_km;
                    }else {
                        $item->amount += $line->amount;
                    }
                    unset($line->parameter, $line->parameter_id, $line->number_of_km, $line->id, $line->DR_request_id);

                    foreach ($line->diverse_reimbursement_evidences as $evidence){
                        $exploded_path = explode('/', $evidence->filepath);
                        if (!empty($exploded_path)){
                            $evidence['name'] = end($exploded_path);
                            $extension = explode(".", $evidence['name']);
                            $extension = end($extension);
                            $extension = strtolower($extension);

                            $extensions = ["doc", "docx", "gif", "jpg", "jpeg", "mkv", "mov", "mp3", "mp4", "mpg", "pdf", "png", "ppt", "rar", "tiff", "txt", "xls", "xlsx", "zip"];
                            if (in_array($extension, $extensions)){
                                if ($extension == "jpeg"){
                                    $evidence['icon'] = "jpg.png";
                                } else {
                                    $evidence['icon'] = $extension . ".png";
                                }
                            } else {
                                $evidence['icon'] = "unknown.png";
                            }
                        }
                        unset($evidence['DR_line_id'], $evidence['created_at'], $evidence['updated_at']);
                    }
                }

                if ($item->status_FE == "goedgekeurd"){
                    $total_open_payments += $item->amount;
                }
                return $item;
            });

        $maxpaymentlaptop = Parameter::where('name', '=', 'Maximum schijfgrootte laptop')->latest('created_at')->first()->max_reimbursement_laptop;

        $laptop_requests = Laptop_reimbursement::whereHas('status_cc_manager', function ($innerQuery){
            $innerQuery->where('id', '=', 2);
        })
            ->with(['laptop_invoice.user', 'laptop_reimbursement_parameters.parameter', 'status_fe', 'financial_employee', 'cost_center_manager'])
            ->get()
            ->transform(function ($item, $key) use (&$total_open_payments, $maxpaymentlaptop){
                Log::debug($item->laptop_invoice->invoice_description);
                $item->laptop_invoice->invoice_description = htmlspecialchars($item->laptop_invoice->invoice_description);
                Log::debug($item->laptop_invoice->invoice_description);

                $item->laptop_invoice->username = $item->laptop_invoice->user->first_name . ' ' . $item->laptop_invoice->user->last_name;
                unset($item->laptop_invoice->user);

                $item->invoice_decription = htmlspecialchars($item->invoice_description);

                unset($item->laptop_invoice->created_at, $item->laptop_invoice->updated_at);

                $item->fe_name = $item->financial_employee->first_name . " " . $item->financial_employee->last_name;

                $item->status_FE = $item->status_fe->name;
                unset($item->status_fe);

                $item->status_CC_manager = $item->status_cc_manager->name;
                unset($item->status_cc_manager);

                $item->ccm_name = $item->cost_center_manager->first_name . " " . $item->cost_center_manager->last_name;
                unset($item->cost_center_manager);

                if ($item->review_date_Cost_center_manager != null){
                    $item->review_date_Cost_center_manager = date("d/m/Y", strtotime($item->review_date_Cost_center_manager));
                }
                if ($item->review_date_Financial_employee != null){
                    $item->review_date_Financial_employee = date("d/m/Y", strtotime($item->review_date_Financial_employee));
                }
                if ($item->laptop_invoice->purchase_date != null){
                    $item->laptop_invoice->purchasedate = date("d/m/Y", strtotime($item->laptop_invoice->purchase_date));
                }

                $parameters = $item->laptop_reimbursement_parameters;
                foreach ($parameters as $parameter){
                    $cost_center_id = $parameter->parameter->standard_Cost_center_id;
                    if ($cost_center_id != null){
                        $cost_center = Cost_center::find($cost_center_id);
                        $parameter->parameter->cost_center_name = $cost_center->name;
                    }
                }

                $exploded_path = explode('/', $item->laptop_invoice->filepath);
                if (!empty($exploded_path)){
                    $item->laptop_invoice->file_name = end($exploded_path);
                    $extension = explode(".", $item->laptop_invoice->file_name);
                    $extension = end($extension);
                    $extension = strtolower($extension);

                    $extensions = ["doc", "docx", "gif", "jpg", "jpeg", "mkv", "mov", "mp3", "mp4", "mpg", "pdf", "png", "ppt", "rar", "tiff", "txt", "xls", "xlsx", "zip"];
                    if (in_array($extension, $extensions)){
                        if ($extension == "jpeg"){
                            $item->laptop_invoice->file_icon = "jpg.png";
                        } else {
                            $item->laptop_invoice->file_icon = $extension . ".png";
                        }
                    } else {
                        $item->laptop_invoice->file_icon = "unknown.png";
                    }
                }

                if ($item->laptop_invoice->amount / 4 <= $maxpaymentlaptop){
                    $item->laptop_invoice->amount = $item->laptop_invoice->amount / 4;
                } else {
                    $item->laptop_invoice->amount = $maxpaymentlaptop;
                }

                if ($item->status_FE == "goedgekeurd"){
                    $total_open_payments += $item->amount;
                }

                return $item;
            });
        $bike_reimbursements = Bike_reimbursement::whereIn('status_id', [1, 2, 3, 4])
            ->with('status', 'bikerides', 'bikerides.user', 'bike_reimbursement_parameters.parameter', 'financial_employee')
            ->get()
            ->transform(function ($item, $key) use (&$total_open_payments){
                $item->username = $item->bikerides[0]->user->first_name . ' ' . $item->bikerides[0]->user->last_name;
                $item->costcenter = Cost_center::where('id', '=', $item->bike_reimbursement_parameters[1]->parameter->standard_Cost_center_id)->first()->name;
                $item->status_FE = $item->status->name;
                if ($item->financial_employee != null){
                    $item->fe_name = $item->financial_employee->first_name . ' ' . $item->financial_employee->last_name;
                }
                $item->amount = 0;

                foreach ($item->bikerides as $bikeride){
                    if ($bikeride->number_of_km == null){
                        $item->amount += $bikeride->user->number_of_km * $item->bike_reimbursement_parameters[0]->parameter->amount_per_km;
                    }else {
                        $item->amount += $bikeride->number_of_km * $item->bike_reimbursement_parameters[0]->parameter->amount_per_km;
                    }
                }

                unset($item->bikerides, $item->bike_reimbursement_parameters, $item->status, $item->financial_employee);

                $item->amount = round($item->amount, 2);


                if ($item->review_date_Financial_employee != null){
                    $item->review_date_Financial_employee = date("d/m/Y", strtotime($item->review_date_Financial_employee));
                }
                if ($item->request_date != null){
                    $item->request_date = date("d/m/Y", strtotime($item->request_date));
                }

                if ($item->status_id == 2){
                    $total_open_payments += $item->amount;
                }

                return $item;
            });
        $statuses = Status::all();
        $result = compact('diverse_requests', 'laptop_requests', 'bike_reimbursements', 'statuses', 'total_open_payments');
        JSON::dump($result);

        return $result;
    }

    public function saveComment(Request $request){
        $status = Status::where("name", "=", $request->keuring)->first()->id;
        $type = $request->type;
        if ($type == "divers"){
            $diverse_reimbursement = Diverse_reimbursement_request::find($request->id);
            $diverse_reimbursement->comment_Financial_employee = $request->commentaar;
            $diverse_reimbursement->review_date_Financial_employee = now();
            $diverse_reimbursement->status_FE = $status;
            $diverse_reimbursement->user_id_Fin_employee = Auth()->user()->id;

            $diverse_reimbursement->save();
        }
        elseif($type == "laptop"){
            $laptop_reimbursement = Laptop_reimbursement::find($request->id);
            $laptop_reimbursement->comment_Financial_employee = $request->commentaar;
            $laptop_reimbursement->review_date_Financial_employee = now();
            $laptop_reimbursement->status_FE = $status;
            $laptop_reimbursement->user_id_Financial_employee = Auth()->user()->id;

            $laptop_reimbursement->save();
        } else {
            $bike_reimbursement = Bike_reimbursement::find($request->id);
            $bike_reimbursement->comment_Financial_employee = $request->commentaar;
            $bike_reimbursement->review_date_Financial_employee = now();
            $bike_reimbursement->status_id = $status;
            $bike_reimbursement->user_id_Financial_employee = Auth()->user()->id;

            $bike_reimbursement->save();
        }

        if($status==3){
            //get the corresponding user
            $diverse_with_user = Diverse_reimbursement_request::where('id', $request->id)->with(['user', 'financial_employee'])->get()[0];

            //get the mailcontent associated
            //with this action
            $mailcontent = Mailcontent::firstWhere('mailtype', 'Afwijzing');
            $mailtext = $mailcontent->content;

            //replace all replaceables with
            //the necessary data
            $mailtext = str_replace("[NAAM]", $diverse_with_user->user->first_name, $mailtext);
            $finance_employee = $diverse_with_user->financial_employee;
            $mailtext = str_replace("[NAAM FINANCIEEL MEDEWERKER]", $finance_employee->first_name.' '.$finance_employee->last_name, $mailtext);
            $mailtext = str_replace("[AANVRAAG]", $diverse_with_user->description, $mailtext);
            $mailtext = str_replace("[REDEN]", $diverse_with_user->comment_Financial_employee, $mailtext);

            $mailtext = explode("\n", $mailtext);

            $data = array('content'=>$mailtext);

            Mail::to($diverse_with_user->user->email)->send(new SendRequestDenied($data));
        }
    }

    public function getOpenPayments(){
        $total_open_payments = 0;

        $diverse_requests = Diverse_reimbursement_request::whereHas('status_FE', function ($innerQuery){
            $innerQuery->where('id', '=', '2');
        })
            ->with(['user', 'diverse_reimbursement_lines.parameter'])
            ->get()
            ->transform(function ($item, $key) use (&$total_open_payments){
                unset($item->user_id, $item->cost_center_id);

                $item->username = $item->user->first_name . ' ' . $item->user->last_name;
                $item->iban = $item->user->IBAN;
                unset($item->user);

                $item->amount = 0;
                foreach ($item->diverse_reimbursement_lines as $line){

                    unset($line->created_at, $line->updated_at);
                    if ($line->amount == null){
                        $item->amount += $line->number_of_km * $line->parameter->amount_per_km;
                    }else {
                        $item->amount += $line->amount;
                    }
                    unset($line->parameter, $line->parameter_id, $line->number_of_km, $line->id, $line->DR_request_id);
                }

                $total_open_payments += $item->amount;
                return $item;
            });

        $maxpaymentlaptop = Parameter::where('name', '=', 'Maximum schijfgrootte laptop')->latest('created_at')->first()->max_reimbursement_laptop;
        $laptop_requests = Laptop_reimbursement::whereHas('status_FE', function ($innerQuery){
            $innerQuery->where('id', '=', 2);
        })
            ->with(['laptop_invoice.user', 'laptop_reimbursement_parameters.parameter'])
            ->get()
            ->transform(function ($item, $key) use (&$total_open_payments, $maxpaymentlaptop){

                $item->laptop_invoice->username = $item->laptop_invoice->user->first_name . ' ' . $item->laptop_invoice->user->last_name;
                $item->laptop_invoice->iban = $item->laptop_invoice->user->IBAN;
                unset($item->laptop_invoice->user, $item->laptop_invoice->created_at, $item->laptop_invoice->updated_at);

                if ($item->laptop_invoice->amount / 4 <= $maxpaymentlaptop){
                    $item->laptop_invoice->amount = $item->laptop_invoice->amount / 4;
                } else {
                    $item->laptop_invoice->amount = $maxpaymentlaptop;
                }

                $total_open_payments += $item->amount;

                return $item;
            });

        $bike_reimbursements = Bike_reimbursement::where('status_id', '=', '2')
            ->with('bikerides', 'bikerides.user', 'bike_reimbursement_parameters.parameter')
            ->get()
            ->transform(function ($item, $key) use (&$total_open_payments){
                $item->username = $item->bikerides[0]->user->first_name . ' ' . $item->bikerides[0]->user->last_name;
                $item->iban = $item->bikerides[0]->user->IBAN;
                $item->amount = 0;

                foreach ($item->bikerides as $bikeride){
                    if ($bikeride->number_of_km == null){
                        $item->amount += $bikeride->user->number_of_km * $item->bike_reimbursement_parameters[0]->parameter->amount_per_km;
                    }else {
                        $item->amount += $bikeride->number_of_km * $item->bike_reimbursement_parameters[0]->parameter->amount_per_km;
                    }
                }

                unset($item->bikerides, $item->bike_reimbursement_parameters);

                $item->amount = round($item->amount, 2);
                $total_open_payments += $item->amount;

                return $item;
            });

        $result = compact('diverse_requests', 'laptop_requests', 'bike_reimbursements','total_open_payments');
        JSON::dump($result);

        return $result;
    }

    public function payOpenPayments(){
        $diverse_requests = Diverse_reimbursement_request::whereHas('status_FE', function ($innerQuery){
            $innerQuery->where('id', '=', '2');
        })
            ->with(['user', 'diverse_reimbursement_lines.parameter'])
            ->get();

        foreach ($diverse_requests as $diverse_request){
            $diverse_request->status_FE = 4;
            $diverse_request->save();
        }

        $laptop_requests = Laptop_reimbursement::whereHas('status_FE', function ($innerQuery){
            $innerQuery->where('id', '=', 2);
        })
            ->with(['laptop_invoice.user', 'laptop_reimbursement_parameters.parameter'])
            ->get();

        foreach ($laptop_requests as $laptop_request){
            $laptop_request->status_FE = 4;
            $laptop_request->payment_date = now();
            $laptop_request->save();
        }

        $bike_reimbursements = Bike_reimbursement::where('status_id', '=', '2')
            ->with('bikerides', 'bikerides.user', 'bike_reimbursement_parameters.parameter')
            ->get();

        foreach ($bike_reimbursements as $bike_reimbursement){
            $bike_reimbursement->status_id = 4;
            $bike_reimbursement->save();
        }

        return "De betaling is in orde";
    }
}
