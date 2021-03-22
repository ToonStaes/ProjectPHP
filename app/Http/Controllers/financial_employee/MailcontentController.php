<?php

namespace App\Http\Controllers\financial_employee;

use App\Helpers\Json;
use App\Http\Controllers\Controller;
use App\Mailcontent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use View;

class MailcontentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('financial_employee.mailcontent.mailcontent');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return redirect('/Mailcontents');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return redirect('/Mailcontents');
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Mailcontent $mailcontent
     * @return \Illuminate\Http\Response
     */
    public function show(Mailcontent $mailcontent)
    {
        return redirect('/Mailcontents');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Mailcontent $mailcontent
     * @return \Illuminate\Http\Response
     */
    public function edit(Mailcontent $mailcontent)
    {
        return redirect('/Mailcontents');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Mailcontent $mailcontent
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Mailcontent $mailcontent)
    {
        $this->validate($request, [
            'mailcontent' => 'required|min:10'
        ]);

        $mailcontent->content = $request->mailcontent;
        $mailcontent->mailtype = $request->mailtype;
        $mailcontent->save();
        session()->flash('success', "De mail voor <b>$mailcontent->mailtype</b> is bijgewerkt");
        return View::make('shared.alert');
            //view::make('shared.alert');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Mailcontent $mailcontent
     * @return \Illuminate\Http\Response
     */
    public function destroy(Mailcontent $mailcontent)
    {
        //
    }


    public function qryMailcontents()
    {
        $mailcontent = Mailcontent::get()
            ->transform(function ($item, $key) {
                $item->ID = str_replace(' ', '', $item->mailtype);
                $item->content = nl2br($item->content);
                // unset unnecessary fields
                unset($item->created_at, $item->updated_at);
                return $item;
            });
//        $mailcontent->ID = trim($mailcontent->mailtype, ' ');
        $result = compact('mailcontent');
        \Facades\App\Helpers\Json::dump($result);
        return $result;
    }
}
