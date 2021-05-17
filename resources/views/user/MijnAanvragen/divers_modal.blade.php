<div class="modal" id="modal-divers">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">modal-genre-title</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="reden">TEST id </label>
                    <input type="text" name="test" id="test"
                           class="form-control @error('reden') is-invalid @enderror"
                           placeholder="test"
                           required>
                    @error('reden')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
    </div>
</div>

