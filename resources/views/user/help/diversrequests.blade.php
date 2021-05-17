@extends('layouts.template')

@section('title', 'Help - diverse aanvragen')

@section('main')
    <div class="help container">
        <h1>Help - diverse aanvragen</h1>

        <div class="container">
            <div class="row">
                <div class="col-md-8 col-sm-12">
                    <img src="\assets\images\DiverseScreenshot.png" alt="Screenshot website" style="width: 100%">
                </div>
                <div class="col-md-4 col-sm-12">
                    <h4>Diverse vergoedingen aanvragen gaat als volgt:</h4>
                    <ol class="">
                        <li>
                            <p>Voeg met de knop bovenaan zoveel kosten toe als gewenst.</p>
                        </li>
                        <li>
                            <p>Begin met het selecteren van een kostenplaats. <em>Dit is de kostenplaats voor alle kosten die je toevoegt.</em></p>
                        </li>
                        <li>
                            <p>Geef vervolgens een verklaring in voor de aanvraag. Ook deze reden geld voor elke kost.</p>
                        </li>
                        <li>
                            <p>Indien het gaat over een autovergoeding, klik dan op de slider. Er verschijnt een vakje om het aantal kilometers in te geven. Voor de rest hoef je bij een autovergoeding niets in te vullen.</p>
                        </li>
                        <li>
                            <p>Indien het niet gaat over een autovergoeding vul je eerst het bedrag van de kost in. Vervolgens geef je de datum in van de aankoop. Deze datum kan niet na vandaag zijn. Als laatste klik je nog op "bladeren" en selecteer je een bestand op je toestel als bewijs. Je kan nog extra bestanden toevoegen door op het "+" knopje te klikken.</p>
                        </li>
                        <li>
                            <p>Wanneer u klaar bent met het invullen van de gegevens, klikt u op 'indienen'. U krijgt bovenaan het formulier een bericht dat u laat weten of de aanvraag succesvol is. Indien er toch iets fout loopt krijgt u te zien wat het probleem is.</p>
                        </li>
                    </ol>
                </div>
            </div>
        </div>
        <h2>Bekijk hier het demonstratiefilmpje</h2>
        <div>
            <iframe width="560" height="315" src="https://www.youtube.com/embed/6tIEbCC-TDg" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
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
