@extends('layouts.app')

@section('title', 'Setting Jam Pelajaran')

@section('subtitle', 'Setting Jam Pelajaran')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <x-card>
                <form id="form-jam-pelajaran">
                    <div id="jam-container">
                        <div class="jam-row">
                            <div class="row">
                                <div class="col-md-3">
                                    <label for="jam_ke[]">Jam Ke</label>
                                    <input type="number" class="form-control" name="jam_ke[]" required value="1">
                                </div>
                                <div class="col-md-3">
                                    <label for="mulai[]">Waktu Mulai</label>
                                    <input type="time" class="form-control" name="mulai[]" required
                                        onchange="updateSelesai(this)">
                                </div>
                                <div class="col-md-3">
                                    <label for="durasi[]">Durasi (menit)</label>
                                    <input type="number" class="form-control" name="durasi[]" required value="35"
                                        oninput="updateSelesai(this)">
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
                </form>
            </x-card>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function updateSelesai(element) {
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
                <div class="col-md-3">
                    <input type="number" class="form-control" name="jam_ke[]" required value="${lastJamKe + 1}">
                </div>
                <div class="col-md-3">
                    <input type="time" class="form-control" name="mulai[]" required value="${lastSelesai}" readonly>
                </div>
                <div class="col-md-3">
                    <input type="number" class="form-control" name="durasi[]" required value="35" oninput="updateSelesai(this)">
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
    </script>
@endpush
