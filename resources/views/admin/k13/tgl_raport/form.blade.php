<x-modal data-backdrop="static" data-keyboard="false" size="modal-md">
    <x-slot name="title">
        Tambah
    </x-slot>

    @method('POST')

    <div class="row">
        <div class="col-lg-12 col-md-12 col-12">
            <div class="form-group">
                <label for="tapel_id">Tahun Pelajaran<span class="text-danger">*</span></label>
                <input id="tapel_id" class="form-control" type="text" name="tapel_id" autocomplete="off"
                    value="{{ $tapel->nama }} {{ $tapel->semester->nama }}" disabled readonly>
                <input type="hidden" name="tahun_pelajaran_id" value="{{ $tapel->id }}">
            </div>
        </div>
    </div>

    <div class="form-group row">
        <label for="tempat_penerbitan" class="col-sm-3 col-form-label">Tempat Penerbitan</label>
        <div class="col-sm-9">
            <input type="text" class="form-control" name="tempat_penerbitan">
        </div>
    </div>
    <div class="form-group row">
        <label for="tanggal_pembagian" class="col-sm-3 col-form-label">Tanggal Pembagian</label>
        <div class="col-sm-9">
            <input type="date" class="form-control" name="tanggal_pembagian">
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
