@extends('layouts.template')

@section('main')
    @guest
        <h1>Welkom op het onkostenportaal van Thomas More!</h1>
        <p>Als Thomas More medewerker is de kans zeer groot dat u wel eens onkosten maakt voor uw beroep, zoals een stagebezoek, een laptopaankoop... Ook is er de mogelijkheid om in deze webapplicatie een vergoeding aan te vragen wanneer u zich met de fiets verplaatst naar het werk.</p>
        <p>Indien u nog niet over inloggegevens beschikt, contacteer dan de financiële dienst. Beschikbaar van maandag tot en met vrijdag, vanaf 8u tot 18u, op het telefoonnummer 015 36 91 00.</p>
    @else
    @endguest

    @auth
        <h1 class="text-center">Welkom {{Auth::user()->first_name}}</h1>
        <div class="help container">
        <div class="container row justify-content-between">
        <div class="col-md-6">
            <a class="card" href="/user/request_bike_reimbursement">
                <div class="card-body container row text-center">
                    <div class="col-12">
                        <i style="font-size: 50px;" class="fas fa-biking card-img col-6"></i>
                    </div>
                    <div class="col-12 mt-2">
                        <h5 class="card-title">Fietsvergoeding aanvragen</h5>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-6">
            <a class="card" href="/user/laptop">
                <div class="card-body container row text-center">
                    <div class="col-12">
                        <i style="font-size: 50px;" class="fas fa-laptop card-img col-6"></i>
                    </div>
                    <div class="col-12 mt-2">
                        <h5 class="card-title">Laptopvergoeding aanvragen</h5>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-6">
            <a class="card" href="/user/divers">
                <div class="card-body container row text-center">
                    <div class="col-12">
                        <i style="font-size: 50px;" class="fas fa-receipt card-img col-6"></i>
                    </div>
                    <div class="col-12 mt-2">
                        <h5 class="card-title">Diverse vergoeding aanvragen</h5>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-6">
            <a class="card" href="/user/mijnaanvragen">
                <div class="card-body container row text-center">
                    <div class="col-12">
                        <i style="font-size: 50px;" class="fas fa-wallet card-img col-6"></i>
                    </div>
                    <div class="col-12 mt-2">
                        <h5 class="card-title">Mijn aanvragen</h5>
                    </div>
                </div>
            </a>
        </div>
        </div>
        </div>
    @endauth
@endsection
