<x-modal data-backdrop="static" data-keyboard="false" size="modal-xl">
    <x-slot name="title">
        Tambah Data
    </x-slot>

    @method('POST')

    <div id="jam-container">
        <div class="jam-row">
            <div class="row">
                <div class="col-md-1">
                    <label for="jam_ke[]">Jam Ke</label>
                    <input type="number" class="form-control" name="jam_ke[]" required value="1">
                </div>
                <div class="col-md-3">
                    <label for="jenis[]">Jenis</label>
                    <select class="form-control" name="jenis[]" required onchange="updateDurasi(this)">
                        <option value="pembelajaran" selected>Pembelajaran</option>
                        <option value="upacara">Upacara</option>
                        <option value="istirahat">Istirahat</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="durasi[]">Durasi (menit)</label>
                    <input type="number" class="form-control" name="durasi[]" required value="35"
                        oninput="updateSelesai()">
                </div>
                <div class="col-md-3">
                    <label for="mulai[]">Waktu Mulai</label>
                    <input type="time" class="form-control" name="mulai[]" required onchange="updateSelesai()">
                </div>
                <div class="col-md-3">
                    <label for="selesai[]">Waktu Selesai</label>
                    <input type="time" class="form-control" name="selesai[]" readonly>
                </div>
            </div>
        </div>
    </div>
    <button type="button" class="btn btn-sm btn-outline-success mt-2" onclick="addJam()">
        <i class="fas fa-plus"></i> Tambah Jam
    </button>
    <button type="submit" class="btn btn-sm btn-outline-primary mt-2">
        <i class="fas fa-save"></i> Simpan
    </button>

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
        function updateSelesai() {
            let rows = document.querySelectorAll('.jam-row');
            let prevSelesai = null;

            rows.forEach((row, index) => {
                let mulaiInput = row.querySelector('[name="mulai[]"]');
                let durasiInput = row.querySelector('[name="durasi[]"]');
                let selesaiInput = row.querySelector('[name="selesai[]"]');

                if (prevSelesai) {
                    mulaiInput.value = prevSelesai; // Set waktu mulai dari jam sebelumnya
                }

                if (mulaiInput.value && durasiInput.value) {
                    let mulaiTime = new Date(`2000-01-01T${mulaiInput.value}`);
                    mulaiTime.setMinutes(mulaiTime.getMinutes() + parseInt(durasiInput.value));
                    let selesaiTime = mulaiTime.toTimeString().split(' ')[0].substring(0, 5);
                    selesaiInput.value = selesaiTime;
                    prevSelesai = selesaiTime; // Update prevSelesai untuk jam berikutnya
                }
            });
        }

        function updateDurasi(selectElement) {
            let row = selectElement.closest('.jam-row');
            let durasiInput = row.querySelector('[name="durasi[]"]');

            if (selectElement.value === "upacara") {
                durasiInput.value = 60; // Upacara biasanya lebih lama
            } else if (selectElement.value === "istirahat") {
                durasiInput.value = 30; // Istirahat lebih lama dari pelajaran
            } else {
                durasiInput.value = 35; // Default pembelajaran
            }

            updateSelesai();
        }

        function addJam() {
            let container = document.getElementById('jam-container');
            let rows = document.querySelectorAll('.jam-row');
            let lastRow = rows[rows.length - 1];

            let lastJamKe = parseInt(lastRow.querySelector('[name="jam_ke[]"]').value);
            let lastSelesai = lastRow.querySelector('[name="selesai[]"]').value;

            let newRow = document.createElement('div');
            newRow.classList.add('jam-row', 'mt-2');

            newRow.innerHTML = `
            <div class="row">
                <div class="col-md-1">
                    <input type="number" class="form-control" name="jam_ke[]" required value="${lastJamKe + 1}">
                </div>
                <div class="col-md-3">
                    <select class="form-control" name="jenis[]" required onchange="updateDurasi(this)">
                        <option value="pembelajaran" selected>Pembelajaran</option>
                        <option value="upacara">Upacara</option>
                        <option value="istirahat">Istirahat</option>
                    </select>
                </div>
                 <div class="col-md-2">
                    <input type="number" class="form-control" name="durasi[]" required value="35" oninput="updateSelesai()">
                </div>
                <div class="col-md-3">
                    <input type="time" class="form-control" name="mulai[]" required value="${lastSelesai}" readonly>
                </div>

                <div class="col-md-3">
                    <div class="input-group">
                        <input type="time" class="form-control" name="selesai[]" readonly>
                        <div class="input-group-append">
                            <button type="button" class="btn btn-danger btn-sm" onclick="removeJam(this)">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;

            container.appendChild(newRow);
            updateSelesai();
        }

        function removeJam(button) {
            button.closest('.jam-row').remove();
            updateSelesai();
        }

        function submitForm(event) {
            event.preventDefault(); // Mencegah form submit secara default

            Swal.fire({
                title: 'Menyimpan...',
                text: 'Silakan tunggu',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            let formData = new FormData(document.getElementById('form-jam-pelajaran'));

            $.ajax({
                url: '{{ route('jampelajaran.store') }}', // Ganti dengan route yang sesuai
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: 'Data berhasil disimpan.',
                    }).then(() => {
                        location.reload(); // Reload halaman setelah sukses
                    });
                },
                error: function(xhr) {
                    let errorMessage = 'Terjadi kesalahan';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: errorMessage,
                    });
                }
            });
        }

        $(document).ready(function() {
            $('#form-jam-pelajaran').on('submit', submitForm);
        });
    </script>
@endpush
