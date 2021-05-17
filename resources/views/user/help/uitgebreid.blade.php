@extends('layouts.template')

@section('title', 'Help - laptopvergoeding')

@section('main')
    <div class="help container">
        <h1>Uitgebreide helpfunctie</h1>
        <h2>De handleiding</h2>
        <div>
            <a href="/storage/Handleiding_onkostenportaal.pdf" class="btn btn-primary m-0 mt-2 mb-2" download> <i class="fas fa-file-pdf"></i> Download hier de handleiding</a>
        </div>
        <h2>Het demonstratiefilmpje</h2>
        <div>
            <iframe width="560" height="315" src="https://www.youtube.com/embed/OKc9FLSVyFo" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
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
