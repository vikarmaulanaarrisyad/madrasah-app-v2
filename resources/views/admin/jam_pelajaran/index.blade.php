@extends('layouts.app')

@section('title', 'Setting Jam Pelajaran')

@section('subtitle', 'Setting Jam Pelajaran')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <x-card>
                <form id="form-jam-pelajaran">
                    <div id="jam-container">
                        <div id="jam-container">
                            @if ($jamPelajaran->isEmpty())
                                <div class="jam-row mt-2">
                                    <div class="row">
                                        <div class="col-md-1">
                                            <input type="number" class="form-control" name="jam_ke[]" required value="1"
                                                readonly>
                                        </div>
                                        <div class="col-md-3">
                                            <select class="form-control" name="jenis[]" required
                                                onchange="updateDurasi(this)">
                                                <option value="pembelajaran" selected>Pembelajaran</option>
                                                <option value="upacara">Upacara</option>
                                                <option value="istirahat">Istirahat</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <input type="number" class="form-control" name="durasi[]" required
                                                value="35" oninput="updateSelesai()">
                                        </div>
                                        <div class="col-md-3">
                                            <input type="time" class="form-control" name="mulai[]" required>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="input-group">
                                                <input type="time" class="form-control" name="selesai[]" readonly>
                                                <div class="input-group-append">
                                                    <button type="button" class="btn btn-danger btn-sm"
                                                        onclick="removeJam(this, null)">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @else
                                @foreach ($jamPelajaran as $jam)
                                    <div class="jam-row mt-2" data-id="{{ $jam->id }}">
                                        <div class="row">
                                            <div class="col-md-1">
                                                <input type="number" class="form-control" name="jam_ke[]" required
                                                    value="{{ $jam->jam_ke }}" readonly>
                                            </div>
                                            <div class="col-md-3">
                                                <select class="form-control" name="jenis[]" required
                                                    onchange="updateDurasi(this)">
                                                    <option value="pembelajaran"
                                                        {{ $jam->jenis == 'pembelajaran' ? 'selected' : '' }}>Pembelajaran
                                                    </option>
                                                    <option value="upacara"
                                                        {{ $jam->jenis == 'upacara' ? 'selected' : '' }}>Upacara</option>
                                                    <option value="istirahat"
                                                        {{ $jam->jenis == 'istirahat' ? 'selected' : '' }}>Istirahat
                                                    </option>
                                                </select>
                                            </div>
                                            <div class="col-md-2">
                                                <input type="number" class="form-control" name="durasi[]" required
                                                    value="{{ $jam->durasi }}" oninput="updateSelesai()">
                                            </div>
                                            <div class="col-md-3">
                                                <input type="time" class="form-control" name="mulai[]" required
                                                    value="{{ $jam->mulai }}" onchange="updateSelesai()">
                                            </div>
                                            <div class="col-md-3">
                                                <div class="input-group">
                                                    <input type="time" class="form-control" name="selesai[]" readonly
                                                        value="{{ $jam->selesai }}">
                                                    <div class="input-group-append">
                                                        <button type="button" class="btn btn-danger btn-sm"
                                                            onclick="removeJam(this, {{ $jam->id }})">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
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
        function updateSelesai() {
            let rows = document.querySelectorAll('.jam-row');
            let prevSelesai = null;

            rows.forEach((row, index) => {
                let mulaiInput = row.querySelector('[name="mulai[]"]');
                let durasiInput = row.querySelector('[name="durasi[]"]');
                let selesaiInput = row.querySelector('[name="selesai[]"]');

                if (prevSelesai) {
                    mulaiInput.value = prevSelesai;
                }

                if (mulaiInput.value && durasiInput.value) {
                    let mulaiTime = new Date(`2000-01-01T${mulaiInput.value}`);
                    mulaiTime.setMinutes(mulaiTime.getMinutes() + parseInt(durasiInput.value));
                    let selesaiTime = mulaiTime.toTimeString().split(' ')[0].substring(0, 5);
                    selesaiInput.value = selesaiTime;
                    prevSelesai = selesaiTime;
                }
            });
        }

        function updateDurasi(selectElement) {
            let row = selectElement.closest('.jam-row');
            let durasiInput = row.querySelector('[name="durasi[]"]');

            if (selectElement.value === "upacara") {
                durasiInput.value = 60;
            } else if (selectElement.value === "istirahat") {
                durasiInput.value = 30;
            } else {
                durasiInput.value = 35;
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
                    <input type="number" class="form-control" name="jam_ke[]" required value="${lastJamKe + 1}" readonly>
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
                            <button type="button" class="btn btn-danger btn-sm" onclick="removeJam(this, null)">
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

        function removeJam(button, id = null) {
            let row = button.closest('.jam-row');
            let container = document.getElementById('jam-container');

            if (container.children.length >= 1) {
                if (id) {
                    Swal.fire({
                        title: 'Hapus Jam Pelajaran?',
                        text: 'Data ini akan dihapus dari database!',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Ya, Hapus!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Tampilkan SweetAlert loading sebelum AJAX dimulai
                            Swal.fire({
                                title: 'Menghapus...',
                                text: 'Mohon tunggu...',
                                allowOutsideClick: false,
                                allowEscapeKey: false,
                                didOpen: () => {
                                    Swal.showLoading();
                                }
                            });

                            $.ajax({
                                url: `/admin/jampelajaran/${id}`,
                                type: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                                        'content') // Ambil CSRF token dari meta tag
                                },
                                success: function() {
                                    row.remove();
                                    updateJamKe();
                                    updateSelesai();
                                    Swal.fire('Terhapus!', 'Data berhasil dihapus.', 'success');
                                    window.location.reload()
                                },
                                error: function(xhr) {
                                    Swal.fire('Error!', 'Gagal menghapus data.', 'error');
                                }
                            });
                        }
                    });
                } else {
                    row.remove();
                    updateJamKe();
                    updateSelesai();
                }
            } else {
                Swal.fire({
                    icon: 'warning',
                    title: 'Tidak Bisa Dihapus!',
                    text: 'Minimal harus ada 1 jam pelajaran.',
                });
            }
        }

        function updateJamKe() {
            document.querySelectorAll('.jam-row').forEach((row, index) => {
                row.querySelector('[name="jam_ke[]"]').value = index + 1;
            });
        }

        $('#form-jam-pelajaran').on('submit', function(e) {
            e.preventDefault();

            Swal.fire({
                title: 'Menyimpan...',
                text: 'Mohon tunggu...',
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            $.ajax({
                url: '{{ route('jampelajaran.store') }}', // Ganti dengan route yang benar
                type: 'POST',
                data: $(this).serialize(),
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: 'Data jam pelajaran berhasil disimpan.',
                    }).then(() => {
                        window.location.reload() // Reload halaman setelah sukses
                    });
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: 'Terjadi kesalahan saat menyimpan data.',
                    });
                }
            });
        });
    </script>
@endpush
