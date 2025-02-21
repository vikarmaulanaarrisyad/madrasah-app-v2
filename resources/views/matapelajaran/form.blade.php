<x-modal data-backdrop="static" data-keyboard="false" size="modal-md">
    <x-slot name="title">
        Tambah
    </x-slot>

    @method('POST')

    <div class="row">
        <div class="col-lg-12 col-md-12 col-12">
            <div class="form-group">
                <label for="kode">Kode Mapel <span class="text-danger">*</span></label>
                <input id="kode" class="form-control" type="text" name="kode" autocomplete="off">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-12">
            <div class="form-group">
                <label for="nama">Nama Mapel <span class="text-danger">*</span></label>
                <input id="nama" class="form-control" type="text" name="nama" autocomplete="off">
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12 col-md-12 col-12">
            <div class="form-group">
                <label for="kelompok">Kelompok <span class="text-danger">*</span></label>
                <select name="kelompok" id="kelompok" class="form-control">
                    <option disabled selected>Pilih salah satu</option>
                    <option value="A">Kelompook A</option>
                    <option value="B">Kelompook B</option>
                    <option value="C">Kelompook C</option>
                </select>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12 col-md-12 col-12">
            <div class="form-group">
                <label for="kurikulum_id">Kurikulum <span class="text-danger">*</span></label>
                <select name="kurikulum_id" id="kurikulum_id" class="form-control">
                    <option disabled selected>Pilih salah satu</option>
                    @foreach ($kurikulums as $item)
                        <option value="{{ $item->id }}">{{ $item->nama }}</option>
                    @endforeach
                </select>
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
