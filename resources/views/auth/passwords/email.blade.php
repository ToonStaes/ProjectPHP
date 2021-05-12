@extends('layouts.template')

@section('main')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <h1>Wachtwoord opnieuw instellen</h1>
                <div class="card">
                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif
                        <form method="POST" action="/password/reset">
                            @csrf

                            <div class="form-group row">
                                <label for="email" class="col-md-4 col-form-label text-md-right">E-mail adres</label>
                                <div class="col-md-6">
                                    <input type="email" id="email"
                                           class="form-control @error('email') is-invalid @enderror"
                                           name="email" required autofocus>
                                    @error('email')
                                    <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">Vraag nieuw wachtwoord aan</button>
                                </div>
                            </div>
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
