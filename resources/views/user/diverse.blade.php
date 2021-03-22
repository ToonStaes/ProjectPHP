@extends('layouts.template')

@section('title', 'Diverse vergoeding aanvragen')

@section('main')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                @include('shared.alert')
                <div class="card">
                    <div class="card-header">{{ __('Diverse vergoeding aanvragen') }}</div>
                    <div class="card-body">

                        <form action="/user/divers" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label for="reden">Verklaring aanvraag</label>
                                <input type="text" name="reden" id="reden"
                                       class="form-control @error('reden') is-invalid @enderror"
                                       placeholder="Reden"
                                       required>
                                @error('reden')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="AutoSwitch" name="AutoSwitch">
                                <label class="form-check-label" for="flexSwitchCheckDefault">Autovergoeding?</label>
                            </div>
                            <div class="form-group NotAuto">
                                <label for="bedrag">Bedrag in Euro</label>
                                <input type="number" name="bedrag" id="bedrag"
                                       class="NotAuto form-control @error('bedrag') is-invalid @enderror"
                                       placeholder="123"
                                       required>
                                @error('bedrag')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group NotAuto">
                                <label for="datum">Datum van aankoop</label>
                                <input type="date" name="datum" id="datum"
                                       class="NotAuto form-control @error('datum') is-invalid @enderror"
                                       placeholder=""
                                       required>
                                @error('datum')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group NotAuto">
                                <label for="bestand">Uploaden bewijsstuk</label>
                                <input type="file" name="UploadBestand" id="bestand"
                                       class="NotAuto form-control-file @error('bestand') is-invalid @enderror"
                                       required>
                                @error('bestand')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group IsAuto" style="display: none">
                                <label for="bedrag">Afstand in KM</label>
                                <input type="number" name="afstand" id="afstand"
                                       class="IsAuto form-control @error('afstand') is-invalid @enderror"
                                       placeholder="123">
                                @error('afstand')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-success">Aanvraag bevestigen</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script_after')
    <script>
        $("#AutoSwitch").click(function(){
            if ($("#AutoSwitch").is(':checked')){
                $(".NotAuto").hide();
                $('.NotAuto').removeAttr('required');
                $(".IsAuto").show();
                $('.IsAuto').prop('required',true);
            }
            else {
                $(".NotAuto").show();
                $('.NotAuto').prop('required',true);
                $(".IsAuto").hide();
                $('.IsAuto').removeAttr('required');
            }
        });
    </script>
@endsection
