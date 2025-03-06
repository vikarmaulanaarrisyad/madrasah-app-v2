<x-modal data-backdrop="static" data-keyboard="false" size="modal-md">
    <x-slot name="title">
        Tambah
    </x-slot>

    @method('POST')

    <div class="row">
        <div class="col-lg-12 col-md-12 col-12">
            <div class="form-group">
                <label for="nama">Nama <span class="text-danger">*</span></label>
                <input id="nama" class="form-control" type="text" name="nama" autocomplete="off">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-12">
            <div class="form-group">
                <label for="short">Deskripsi Singkat <span class="text-danger">*</span></label>
                <p class="text-xs text-red">Jumlah karakter dalam teks yang diberikan adalah 150 karakter, termasuk
                    spasi dan tanda baca</p>
                <textarea name="short" id="short" cols="20" rows="5" class="form-control" minlength="150"></textarea>

            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-12">
            <div class="form-group">
                <label for="gambar">Gambar</label>
                <input id="gambar" class="form-control" type="file" name="gambar" accept="gambar/*">
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
