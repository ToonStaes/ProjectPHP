@extends('layouts.template')

@section('title', 'Laptopvergoeding aanvragen')

@section('main')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                @include('shared.alert')
                <div class="card">
                    <div class="card-header">{{ __('Laptopvergoeding aanvragen') }}</div>
                    <div class="card-body">

                        <form action="/user/laptop" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label for="bedrag">Bedrag in Euro</label>
                                <input type="number" name="bedrag" id="bedrag"
                                       class="form-control @error('bedrag') is-invalid @enderror"
                                       placeholder="123"
                                       required>
                                @error('bedrag')
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
                            <div class="form-group">
                                <label for="datum">Datum van aankoop</label>
                                <input type="date" name="datum" id="datum"
                                       class="form-control @error('datum') is-invalid @enderror"
                                       placeholder=""
                                       required>
                                @error('datum')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="bestand">Uploaden bewijsstuk</label>
                                <input type="file" name="UploadBestand" id="bestand"
                                       class="form-control @error('bestand') is-invalid @enderror"
                                       required>
                                @error('bestand')
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
