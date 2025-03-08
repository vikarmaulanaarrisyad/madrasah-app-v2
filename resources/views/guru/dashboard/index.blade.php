@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <x-card>
                <x-slot name="header">
                    <h4>Jadwal Mengajar - {{ $hariIni }}</h4>
                </x-slot>
                <x-table>
                    <x-slot name="thead">
                        <th>Jam Ke</th>
                        <th>Waktu</th>
                        <th>Mata Pelajaran</th>
                        <th>Rombel</th>
                    </x-slot>

                    @if ($jadwalPelajaran->count() > 0)
                        @foreach ($jadwalPelajaran as $data)
                            <tr>
                                <td>{{ $data->jamPelajaran->jam_ke }}</td>
                                <td>{{ \Carbon\Carbon::parse($data->jamPelajaran->mulai)->format('H:i') }} -
                                    {{ \Carbon\Carbon::parse($data->jamPelajaran->selesai)->format('H:i') }}
                                </td>

                                <td>{{ $data->mataPelajaran->nama }}</td>
                                <td>{{ $data->rombel->kelas->nama }} {{ $data->rombel->nama }}</td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="3" class="text-center text-muted">Tidak ada jadwal hari ini</td>
                        </tr>
                    @endif
                </x-table>
            </x-card>
        </div>
    </div>
@endsection
