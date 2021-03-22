{{--@extends('layouts.template')--}}

{{--@section('main')--}}
{{--    <h1>Parameters beheren</h1>--}}
{{--    <div class="card">--}}
{{--        <div class="card-body">--}}

{{--            <form action="/user/laptop" method="post" enctype="multipart/form-data">--}}
{{--            <form action="">--}}
{{--                @csrf--}}
{{--                <div class="form-group">--}}
{{--                    <label for="bikereimbursement">Fietsvergoeding:</label>--}}
{{--                    <input type="text" id="bikereimbursement" name="bikereimbursement" class="form-control-file">--}}
{{--                    <input type="number" name="bedrag" id="bedrag"--}}
{{--                           class="form-control @error('bedrag') is-invalid @enderror"--}}
{{--                           placeholder="123"--}}
{{--                           required>--}}
{{--                    @error('bedrag')--}}
{{--                    <div class="invalid-feedback">{{ $message }}</div>--}}
{{--                    @enderror--}}
{{--                </div>--}}
{{--                <div class="form-group">--}}
{{--                    <label for="carreimbursement">Autovergoeding:</label>--}}
{{--                    <input type="text" id="carreimbursement" name="carreimbursement">--}}
{{--                </div>--}}
{{--                <div class="form-group">--}}
{{--                    <label for="laptop">Maximum schijfgrootte laptop:</label>--}}
{{--                    <input type="text" id="laptop" name="laptop">--}}
{{--                </div>--}}
{{--                <div class="form-group">--}}
{{--                    <label for="cost_center_laptopreimbursement">Standaard kostenplaats laptopvergoeding:</label>--}}
{{--                    <input type="text" id="cost_center_laptopreimbursement" name="cost_center_laptopreimbursement">--}}
{{--                </div>--}}
{{--                <div class="form-group">--}}
{{--                    <label for="cost_center_bikereimbursement">Standaard kostenplaats fietsvergoeding:</label>--}}
{{--                    <input type="text" id="cost_center_bikereimbursement" name="cost_center_bikereimbursement">--}}
{{--                </div>--}}
{{--                <button type="submit" class="btn btn-success">Parameters opslaan</button>--}}
{{--            </form>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--@endsection--}}

@extends('layouts.template')

@section('title', 'Laptopvergoeding aanvragen')

@section('main')
    <div class="container">
        <h1>Parameters beheren</h1>
        <div class="row justify-content-center">
            <div class="col-md-8">
                @include('shared.alert')
                <div class="card">
                    <div class="card-body">
                        <form action="" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label for="bikereimbursement">Fietsvergoeding: </label>
                                <input type="number" min="0.00" step="0.01" name="bikereimbursement" id="bikereimbursement"
                                       class="form-control"
                                       placeholder="Bedrag per km"
                                       required>
                            </div>
                            <div class="form-group">
                                <label for="carreimbursement">Autovergoeding:</label>
                                <input type="number" min="0.00" step="0.01" id="carreimbursement" name="carreimbursement"
                                       class="form-control"
                                       placeholder="Bedrag per km"
                                       required>
                            </div>
                            <div class="form-group">
                                <label for="laptop">Maximum schijfgrootte laptop:</label>
                                <input type="number" min="0.00" step="0.01" id="laptop" name="laptop"
                                       class="form-control"
                                       placeholder="Bedrag"
                                       required>
                            </div>
                            <div class="form-group">
                                <label for="cost_center_laptopreimbursement">Standaard kostenplaats laptopvergoeding:</label>
                                <select class="form-control" name="cost_center_laptopreimbursement" id="cost_center_laptopreimbursement">
                                    <option value="%">Selecteer een kostenplaats</option>
                                    @foreach($cost_centers as $cost_center)
                                        <option  value="{{ $cost_center->id }}">{{ $cost_center->name }}</option>
                                    @endforeach
{{--                                    {{ (request()->programme_id ==  $programme->id ? 'selected' : '') }}--}}
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="cost_center_bikereimbursement">Standaard kostenplaats fietsvergoeding:</label>
                                <select class="form-control" name="cost_center_bikereimbursement" id="cost_center_bikereimbursement">
                                    <option value="%">Selecteer een kostenplaats</option>
                                    @foreach($cost_centers as $cost_center)
                                        <option  value="{{ $cost_center->id }}">{{ $cost_center->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Parameters opslaan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
