@extends('layouts.template')
@section('main')
    <h3 class="text-center my-5">419 | <span class="text-black-50">Pagina verlopen</span></h3>
    <p class="text-center">
        Je pagina of sessie is verlopen, probeer de pagina te herladen.
    </p>
    <p class="text-center">
        Als het probleem zich blijft voordoen kan je de volgende dingen proberen:
    </p>
    <ul class="text-center no-bullet">
        <li>De pagina sluiten en weer openen</li>
        <li>Opnieuw uitloggen en inloggen</li>
        <li>Je browser cache leegmaken</li>
    </ul>
    <p class="text-center my-5">
        <a href="/" class="btn btn-primary mr-2">
            <i class="fas fa-home mr-1"></i>homepagina
        </a>
        <a href="#!" class="btn btn-primary
         ml-2" id="back">
            <i class="fas fa-undo mr-1"></i>terug
        </a>
    </p>
@endsection
@section('script_after')
    <script type="text/javascript">
        $('#back').click(function(){
            window.history.back();
        });
    </script>
@endsection
