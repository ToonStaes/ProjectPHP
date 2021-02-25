<nav class="navbar navbar-fixed-top navbar-expand-lg flex-column">
    <div id="lijn1">
        <a class="navbar-brand" href="/">Onkostenportaal</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsNav">
            <span class="navbar-toggler-icon"><i class="fas fa-bars"></i></span>
        </button>
    </div>
    <div class="collapse navbar-collapse shadow-sm" id="collapsNav">
{{--                Navigatie voor iedere user--}}
        <ul class="navbar-nav mx-auto">
            <li class="nav-item">
                <a class="nav-link" href="/">Fietsvergoeding aanvragen</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/">Laptopvergoeding aanvragen</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/">Diverse vergoeding aanvragen</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/">Mijn aanvragen</a>
            </li>
            @guest
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('login') }}">{{ __('Inloggen') }}</a>
                </li>
                @if (Route::has('register'))
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('register') }}">{{ __('Registreren') }}</a>
                    </li>
                @endif
            @else
            @endguest
            @auth
                <li class="nav-item dropdown">
                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                        {{ Auth::user()->first_name }} {{ Auth::user()->last_name }}
                    </a>

                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">


                        @if(auth()->user()->isFinancial_employee == true)
                            <a class="dropdown-item" href="/">Vergoedingen behandelen</a>
                            <a class="dropdown-item" href="/">Gebruikers beheren</a>
                            <a class="dropdown-item" href="/">Kostenplaatsen beheren</a>
                            <a class="dropdown-item" href="/">Mailteksten beheren</a>
                            <a class="dropdown-item" href="/">Tarieven beheren</a>
                            <div class="dropdown-divider"></div>
                        @endif

                            @if(auth()->user()->isCost_Center_manager == true)
                                <a class="dropdown-item" href="/">Aanvragen beheren</a>
                                <a class="dropdown-item" href="/">Kostenplaatsen vergelijken</a>
                                <div class="dropdown-divider"></div>
                            @endif

                        <a class="dropdown-item" href="">Wachtwoord aanpassen</a>
                        <a class="dropdown-item" href="{{ route('logout') }}"
                           onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                            {{ __('Uitloggen') }}
                        </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </div>
                </li>
            @endauth
{{--                    Navigatie voor docent--}}
{{--            <li class="nav-item dropdown">--}}
{{--                <a class="nav-link dropdown-toggle" href="#!" data-toggle="dropdown">Rik Rikken (d)</a>--}}
{{--                <div class="dropdown-menu dropdown-menu-right">--}}
{{--                    <a class="dropdown-item" href="/">Uitloggen</a>--}}
{{--                    <a class="dropdown-item" href="/">Wachtwoord aanpassen</a>--}}
{{--                </div>--}}
{{--            </li>--}}
{{--                    Navigatie voor kostenplaatsverantwoordelijke --}}
{{--            <li class="nav-item dropdown">--}}
{{--                <a class="nav-link dropdown-toggle" href="#!" data-toggle="dropdown">John Doe (k)</a>--}}
{{--                <div class="dropdown-menu dropdown-menu-right">--}}
{{--                    <a class="dropdown-item" href="/">Uitloggen</a>--}}
{{--                    <a class="dropdown-item" href="/">Wachtwoord aanpassen</a>--}}
{{--                    <a class="dropdown-item" href="/">Aanvragen beheren</a>--}}
{{--                    <a class="dropdown-item" href="/">Kostenplaatsen vergelijken</a>--}}
{{--                </div>--}}
{{--            </li>--}}
{{--                    Navigatie voor financieel medewerker--}}
{{--            <li class="nav-item dropdown">--}}
{{--                <a class="nav-link dropdown-toggle" href="#!" data-toggle="dropdown">Jane Doe (f)</a>--}}
{{--                <div class="dropdown-menu dropdown-menu-right">--}}
{{--                    <a class="dropdown-item" href="/">Uitloggen</a>--}}
{{--                    <a class="dropdown-item" href="/">Wachtwoord aanpassen</a>--}}
{{--                    <a class="dropdown-item" href="/">Vergoedingen behandelen</a>--}}
{{--                    <a class="dropdown-item" href="/">Gebruikers beheren</a>--}}
{{--                    <a class="dropdown-item" href="/">Kostenplaatsen beheren</a>--}}
{{--                    <a class="dropdown-item" href="/">Mailteksten beheren</a>--}}
{{--                    <a class="dropdown-item" href="/">Tarieven beheren</a>--}}
{{--                </div>--}}
{{--            </li>--}}
        </ul>
    </div>
</nav>

