@extends('layouts.template')

@section('title', 'Diverse vergoeding aanpassen')
@section('main')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <h1>Diverse vergoeding aanpassen <i class="fas fa-info-circle" data-toggle="tooltip" data-placement="right" title="Op deze pagina kan u uw aanvraag aanpassen voor diverse aankopen of voor een autorit."></i></h1>
                <div class="card">
                    <div class="card-body" id="FormDiv">
                        <button class="btn btn-primary" id="btnaddvergoeding"><i class="fas fa-plus-square"></i> Kost toevoegen</button>
                        <hr>
                        <form action="/user/divers/{{$Req->id}}" method="post" enctype="multipart/form-data" id="vergoedingform">
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
                                <input type="text" name="reden" id="reden" value="{{$Req->description}}"
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
                                        <input type="hidden" name="aantalbestaandebestanden1" id="aantalbestaandebestanden1" value=0
                                               class="form-control ExFileCount" required>
                                        <label for="existbestand">Bestaande bewijsstukken</label>
                                        <div class="FileExistingDiv">

                                        </div>
                                        @error('existbestand')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group NotAuto FileUpRoot">
                                        <input type="hidden" name="aantalbestanden1" id="aantalbestanden1" value=1
                                               class="form-control FileCount" required>
                                        <label for="bestand">Upload extra bewijsstukken</label> <a class="btn btn-sm btn-outline-dark btnaddbewijs"><i class="fas fa-plus-square"></i></a>
                                        <div class="FileUpDiv">
                                            <input type="file" name="UploadBestand1-1" id="bestand1-1" class="BestandInput NotAuto form-control-file @error('bestand') is-invalid @enderror">
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
                            <button class="btn btn-primary" id="btndienin" type="submit">Indienen</button>
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

        let $kostenplaatsen = {!! $kostenplaatsen !!};
        let $Req = {!! $Req !!};
        let $Lines = {!! $Lines !!};
        let $Evid = {!! $Evid !!};

        $('#btnaddvergoeding').click(function (){
            AddKost();
        });

        function AddKost(){
            $aantalaanvragen++;
            $('.kost').last().clone().appendTo('.kosten');
            $( ".kost" ).last().attr("id","kost"+$aantalaanvragen)
            $( ".autoswitch" ).last().attr("id","AutoSwitch"+$aantalaanvragen)
            $( ".autoswitch" ).last().attr("name","AutoSwitch"+$aantalaanvragen)
            $( ".Bedrag" ).last().attr("name","bedrag"+$aantalaanvragen)
            $( ".Bedrag" ).last().attr("id","bedrag"+$aantalaanvragen)
            $( ".Bedrag" ).last().val(null);
            $( ".Datum" ).last().attr("name","datum"+$aantalaanvragen)
            $( ".Datum" ).last().attr("id","datum"+$aantalaanvragen)
            $( ".Datum" ).last().val(null);
            $( ".Bestand" ).last().attr("name","UploadBestand"+$aantalaanvragen)
            $( ".Bestand" ).last().attr("id","bestand"+$aantalaanvragen)
            $( ".Afstand" ).last().attr("name","afstand"+$aantalaanvragen)
            $( ".Afstand" ).last().attr("id","afstand"+$aantalaanvragen)
            $( ".Afstand" ).last().val(null);
            $( ".FileCount" ).last().attr("name","aantalbestanden"+$aantalaanvragen)
            $( ".FileCount" ).last().attr("id","aantalbestanden"+$aantalaanvragen)
            $( ".ExFileCount" ).last().attr("name","aantalbestaandebestanden"+$aantalaanvragen)
            $( ".ExFileCount" ).last().attr("id","aantalbestaandebestanden"+$aantalaanvragen)
            $( ".FileCount" ).last().val(1);
            $('.FileExistingDiv').last().html("");
            $('.FileUpDiv').last().html('<input type="file" name="UploadBestand1" id="bestand1" class="BestandInput NotAuto form-control-file">');
            $( ".BestandInput" ).last().attr("name","UploadBestand"+$aantalaanvragen+"-1")
            $( ".BestandInput" ).last().attr("id","bestand"+$aantalaanvragen+"-1")
            $('#aantalkosten').val($aantalaanvragen);
            $(".BestandInput").removeAttr('required');
        }

        function BuildPage(){
            $("#kostenplaats").val($Req["cost_center_id"]);

            $first = true;
            $Lines.forEach(currline => {
                if ($first == false){
                    AddKost();
                }
                console.log(currline['amount']);
                if (currline['amount'] == null){
                    $('.autoswitch').last().prop('checked', true)

                    $('.autoswitch').last().parent().parent().find(".NotAuto").hide();
                    $('.autoswitch').last().parent().parent().find('.NotAuto').removeAttr('required');
                    $('.autoswitch').last().parent().parent().find(".IsAuto").show();
                    $('.autoswitch').last().parent().parent().find('.IsAuto').prop('required',true);

                    $( ".Afstand" ).last().val(currline["number_of_km"])
                    $( ".Datum" ).last().val(null);
                }
                else
                {
                    $('.autoswitch').last().prop('checked', false)

                    $('.autoswitch').last().parent().parent().find(".NotAuto").show();
                    $('.autoswitch').last().parent().parent().find('.NotAuto').prop('required',true);
                    $('.autoswitch').last().parent().parent().find(".IsAuto").hide();
                    $('.autoswitch').last().parent().parent().find('.IsAuto').removeAttr('required');

                    $( ".Bedrag" ).last().val(currline["amount"]);
                    $( ".Datum" ).last().val(currline["purchase_date"]);

                    $Evid.forEach(currevid =>{
                        if (currevid["DR_line_id"] == currline["id"]){
                            //toevoegen existing file
                            $FileCount =  $('.ExFileCount').last().val();
                            $FileCount++;
                            $displayname = currevid["filepath"].substring(13,currevid["filepath"].length);
                            $('.FileExistingDiv').last().append("<p class='ExBewijsTXT'><i class=\"fas fa-file\"></i> "+$displayname+"<a class='btnExDelete' style='cursor: pointer'>   <i class=\"fas fa-times\"></i></a></p>");
                            $('.FileExistingDiv').last().append('<input type="text" name="ExistBestand1-1" id="Exist1-1" class="d-none ExBestandInput NotAuto">');
                            $('.ExBestandInput').last().attr("name","ExistBestand"+$aantalaanvragen+"-"+$FileCount)
                            $('.ExBestandInput').last().attr("id","ExistBestand"+$aantalaanvragen+"-"+$FileCount)
                            $('.ExBestandInput').last().val(currevid["filepath"]);
                            $('.ExFileCount').last().val($FileCount);
                        }
                    });
                }
                $first = false;
            })
            $(".BestandInput").removeAttr('required');
        }

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
            $(".BestandInput").removeAttr('required');
        });

        $('#FormDiv').on('click', '.btnaddbewijs', function(){
            $FileCount =  $(this).closest('.FileUpRoot').find('.FileCount').val();
            $FileCount++;
            $(this).closest('.FileUpRoot').find('.FileUpDiv').append('<input type="file" name="UploadBestand1" id="bestand1" class="BestandInput NotAuto form-control-file" required>');
            $(this).closest('.FileUpRoot').find('.BestandInput').last().attr("name","UploadBestand"+$aantalaanvragen+"-"+$FileCount)
            $(this).closest('.FileUpRoot').find('.BestandInput').last().attr("id","bestand"+$aantalaanvragen+"-"+$FileCount)
            $(this).closest('.FileUpRoot').find('.FileCount').val($FileCount);
        });

        $('#FormDiv').on('click', '.btnExDelete', function(){
            $(this).parent().next().remove();
            $(this).closest('.ExBewijsTXT').remove();
        });

        BuildPage();
        @if (session()->has('success'))
        let success = new Noty({
            text: '{!! session()->get('success') !!}',
            type: 'success',
            layout: "topRight",
            timeout: 5000,
            progressBar: true,
            modal: false
        }).show();
        @endif
        @if (session()->has('danger'))
        let error = new Noty({
            text: '{!! session()->get('danger') !!}',
            type: 'error',
            layout: "topRight",
            timeout: 5000,
            progressBar: true,
            modal: false
        }).show();
        @endif
    </script>
@endsection
