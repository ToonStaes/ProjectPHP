@extends('layouts.template')

@section('title', 'Help')

@section('main')
    <div class="help container">
        <h1>Help</h1>
        <div class="container row justify-content-between">
            @auth
                <div class="col-md-6">
                    <a class="card" href="help/fietsvergoeding">
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
                    <a class="card" href="help/laptopvergoeding">
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
                    <a class="card" href="help/diverseAanvragen">
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
                    <a class="card" href="help/mijnAanvragen">
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
                @if(auth()->user()->isFinancial_employee == true)
                    <div class="col-md-6">
                        <a class="card" href="/financial_employee/help/vergoedingenBeheren">
                            <div class="card-body container row">
                                <div class="col-sm-3 col-md-12 col-lg-3">
                                    <i style="font-size: 50px;" class="fas fa-comments-dollar card-img col-6"></i>
                                </div>
                                <div class="col-sm-9 col-md-12 col-lg-9">
                                    <h5 class="card-title">Vergoedingen behandelen</h5>
                                    <p class="card-text">Meer informatie over het goed- of afkeuren van vergoedingen.</p>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-md-6">
                        <a class="card" href="/financial_employee/help/gebruikersBeheren">
                            <div class="card-body container row">
                                <div class="col-sm-3 col-md-12 col-lg-3">
                                    <i style="font-size: 50px;" class="fas fa-users card-img col-6"></i>
                                </div>
                                <div class="col-sm-9 col-md-12 col-lg-9">
                                    <h5 class="card-title">Gebruikers beheren</h5>
                                    <p class="card-text">Meer informatie over het toevoegen, wijzigen en verwijderen van gebruikers.</p>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-md-6">
                        <a class="card" href="/financial_employee/help/kostenplaatsenBeheren">
                            <div class="card-body container row">
                                <div class="col-sm-3 col-md-12 col-lg-3">
                                    <i style="font-size: 50px;" class="fas fa-donate card-img col-6"></i>
                                </div>
                                <div class="col-sm-9 col-md-12 col-lg-9">
                                    <h5 class="card-title">Kostenplaatsen beheren</h5>
                                    <p class="card-text">Meer informatie over het toevoegen, wijzigen en verwijderen van kostenplaatsen en hun budgetten.</p>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-md-6">
                        <a class="card" href="/financial_employee/help/mailtekstenBeheren">
                            <div class="card-body container row">
                                <div class="col-sm-3 col-md-12 col-lg-3">
                                    <i style="font-size: 50px;" class="fas fa-mail-bulk card-img col-6"></i>
                                </div>
                                <div class="col-sm-9 col-md-12 col-lg-9">
                                    <h5 class="card-title">Mailteksten beheren</h5>
                                    <p class="card-text">Meer informatie over het wijzigen van de standaard mailteksten.</p>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-md-6">
                        <a class="card" href="/financial_employee/help/parametersBeheren">
                            <div class="card-body container row">
                                <div class="col-sm-3 col-md-12 col-lg-3">
                                    <i style="font-size: 50px;" class="fas fa-edit card-img col-6"></i>
                                </div>
                                <div class="col-sm-9 col-md-12 col-lg-9">
                                    <h5 class="card-title">Parameters beheren</h5>
                                    <p class="card-text">Meer informatie over het beheren van de parameters.</p>
                                </div>
                            </div>
                        </a>
                    </div>
                @endif

                @if(auth()->user()->isCost_Center_manager == true)
                    <div class="col-md-6">
                        <a class="card" href="/cost_center_manager/help/aanvragenBeheren">
                            <div class="card-body container row">
                                <div class="col-sm-3 col-md-12 col-lg-3">
                                    <i style="font-size: 50px;" class="fas fa-comments card-img col-6"></i>
                                </div>
                                <div class="col-sm-9 col-md-12 col-lg-9">
                                    <h5 class="card-title">Aanvragen beheren</h5>

                                    <p class="card-text">Meer informatie over het goed- of afkeuren van aanvragen.</p>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-6">
                        <a class="card" href="/cost_center_manager/help/kostenplaatsenVergelijken">
                            <div class="card-body container row">
                                <div class="col-sm-3 col-md-12 col-lg-3">
                                    <i style="font-size: 50px;" class="fas fa-balance-scale card-img col-6"></i>
                                </div>
                                <div class="col-sm-9 col-md-12 col-lg-9">
                                    <h5 class="card-title">Kostenplaatsen vergelijken</h5>
                                    <p class="card-text">Meer informatie over het vergelijken van verschillende kostenplaatsen.</p>
                                </div>
                            </div>
                        </a>
                    </div>
                @endif
            @endauth
        </div>
    </div>
@endsection


