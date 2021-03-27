@extends('layouts.template')

@section('title', 'Parameters beheren')

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
                                @if($bike_reimbursement->isEmpty())
                                    <input type="number" min="0.00" step="0.01" name="bikereimbursement" id="bikereimbursement"
                                           class="form-control"
                                           placeholder="Fietsvergoeding per km"
                                           required>
                                @else
                                    <input type="number" min="0.00" step="0.01" name="bikereimbursement" id="bikereimbursement"
                                           class="form-control"
                                           placeholder="{{$bike_reimbursement[0]->amount_per_km}}"
                                           value="{{ old('bike_reimbursement', $bike_reimbursement[0]->amount_per_km) }}"
                                           required>
                                @endif
                            </div>
                            <div class="form-group">
                                <label for="carreimbursement">Autovergoeding:</label>
                                @if($car_reimbursement->isEmpty())
                                    <input type="number" min="0.00" step="0.01" id="carreimbursement" name="carreimbursement"
                                           class="form-control"
                                           placeholder="Autovergoeding per km"
                                           required>
                                @else
                                    <input type="number" min="0.00" step="0.01" id="carreimbursement" name="carreimbursement"
                                           class="form-control"
                                           placeholder="{{$car_reimbursement[0]->amount_per_km}}"
                                           value="{{ old('car_reimbursement', $car_reimbursement[0]->amount_per_km) }}"
                                           required>
                                @endif

                            </div>
                            <div class="form-group">
                                <label for="laptop">Maximum schijfgrootte laptop:</label>
                                @if($laptop->isEmpty())
                                    <input type="number" min="0.00" step="0.01" id="laptop" name="laptop"
                                           class="form-control"
                                           placeholder="Maximum schijfgrootte voor laptopvergoeding"
                                           required>
                                @else
                                    <input type="number" min="0.00" step="0.01" id="laptop" name="laptop"
                                           class="form-control"
                                           placeholder="{{$laptop[0]->max_reimbursement_laptop}}"
                                           value="{{ old('laptop', $laptop[0]->max_reimbursement_laptop) }}"
                                           required>
                                @endif

                            </div>
                            <div class="form-group">
                                <label for="cost_center_laptopreimbursement">Standaard kostenplaats laptopvergoeding:</label>
                                <select class="form-control" name="cost_center_laptopreimbursement" id="cost_center_laptopreimbursement">
                                    <option value="%">Selecteer een kostenplaats</option>
                                    @foreach($cost_centers as $cost_center)
                                        @if($cost_center_laptopreimbursement->isEmpty())
                                            <option  value="{{ $cost_center->id }}" >{{ $cost_center->name }}</option>
                                        @else
                                            <option  value="{{ $cost_center->id }}" {{ $cost_center_laptopreimbursement[0]->standard_Cost_center_id ==  $cost_center->id ? 'selected' : '' }}>{{ $cost_center->name }}</option>
                                        @endif
                                    @endforeach

                                </select>
                            </div>

                            <div class="form-group">
                                <label for="cost_center_bikereimbursement">Standaard kostenplaats fietsvergoeding:</label>
                                <select class="form-control" name="cost_center_bikereimbursement" id="cost_center_bikereimbursement">
                                    <option value="%">Selecteer een kostenplaats</option>
                                    @foreach($cost_centers as $cost_center)
                                        @if($cost_center_bikereimbursement->isEmpty())
                                            <p>Test</p>
                                            <option  value="{{ $cost_center->id }}">{{ $cost_center->name }}</option>
                                        @else
                                            <option  value="{{ $cost_center->id }}" {{ $cost_center_bikereimbursement[0]->standard_Cost_center_id ==  $cost_center->id ? 'selected' : '' }}>{{ $cost_center->name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Parameters opslaan</button>
                            <p></p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
