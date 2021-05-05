@extends('layouts.template')

@section('title', 'Help - vergoedingen behandelen')

@section('main')
    <div class="help container">
        <h1>Help - vergoedingen behandelen</h1>
        <p>Ook deze pagina maakt gebruik van een volledig sorteerbare tabel. Wil je meer info over hoe dit juist werkt? Dat kan je vinden onder de sectie ‘In een tabel navigeren (financieel medewerker – gebruikers beheren)’.</p>
        <p>Alle aanvragen die goedgekeurd zijn door de kostenplaatsverantwoordelijke komen in deze tabel te staan. </p>
        <p><img src="/assets/icons/help/financial_employee/manageRequest/pageVergoedingen.png" width="80%" alt="Pagina vergoedingen behandelen"></p>
        <p>Je kan bekijken wat deze persoon geschreven heeft door met je muis over het <i class="fas fa-info-circle"></i> te gaan staan. Op deze manier kan je zien wanneer de kostenplaatsverantwoordelijke het goedgekeurd heeft, wie de verantwoordelijke is en wat zijn/haar commentaar was.</p>
        <p><img src="/assets/icons/help/financial_employee/manageRequest/i.png" alt="Commentaar"></p>

        <h2>Aanvragen goed-/ afkeuren</h2>
        <p>Je kan zelf een beoordeling toevoegen door een keuze te maken in de dropdownlijst in de laatste kolom.</p>
        <p><img src="/assets/icons/help/financial_employee/manageRequest/dropdown.png" alt="Beoordeling dropdownlist"></p>
        <p>Als je hier een beoordeling selecteert, zal er een venster tevoorschijn komen waar je commentaar kan ingeven. De beoordeling wordt opgeslagen wanneer je ‘opslaan’ klikt in het geopende venster.</p>
        <p><img src="/assets/icons/help/financial_employee/manageRequest/comment.png" alt="Commentaar"></p>

        <h2>Aanvragen uitbetalen</h2>
        <p>Een goedgekeurde aanvraag is natuurlijk nog niet betaald. Om deze aanvragen uit te betalen, kan je rechts bovenaan klikken op ‘Openstaande betalingen uitbetalen’. <img src="/assets/icons/help/financial_employee/manageRequest/pay.png" alt="Openstaande betalingen"></p>
        <p>Dit zijn alle aanvragen die goedgekeurd zijn door een financiële medewerker, maar die nog niet betaald zijn. Na het drukken op deze knop krijg je nog een overzicht van alle aanvragen die betaald worden, hier kan je nog eens controleren of alles correct ingevuld is. Zolang een aanvraag nog niet betaald is, kan deze nog aangepast worden dus kijk dit zeker goed na.</p>

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
