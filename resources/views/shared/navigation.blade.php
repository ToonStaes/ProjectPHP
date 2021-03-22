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
            @guest
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('login') }}">{{ __('Inloggen') }}</a>
                </li>
            @else
            @endguest
            @auth
                    <li class="nav-item">
                        <a class="nav-link" href="/">Fietsvergoeding aanvragen</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/user/laptop">Laptopvergoeding aanvragen</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/">Diverse vergoeding aanvragen</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/user/mijnaanvragen">Mijn aanvragen</a>
                    </li>
                <li class="nav-item dropdown">

                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                        {{ Auth::user()->first_name }} {{ Auth::user()->last_name }}
                    </a>

                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">


                        @if(auth()->user()->isFinancial_employee == true)
                            <a class="dropdown-item" href="/">Vergoedingen behandelen</a>
                            <a class="dropdown-item" href="/users">Gebruikers beheren</a>
                            <a class="dropdown-item" href="/cost_centers">Kostenplaatsen beheren</a>
                            <a class="dropdown-item" href="/financial_employee/Mailcontent">Mailteksten beheren</a>
                            <a class="dropdown-item" href="/">Tarieven beheren</a>
                            <div class="dropdown-divider"></div>
                        @endif

                            @if(auth()->user()->isCost_Center_manager == true)
                                <a class="dropdown-item" href="/aanvragen_beheren">Aanvragen beheren</a>
                                <a class="dropdown-item" href="/">Kostenplaatsen vergelijken</a>
                                <div class="dropdown-divider"></div>
                            @endif

                        <a class="dropdown-item" href="/user/password">Wachtwoord aanpassen</a>
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
        </ul>
    </div>
</nav>

