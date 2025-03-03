<x-modal data-backdrop="static" data-keyboard="false" size="modal-lg">
    <x-slot name="title">
        Tambah Data
    </x-slot>

    @method('POST')

    <div class="row">
        <div class="col-lg-12 col-md-12 col-12">
            <div class="form-group">
                <label for="tgl_publish">Tanggal Publish <span class="text-danger">*</span></label>
                <div class="input-group datepicker" id="tgl_publish" data-target-input="nearest">
                    <input type="text" name="tgl_publish" class="form-control datetimepicker-input"
                        data-target="#tgl_publish" data-toggle="datetimepicker" autocomplete="off" />
                    <div class="input-group-append" data-target="#tgl_publish" data-toggle="datetimepicker">
                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12 col-md-12 col-12">
            <div class="form-group">
                <label for="kategori_id">Kategori <span class="text-danger">*</span></label>
                <select id="kategori_id" class="form-control" name="kategori_id" required>
                    <option value="" disabled selected>Pilih Kategori</option>
                    @foreach ($kategori as $item)
                        <option value="{{ $item->id }}">{{ $item->nama }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12 col-md-12 col-12">
            <div class="form-group">
                <label for="judul">Judul <span class="text-danger">*</span></label>
                <input id="judul" class="form-control" type="text" name="judul" autocomplete="off" required>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12 col-md-12 col-12">
            <div class="form-group">
                <label for="content">Konten</label>
                <textarea id="content" class="form-control summernote" name="content" rows="3"></textarea>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12 col-md-12 col-12">
            <div class="form-group">
                <label for="image">Gambar</label>
                <input id="image" class="form-control" type="file" name="image" accept="image/*">
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
