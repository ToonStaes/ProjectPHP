@extends('layouts.template')

@section('title', 'Diverse vergoeding aanvragen')

@section('main')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                @include('shared.alert')
                <div class="card">
                    <div class="card-header">{{ __('Diverse vergoeding aanvragen') }}</div>
                    <div class="card-body" id="FormDiv">
                        <button class="btn btn-primary" id="btnaddvergoeding"><i class="fas fa-plus-square"></i> Kost toevoegen</button>
                        <hr>
                        <form action="/user/divers" method="post" enctype="multipart/form-data" id="vergoedingform">
                            @csrf
                            <div class="form-group">
                                <label for="kostenplaats">Kostenplaats</label>
                                <select class="form-control" name="kostenplaats" id="kostenplaats" required>
                                    @foreach($kostenplaatsen as $kostenplaats)
                                        <option value="{{ $kostenplaats->id }}">{{ $kostenplaats->description}}</option>
                                    @endforeach
                                </select>
                                @error('kostenplaats')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
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
                            <button class="btn btn-success" id="btndienin" type="submit">Indienen</button>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script_after')
    <script>
        $('#FormDiv').on('click', '#AutoSwitch', function(){
            if ($(this).is(':checked')){
                $( this ).parent().parent().find(".NotAuto").hide();
                $( this ).parent().parent().find('.NotAuto').removeAttr('required');
                $( this ).parent().parent().find(".IsAuto").show();
                $( this ).parent().parent().find('.IsAuto').prop('required',true);
            }
            else {
                $( this ).parent().parent().find(".NotAuto").show();
                $( this ).parent().parent().find('.NotAuto').prop('required',true);
                $( this ).parent().parent().find(".IsAuto").hide();
                $( this ).parent().parent().find('.IsAuto').removeAttr('required');
            }
        });
    </script>
@endsection
