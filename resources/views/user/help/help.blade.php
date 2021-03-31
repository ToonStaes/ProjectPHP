@extends('layouts.template')

@section('title', 'Help')

@section('main')
    <div class="help container">
        <h1>Help</h1>
        <div class="container row justify-content-around">
            <div class="col-md-6">
                <a class="card" href="https://google.com">
                    <div class="card-body container row">
                        <div class="col-sm-3 col-md-12 col-lg-3" >
                            <i style="font-size: 50px;" class="fas fa-biking card-img col-6"></i>
                        </div>
                        <div class="col-sm-9 col-md-12 col-lg-9">
                            <h5 class="card-title">Fietsvergoeding aanvragen</h5>
                            <p class="card-text">Meer informatie over hoe fietsritten op te slaan en hoe fietsvergoedingen aan te vragen.</p>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-6">
                <a class="card" href="https://google.com">
                    <div class="card-body container row">
                        <div class="col-sm-3 col-md-12 col-lg-3">
                            <i style="font-size: 50px;" class="fas fa-laptop card-img col-6"></i>
                        </div>
                        <div class="col-sm-9 col-md-12 col-lg-9">
                            <h5 class="card-title">Laptopvergoeding aanvragen</h5>

                            <p class="card-text">Meer informatie over hoe een vergoeding aan te vragen voor je laptop.</p>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-6">
                <a class="card" href="https://google.com">
                    <div class="card-body container row">
                        <div class="col-sm-3 col-md-12 col-lg-3">
                            <i style="font-size: 50px;" class="fas fa-receipt card-img col-6"></i>
                        </div>
                        <div class="col-sm-9 col-md-12 col-lg-9">
                            <h5 class="card-title">Diverse aanvragen</h5>

                            <p class="card-text">Meer informatie over het aanvragen van diverse zaken.</p>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-6">
                <a class="card" href="https://google.com">
                    <div class="card-body container row">
                        <div class="col-sm-3 col-md-12 col-lg-3">
                            <i style="font-size: 50px;" class="fas fa-wallet card-img col-6"></i>
                        </div>
                        <div class="col-sm-9 col-md-12 col-lg-9">
                            <h5 class="card-title">Mijn aanvragen</h5>

                            <p class="card-text">Meer informatie over het overzicht van je eigen aanvragen.</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
@endsection
