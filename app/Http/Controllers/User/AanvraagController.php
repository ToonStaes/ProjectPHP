<?php

namespace App\Http\Controllers\User;

use App\Bike_reimbursement;
use App\Bikeride;
use App\Cost_center;
use App\Diverse_reimbursement_request;
use App\Http\Controllers\Controller;
use App\Laptop_reimbursement;
use App\User;
use Facades\App\Helpers\Json;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AanvraagController extends Controller
{
    public function index() {
        return view('user.MijnAanvragen.mijnAanvragen');
    }

    private function FormatDate($item){
        if ($item != null) {
            $item = date("d/m/Y", strtotime($item));
        }
        return $item;
    }

    public function qryRequests() {
        // get all diverse_reimbursement_requests with linked tables included
        $diverse_requests = Diverse_reimbursement_request::with(['user', 'cost_center.user', 'diverse_reimbursement_lines.parameter', 'diverse_reimbursement_lines.diverse_reimbursement_evidences', 'status_cc_manager', 'status_fe', 'financial_employee'])
            ->where('user_id', '=', auth()->user()->id)
            ->get()
            ->transform(function ($item, $key){
                $item->description = htmlspecialchars($item->description);

                unset($item->user_id, $item->cost_center_id, $item->user_id_Fin_employee, $item->user_id_CC_manager, $item->created_at, $item->updated_at, $item->user);
                $item->cost_center_name = $item->cost_center->name;

                $item->status_CC_manager = $item->status_cc_manager->name;
                unset($item->status_cc_manager);

                $item->status_FE = $item->status_fe->name;
                unset($item->status_fe);

                $item->fe_name = $item->financial_employee->first_name . " " . $item->financial_employee->last_name;
                unset($item->financial_employee);

                $item->cc_manager_name = $item->cost_center->user->first_name . " " . $item->cost_center->user->last_name;
                unset($item->cost_center);

                $item->amount = 0;

                foreach ($item->diverse_reimbursement_lines as $line){

                    unset($line->created_at, $line->updated_at);
                    if ($line->amount == null){
                        $item->amount += $line->number_of_km * $line->parameter->amount_per_km;
                    }else {
                        $item->amount += $line->amount;
                    }
                }
                unset($item->diverse_reimbursement_lines);
                $item->request_date = $this->FormatDate($item->request_date);
                $item->review_date_Cost_center_manager = $this->FormatDate($item->review_date_Cost_center_manager);
                $item->review_date_Financial_employee = $this->FormatDate($item->review_date_Financial_employee);

                return $item;
            });

        // get all laptop_reimbursements
        $laptop_reimbursements = Laptop_reimbursement::whereHas('laptop_invoice.user', function ($q) {
                $q->where('user_id', '=', auth()->user()->id);
            })
            ->with(['laptop_reimbursement_parameters', 'status_fe', 'status_cc_manager', 'cost_center_manager', 'financial_employee'])->get()
            ->transform(function ($item, $key){
                $item->laptop_invoice->invoice_description = htmlspecialchars($item->laptop_invoice->invoice_description);

                $item->user_name = $item->laptop_invoice->user->first_name . " " . $item->laptop_invoice->user->last_name;
                $item->cc_manager_name = $item->cost_center_manager->first_name . " " . $item->cost_center_manager->last_name;

                if($item->financial_employee != []){
                    $item->financial_employee_name = $item->financial_employee->first_name . " " . $item->financial_employee->last_name;
                }

                unset($item->laptop_invoice->user, $item->laptop_invoice->user_id,
                    $item->cost_center_manager, $item->financial_employee, $item->laptop_invoice_id, $item->user_id_Cost_center_manager, $item->user_id_Financial_employee,
                    $item->created_at, $item->updated_at);

                $parameters = $item->laptop_reimbursement_parameters;
                $max_schrijfgrootte = null;
                foreach ($parameters as $parameter){
                    $cost_center_id = $parameter->parameter->standard_Cost_center_id;
                    if ($cost_center_id != null){
                        $cost_center = Cost_center::find($cost_center_id);
                        $parameter->parameter->cost_center_name = $cost_center->name;
                        unset($parameter->parameter->max_reimbursement_laptop);
                    }

                    if ($parameter->parameter->max_reimbursement_laptop != null){
                        $max_schrijfgrootte = $parameter->parameter->max_reimbursement_laptop;
                    }

                    unset($parameter->id, $parameter->laptop_reimbursement_id, $parameter->parameter_id, $parameter->created_at, $parameter->updated_at,
                        $parameter->parameter->id, $parameter->parameter->name, $parameter->parameter->amount_per_km, $parameter->parameter->description,
                        $parameter->parameter->created_at, $parameter->parameter->updated_at);
                }

                $item->amount = $item->laptop_invoice->amount / 4;
                if ($item->amount > $max_schrijfgrootte) {
                    $item->amount = $max_schrijfgrootte;
                }

                $item->status_FE = $item->status_fe->name;
                $item->status_CC_manager = $item->status_cc_manager->name;
                unset($item->status_fe, $item->status_cc_manager);

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

                $item->laptop_invoice->purchase_date_no_format = $item->laptop_invoice->purchase_date;
                $item->laptop_invoice->purchase_date = $this->FormatDate($item->laptop_invoice->purchase_date);
                $item->payment_date = $this->FormatDate($item->payment_date);
                $item->review_date_Cost_center_manager = $this->FormatDate($item->review_date_Cost_center_manager);
                $item->review_date_Financial_employee = $this->FormatDate($item->review_date_Financial_employee);

                return $item;
            });

        // get all bike_requests
        $bike_requests = Bike_reimbursement::whereHas('bikerides.user', function ($q) {
                $q->where('user_id', '=', auth()->user()->id);
            })
            ->with(['bike_reimbursement_parameters.parameter.cost_center', 'status', 'financial_employee'])
            ->get()
            ->transform(function ($item, $key){
                $item->status_FE = $item->status->name;
                if ($item->financial_employee != null){
                    $item->fe_name = $item->financial_employee->first_name . " " . $item->financial_employee->last_name;
                }

                $parameters = $item->bike_reimbursement_parameters;
                $amount_per_km = null;
                foreach ($parameters as $parameter){
                    unset($parameter->id, $parameter->bike_reimbursement_id, $parameter->parameter_id, $parameter->created_at, $parameter->updated_at);
                    // within $parameter->parameter
                    if ($parameter->parameter->amount_per_km != null) {
                        $amount_per_km = $parameter->parameter->amount_per_km;
                    }

                    $cost_center_id = $parameter->parameter->standard_Cost_center_id;
                    if ($cost_center_id != null){
                        $cost_center = Cost_center::find($cost_center_id);
                        $item->cost_center_name = $cost_center->name;
                    }

                    unset($parameter->id, $parameter->laptop_reimbursement_id, $parameter->parameter_id, $parameter->created_at, $parameter->updated_at,
                        $parameter->parameter->id, $parameter->parameter->name, $parameter->parameter->max_reimbursement_laptop, $parameter->parameter->description,
                        $parameter->parameter->created_at, $parameter->parameter->updated_at, $parameter->parameter->cost_center);
                }

                $counter_normal_rides = 0;
                $number_of_km = 0;
                $bikerides = Bikeride::where('bike_reimbursement_id', '=', $item->id)
                    ->get()
                    ->transform(function ($item, $key) use (&$counter_normal_rides, &$number_of_km) {
                        if ($item->number_of_km != null) {
                            $number_of_km +=  $item->number_of_km;
                        } else {
                            $counter_normal_rides++;
                        }
                        return $item;
                    });

                $currentUser = User::find(auth()->user()->id);
                $normalDistance = $currentUser->number_of_km;
                $normalDistanceTravelled = $counter_normal_rides * $normalDistance;
                $number_of_km += $normalDistanceTravelled;

                $amount = $number_of_km * $amount_per_km;

                $item->amount = $amount;

                unset($item->status, $item->id, $item->status_id, $item->created_at, $item->updated_at);

                $item->request_date = $this->FormatDate($item->request_date);
                $item->review_date_Financial_employee = $this->FormatDate($item->review_date_Financial_employee);

                return $item;
            });

        $result = compact('diverse_requests', 'laptop_reimbursements', 'bike_requests');
        return $result;
    }
}
