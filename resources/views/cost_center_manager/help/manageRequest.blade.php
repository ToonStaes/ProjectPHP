@extends('layouts.template')

@section('title', 'Help - aanvragen beheren')

@section('main')
    <div class="help container">
        <h1>Help - aanvragen beheren</h1>
        <h2>In de tabel navigeren</h2>
        <p>Net boven de tabel kan je kiezen hoeveel rijen je wil zien per pagina: <img src="/assets/icons/help/cost_center_manager/manageRequests/aanvragenPerPagina.PNG" alt="Aantal rijen per pagina"></p>
        <p>Rechts onderaan kan je navigeren tussen pagina’s: <img src="/assets/icons/help/financial_employee/manageUsers/navigate.png" alt="Navigeren tussen paginas"></p>
        <p>De tabel kan volledig gesorteerd worden en er kan gefilterd worden op elke kolom. Sorteren doe je door op de 2 pijltjes naast de kolomtitel te klikken. Wil je graag op meerdere kolommen sorteren? Dat kan ook, als je de shift toets ingedrukt houdt terwijl je op de pijltjes duwt.</p>
        <p>Filteren kan door rechts bovenaan een filteropdracht in te geven in het tekstveldje: <img src="/assets/icons/help/financial_employee/manageUsers/filter.png" alt="Filteroptie"></p>
        <p>Deze filteropdracht gaat zoeken naar gelijkenissen in elke kolom. Om de filter te verwijderen, kan je het tekstveldje gewoon terug leeg maken.</p>


        <h2>Aanvragen goed-/ afkeuren</h2>
        <p>Op deze pagina kan je alle binnengekomen aanvragen beoordelen.</p>
        <p><img src="/assets/icons/help/cost_center_manager/manageRequests/page.PNG" width="" alt="Pagina aanvragen beheren"></p>
        <p>In de voorlaatste tabel is een dropdownlijst waar je een beoordeling kan selecteren.</p>
        <p><img src="/assets/icons/help/financial_employee/manageRequest/dropdown.png" alt="Dropdown"></p>
        <p>Na het selecteren van een beoordeling, krijg je een venster te zien waar je commentaar kan geven. Om alles op te slaan, kan je gewoon op de knop ‘opslaan’ klikken onderaan het commentaar-venster.</p>
        <p><img src="/assets/icons/help/financial_employee/manageRequest/comment.png" alt="Commentaar"></p>
        <p>Als je een aanvraag goed- of afgekeurd hebt, kan je je commentaar bekijken door met je muis over de dropdownlijst te gaan staan. Als een financiële medewerker deze aanvraag beoordeeld heeft, kan je de gegevens daarvan bekijken door met je muis op het <i class="fas fa-info-circle"></i> in de laatste kolom te gaan staan.</p>
        <p><img src="/assets/icons/help/cost_center_manager/manageRequests/i.png" alt="Commentaar financieel medewerker"></p>
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
