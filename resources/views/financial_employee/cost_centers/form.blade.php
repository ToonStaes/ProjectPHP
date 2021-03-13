<form id="cost_center_form">
    <div class="row flex-row-reverse">
        <div class="col col-12 col-md-6">
            <div class="row">
                <div class="col col-12"><label for="responsible_list">Verantwoordelijke<sup>*</sup></label></div>
                <div class="col col-12">
                    <select name="responsible_list" required id="responsible_list">
                        @foreach($users as $user)
                            <option {{($loop->first) ? "selected" : ""}} value="{{$user->id}}">{{$user->first_name.' '.$user->last_name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col col-12"><label for="descr_input">Omschrijving</label></div>
                <div class="col col-12"><textarea name="omschrijving" id="descr_input" cols="30" rows="5"></textarea></div>
            </div>
            <div class="row">
                <label for="active_input">Actief</label>
                <input type="checkbox" id="active_input">
            </div>
        </div>
        <div class="col col-12 col-md-6">
            <div class="row">
                <div class="col col-12"><label for="programmes_list">Unit<sup>*</sup></label></div>
                <div class="col col-12">
                    <select name="programmes_list" required id="programmes_list">
                        @foreach($programmes as $programme)
                            <option {{($loop->first) ? "selected" : ""}} value="{{$programme->id}}">{{$programme->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col col-12"><label for="cost_center_input">Kostenplaats<sup>*</sup></label></div>
                <div class="col col-12"><input list="cost_centers_list" required id="cost_center_input">
                    <datalist id="cost_centers_list">
                        @foreach($cost_centers as $cost_center)
                            <option data-id="{{$cost_center->id}}">{{$cost_center->name}}</option>
                        @endforeach
                    </datalist>
                </div>
            </div>
            <div class="row">
                <div class="col col-12"><label for="budget_input">Budget</label></div>
                <div class="col col-12"><input type="number" step="0.01" min="0" oninput="this.value = (this.value < 0) ? 0 : this.value"
                                               id="budget_input"></div>
            </div>
        </div>
    </div>
</form>
<button class="btn btn-primary" id="cost_center_submit">Kostenplaats opslaan</button>
