@extends('layouts.template')

@section('title', 'Help - mijn aanvragen')

@section('main')
    <div class="help container">
        <h1>Help - mijn aanvragen</h1>
        <p>Net boven de tabel kan je kiezen hoeveel rijen je wil zien per pagina: <img src="/assets/icons/help/user/myRequests/aanvragenPerPagina.PNG" alt="Aantal rijen per pagina"></p>
        <p>Rechts onderaan kan je navigeren tussen pagina’s: <img src="/assets/icons/help/financial_employee/manageUsers/navigate.png" alt="Navigeren tussen paginas"></p>
        <p>De tabel kan volledig gesorteerd worden en er kan gefilterd worden op elke kolom. Sorteren doe je door op de 2 pijltjes naast de kolomtitel te klikken. Wil je graag op meerdere kolommen sorteren? Dat kan ook, als je de shift toets ingedrukt houdt terwijl je op de pijltjes duwt.</p>
        <p>Filteren kan door rechts bovenaan een filteropdracht in te geven in het tekstveldje: <img src="/assets/icons/help/financial_employee/manageUsers/filter.png" alt="Filteroptie"></p>
        <p>Deze filteropdracht gaat zoeken naar gelijkenissen in elke kolom. Om de filter te verwijderen, kan je het tekstveldje gewoon terug leeg maken.</p>

        <h2>Aanvragen bekijken</h2>
        <p>Op deze pagina vind je een overzicht van alle aanvragen die je ooit gedaan hebt. In deze tabel kan u verschillende zaken terugvinden over uw aanvragen</p>
        <p>[INSERT FOTO TABEL]</p>
        <p>In de eerste kolommen vind je de aanvraagdatum en de beoordelingsdata van de financieel medewerker en de kostenplaatsverantwoordelijke.</p>
        <p>[INSERT FOTO KOLOM 1 TEM 3]</p>
        <p>Als u met de muis over de statussen gaat staan, krijg je meer informatie over de kostenplaatsverantwoordelijke of de financieel medewerker. Als er nog geen informatie bekend is over die persoon of diens beoordeling krijg je geen extra info te zien.</p>

        <h2>Aanvraag aanpassen</h2>
        <p>Indien er in de laatste kolom een knopje staat om te bewerken kan de aanvraag nog aangepast worden.</p>
        <p>[INSERT FOTO LAATSTE KOLOM]</p>
        <p>Na het klikken zijn er 2 mogelijkheden die kunnen gebeuren. Ofwel heb je gekozen om een laptopaanvraag te bewerken en krijg je een pop-up met een formulier met de gegevens van de aanvraag ingevuld. </p>
        <p>[SCENARIO DIVERSE AANVRAAG; IS ME NOG ONDUIDELIJK HOE WE HET GAAN DOEN]</p>

        <h2>Laptopvergoeding aanpassen</h2>
        <p>Hier krijg je een pop-up met een gelijkaardig formulier met het originele. Het grootste verschil hier is dat er al gegevens in staan. Die kan u gewoon aanpassen. Het bestand veranderen werkt een beetje anders. In het formulier krijgt u het bestand dat u bij de oorspronkelijke aanvraag hebt meegegeven. Indien u dit wil veranderen kan u op het kruisje drukken en een nieuw bestand meegeven.</p>
        <p>[INSERT FOTO POP-UP FORMULIER]</p>

        <h2>Diverse aanvraag aanpassen</h2>
        <p>[MSS BEST DOOR JORNE LATEN INVULLEN?]</p>
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
