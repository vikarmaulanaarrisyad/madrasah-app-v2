@extends('layouts.app')

@section('title', 'Edit Nilai Formatif ' . $sum)
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('nilaiformatif.index') }}">Nilai Harian</a></li>
    <li class="breadcrumb-item active">Edit Nilai BAB {{ $sum }}</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <x-card>
                <x-slot name="header">
                    <h5 class="card-title mb-2">
                        Edit Nilai BAB {{ $sum }} - {{ $mataPelajaran->nama }}
                    </h5>
                </x-slot>

                <form id="form-edit-nilai"
                    action="{{ route('nilaiformatif.update', [$rombel->id, $mataPelajaran->id, $sum]) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label for="materi">Materi</label>
                        <textarea name="materi" id="materi" cols="2" rows="2" class="form-control"
                            placeholder="Materi singkat...">{{ $nilaiSUM->first()->materi ?? '' }}</textarea>
                    </div>

                    <x-table>
                        <x-slot name="thead">
                            <tr>
                                <th>No</th>
                                <th>Nama Siswa</th>
                                <th class="text-center">
                                    Nilai <input type="number" name="semua_nilai" id="semua_nilai" min="0"
                                        max="100" class="form-control" style="width: 80px; display: inline-block;"
                                        placeholder="0">
                                </th>
                            </tr>
                        </x-slot>
                        @foreach ($nilaiSUM as $nilai)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $nilai->siswa->nama_lengkap }}</td>
                                <td>
                                    <input type="hidden" name="siswa_id[]" value="{{ $nilai->siswa_id }}">
                                    <input type="number" name="nilai[]" class="form-control" value="{{ $nilai->nilai }}"
                                        min="0" max="100" required>
                                </td>
                            </tr>
                        @endforeach

                    </x-table>

                    <div class="text-right">
                        <button type="button" id="btn-simpan" class="btn btn-success">Simpan</button>
                        <button type="button" class="btn btn-secondary" onclick="kembali()">Kembali</button>
                    </div>
                </form>
            </x-card>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#btn-simpan').on('click', function() {
                Swal.fire({
                    title: 'Menyimpan...',
                    text: 'Harap tunggu sementara data disimpan.',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                $.ajax({
                    url: "{{ route('nilaiformatif.update', [$rombel->id, $mataPelajaran->id, $sum]) }}",
                    type: "PUT",
                    data: $('#form-edit-nilai').serialize(),
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: response.message || 'Nilai berhasil diperbarui!',
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            let rombelId = "{{ $rombel->id }}";
                            let mataPelajaranId = "{{ $mataPelajaran->id }}";

                            // Redirect ke halaman dengan parameter dinamis
                            window.location.href =
                                `/guru/nilaiformatif?rombel_id=${rombelId}&mata_pelajaran_id=${mataPelajaranId}`;
                        });
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: xhr.responseJSON.message ||
                                'Terjadi kesalahan saat menyimpan.',
                        });
                    }
                });
            });
        });

        function kembali() {
            window.history.back();
        }
    </script>
@endpush
