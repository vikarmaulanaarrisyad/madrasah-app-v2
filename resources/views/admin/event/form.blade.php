<x-modal data-backdrop="static" data-keyboard="false" size="modal-lg">
    <x-slot name="title">
        Tambah Data
    </x-slot>

    @method('POST')

    <div class="row">
        <div class="col-lg-4">
            <div class="form-group">
                <label for="tanggal">Tanggal<span class="text-danger">*</span></label>
                <div class="input-group datepicker" id="tanggal" data-target-input="nearest">
                    <input type="text" name="tanggal" class="form-control datetimepicker-input"
                        data-target="#tanggal" data-toggle="datetimepicker" autocomplete="off" />
                    <div class="input-group-append" data-target="#tanggal" data-toggle="datetimepicker">
                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="form-group">
                <label for="waktu_mulai">Waktu Mulai<span class="text-danger">*</span></label>
                <div class="input-group picker" id="waktu_mulai" data-target-input="nearest">
                    <input type="text" name="waktu_mulai" class="form-control datetimepicker-input"
                        data-target="#waktu_mulai" data-toggle="datetimepicker" autocomplete="off" />
                    <div class="input-group-append" data-target="#waktu_mulai" data-toggle="datetimepicker">
                        <div class="input-group-text"><i class="fa fa-clock"></i></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="form-group">
                <label for="waktu_selesai">Waktu Selesai<span class="text-danger">*</span></label>
                <div class="input-group picker" id="waktu_selesai" data-target-input="nearest">
                    <input type="text" name="waktu_selesai" class="form-control datetimepicker-input"
                        data-target="#waktu_selesai" data-toggle="datetimepicker" autocomplete="off" />
                    <div class="input-group-append" data-target="#waktu_selesai" data-toggle="datetimepicker">
                        <div class="input-group-text"><i class="fa fa-clock"></i></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="form-group">
                <label for="judul">Judul <span class="text-danger">*</span></label>
                <input id="judul" class="form-control" type="text" name="judul" autocomplete="off" required>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="form-group">
                <label for="lokasi">Lokasi / Tempat <span class="text-danger">*</span></label>
                <input id="lokasi" class="form-control" type="text" name="lokasi" autocomplete="off" required>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="form-group">
                <label for="deskripsi">Deskripsi</label>
                <textarea id="deskripsi" class="form-control summernote" name="deskripsi" rows="3"></textarea>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="form-group">
                <label for="image">Gambar</label>
                <input id="image" class="form-control" type="file" name="image" accept="image/*">
            </div>
        </div>
    </div>

    <x-slot name="footer">
        <button type="button" onclick="submitForm(this.form)" class="btn btn-sm btn-outline-primary" id="submitBtn">
            <span id="spinner-border" class="spinner-border spinner-border-sm" role="status"
                aria-hidden="true"></span>
            <i class="fas fa-save mr-1"></i>
            Simpan
        </button>
        <button type="button" data-dismiss="modal" class="btn btn-sm btn-outline-danger">
            <i class="fas fa-times"></i>
            Close
        </button>
    </x-slot>
</x-modal>
