<?php

namespace App\Http\Controllers\cost_center_manager;

use App\Cost_center;
use App\Diverse_reimbursement_request;
use App\Http\Controllers\Controller;
use App\Laptop_reimbursement;
use Facades\App\Helpers\Json;
use Illuminate\Http\Request;

class RequestController extends Controller
{
    public function index()
    {
        return view('cost_center_manager.aanvragen_beheren');
    }

    public function getRequests()
    {
        $diverse_requests = Diverse_reimbursement_request::with(['user', 'cost_center', 'diverse_reimbursement_lines.parameter', 'diverse_reimbursement_lines.diverse_reimbursement_evidences'])
            ->get()
            ->transform(function ($item, $key){
                unset($item['user_id'], $item['cost_center_id']);

                $item->user->name = $item->user->first_name . ' ' . $item->user->last_name;
                unset($item['user']['address'], $item['user']['city'], $item['user']['zip_code'], $item['user']['IBAN'],
                    $item['user']['email'], $item['user']['phone_number'], $item['user']['changedPassword'],
                    $item['user']['isActive'], $item['user']['isCost_Center_manager'], $item['user']['isFinancial_employee'],
                    $item['user']['number_of_km'], $item['user']['created_at'], $item['user']['updated_at'],
                    $item['user']['first_name'], $item['user']['last_name']);

                unset($item['cost_center']['user_id_Cost_center_manager'], $item['cost_center']['isActive'],
                    $item['cost_center']['created_at'], $item['cost_center']['updated_at']);

                foreach ($item['diverse_reimbursement_lines'] as $line){
                    unset($line['created_at'], $line['updated_at']);
                    if ($line['amount'] == null){
                        $line['amount'] = $line['number_of_km'] * $line['parameter']['amount_per_km'];
                    }
                    unset($line['parameter']);

                    foreach ($line['diverse_reimbursement_evidences'] as $evidence){
                        $exploded_path = explode('/', $evidence['filepath']);
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

        $laptop_requests = Laptop_reimbursement::with(['laptop_invoice.user', 'laptop_reimbursement_parameters.parameter'])
            ->get()
            ->transform(function ($item, $key){

                $item['laptop_invoice']['user']['name'] = $item['laptop_invoice']['user']['first_name'] . ' ' . $item['laptop_invoice']['user']['last_name'];

                unset($item['laptop_invoice']['user']['address'], $item['laptop_invoice']['user']['city'], $item['laptop_invoice']['user']['zip_code'], $item['laptop_invoice']['user']['IBAN'],
                    $item['laptop_invoice']['user']['email'], $item['laptop_invoice']['user']['phone_number'], $item['laptop_invoice']['user']['changedPassword'],
                    $item['laptop_invoice']['user']['isActive'], $item['laptop_invoice']['user']['isCost_Center_manager'], $item['laptop_invoice']['user']['isFinancial_employee'],
                    $item['laptop_invoice']['user']['number_of_km'], $item['laptop_invoice']['user']['created_at'], $item['laptop_invoice']['user']['updated_at']);

                unset($item['laptop_invoice']['created_at'], $item['laptop_invoice']['updated_at']);

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

                return $item;
            });
        $result = compact('diverse_requests', 'laptop_requests');
        JSON::dump($result);

        return $result;
    }
}
