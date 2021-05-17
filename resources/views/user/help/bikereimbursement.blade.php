@extends('layouts.template')

@section('title', 'Help - fietsvergoeding')

@section('main')
    <div class="help container">
        <h1>Help - fietsvergoeding</h1>
        <p>Op deze pagina is er de mogelijkheid om fietsritten op te slaan en voor deze opgeslagen fietsritten een fietsvergoeding aan te vragen.</p>
        <p><img src="/assets/icons/help/user/bike/page.png" alt="Pagina fietsvergoeding aanvragen"></p>
        <h2>Fietsritten selecteren</h2>
        <p>Bij het klikken op een datum kunnen fietsritten geselecteerd worden. Bij het klikken op een geselecteerde fietsrit, wordt de fietsrit gedeselecteerd. De geselecteerde fietsritten krijgen een oranje achtergrondkleur.</p>
        <p><img src="/assets/icons/help/user/bike/geselecteerd.png" alt="Fietsritten selecteren"></p>
        <h2>Fietsritten opslaan</h2>
        <p>De geselecteerde fietsritten kunnen opgeslagen worden door op de knop “Ritten opslaan” te klikken. </p>
        <p>Naast de knop “Ritten opslaan” staat een invoerveld voor het aantal km van de geselecteerde fietsritten, standaard staat hier de woon-werk afstand van de medewerker die ingelogd is. Deze waarde is aanpasbaar voor speciale ritten.</p>
        <p><img src="/assets/icons/help/user/bike/aantalKm.png" alt="Knoppen"></p>
        <p>Na het opslaan van de geselecteerde fietsritten, krijgen deze een blauwe achtergrondkleur.</p>
        <p><img src="/assets/icons/help/user/bike/opgeslagen.png" alt="Fietsritten opslaan"></p>
        <h2>Opgeslagen fietsritten aanpassen</h2>
        <p>De opgeslagen, blauw gekleurde, fietsritten kunnen steeds aangepast worden indien de fietsvergoeding nog niet aangevraagd is. Dit kan door op de fietsrit(ten) aan te klikken en op de knop “Ritten opslaan” te klikken.</p>
        <p><img src="/assets/icons/help/user/bike/editSaved.png" alt="Opgeslagen fietsritten aanpassen"></p>
        <h2>Fietsvergoeding aanvragen</h2>
        <p>Voor de opgeslagen, blauw gekleurde, fietsritten kan een fietsvergoeding aangevraagd worden. Dit kan door op de knop “Aanvraag indienen”. </p>
        <p>De aangevraagde fietsritten krijgen een grijze achtergrondkleur en kunnen niet meer aangepast worden.</p>
        <p><img src="/assets/icons/help/user/bike/request.png" alt="Fietsvergoeding aanvragen"></p>
        <h2>Bekijk hier het demonstratiefilmpje</h2>
        <div>
            <iframe width="560" height="315" src="https://www.youtube.com/embed/ukKXXLm9HPU" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
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
