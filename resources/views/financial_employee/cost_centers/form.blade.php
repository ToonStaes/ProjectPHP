<form id="cost_center_form">
    <div class="row">
        <div class="col col-12 col-md-6">
            <div class="row">
                <div class="col col-12"><label for="programmes_list">Opleiding / unit<sup>*</sup></label></div>
                <div class="col col-12">
                    <select class="search-dropdown" name="programmes_list" required id="programmes_list">
                        @foreach($programmes as $programme)
                            <option value="{{$programme->id}}">{{$programme->name}}</option>
                        @endforeach
                    </select>
                    <div class="invalid-feedback d-block" id="invalid-programme"></div>
                </div>
            </div>
            <div class="row">
                <div class="col col-12"><label for="cost_center_input">Kostenplaats<sup>*</sup></label></div>
                <div class="col col-12">
                    <input list="cost_centers_list" required id="cost_center_input">
                    <datalist id="cost_centers_list">
                        @foreach($cost_centers as $cost_center)
                            <option data-id="{{$cost_center->id}}">{{$cost_center->name}}</option>
                        @endforeach
                    </datalist>
                    <div class="invalid-feedback d-block" id="invalid-cost_center"></div>
                </div>
            </div>
            <div class="row">
                <div class="col col-12"><label for="budget_input">Budget</label></div>
                <div class="col col-12"><input class="form-control-range" type="number" step="0.01" min="0" oninput="this.value = (this.value < 0) ? 0 : this.value"
                                               id="budget_input"></div>
                <div class="invalid-feedback d-block" id="invalid-budget"></div>
            </div>
        </div>
        <div class="col col-12 col-md-6">
            <div class="row">
                <div class="col col-12"><label for="responsible_list">Verantwoordelijke<sup>*</sup></label></div>
                <div class="col col-12">
                    <select class="search-dropdown" name="responsible_list" required id="responsible_list">
                        @foreach($users as $user)
                            <option value="{{$user->id}}">{{$user->first_name.' '.$user->last_name}}</option>
                        @endforeach
                    </select>
                    <div class="invalid-feedback d-block" id="invalid-user"></div>
                </div>
            </div>
            <div class="row">
                <div class="col col-12"><label for="descr_input">Omschrijving</label></div>
                <div class="col col-12"><textarea class="form-control-plaintext border" name="omschrijving" id="descr_input" cols="30" rows="5"></textarea></div>
                <div class="invalid-feedback d-block" id="invalid-description"></div>
            </div>
            <div class="row">
                <div class="form-group">
                    <div class="form-check">
                        <input class="form-check-input" checked type="checkbox" id="active_input">
                        <label class="form-check-label" for="active_input">Actief</label>
                    </div>
                </div>
                <div class="invalid-feedback d-block" id="invalid-active"></div>
            </div>
        </div>
    </div>
    <button type="submit" class="btn btn-primary" id="cost_center_submit">Kostenplaats opslaan</button>
    <button type="button" class="btn btn-secondary modal-close">Annuleren</button>
</form>
