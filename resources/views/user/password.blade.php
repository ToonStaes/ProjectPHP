@extends('layouts.template')

@section('title', 'Wachtwoord aanpassen')

@section('main')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Wachtwoord aanpassen') }}</div>
                    <div class="card-body">
                        @include('shared.alert')
                        <form action="/user/password" method="post">
                            @csrf
                            <div class="form-group">
                                <label for="current_password">Huidig wachtwoord</label>
                                <input type="password" name="current_password" id="current_password"
                                       class="form-control @error('current_password') is-invalid @enderror"
                                       placeholder="Huidig wachtwoord"
                                       value="{{ old('current_password') }}"
                                       required>
                                @error('current_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="password">Nieuw wachtwoord</label>
                                <input type="password" name="password" id="password"
                                       class="form-control @error('password') is-invalid @enderror"
                                       placeholder="Nieuw wachtwoord"
                                       value="{{ old('password') }}"
                                       minlength="8"
                                       required>
                                @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="password_confirmation">Bevestig nieuw wachtwoord</label>
                                <input type="password" name="password_confirmation" id="password_confirmation"
                                       class="form-control"
                                       placeholder="Bevestig nieuw wachtwoord"
                                       value="{{ old('password_confirmation') }}"
                                       minlength="8"
                                       required>
                            </div>
                            <button type="submit" class="btn btn-success">Toepassen</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
