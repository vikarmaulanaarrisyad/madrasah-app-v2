@extends('layouts.app')

@section('title', 'Setting Jadwal Pelajaran')
@section('subtitle', 'Setting Jadwal Pelajaran')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Jadwal Pelajaran</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <x-card>
                <x-slot name="header">
                    <div class="d-flex justify-content-between">
                        <div>
                            <label for="rombel_id">Pilih Rombel:</label>
                            <select id="rombel_id" class="form-control">
                                @foreach ($rombels as $rombel)
                                    <option value="{{ $rombel->id }}" {{ $rombelId == $rombel->id ? 'selected' : '' }}>
                                        {{ $rombel->kelas->nama }} {{ $rombel->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        {{--  <div>
                            <label for="kelas_id">Pilih Kelas:</label>
                            <select id="kelas_id" class="form-control">
                                @foreach ($kelasList as $kelas)
                                    <option value="{{ $kelas->id }}" {{ $kelasId == $kelas->id ? 'selected' : '' }}>
                                        {{ $kelas->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>  --}}
                    </div>
                </x-slot>

                <x-table>
                    <x-slot name="thead">
                        <tr>
                            <th>Jam Ke</th>
                            <th>Waktu</th>
                            @foreach ($days as $day)
                                <th>{{ $day }}</th>
                            @endforeach
                        </tr>
                    </x-slot>
                    <tbody>
                        @foreach ($jamPelajarans as $jam)
                            <tr>
                                <td width="5%" style="text-center">{{ $jam->jam_ke }}</td>
                                <td width="10%">{{ date('H:i', strtotime($jam->mulai)) }} -
                                    {{ date('H:i', strtotime($jam->selesai)) }}
                                </td>
                                @foreach ($days as $day)
                                    @php
                                        $subject = $jadwalPelajaran->get($jam->id)?->firstWhere('hari', $day);
                                    @endphp
                                    <td class="schedule-cell {{ in_array($jam->jenis, ['istirahat', 'pembiasaan', 'upacara']) ? 'non-clickable' : '' }}"
                                        data-hour="{{ $jam->id }}" data-day="{{ $day }}">

                                        @if ($jam->jenis == 'istirahat')
                                            <span class="badge bg-warning">Istirahat</span>
                                        @elseif ($jam->jenis == 'pembiasaan')
                                            <span class="badge bg-info">Pembiasaan Diri</span>
                                        @elseif ($jam->jenis == 'upacara')
                                            <span class="badge bg-danger">Upacara</span>
                                        @elseif ($subject)
                                            <span class="subject">{{ $subject->mataPelajaran->nama }}</span>
                                        @else
                                            <span class="text-muted">+ Tambah</span>
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </x-table>
            </x-card>
        </div>
    </div>
@endsection


@push('scripts')
    <script>
        $(document).ready(function() {
            $('body').addClass('sidebar-collapse');
            // Filter berdasarkan rombel
            $('#rombel_id').change(function() {
                window.location.href = "{{ route('jadwalpelajaran.index') }}?rombel_id=" + $('#rombel_id')
                    .val();
            });

            // Tambah/Edit Jadwal dengan Swal dan AJAX
            $('.schedule-cell').on('click', function() {
                if ($(this).hasClass('non-clickable')) {
                    return; // Jika memiliki kelas 'non-clickable', tidak menjalankan modal
                }

                let hour = $(this).data('hour');
                let day = $(this).data('day');
                let rombel_id = $('#rombel_id').val();

                Swal.fire({
                    title: 'Atur Mata Pelajaran',
                    input: 'select',
                    inputOptions: {
                        @foreach ($mataPelajarans as $mp)
                            "{{ $mp->id }}": "{{ $mp->nama }}",
                        @endforeach
                    },
                    inputPlaceholder: 'Pilih Mata Pelajaran',
                    showCancelButton: true,
                    confirmButtonText: 'Simpan',
                    showLoaderOnConfirm: true,
                    customClass: {
                        popup: 'swal-dropdown-below' // Tambahkan class custom
                    },
                    preConfirm: (subject_id) => {
                        return $.ajax({
                            url: "{{ route('jadwalpelajaran.store') }}",
                            method: "POST",
                            data: {
                                _token: "{{ csrf_token() }}",
                                rombel_id: rombel_id,
                                jam_pelajaran_id: hour,
                                day: day,
                                mata_pelajaran_id: subject_id
                            }
                        }).done(function(response) {
                            Swal.fire('Sukses!', response.success, 'success').then(
                                () => {
                                    location.reload();
                                });
                        }).fail(function(error) {
                            Swal.fire('Error!', 'Gagal menyimpan data.', 'error');
                        });
                    }
                });
            });
        });
    </script>
@endpush
