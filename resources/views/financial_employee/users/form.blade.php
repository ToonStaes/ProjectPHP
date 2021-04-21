    <div class="modal-body">
        <div class="form-group">
            <label for="voornaam">Voornaam</label>
            <input type="text" class="form-control " id="voornaam" name="voornaam" value="{{old('voornaam')}}">
            <span id="voornaam_error" class="form-error text-danger"></span>
        </div>
        <div class="form-group">
            <label for="achternaam">Achternaam</label>
            <input type="text" class="form-control" id="achternaam" name="achternaam" value="{{old('achternaam')}}">
            <span id="achternaam_error" class="form-error text-danger"></span>
        </div>
        <div class="form-group">
            <label for="adres">Adres</label>
            <input type="text" class="form-control" id="adres" name="adres" value="{{old('adres')}}">
            <span id="adres_error" class="form-error text-danger"></span>
        </div>
        <div class="form-group">
            <label for="postcode">Postcode</label>
            <input type="text" class="form-control" id="postcode" name="postcode" value="{{old('postcode')}}">
            <span id="postcode_error" class="form-error text-danger"></span>
        </div>
        <div class="form-group">
            <label for="woonplaats">Woonplaats</label>
            <input type="text" class="form-control" id="woonplaats" name="woonplaats" value="{{old('woonplaats')}}">
            <span id="woonplaats_error" class="form-error text-danger"></span>
        </div>
        <div class="form-group">
            <label for="iban">IBAN</label>
            <input type="text" class="form-control" id="iban" name="iban" value="{{old('iban')}}">
            <span id="iban_error" class="form-error text-danger"></span>
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="{{old('email')}}">
            <span id="email_error" class="form-error text-danger"></span>
        </div>
        <div class="form-group">
            <label for="telefoonnummer">Telefoonnummer</label>
            <input type="text" class="form-control" id="telefoonnummer" name="telefoonnummer" value="{{old('telefoonnummer')}}">
            <span id="telefoonnummer_error" class="form-error text-danger"></span>
        </div>
        <div class="form-group">
            <label for="aantal_km">Aantal km</label>
            <input type="text" class="form-control" id="aantal_km" name="aantal_km" value="{{old('aantal_km')}}">
            <span id="aantal_km_error" class="form-error text-danger"></span>
        </div>
        <div class="form-group">
            <label for="opleidingen">Opleiding toevoegen</label>
            <input type="text" class="form-control opleiding_zoek" name="opleiding_zoek" placeholder="Zoek een opleiding">
            <select multiple class="form-control opleidingen_select opleidingen" name="opleidingen"></select>
            <p class="text-right mb-0"><a href="#!" class="btn btn-primary btn-sm opleiding_toevoegen mt-2">Geselecteerde opleiding toevoegen</a></p>
            <p>Opleidingen:</p>
            <ul class="geselecteerde_opleidingen no-bullet">

            </ul>
        </div>
        <div class="form-check">
            <input type="checkbox" class="form-check-input" id="actief" name="actief">
            <label class="form-check-label" for="actief">Geactiveerd</label>
        </div>
        <div class="form-check">
            <input type="checkbox" class="form-check-input" id="kostenplaatsverantwoordelijke" name="kostenplaatsverantwoordelijke">
            <label class="form-check-label" for="kostenplaatsverantwoordelijke">Kostenplaatsverantwoordelijke</label>
        </div>
        <div class="form-check">
            <input type="checkbox" class="form-check-input" id="financieel_medewerker" name="financieel_medewerker">
            <label class="form-check-label" for="financieel_medewerker">Financieel medewerker</label>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuleren</button>
        <button type="submit" class="btn btn-primary">Opslaan</button>
    </div>
