@extends('layouts.template')

@section('title', 'Wachtwoord aanpassen')

@section('main')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <h1>Wachtwoord aanpassen <i class="fas fa-info-circle" data-toggle="tooltip" data-placement="right" title="Op deze pagina kan u uw wachtwoord wijzigen."></i></h1>
                <div class="card">
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
                                <i class="far fa-eye" id="togglePasswordCurrent"></i>
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
                                <i class="far fa-eye" id="togglePasswordNew1"></i>
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
                                <i class="far fa-eye" id="togglePasswordNew2"></i>
                            </div>
                            <button type="submit" class="btn btn-primary" data-toggle="tooltip" data-placement="right" title="Uw nieuw wachtwoord opslaan">Opslaan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script_after')
    <script>
            //current password
            document.querySelector('#togglePasswordCurrent').addEventListener('click', function (e) {
                // toggle the type attribute
                const type = document.querySelector('#current_password').getAttribute('type') === 'password' ? 'text' : 'password';
                document.querySelector('#current_password').setAttribute('type', type);
                // toggle the eye slash icon
                this.classList.toggle('fa-eye-slash');
            });

            //new password 1
            document.querySelector('#togglePasswordNew1').addEventListener('click', function (e) {
                // toggle the type attribute
                const type = document.querySelector('#password').getAttribute('type') === 'password' ? 'text' : 'password';
                document.querySelector('#password').setAttribute('type', type);
                // toggle the eye slash icon
                this.classList.toggle('fa-eye-slash');
            });

            //new password 2
            document.querySelector('#togglePasswordNew2').addEventListener('click', function (e) {
                // toggle the type attribute
                const type = document.querySelector('#password_confirmation').getAttribute('type') === 'password' ? 'text' : 'password';
                document.querySelector('#password_confirmation').setAttribute('type', type);
                // toggle the eye slash icon
                this.classList.toggle('fa-eye-slash');
            });


    </script>
@endsection
