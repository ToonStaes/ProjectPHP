<?php

namespace App\Http\Controllers\cost_center_manager;

use App\Diverse_reimbursement_request;
use App\Http\Controllers\Controller;
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
        return Diverse_reimbursement_request::with(['user', 'cost_center', 'diverse_reimbursement_lines.parameter', 'diverse_reimbursement_lines.diverse_reimbursement_evidences'])
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
                        unset($evidence['DR_line_id'], $evidence['created_at'], $evidence['updated_at']);
                    }
                }

                return $item;
            });
//        $result = compact('diverse_reimbursements');
//        JSON::dump($result);
    }
}
