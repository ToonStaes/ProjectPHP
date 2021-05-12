@extends('layouts.template')

@section('title', 'Help - mailteksten beheren')

@section('main')
    <div class="help container">
        <h1>Help - mailteksten beheren</h1>
        <div class="container">
            <div class="row">
                <div class="col col-12 col-md-7">
                    <img src="/assets/images/MailTextScreenshot.jpg"  alt="Screenshot over het aanpassen van mailteksten" style="width: 100%">
                </div>
                <div class="col col-12 col-md-5">
                    <h4>Het aanpassen van mailteksten</h4>
                    <ul>
                        <li class="mt-4">
                            <h5>Mailtypes</h5>
                            <p>
                                Er zijn drie verschillende acties waarbij het systeem automatisch een mail verstuurd.
                                Voor deze mails kan de tekst die verstuurd wordt aangepast worden.
                            </p>
                            <ul>
                                <li><b>Afwijzing van aanvraag:</b> dit is de mail die wordt verstuurd als een
                                vergoedingsaanvraag van een gebruiker afgewezen wordt.</li>
                                <li><b>Nieuwe gebruiker:</b> dit is de mail die wordt verstuurd als een nieuwe gebruiker
                                een account aanmaakt.</li>
                                <li><b>Wachtwoord vergeten:</b> dit is de mail die wordt verstuurd als een gebruiker hun
                                wachtwoord vergeten is.</li>
                            </ul>
                        </li>
                        <li class="mt-4">
                            <h5>Gebruik van gegevens</h5>
                            <p>
                                Vaak is het nodig om bij het versturen van deze mails extra informatie mee te geven
                                vanuit het systeem. Deze kunnen worden weergegeven via speciale "variabelen" die in de
                                tekst geplaatst kunnen worden. Die variabelen worden dan vervangen door de juiste gegevens.
                            </p>
                            <p>
                                Elke sommige gegevens kunnen alleen gebruikt worden bij de correcte soort mail. Deze
                                gegevens zijn:
                            </p>
                            <h6><b>Afwijzing van aanvraag:</b></h6>
                            <ul>
                                <li>Naam van bestemmeling: [NAAM]</li>
                                <li>Naam van de medewerker die de vergoeding heeft behandelt: [NAAM FINANCIEEL MEDEWERKER]</li>
                                <li>Omschrijving van de aanvraag: [AANVRAAG]</li>
                                <li>Reden van afwijzing: [REDEN]</li>
                            </ul>
                            <h6><b>Nieuwe gebruiker:</b></h6>
                            <ul>
                                <li>Naam van de gebruiker: [NAAM]</li>
                                <li>Emailadres van de gebruiker: [EMAIL]</li>
                                <li>Wachtwoord van de gebruiker: [WACHTWOORD]</li>
                            </ul>
                            <h6><b>Wachtwoord vergeten:</b></h6>
                            <ul>
                                <li>Naam van de gebruiker: [NAAM]</li>
                                <li>Emailadres van de gebruiker: [EMAIL]</li>
                                <li>Wachtwoord van de gebruiker: [WACHTWOORD]</li>
                            </ul>
                        </li>
                        <li class="mt-4">
                            <h5>Verdere hulp</h5>
                            <p>Voor het gebruiksgemak is deze zelfde informatie beschikbaar op de pagina "mailteksten
                            beheren", door op het hulpicoon naast de titel te klikken.</p>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <a href="#!" class="btn btn-primary m-0 mt-2" id="back">Terug</a>
    </div>
@endsection
@section('script_after')
    <script type="text/javascript">
        $('#back').click(function(){
            window.history.back();
        });
    </script>
@endsection
