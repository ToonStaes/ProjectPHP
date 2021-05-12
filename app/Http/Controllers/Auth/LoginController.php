<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Laptop_invoice;
use App\Laptop_reimbursement;
use App\Parameter;
use App\Providers\RouteServiceProvider;
use Facades\App\Helpers\Json;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
        $verantwoordelijke = Parameter::with('cost_center.user')->findOrFail(4)->cost_center->user->id;
        if ($user->id == $verantwoordelijke){
            $laptop_invoices = Laptop_invoice::with('laptop_reimbursements')->withCount('laptop_reimbursements')->get();

            foreach ($laptop_invoices as $invoice){
                if ($invoice->laptop_reimbursements_count < 4 && $invoice->laptop_reimbursements_count != 0){
                    $latest_reimbursement = $invoice->laptop_reimbursements->last();

                    //Als laptop reimbursement langer dan een jaar geleden, maak een nieuwe aan
                    if (strtotime($latest_reimbursement->payment_date)<strtotime('-1 year') && $latest_reimbursement->payment_date != null){
                        $NewLapReimb = new Laptop_reimbursement();
                        $NewLapReimb->laptop_invoice_id = $invoice->id;
                        $NewLapReimb->user_id_Cost_center_manager = $user->id;
                        $NewLapReimb->payment_date = null;
                        $NewLapReimb->save();
                    }
                }
            }
        }

        if ($user->changedPassword) {
            return redirect('/');
        } else {
            return redirect('user/firstPassword');
        }
    }
}
