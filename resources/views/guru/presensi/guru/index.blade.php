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

                    @php
                        use Alkoumi\LaravelHijriDate\Hijri;

                        // Mendapatkan tanggal Hijriyah
                        $hijriDate = Hijri::Date('l d F Y'); // Format hari, tanggal, bulan, tahun Hijriyah

                        // Tentukan periode Ramadhan (Misalnya 1-30 Ramadhan)
                        $isRamadhan = Hijri::Date('m') == 9; // 9 adalah bulan Ramadhan dalam Hijriyah
                    @endphp

                    <div class="alert alert-{{ $isRamadhan ? 'success' : 'primary' }}">
                        <h5>Keterangan Presensi</h5>
                        <p>
                            Hari ini: <strong>{{ $hijriDate }}</strong> <br>
                            Status: <strong>{{ $isRamadhan ? 'Presensi Bulan Ramadhan' : 'Presensi Reguler' }}</strong>
                        </p>
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


                    @php
                        $hariIni = \Carbon\Carbon::now()->locale('id')->isoFormat('dddd');
                    @endphp

                    @if ($hariIni !== 'Minggu')
                        <div class="mt-3" id="presensiButtons">
                            <button class="btn btn-success" id="absenMasuk">
                                <i class="fas fa-check-circle"></i> Absen Masuk
                            </button>
                            <button class="btn btn-danger" id="absenPulang">
                                <i class="fas fa-sign-out-alt"></i> Pulang
                            </button>
                        </div>
                    @else
                        <div class="alert alert-warning">
                            <h5>Hari ini hari Minggu</h5>
                            <p>Presensi tidak tersedia.</p>
                        </div>
                    @endif

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

        function addMinutesToTime(time, minutes) {
            let [hour, minute] = time.split(":").map(Number);
            let date = new Date();
            date.setHours(hour, minute);
            date.setMinutes(date.getMinutes() + minutes);
            return date.toLocaleTimeString('id-ID', {
                hour: '2-digit',
                minute: '2-digit'
            });
        }

        let jamMasukToleransi = addMinutesToTime(jamMasukDB, 15);


        document.addEventListener("DOMContentLoaded", function() {
            let now = new Date();
            let day = now.getDay(); // 0 = Minggu, 1 = Senin, ..., 6 = Sabtu

            if (day === 0) { // Jika hari Minggu
                document.getElementById("absenMasuk").disabled = true;
                document.getElementById("absenPulang").disabled = true;
                Swal.fire({
                    icon: 'info',
                    title: 'Hari Minggu',
                    text: 'Hari ini adalah hari libur. Presensi tidak tersedia.',
                    confirmButtonText: 'OK'
                });
            }

            cekHariLibur();
        });

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

            // Jika waktu saat ini lebih dari batas toleransi masuk (07:15), absen ditolak
            if (compareTime(now, jamMasukToleransi) > 0) {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal Absen Masuk!',
                    text: `Presensi masuk hanya bisa dilakukan hingga ${jamMasukToleransi}.`,
                    confirmButtonText: 'OK'
                });
            } else {
                // Jika masih dalam rentang yang diperbolehkan, kirim absen
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
                        fetchPresensiData();
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

        function cekHariLibur() {
            $.ajax({
                url: '{{ route('presensigtk.cekHariLibur') }}',
                type: 'GET',
                success: function(response) {
                    if (response.status === 'libur') {
                        document.getElementById("presensiButtons").style.display = "none";
                        Swal.fire({
                            icon: 'warning',
                            title: 'Hari Libur!',
                            text: response.message,
                            confirmButtonText: 'OK'
                        });
                        return;
                    }
                    fetchPresensiData();
                }
            });
        }
    </script>
@endpush
