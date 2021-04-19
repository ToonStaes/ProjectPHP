@extends('layouts.template')

@section('title', 'Parameters beheren')

@section('main')
    <div class="container">
        <h1>Parameters beheren <i class="fas fa-info-circle" data-toggle="tooltip" data-placement="right" title="Op deze pagina kan u de parameters wijzigen."></i></h1>
        <div class="row justify-content-center">
            <div class="col-md-8">
                @include('shared.alert')
                <div class="card">
                    <div class="card-body">
                        <form action="/parameters" method="post" enctype="multipart/form-data" novalidate>
                            @csrf
                            <div class="form-group">
                                <label for="bikereimbursement">Fietsvergoeding: </label>
                                @if($bike_reimbursement->isEmpty())
                                    <input type="number" min="0.00" step="0.01" name="bikereimbursement" id="bikereimbursement"
                                           class="form-control {{ $errors->first('bikereimbursement') ? 'is-invalid' : '' }}"
                                           placeholder="Fietsvergoeding per km"
                                           >
                                @else
                                    <input type="number" min="0.00" step="0.01" name="bikereimbursement" id="bikereimbursement"
                                           class="form-control {{ $errors->first('bikereimbursement') ? 'is-invalid' : '' }}"
                                           placeholder="{{$bike_reimbursement[0]->amount_per_km}}"
                                           value="{{ old('bike_reimbursement', $bike_reimbursement[0]->amount_per_km) }}"
                                           >
                                @endif
                                <div class="invalid-feedback">{{ $errors->first('bikereimbursement') }}</div>
                            </div>
                            <div class="form-group">
                                <label for="carreimbursement">Autovergoeding:</label>
                                @if($car_reimbursement->isEmpty())
                                    <input type="number" min="0.00" step="0.01" id="carreimbursement" name="carreimbursement"
                                           class="form-control {{ $errors->first('carreimbursement') ? 'is-invalid' : '' }}"
                                           placeholder="Autovergoeding per km"
                                           >
                                @else
                                    <input type="number" min="0.00" step="0.01" id="carreimbursement" name="carreimbursement"
                                           class="form-control {{ $errors->first('carreimbursement') ? 'is-invalid' : '' }}"
                                           placeholder="{{$car_reimbursement[0]->amount_per_km}}"
                                           value="{{ old('car_reimbursement', $car_reimbursement[0]->amount_per_km) }}"
                                           >
                                @endif
                                <div class="invalid-feedback">{{ $errors->first('carreimbursement') }}</div>
                            </div>
                            <div class="form-group">
                                <label for="laptop">Maximum schijfgrootte laptopvergoeding:</label>
                                @if($laptop->isEmpty())
                                    <input type="number" min="0.00" step="0.01" id="laptop" name="laptop"
                                           class="form-control {{ $errors->first('laptop') ? 'is-invalid' : '' }}"
                                           placeholder="Maximum schijfgrootte voor laptopvergoeding"
                                           >
                                @else
                                    <input type="number" min="0.00" step="0.01" id="laptop" name="laptop"
                                           class="form-control {{ $errors->first('laptop') ? 'is-invalid' : '' }}"
                                           placeholder="{{$laptop[0]->max_reimbursement_laptop}}"
                                           value="{{ old('laptop', $laptop[0]->max_reimbursement_laptop) }}"
                                           >
                                @endif
                                <div class="invalid-feedback">{{ $errors->first('laptop') }}</div>
                            </div>
                            <div class="form-group">
                                <label for="cost_center_laptopreimbursement">Standaard kostenplaats laptopvergoeding:</label>
                                <select data-width="100%" class="search-dropdown select2-container form-control {{ $errors->first('cost_center_laptopreimbursement') ? 'is-invalid' : '' }}" name="cost_center_laptopreimbursement" id="cost_center_laptopreimbursement">
                                    <option value="">Selecteer een kostenplaats</option>
                                    @foreach($cost_centers as $cost_center)
                                        @if($cost_center_laptopreimbursement->isEmpty())
                                            <option  value="{{ $cost_center->id }}" >{{ $cost_center->name }}</option>
                                        @else
                                            <option  value="{{ $cost_center->id }}" {{ $cost_center_laptopreimbursement[0]->standard_Cost_center_id ==  $cost_center->id ? 'selected' : '' }}>{{ $cost_center->name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">{{ $errors->first('cost_center_laptopreimbursement') }}</div>
                            </div>

                            <div class="form-group">
                                <label for="cost_center_bikereimbursement">Standaard kostenplaats fietsvergoeding:</label>
                                <select data-width="100%" class="search-dropdown form-control  {{ $errors->first('cost_center_bikereimbursement') ? 'is-invalid' : '' }}" name="cost_center_bikereimbursement" id="cost_center_bikereimbursement">
                                    <option value="">Selecteer een kostenplaats</option>
                                    @foreach($cost_centers as $cost_center)
                                        @if($cost_center_bikereimbursement->isEmpty())
                                            <option  value="{{ $cost_center->id }}">{{ $cost_center->name }}</option>
                                        @else
                                            <option  value="{{ $cost_center->id }}" {{ $cost_center_bikereimbursement[0]->standard_Cost_center_id ==  $cost_center->id ? 'selected' : '' }}>{{ $cost_center->name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">{{ $errors->first('cost_center_bikereimbursement') }}</div>
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

@section('script_after')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function(){

            // Initialize select2
            $(".search-dropdown").select2();

        });
    </script>
@endsection
