<x-modal data-backdrop="static" data-keyboard="false" size="modal-md">
    <x-slot name="title">
        Tambah
    </x-slot>

    @method('POST')

    <input type="hidden" name="pembelajaran_id" id="pembelajaran_id">
    <div class="row">
        <div class="col-sm-4">
            <div class="form-group">
                <label>Penilaian Harian (PH)</label>
                <input type="number" class="form-control" name="bobot_ph" min="0">
            </div>
        </div>
        <div class="col-sm-4">
            <div class="form-group">
                <label>Penilaian Tengah Semester (PTS)</label>
                <input type="number" class="form-control" name="bobot_pts" min="0">
            </div>
        </div>
        <div class="col-sm-4">
            <div class="form-group">
                <label>Penilaian Akhir Semester (PAS)</label>
                <input type="number" class="form-control" name="bobot_pas" min="0">
            </div>
        </div>
    </div>

    <x-slot name="footer">
        <button type="button" onclick="submitForm(this.form)" class="btn btn-sm btn-outline-primary" id="submitBtn">
            <span id="spinner-border" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
            <i class="fas fa-save mr-1"></i>
            Simpan</button>
        <button type="button" data-dismiss="modal" class="btn btn-sm btn-outline-danger">
            <i class="fas fa-times"></i>
            Close
        </button>
    </x-slot>
</x-modal>
