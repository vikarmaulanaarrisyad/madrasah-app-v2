@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0 text-center">Jadwal Mengajar - {{ $hariIni }}</h5>
                </div>

                <div class="card-body">
                    {{-- Alert Pemberitahuan --}}
                    <div class="alert alert-warning d-flex align-items-center flex-wrap p-3" role="alert">
                        <div class="flex-grow-1 text-center text-md-start">
                            <strong>Perhatian!</strong> Jangan lupa mengisi
                            <b>Jurnal Mengajar</b>, <b>Absensi Siswa</b>, dan <b>Presensi Guru</b>.
                        </div>
                    </div>


                    {{-- Tabel Jadwal Mengajar --}}
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped text-center">
                            <thead class="table-dark">
                                <tr>
                                    <th>Jam Ke</th>
                                    <th>Waktu</th>
                                    <th>Mata Pelajaran</th>
                                    <th>Rombel</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($jadwalPelajaran->count() > 0)
                                    @foreach ($jadwalPelajaran as $data)
                                        <tr>
                                            <td>{{ $data->jamPelajaran->jam_ke }}</td>
                                            <td>
                                                {{ \Carbon\Carbon::parse($data->jamPelajaran->mulai)->format('H:i') }} -
                                                {{ \Carbon\Carbon::parse($data->jamPelajaran->selesai)->format('H:i') }}
                                            </td>
                                            <td>{{ $data->mataPelajaran->nama }}</td>
                                            <td>{{ $data->rombel->kelas->nama }} {{ $data->rombel->nama }}</td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="4" class="text-muted text-center">Tidak ada jadwal hari ini</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
