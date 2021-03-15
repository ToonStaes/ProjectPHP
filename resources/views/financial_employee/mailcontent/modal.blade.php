<div class="modal" id="modal-mailcontent">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">modal-mailcontent-title</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="" method="put">
                    @method('')
                    @csrf
                    <div class="form-group">
                        <label for="mailcontent">Mailcontent</label>
                        <input type="text" name="mailcontent" id="mailcontent"
                               class="form-control"
                               placeholder="content"
                               minlength="10"
                               required
                               value="">
                    </div>
                    <button type="submit" class="btn btn-success">Save mailcontent</button>
                </form>
            </div>
        </div>
    </div>
</div>
