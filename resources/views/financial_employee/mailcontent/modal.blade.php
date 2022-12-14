<div class="modal" id="modal-mailcontent">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">modal-mailcontent-title</h5>
                <button type="button" class="close">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="" method="post">
                    @method('')
                    @csrf
                    <div class="form-group">
                        <label for="mailcontent">Mailtekst</label>
                        <textarea name="mailcontent" id="mailcontent"
                                  class="form-control"
                                  placeholder="content"
                                  minlength="10"
                                  required
                                  value="" rows="25">
                        </textarea>
                        <input type="hidden" id="mailtype" name="mailtype">
                    </div>
                    <button type="submit" class="btn btn-primary">Sla mailtekst op</button>
                    <button type="button" class="btn btn-secondary annuleren">Annuleren</button>
                </form>
            </div>
        </div>
    </div>
</div>
