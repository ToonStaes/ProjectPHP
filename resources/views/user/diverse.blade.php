@extends('layouts.template')

@section('title', 'Diverse vergoeding aanvragen')

@section('main')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                @include('shared.alert')
                <div class="card">
                    <div class="card-header"><h1>Diverse vergoeding aanvragen</h1></div>
                    <div class="card-body" id="FormDiv">
                        <button class="btn btn-primary" id="btnaddvergoeding"><i class="fas fa-plus-square"></i> Kost toevoegen</button>
                        <hr>
                        <form action="/user/divers" method="post" enctype="multipart/form-data" id="vergoedingform">
                            @csrf
                            <input type="hidden" name="aantalkosten" id="aantalkosten" value=1
                                   class="form-control" required>
                            <div class="form-group">
                                <label for="kostenplaats">Kostenplaats</label>
                                <select class="form-control search-dropdown" name="kostenplaats" id="kostenplaats" required>
                                    <option value="">Selecteer een kostenplaats</option>
                                    @foreach($kostenplaatsen as $kostenplaats)
                                        <option value="{{ $kostenplaats->id }}">{{ $kostenplaats->name}} - {{ $kostenplaats->description}}</option>
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
                            <div class="kosten">
                                <div class="kost">
                                    <div class="form-check form-switch mx-3">
                                        <input class="form-check-input autoswitch" type="checkbox" id="AutoSwitch1" name="AutoSwitch1">
                                        <label class="form-check-label" for="flexSwitchCheckDefault">Autovergoeding?</label>
                                    </div>
                                    <div class="form-group NotAuto">
                                        <label for="bedrag">Bedrag in Euro</label>
                                        <input type="number" name="bedrag1" id="bedrag1"
                                               class="Bedrag NotAuto form-control @error('bedrag') is-invalid @enderror"
                                               placeholder="123"
                                               required>
                                        @error('bedrag')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group NotAuto">
                                        <label for="datum">Datum van aankoop</label>
                                        <input type="date" name="datum1" id="datum1"
                                               class="Datum NotAuto form-control @error('datum') is-invalid @enderror"
                                               placeholder=""
                                               required>
                                        @error('datum')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group NotAuto FileUpRoot">
                                        <input type="hidden" name="aantalbestanden1" id="aantalbestanden1" value=1
                                               class="form-control FileCount" required>
                                        <label for="bestand">Uploaden bewijsstuk</label> <a class="btn btn-sm btn-outline-dark btnaddbewijs"><i class="fas fa-plus-square"></i></a>
                                        <div class="FileUpDiv">
                                            <input type="file" name="UploadBestand1-1" id="bestand1-1" class="BestandInput NotAuto form-control-file @error('bestand') is-invalid @enderror" required>
                                        </div>
                                        @error('bestand')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group IsAuto" style="display: none">
                                        <label for="bedrag">Afstand in KM</label>
                                        <input type="number" name="afstand1" id="afstand1"
                                               class="Afstand IsAuto form-control @error('afstand') is-invalid @enderror"
                                               placeholder="123">
                                        @error('afstand')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <hr>
                                </div></div>
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
        $aantalaanvragen = 1;

        $('#btnaddvergoeding').click(function (){
            $aantalaanvragen++;
            $('.kost').last().clone().appendTo('.kosten');
            $( ".kost" ).last().attr("id","kost"+$aantalaanvragen)
            $( ".autoswitch" ).last().attr("id","AutoSwitch"+$aantalaanvragen)
            $( ".autoswitch" ).last().attr("name","AutoSwitch"+$aantalaanvragen)
            $( ".Bedrag" ).last().attr("name","bedrag"+$aantalaanvragen)
            $( ".Bedrag" ).last().attr("id","bedrag"+$aantalaanvragen)
            $( ".Datum" ).last().attr("name","datum"+$aantalaanvragen)
            $( ".Datum" ).last().attr("id","datum"+$aantalaanvragen)
            $( ".Bestand" ).last().attr("name","UploadBestand"+$aantalaanvragen)
            $( ".Bestand" ).last().attr("id","bestand"+$aantalaanvragen)
            $( ".Afstand" ).last().attr("name","afstand"+$aantalaanvragen)
            $( ".Afstand" ).last().attr("id","afstand"+$aantalaanvragen)
            $( ".FileCount" ).last().attr("name","aantalbestanden"+$aantalaanvragen)
            $( ".FileCount" ).last().attr("id","aantalbestanden"+$aantalaanvragen)
            $( ".FileCount" ).last().val(1);
            $('.FileUpDiv').last().html('<input type="file" name="UploadBestand1" id="bestand1" class="BestandInput NotAuto form-control-file" required>');
            $( ".BestandInput" ).last().attr("name","UploadBestand"+$aantalaanvragen+"-1")
            $( ".BestandInput" ).last().attr("id","bestand"+$aantalaanvragen+"-1")
            $('#aantalkosten').val($aantalaanvragen);
        });

        $('#FormDiv').on('click', '.btnaddbewijs', function(){
            $FileCount =  $(this).closest('.FileUpRoot').find('.FileCount').val();
            $FileCount++;
            $(this).closest('.FileUpRoot').find('.FileUpDiv').append('<input type="file" name="UploadBestand1" id="bestand1" class="BestandInput NotAuto form-control-file" required>');
            $(this).closest('.FileUpRoot').find('.BestandInput').last().attr("name","UploadBestand"+$aantalaanvragen+"-"+$FileCount)
            $(this).closest('.FileUpRoot').find('.BestandInput').last().attr("id","bestand"+$aantalaanvragen+"-"+$FileCount)
            $(this).closest('.FileUpRoot').find('.FileCount').val($FileCount);
        });


        $('#FormDiv').on('click', '.autoswitch', function(){
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
