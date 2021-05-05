<div class="modal" id="modal-laptop">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Pas laptopaanvraag aan</h5>
                <button type="button" class="close">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="" method="post" enctype="multipart/form-data">
                    @method('')
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

                    <div class="form-group" id="oldfile" class="mb-3">
                        <a class="btn btn-outline-dark" id="filepath" href="" download><nobr>
                            </nobr></a>

                            <a href="#!" class="btn" id="delete">&times;</a>
                    </div>
                   <div class="form-group d-none" id="uploadFile">
                        <label for="bestand">Uploaden bewijsstuk</label>
                        <input type="file" name="UploadBestand" id="bestand"
                               class="form-control-file @error('bestand') is-invalid @enderror">
                        @error('bestand')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-success">Aanvraag aanpassen
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
