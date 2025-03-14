<!-- Modal Tambah Presensi -->
<x-modal id="modalPresensiStatus" data-backdrop="static" data-keyboard="false" size="modal-md">
    <x-slot name="title">Tambah Presensi</x-slot>

    @method('POST')
    <form id="presensiForm">
        @csrf
        <input type="hidden" name="siswa_id[]" id="siswa_id">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-12">
                <div class="form-group">
                    <label for="presensi_status">Pilih Status<span class="text-danger">*</span></label>
                    <select name="presensi_status" id="presensi_status" class="form-control" required>
                        <option value="">-- Pilih Status --</option>
                        <option value="H">Hadir</option>
                        <option value="A">Alpa</option>
                        <option value="I">Izin</option>
                        <option value="S">Sakit</option>
                    </select>
                </div>
            </div>
        </div>

        <x-slot name="footer">
            <button type="button" onclick="submitPresensi()" class="btn btn-sm btn-outline-primary" id="submitBtn">
                <span id="spinner-border" class="spinner-border spinner-border-sm d-none" role="status"
                    aria-hidden="true"></span>
                <i class="fas fa-save mr-1"></i> Simpan
            </button>
            <button type="button" data-dismiss="modal" class="btn btn-sm btn-outline-danger">
                <i class="fas fa-times"></i> Close
            </button>
        </x-slot>
    </form>
</x-modal>

@push('scripts')
    <script>
        function submitPresensi() {
            let siswa_id = $('#siswa_id').val().split(',');
            let presensi_status = $('#presensi_status').val();

            if (siswa_id.length === 0 || siswa_id[0] === "") {
                Swal.fire({
                    icon: 'warning',
                    title: 'Oops!',
                    text: 'Tidak ada siswa yang dipilih!',
                });
                return;
            }

            if (!presensi_status) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Oops!',
                    text: 'Silakan pilih status presensi!',
                });
                return;
            }

            Swal.fire({
                title: 'Menyimpan data...',
                html: 'Mohon tunggu...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            $('#submitBtn').prop('disabled', true);
            $('#spinner-border').removeClass('d-none');

            $.ajax({
                url: '{{ route('presensissiswa.simpanPresensiAll') }}',
                type: 'POST',
                data: {
                    siswa_id: siswa_id, // Harus dalam bentuk array
                    presensi_status: presensi_status
                },
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: 'Presensi berhasil disimpan!',
                    }).then(() => {
                        $('#modalPresensiStatus').modal('hide');
                        $('.table').DataTable().ajax.reload(null, false);
                        $('.siswa-checkbox').prop('checked', false);
                        $('#pilihSemua').prop('checked', false);
                    });
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: 'Terjadi kesalahan saat menyimpan data!',
                    });
                },
                complete: function() {
                    $('#submitBtn').prop('disabled', false);
                    $('#spinner-border').addClass('d-none');
                }
            });
        }
    </script>
@endpush
