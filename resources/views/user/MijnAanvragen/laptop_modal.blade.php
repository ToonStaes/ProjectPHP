<div class="modal" id="modal-laptop">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Pas uw laptopaanvraag aan</h5>
                <button type="button" class="close">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="" method="post" enctype="multipart/form-data">
                    @method('')
                    @csrf
                    <div class="form-group">
                        <label for="bedrag">Aankoopbedrag in euro</label>
                        <input type="number" name="bedrag" id="bedrag"
                               class="form-control"
                               placeholder="123">
                        <span id="bedrag_error" class="form-error text-danger"></span>
                    </div>
                    <div class="form-group">
                        <label for="reden">Verklaring aanvraag</label>
                        <input type="text" name="reden" id="reden"
                               class="form-control"
                               placeholder="Reden">
                        <span id="reden_error" class="form-error text-danger"></span>
                    </div>
                    <div class="form-group">
                        <label for="datum">Datum van aankoop</label>
                        <input type="date" name="datum" id="datum"
                               class="form-control"
                               placeholder="">
                        <span id="datum_error" class="form-error text-danger"></span>
                    </div>

                    <div class="form-group" id="oldfile" class="mb-3">
                        <a class="btn btn-outline-dark" id="filepath" href="" download>
                            <nobr>
                            </nobr>
                        </a>

                        <a href="#!" class="btn" id="delete">&times;</a>
                    </div>
                    <div class="form-group d-none" id="uploadFile">
                        <label for="bestand">Uploaden bewijsstuk</label>
                        <input type="file" name="UploadBestand" id="bestand"
                               class="form-control-file">
                        <span id="UploadBestand_error" class="form-error text-danger"></span>
                    </div>
                    <button type="submit" class="btn btn-primary">Aanvraag aanpassen
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
