@extends('layouts.app')

@section('title', 'Data Presensi Guru')

@section('subtitle', 'Data Presensi Guru')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Presensi Guru</li>
@endsection

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body text-center">
                    <!-- Jam dan Tanggal Real-time -->
                    <h3 id="current-time"></h3>
                    <p id="current-date"></p>

                    <!-- Informasi Guru -->
                    <div class="alert alert-light">
                        <h5><strong>{{ $sekolah->nama }}</strong></h5>
                        <p>Silahkan melakukan Presensi</p>
                    </div>

                    <!-- Batasan Waktu Presensi -->
                    <div class="alert alert-info">
                        <h5>Anda dapat melakukan Presensi:</h5>
                        <p><strong>Masuk sebelum {{ $jamKerja->jam_masuk ?? '-' }}</strong> dan
                            <strong>Pulang setelah {{ $jamKerja->jam_keluar ?? '-' }}</strong>
                        </p>
                    </div>

                    <!-- Informasi Presensi -->
                    <div class="alert alert-secondary">
                        <h5>Presensi Hari Ini</h5>
                        <p><strong>Jam Masuk:</strong> <span id="jam-masuk">Belum Absen</span></p>
                        <p><strong>Jam Pulang:</strong> <span id="jam-pulang">Belum Absen</span></p>
                    </div>

                    <!-- Checkbox Work From Home -->
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="workFromHome">
                        <label class="form-check-label" for="workFromHome">Work From Home</label>
                    </div>

                    <!-- Tombol Absen -->
                    <div class="mt-3">
                        <button class="btn btn-success" id="absenMasuk">
                            <i class="fas fa-check-circle"></i> Absen Masuk
                        </button>
                        <button class="btn btn-danger" id="absenPulang">
                            <i class="fas fa-sign-out-alt"></i> Pulang
                        </button>
                    </div>

                    <!-- Tombol Edit Absen -->
                    <button class="btn btn-warning mt-3" id="editAbsen">
                        <i class="fas fa-edit"></i> Edit Absen
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function updateTime() {
            let now = new Date();
            let time = now.toLocaleTimeString('id-ID', {
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            });
            let date = now.toLocaleDateString('id-ID', {
                weekday: 'long',
                day: 'numeric',
                month: 'long',
                year: 'numeric'
            });

            document.getElementById("current-time").innerText = time;
            document.getElementById("current-date").innerText = date;
        }
        setInterval(updateTime, 1000);
        updateTime();

        // Fungsi untuk mendapatkan waktu saat ini dalam format HH:MM
        function getCurrentTime() {
            let now = new Date();
            return now.toLocaleTimeString('id-ID', {
                hour: '2-digit',
                minute: '2-digit'
            });
        }

        // Event listener untuk Absen Masuk
        document.getElementById("absenMasuk").addEventListener("click", function() {
            let now = new Date();
            let hour = now.getHours();
            let jenis = 'masuk';

            if (hour >= 7) {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal Absen Masuk!',
                    text: 'Presensi masuk hanya bisa dilakukan sebelum jam 07.00.',
                    confirmButtonText: 'OK'
                });
            } else {
                $.ajax({
                    url: "{{ route('presensigtk.store') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        jenis: jenis
                    },
                    success: function(response, textStatus, xhr) {
                        if (xhr.status === 201 || xhr.status === 200) {
                            document.getElementById("jam-masuk").innerText = response.jam_masuk;
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil Absen Masuk!',
                                text: response.message,
                                confirmButtonText: 'OK'
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: response.message,
                                confirmButtonText: 'OK'
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: xhr.responseJSON.message,
                            confirmButtonText: 'OK'
                        });
                    }
                });
            }
        });

        // Event listener untuk Absen Pulang
        document.getElementById("absenPulang").addEventListener("click", function() {
            let now = new Date();
            let hour = now.getHours();

            if (hour < 15) {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal Absen Pulang!',
                    text: 'Presensi pulang hanya bisa dilakukan setelah jam 15.00.',
                    confirmButtonText: 'OK'
                });
            } else {
                document.getElementById("jam-pulang").innerText = getCurrentTime();
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil Absen Pulang!',
                    text: 'Waktu pulang telah dicatat.',
                    confirmButtonText: 'OK'
                });
            }
        });

        // Event listener untuk Edit Absen
        document.getElementById("editAbsen").addEventListener("click", function() {
            Swal.fire({
                title: 'Edit Waktu Absen',
                html: `
            <label for="editMasuk">Jam Masuk:</label>
            <input type="time" id="editMasuk" class="swal2-input" value="${document.getElementById("jam-masuk").innerText !== 'Belum Absen' ? document.getElementById("jam-masuk").innerText : ''}">

            <label for="editPulang">Jam Pulang:</label>
            <input type="time" id="editPulang" class="swal2-input" value="${document.getElementById("jam-pulang").innerText !== 'Belum Absen' ? document.getElementById("jam-pulang").innerText : ''}">
        `,
                showCancelButton: true,
                confirmButtonText: 'Simpan',
                preConfirm: () => {
                    let masukTime = document.getElementById("editMasuk").value;
                    let pulangTime = document.getElementById("editPulang").value;

                    if (!masukTime || !pulangTime) {
                        Swal.showValidationMessage('Harap isi kedua waktu absen!');
                        return false;
                    }

                    return {
                        masuk: masukTime,
                        pulang: pulangTime
                    };
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById("jam-masuk").innerText = result.value.masuk;
                    document.getElementById("jam-pulang").innerText = result.value.pulang;

                    Swal.fire({
                        icon: 'success',
                        title: 'Absen Diperbarui!',
                        text: 'Waktu presensi telah diperbarui.',
                        confirmButtonText: 'OK'
                    });
                }
            });
        });

        function fetchPresensiData() {
            // Ubah teks sementara menjadi "Menunggu..."
            document.getElementById("jam-masuk").innerText = "Menunggu...";
            document.getElementById("jam-pulang").innerText = "Menunggu...";

            $.ajax({
                url: "{{ route('presensigtk.data') }}", // Pastikan route ini dibuat di backend
                type: "GET",
                success: function(response) {
                    if (response.success) {
                        document.getElementById("jam-masuk").innerText = response.jam_masuk ? response
                            .jam_masuk : "Belum Absen";
                        document.getElementById("jam-pulang").innerText = response.jam_pulang ? response
                            .jam_pulang : "Belum Absen";
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Gagal memperbarui data presensi:", error);
                    // Jika ada error, tetap kembalikan teks ke "Belum Absen"
                    document.getElementById("jam-masuk").innerText = "Belum Absen";
                    document.getElementById("jam-pulang").innerText = "Belum Absen";
                }
            });
        }

        // Panggil fungsi ini saat halaman dimuat
        document.addEventListener("DOMContentLoaded", fetchPresensiData);
    </script>
@endpush
