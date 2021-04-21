@extends('layouts.template')

@section('title', 'Help - mailteksten beheren')

@section('main')
    <div class="help container">
        <h1>Help - mailteksten beheren</h1>
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