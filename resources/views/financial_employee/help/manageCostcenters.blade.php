@extends('layouts.template')

@section('title', 'Help - kostenplaatsen beheren')

@section('main')
    <div class="help container">
        <h1>Help - kostenplaatsen beheren</h1>
        <div class="container">
            <div class="row">
                <div class="col col-12 col-md-8">
                    <img src="\assets\images\ManageCostCentersScreenshot.jpg" alt="Screenshot van het beheren van kostenplaatsen" style="width: 100%">
                </div>
                <div class="col col-12 col-md-4">
                    <h4>Het beheren van kostenplaatsen gaat als volgt:</h4>
                    <ul>
                        <li class="mt-4">
                            <h5>Het wijzigen van budgetten</h5>
                            <ol>
                                <li>
                                    <p>Verander het budget bij de kostenplaatsen waar nodig.</p>
                                </li>
                                <li>
                                    <p>Slaag de wijzigingen op door op de knop "Opslaan" te klikken.</p>
                                </li>
                            </ol>
                        </li>
                        <li class="mt-4">
                            <h5>Het verwijderen van kostenplaatsen</h5>
                            <ol>
                                <li>
                                    <p>Klik op het vuilnisbak icoontje van de kostenplaats die verwijderd moet worden.</p>
                                </li>
                            </ol>
                        </li>
                    </ul>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col col-12 col-md-8">
                    <img src="/assets/images/AddCostCenterScreenshot.jpg" alt="Screenshot over het toevoegen van een kostenplaats" style="width: 100%">
                </div>
                <div class="col col-12 col-md-4">
                    <h4>Het toevoegen van een kostenplaats</h4>
                    <ol>
                        <li>
                            <p>Klik op de knop "Kostenplaats toevoegen.</p>
                        </li>
                        <li>
                            <p>In het formulier dat verschijnt kan u alle gegevens ingeven. Om te annuleren drukt u op "Annuleren" of op het kruisje rechtsbovenaan het formulier.</p>
                        </li>
                        <li>
                            <p>Vervolgens geeft u de verplichte gegevens in; dit zijn de opleiding of unit waar de kostenplaats toe behoort, de naam van de kostenplaats en de verantwoordelijke die instaat voor de kostenplaats.</p>
                        </li>
                        <li>
                            <p>Voor de kostenplaats kan u ook kiezen voor een kostenplaats die al in het systeem zit. Dit is wel alleen mogelijk als de kostenplaats nog niet in de geselecteerde opleiding of unit zit.</p>
                        </li>
                        <li>
                            <p>Optioneel kan het budget ook al worden meegegeven, net zoals een korte omschrijving van de kostenplaats. De optie om de kostenplaats op actief te zetten is standaard aangevinkt. Als de kostenplaats nog niet geactiveerd moet worden komt deze niet in de lijst te staan.</p>
                        </li>
                        <li>
                            <p>Om de nieuwe kostenplaats op te slaan klikt u op de knop "Kostenplaats opslaan".</p>
                        </li>
                    </ol>
                </div>
            </div>
        </div>
        <h2>Bekijk hier het demonstratiefilmpje</h2>
        <div>
            <iframe width="560" height="315" src="https://www.youtube.com/embed/OeQl12ci3Ns" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
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
