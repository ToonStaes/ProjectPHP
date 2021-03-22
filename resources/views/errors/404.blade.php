@extends('layouts.template')
@section('main')
    <h3 class="text-center my-5">404 | <span class="text-black-50">Niet gevonden</span></h3>
    <p class="text-center">
        We konden de pagina die je vroeg niet vinden
    </p>
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
