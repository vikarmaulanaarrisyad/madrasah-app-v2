<x-modal data-backdrop="static" data-keyboard="false" size="modal-lg">
    <x-slot name="title">
        Tambah Nilai KKM
    </x-slot>

    @method('POST')

    <div class="form-group row">
        <label for="mapel" class="col-sm-3 col-form-label">Mata Pelajaran</label>
        <div class="col-sm-9">
            <input type="text" class="form-control" id="mapel" value="" readonly>
        </div>
    </div>
    <div class="form-group row">
        <label for="kode_kd" class="col-sm-3 col-form-label">Kode</label>
        <div class="col-sm-9">
            <input type="text" class="form-control" id="kode_kd" value="" readonly>
        </div>
    </div>
    <div class="form-group row">
        <label for="kompetensi_dasar" class="col-sm-3 col-form-label">Kompetensi Dasar</label>
        <div class="col-sm-9">
            <textarea name="kompetensi_dasar" class="form-control" rows="2"></textarea>
        </div>
    </div>
    <div class="form-group row">
        <label for="ringkasan_kompetensi" class="col-sm-3 col-form-label">Ringkasan Kompetensi</label>
        <div class="col-sm-9">
            <textarea name="ringkasan_kompetensi" class="form-control" rows="2"></textarea>
        </div>
    </div>

    <x-slot name="footer">
        <button type="button" onclick="submitForm(this.form)" class="btn btn-sm btn-outline-primary" id="submitBtn">
            <i class="fas fa-save mr-1"></i> Simpan
        </button>
        <button type="button" data-dismiss="modal" class="btn btn-sm btn-outline-danger">
            <i class="fas fa-times"></i> Close
        </button>
    </x-slot>
</x-modal>
