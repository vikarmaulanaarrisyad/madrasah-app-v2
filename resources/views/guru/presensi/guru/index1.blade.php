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
                        <p><strong>Jam Masuk:</strong> <span id="jam-masuk">Menunggu...</span></p>
                        <p><strong>Jam Pulang:</strong> <span id="jam-pulang">Menunggu...</span></p>
                    </div>

                    <!-- Checkbox Work From Home -->
                    {{--  <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="workFromHome">
                        <label class="form-check-label" for="workFromHome">Work From Home</label>
                    </div>  --}}

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
                    <button class="btn btn-warning mt-3" id="editAbsen" style="display: none;">
                        <i class="fas fa-edit"></i> Edit Absen
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        let jamMasukDB = "{{ $jamKerja->jam_masuk ?? '07:00' }}";
        let jamPulangDB = "{{ $jamKerja->jam_keluar ?? '15:00' }}";

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

        function getCurrentTime() {
            let now = new Date();
            return now.toLocaleTimeString('id-ID', {
                hour: '2-digit',
                minute: '2-digit'
            });
        }

        function compareTime(currentTime, targetTime) {
            return currentTime.localeCompare(targetTime);
        }
        document.getElementById("absenMasuk").addEventListener("click", function() {
            let now = getCurrentTime();

            if (compareTime(now, jamMasukDB) > 0) {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal Absen Masuk!',
                    text: `Presensi masuk hanya bisa dilakukan sebelum ${jamMasukDB}.`,
                    confirmButtonText: 'OK'
                });
            } else {
                $.ajax({
                    url: "{{ route('presensigtk.store') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        jenis: 'masuk'
                    },
                    beforeSend: function() {
                        Swal.fire({
                            title: 'Memproses...',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                    },
                    success: function(response) {
                        fetchPresensiData()
                        document.getElementById("jam-masuk").innerText = response.jam_masuk;
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: response.message,
                            confirmButtonText: 'OK'
                        });
                    },
                    error: function(xhr) {
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

        document.getElementById("absenPulang").addEventListener("click", function() {
            let now = getCurrentTime();

            // Cek apakah sudah absen masuk sebelumnya
            let jamMasuk = document.getElementById("jam-masuk").innerText;
            if (!jamMasuk) {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal Absen Pulang!',
                    text: 'Anda belum melakukan absen masuk!',
                    confirmButtonText: 'OK'
                });
                return;
            }

            if (compareTime(now, jamPulangDB) < 0) {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal Absen Pulang!',
                    text: `Presensi pulang hanya bisa dilakukan setelah ${jamPulangDB}.`,
                    confirmButtonText: 'OK'
                });
            } else {
                $.ajax({
                    url: "{{ route('presensigtk.store') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        jenis: 'pulang'
                    },
                    beforeSend: function() {
                        Swal.fire({
                            title: 'Memproses...',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                    },
                    success: function(response) {
                        fetchPresensiData();
                        document.getElementById("jam-pulang").innerText = response.jam_pulang;
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: response.message,
                            confirmButtonText: 'OK'
                        });
                    },
                    error: function(xhr) {
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

        function fetchPresensiData() {
            $.ajax({
                url: "{{ route('presensigtk.data') }}",
                type: "GET",
                success: function(response) {
                    document.getElementById("jam-masuk").innerText = response.jam_masuk || "Belum Absen";
                    document.getElementById("jam-pulang").innerText = response.jam_pulang || "Belum Absen";
                },
                error: function() {
                    document.getElementById("jam-masuk").innerText = "Belum Absen";
                    document.getElementById("jam-pulang").innerText = "Belum Absen";
                }
            });
        }

        document.addEventListener("DOMContentLoaded", fetchPresensiData);
    </script>
@endpush
