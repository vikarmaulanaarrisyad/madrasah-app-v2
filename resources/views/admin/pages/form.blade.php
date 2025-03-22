<x-modal data-backdrop="static" data-keyboard="false" size="modal-lg">
    <x-slot name="title">
        Tambah Data
    </x-slot>

    @method('POST')

    <div class="row">
        <div class="col-lg-12 col-md-12 col-12">
            <div class="form-group">
                <label for="menu_id">Menu <span class="text-danger">*</span></label>
                <select id="menu_id" class="form-control" name="menu_id" required>
                    <option value="" disabled selected>Pilih Menu</option>
                    @foreach ($menus as $item)
                        <option value="{{ $item->id }}">{{ $item->menu_title }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12 col-md-12 col-12">
            <label for="menu_parent_id">Sub Menu</label>
            <select id="menu_parent_id" class="form-control" name="menu_parent_id" disabled>
                <option value="" disabled selected>Pilih Sub Menu</option>
            </select>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12 col-md-12 col-12">
            <div class="form-group">
                <label for="title">Judul <span class="text-danger">*</span></label>
                <input id="title" class="form-control" type="text" name="title" autocomplete="off" required>
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
                <label for="gambar">Gambar</label>
                <input id="gambar" class="form-control" type="file" name="gambar">
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

@push('scripts')
    <script>
        $(document).ready(function() {
            $("#menu_id").change(function() {
                let menuId = $(this).val();
                let subMenuSelect = $("#menu_parent_id");

                // Nonaktifkan dropdown submenu saat memuat data
                subMenuSelect.prop("disabled", true);

                // Tampilkan Swal Loading
                Swal.fire({
                    title: 'Loading...',
                    html: 'Mengambil data sub menu',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                // Request AJAX untuk mendapatkan submenu
                $.ajax({
                    url: "/admin/get-submenu/" + menuId, // Pastikan route ini ada di Laravel
                    type: "GET",
                    dataType: "json",
                    success: function(response) {
                        Swal.close(); // Tutup Swal Loading setelah data didapat

                        // Kosongkan dulu select sub menu
                        subMenuSelect.empty();
                        subMenuSelect.append(
                            '<option value="" disabled selected>Pilih Sub Menu</option>');

                        if (response.length > 0) {
                            // Tambahkan opsi submenu yang diterima dari AJAX
                            $.each(response, function(key, item) {
                                subMenuSelect.append('<option value="' + item.id +
                                    '">' + item.menu_title + '</option>');
                            });

                            // Aktifkan dropdown submenu jika ada data
                            subMenuSelect.prop("disabled", false);
                        }
                    },
                    error: function() {
                        Swal.fire("Error", "Gagal mengambil data sub menu", "error");
                    }
                });
            });
        });
    </script>
@endpush
