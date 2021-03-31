<?php

namespace App\Http\Controllers\financial_employee;

use App\Bike_reimbursement;
use App\Cost_center;
use App\Diverse_reimbursement_line;
use App\Diverse_reimbursement_request;
use App\Http\Controllers\Controller;
use App\Laptop_reimbursement;
use App\Status;
use Facades\App\Helpers\Json;
use Illuminate\Http\Request;

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

        $laptop_requests = Laptop_reimbursement::whereHas('status_cc_manager', function ($innerQuery){
            $innerQuery->where('id', '=', 2);
        })
            ->with(['laptop_invoice.user', 'laptop_reimbursement_parameters.parameter', 'status_fe', 'financial_employee', 'cost_center_manager'])
            ->get()
            ->transform(function ($item, $key) use (&$total_open_payments){

                $item['laptop_invoice']['user']['name'] = $item['laptop_invoice']['user']['first_name'] . ' ' . $item['laptop_invoice']['user']['last_name'];

                unset($item['laptop_invoice']['user']['address'], $item['laptop_invoice']['user']['city'], $item['laptop_invoice']['user']['zip_code'], $item['laptop_invoice']['user']['IBAN'],
                    $item['laptop_invoice']['user']['email'], $item['laptop_invoice']['user']['phone_number'], $item['laptop_invoice']['user']['changedPassword'],
                    $item['laptop_invoice']['user']['isActive'], $item['laptop_invoice']['user']['isCost_Center_manager'], $item['laptop_invoice']['user']['isFinancial_employee'],
                    $item['laptop_invoice']['user']['number_of_km'], $item['laptop_invoice']['user']['created_at'], $item['laptop_invoice']['user']['updated_at']);

                unset($item['laptop_invoice']['created_at'], $item['laptop_invoice']['updated_at']);

                $item['fe_name'] = $item['financial_employee']['first_name'] . " " . $item['financial_employee']['last_name'];

                $item->status_FE = $item->status_fe->name;
                unset($item->status_fe);

                $item->status_CC_manager = $item->status_cc_manager->name;
                unset($item->status_cc_manager);

                $item->ccm_name = $item->cost_center_manager->first_name . " " . $item->cost_center_manager->last_name;
                unset($item->cost_center_manager);

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

                $item->amount = $item->laptop_invoice->amount / 4;

                if ($item->status_FE == "goedgekeurd"){
                    $total_open_payments += $item->amount;
                }

                return $item;
            });
        $statuses = Status::all();
        $result = compact('diverse_requests', 'laptop_requests', 'statuses', 'total_open_payments');
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
        }
    }
}
