<x-modal data-backdrop="static" data-keyboard="false" size="modal-md">
    <x-slot name="title">
        Tambah Nilai KKM
    </x-slot>

    @method('POST')

    <div class="row">
        <div class="col-lg-12">
            <div class="form-group">
                <label for="kelas_id">Pilih Kelas <span class="text-danger">*</span></label>
                <select id="kelas_id" class="form-control select2" name="kelas_id" required>
                    <option value="">-- Pilih Kelas --</option>
                </select>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="form-group">
                <label for="mata_pelajaran_id">Pilih Mata Pelajaran <span class="text-danger">*</span></label>
                <select id="mata_pelajaran_id" class="form-control select2" name="mata_pelajaran_id" required>
                    <option value="">-- Pilih Mata Pelajaran --</option>
                </select>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="form-group">
                <label for="kkm">Nilai KKM <span class="text-danger">*</span></label>
                <input type="number" id="kkm" class="form-control" name="kkm"
                    placeholder="Masukkan nilai KKM" required>
            </div>
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

@push('scripts')
    <script>
        $(document).ready(function() {
            let kelasSelect = $('#kelas_id');
            let mataPelajaranSelect = $('#mata_pelajaran_id');

            // Disable mata pelajaran saat halaman dimuat
            mataPelajaranSelect.prop('disabled', true);

            // Load daftar kelas saat halaman dimuat
            $.ajax({
                url: '{{ route('kelas.get') }}',
                type: 'GET',
                success: function(data) {
                    kelasSelect.empty().append('<option value="">-- Pilih Kelas --</option>');
                    data.forEach(kelas => {
                        kelasSelect.append(
                            `<option value="${kelas.id}">${kelas.nama}</option>`);
                    });
                }
            });

            // Saat kelas dipilih, load mata pelajaran berdasarkan kelas
            kelasSelect.change(function() {
                let kelasId = $(this).val();

                // Disable mata pelajaran kembali saat mengganti kelas
                mataPelajaranSelect.prop('disabled', true).empty().append(
                    '<option value="">-- Pilih Mata Pelajaran --</option>'
                );

                if (kelasId) {
                    $.ajax({
                        url: `{{ route('matapelajaran.get', '') }}/${kelasId}`,
                        type: 'GET',
                        success: function(data) {
                            mataPelajaranSelect.empty().append(
                                '<option value="">-- Pilih Mata Pelajaran --</option>'
                            );
                            data.forEach(mapel => {
                                mataPelajaranSelect.append(
                                    `<option value="${mapel.id}">${mapel.nama}</option>`
                                );
                            });

                            // Enable kembali setelah data tersedia
                            mataPelajaranSelect.prop('disabled', false);
                        }
                    });
                }
            });
        });
    </script>
@endpush
