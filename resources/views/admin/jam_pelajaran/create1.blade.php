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
                                    <input type="number" class="form-control" name="jam_ke[]" required>
                                </div>
                                <div class="col-md-3">
                                    <label for="mulai[]">Waktu Mulai</label>
                                    <input type="time" class="form-control" name="mulai[]" required
                                        onchange="updateSelesai(this)">
                                </div>
                                <div class="col-md-3">
                                    <label for="durasi[]">Durasi (menit)</label>
                                    <input type="number" class="form-control" name="durasi[]" required
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
            let row = element.closest('.jam-row');
            let mulai = row.querySelector('[name="mulai[]"]').value;
            let durasi = parseInt(row.querySelector('[name="durasi[]"]').value);

            if (mulai && durasi) {
                let mulaiTime = new Date(`2000-01-01T${mulai}`);
                mulaiTime.setMinutes(mulaiTime.getMinutes() + durasi);
                let selesai = mulaiTime.toTimeString().split(' ')[0].substring(0, 5);

                row.querySelector('[name="selesai[]"]').value = selesai;
            }
        }

        function addJam() {
            let container = document.getElementById('jam-container');
            let newRow = document.createElement('div');
            newRow.classList.add('jam-row', 'mt-2');

            newRow.innerHTML = `
            <div class="row">
                <div class="col-md-3">
                    <input type="number" class="form-control" name="jam_ke[]" required placeholder="Jam Ke">
                </div>
                <div class="col-md-3">
                    <input type="time" class="form-control" name="mulai[]" required onchange="updateSelesai(this)">
                </div>
                <div class="col-md-3">
                    <input type="number" class="form-control" name="durasi[]" required placeholder="Durasi (menit)" oninput="updateSelesai(this)">
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
        }

        function removeJam(button) {
            button.closest('.jam-row').remove();
        }
    </script>
@endpush
