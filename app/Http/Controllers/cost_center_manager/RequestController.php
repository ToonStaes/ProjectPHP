<?php

namespace App\Http\Controllers\cost_center_manager;

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

class RequestController extends Controller
{
    public function index()
    {
        return view('cost_center_manager.aanvragen_beheren');
    }

    public function getRequests()
    {
        $diverse_requests = Diverse_reimbursement_request::with(['user', 'cost_center', 'diverse_reimbursement_lines.parameter', 'diverse_reimbursement_lines.diverse_reimbursement_evidences', 'status_cc_manager', 'status_fe', 'financial_employee'])
            ->get()
            ->transform(function ($item, $key){
                unset($item->user_id, $item->cost_center_id, $item->user_id_Fin_employee, $item->user_id_CC_manager);

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

                return $item;
            });

        $maxpaymentlaptop = Parameter::find(3)->max_reimbursement_laptop;

        $laptop_requests = Laptop_reimbursement::with(['laptop_invoice.user', 'laptop_reimbursement_parameters.parameter', 'status_cc_manager', 'status_fe', 'financial_employee'])
            ->get()
            ->transform(function ($item, $key) use ($maxpaymentlaptop){
                $item->laptop_invoice->username = $item->laptop_invoice->user->first_name . ' ' . $item->laptop_invoice->user->last_name;
                unset($item->laptop_invoice->user);

                unset($item->laptop_invoice->created_at, $item->laptop_invoice->updated_at);

                $item->fe_name = $item->financial_employee->first_name . " " . $item->financial_employee->last_name;

                if ($item->review_date_Cost_center_manager != null){
                    $item->review_date_Cost_center_manager = date("d/m/Y", strtotime($item->review_date_Cost_center_manager));
                }
                if ($item->review_date_Financial_employee != null){
                    $item->review_date_Financial_employee = date("d/m/Y", strtotime($item->review_date_Financial_employee));
                }
                if ($item->laptop_invoice->purchase_date != null){
                    $item->laptop_invoice->purchasedate = date("d/m/Y", strtotime($item->laptop_invoice->purchase_date));
                }

                $parameters = $item['laptop_reimbursement_parameters'];
                foreach ($parameters as $parameter){
                    $cost_center_id = $parameter->parameter->standard_Cost_center_id;
                    if ($cost_center_id != null){
                        $cost_center = Cost_center::find($cost_center_id);
                        $parameter->parameter['cost_center_name'] = $cost_center->name;
                    }
                }

                $exploded_path = explode('/', $item['laptop_invoice']['filepath']);
                if (!empty($exploded_path)){
                    $item['laptop_invoice']['file_name'] = end($exploded_path);
                    $extension = explode(".", $item['laptop_invoice']['file_name']);
                    $extension = end($extension);
                    $extension = strtolower($extension);

                    $extensions = ["doc", "docx", "gif", "jpg", "jpeg", "mkv", "mov", "mp3", "mp4", "mpg", "pdf", "png", "ppt", "rar", "tiff", "txt", "xls", "xlsx", "zip"];
                    if (in_array($extension, $extensions)){
                        if ($extension == "jpeg"){
                            $item['laptop_invoice']['file_icon'] = "jpg.png";
                        } else {
                            $item['laptop_invoice']['file_icon'] = $extension . ".png";
                        }
                    } else {
                        $item['laptop_invoice']['file_icon'] = "unknown.png";
                    }
                }

                if ($item->laptop_invoice->amount / 4 < $maxpaymentlaptop){
                    $item->laptop_invoice->amount = $item->laptop_invoice->amount / 4;
                } else {
                    $item->laptop_invoice->amount = $maxpaymentlaptop;
                }

                return $item;
            });
        $statuses = Status::all();
        $result = compact('diverse_requests', 'laptop_requests', 'statuses', 'maxpaymentlaptop');
        JSON::dump($result);

        return $result;
    }

    public function saveComment(Request $request){
        $status = Status::where("name", "=", $request->keuring)->first()->id;
        $type = $request->type;
        if ($type == "divers"){
            $diverse_reimbursement = Diverse_reimbursement_request::find($request->id);
            $diverse_reimbursement->comment_Cost_center_manager = $request->commentaar;
            $diverse_reimbursement->review_date_Cost_center_manager = now();
            $diverse_reimbursement->status_CC_manager = $status;

            if ($status == 3){
                $diverse_reimbursement->status_FE = 5;
            }

            $diverse_reimbursement->save();
        }
        elseif($type == "laptop"){
            $laptop_reimbursement = Laptop_reimbursement::find($request->id);
            $laptop_reimbursement->comment_Cost_center_manager = $request->commentaar;
            $laptop_reimbursement->review_date_Cost_center_manager = now();
            $laptop_reimbursement->status_CC_manager = $status;
            $laptop_reimbursement->user_id_Cost_center_manager = Auth()->user()->id;

            $laptop_reimbursement->save();
        }

        if($status==3){
            //get the corresponding user
            $diverse_with_user = Diverse_reimbursement_request::where('id', $request->id)->with(['user', 'cost_center_manager'])->get()[0];

            //get the mailcontent associated
            //with this action
            $mailcontent = Mailcontent::firstWhere('mailtype', 'Afwijzing');
            $mailtext = $mailcontent->content;

            //replace all replaceables with
            //the necessary data
            $mailtext = str_replace("[NAAM]", $diverse_with_user->user->first_name, $mailtext);
            $cost_manager = $diverse_with_user->cost_center_manager;
            $mailtext = str_replace("[NAAM FINANCIEEL MEDEWERKER]", $cost_manager->first_name.' '.$cost_manager->last_name, $mailtext);
            $mailtext = str_replace("[AANVRAAG]", $diverse_with_user->description, $mailtext);
            $mailtext = str_replace("[REDEN]", $diverse_with_user->comment_Cost_center_manager, $mailtext);

            $mailtext = explode("\n", $mailtext);

            $data = array('content'=>$mailtext);

            Mail::to($diverse_with_user->user->email)->send(new SendRequestDenied($data));
        }
    }
}
