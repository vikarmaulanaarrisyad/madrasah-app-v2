<x-modal data-backdrop="static" data-keyboard="false" size="modal-md">
    <x-slot name="title">
        Tambah
    </x-slot>

    @method('POST')

    <form action="" method="GET">
        @csrf
        <div class="form-group row">
            <label for="pembelajaran_id" class="col-sm-3 col-form-label">Mata Pelajaran</label>
            <div class="col-sm-9">
                <select class="form-control" name="pembelajaran_id" style="width: 100%;" aria-readonly="true">
                    <option value="{{ $penilaian->id }}" selected>{{ $penilaian->mapel->nama_mapel }}
                        {{ $penilaian->kelas->nama_kelas }}</option>
                </select>
            </div>
        </div>
        <div class="form-group row">
            <label for="kode_penilaian" class="col-sm-3 col-form-label">Kode Penilaian</label>
            <div class="col-sm-9">
                <select class="form-control select2" name="kode_penilaian" style="width: 100%;" required
                    onchange="this.form.submit();">
                    <option value="">-- Pilih Penilaian --</option>
                    @foreach ($penilaian->data_rencana_nilai as $kode_penilaian)
                        <option value="{{ $kode_penilaian->kode_penilaian }}">{{ $kode_penilaian->kode_penilaian }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    </form>

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
