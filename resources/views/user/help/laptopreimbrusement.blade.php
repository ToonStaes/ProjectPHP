@extends('layouts.template')

@section('title', 'Help - laptopvergoeding')

@section('main')
    <div class="help container">
        <h1>Help - laptopvergoeding</h1>

        <div class="container">
            <div class="row">
                <div class="col-md-8 col-sm-12">
                    <img src="\assets\images\LaptopScreenshot.png" alt="Screenshot website" style="width: 100%">
                </div>
                <div class="col-md-4 col-sm-12">
                    <h4>Een laptopvergoeding aanvragen gaat als volgt:</h4>
                    <ol class="">
                        <li>
                            <p>Vul het bedrag van de aankoop in in Euro.</p>
                        </li>
                        <li>
                            <p>Geef de reden van de aanvraag. Dit moet slechts een korte tekst zijn.</p>
                        </li>
                        <li>
                            <p>Selecteer de datum van de aankoop. Deze datum kan geen dag zijn na vandaag.</p>
                        </li>
                        <li>
                            <p>Upload een bestand om de aankoop te bewijzen door op de knop 'bladeren' te klikken en een bestand op uw apparaat te kiezen. Dit kan in om het even welk formaat zijn, maar de voorkeur gaat uit naar PDF.</p>
                        </li>
                        <li>
                            <p>Wanneer u klaar bent met het invullen van de gegevens, klikt u op 'aanvraag bevestigen'. U krijgt bovenaan het formulier een bericht dat u laat weten of de aanvraag succesvol is. Indien er toch iets fout loopt krijgt u te zien wat het probleem is.</p>
                        </li>
                    </ol>
                </div>
            </div>
        </div>
        <h2>Bekijk hier het demonstratiefilmpje</h2>
        <div>
            <iframe width="560" height="315" src="https://www.youtube.com/embed/eOByn1BxKeE" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
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
