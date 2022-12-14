@extends('layouts.template')

@section('title', 'Laptopvergoeding aanvragen')

@section('main')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <h1>Laptopvergoeding aanvragen <i class="fas fa-info-circle" data-toggle="tooltip"
                                                  data-placement="right"
                                                  title="Op deze pagina kan u een vergoeding aanvragen voor uw aangekochte laptop."></i>
                </h1>
                <div class="card">
                    <div class="card-body">
                        <form action="/user/laptop" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label for="bedrag">Aankoopbedrag in euro</label>
                                <input type="number" name="bedrag" id="bedrag"
                                       class="form-control @error('bedrag') is-invalid @enderror"
                                       placeholder="123"
                                       value="{{old('bedrag')}}"
                                >
                                @error('bedrag')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="reden">Verklaring aanvraag <i class="fas fa-info-circle pr-3 pl-1"
                                                                          data-toggle="tooltip" data-placement="right"
                                                                          data-html="true"
                                                                          title="Geef hier de reden van aankoop in. <em>Voorbeeld: 'Ik heb een nieuwe laptop aangekocht. Mijn vorige laptop was erg vertraagd, hij was namelijk al 6 jaar oud.' </em>"></i></label>
                                <input type="text" name="reden" id="reden"
                                       class="form-control @error('reden') is-invalid @enderror"
                                       placeholder="Reden"
                                       value="{{old('reden')}}">
                                @error('reden')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="datum">Datum van aankoop</label>
                                <input type="date" name="datum" id="datum"
                                       class="form-control @error('datum') is-invalid @enderror"
                                       value="{{old('datum')}}">
                                @error('datum')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="bestand">Uploaden bewijsstuk <i class="fas fa-info-circle pr-3 pl-1"
                                                                            data-toggle="tooltip" data-placement="right"
                                                                            data-html="true"
                                                                            title="Upload hier bijvoorbeeld de factuur van uw laptop."></i></label>
                                <input type="file" name="UploadBestand" id="bestand"
                                       class="form-control-file @error('bestand') is-invalid @enderror">
                                @error('bestand')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary" data-toggle="tooltip"
                                    title="Dien uw laptopaanvraag in.">Aanvraag bevestigen
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script_after')
    <script>
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
