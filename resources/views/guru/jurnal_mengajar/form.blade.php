<x-modal data-backdrop="static" data-keyboard="false" size="modal-md">
    <x-slot name="title">
        Tambah Data
    </x-slot>

    @method('POST')
    <div class="row">
        <div class="col-lg-12">
            <div class="form-group">
                <label for="tanggal">Tanggal <span class="text-danger">*</span></label>
                <div class="input-group datepicker" id="tanggal" data-target-input="nearest">
                    <input type="text" name="tanggal" class="form-control datetimepicker-input"
                        data-target="#tanggal" data-toggle="datetimepicker" autocomplete="off"
                        value="{{ date('Y-m-d') }}" />
                    <div class="input-group-append" data-target="#tanggal" data-toggle="datetimepicker">
                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <input type="hidden" id="mata_pelajaran_id" name="mata_pelajaran_id">
    <input type="hidden" id="jam_ke_hidden" name="jam_ke">

    <div class="row">
        <div class="col-lg-12">
            <div class="form-group">
                <label for="mata_pelajaran">Mata Pelajaran</label>
                <input type="text" id="mata_pelajaran" class="form-control" name="mata_pelajaran" disabled>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12 col-md-12 col-12">
            <div class="form-group">
                <label for="jam_ke">Jam Ke</label>
                <input type="number" id="jam_ke" class="form-control" name="jam_ke" min="1" max="10"
                    disabled>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-12">
            <div class="form-group">
                <label for="materi">Materi</label>
                <textarea id="materi" class="form-control" name="materi" rows="3"></textarea>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12 col-md-12 col-12">
            <div class="form-group">
                <label for="keterangan">Keterangan</label>
                <textarea id="keterangan" class="form-control" name="keterangan" rows="3"></textarea>
            </div>
        </div>
    </div>

    <x-slot name="footer">
        <button type="button" onclick="submitForm(this.form)" class="btn btn-sm btn-outline-primary" id="submitBtn">
            <span id="spinner-border" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
            <i class="fas fa-save mr-1"></i>
            Simpan
        </button>
        <button type="button" data-dismiss="modal" class="btn btn-sm btn-outline-danger">
            <i class="fas fa-times"></i>
            Close
        </button>
    </x-slot>
</x-modal>
