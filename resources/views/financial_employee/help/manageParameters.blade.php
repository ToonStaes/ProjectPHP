@extends('layouts.template')

@section('title', 'Help - parameters beheren')

@section('main')
    <div class="help container">
        <h1>Help - parameters beheren</h1>
        <p>Op deze pagina kan u de parameters beheren.</p>
        <p><img src="/assets/icons/help/financial_employee/manageParameters/parameters.png" alt="Pagina parameters beheren"></p>
        <h2>Fietsvergoeding aanpassen</h2>
        <p>In het invoerveld vindt u de standaard fietsvergoeding per km op dit moment, deze kan u aanpassen door een andere waarde in te vullen en op de knop “Parameters opslaan” te klikken.</p>
        <p><img src="/assets/icons/help/financial_employee/manageParameters/fiets.png" alt="Standaard fietsvergoeding"></p>
        <h2>Autovergoeding aanpassen</h2>
        <p>In het invoerveld vindt u de standaard autovergoeding per km op dit moment, deze kan u aanpassen door een andere waarde in te vullen en op de knop “Parameters opslaan” te klikken.</p>
        <p><img src="/assets/icons/help/financial_employee/manageParameters/auto.png" alt="Standaard autovergoeding"></p>
        <h2>Maximum schijfgrootte van de laptopvergoeding aanpassen</h2>
        <p>In het invoerveld vindt u de maximum schijfgrootte voor een laptopvergoeding op dit moment, deze kan u aanpassen door een andere waarde in te vullen en op de knop “Parameters opslaan” te klikken.</p>
        <p><img src="/assets/icons/help/financial_employee/manageParameters/laptop.png" alt="Maximum schijfgrootte van de laptopvergoeding"></p>
        <h2>Standaard kostenplaats van de laptopvergoeding aanpassen</h2>
        <p>In het invoerveld vindt u de standaard kostenplaats voor de laptopvergoeding op dit moment. Deze kan u aanpassen door een andere kostenplaats te selecteren in de dropdownlijst, het is ook mogelijk om in deze lijst te zoeken. De waarde wordt opgeslagen bij het klikken op de knop “Parameters opslaan”.</p>
        <p><img src="/assets/icons/help/financial_employee/manageParameters/kostenplaatsLaptop.png" alt="Standaard kostenplaats laptopvergoeding"></p>
        <h2>Standaard kostenplaats van de fietsvergoeding aanpassen</h2>
        <p>In het invoerveld vindt u de standaard kostenplaats voor de fietsvergoeding op dit moment. Deze kan u aanpassen door een andere kostenplaats te selecteren in de dropdownlijst, het is ook mogelijk om in deze lijst te zoeken. De waarde wordt opgeslagen bij het klikken op de knop “Parameters opslaan”.</p>
        <p><img src="/assets/icons/help/financial_employee/manageParameters/kostenplaatsFiets.png" alt="Standaard kostenplaats fietsvergoeding"></p>
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
