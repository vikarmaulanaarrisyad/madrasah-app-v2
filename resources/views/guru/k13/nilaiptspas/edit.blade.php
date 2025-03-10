@extends('layouts.app')

@section('title', 'Edit Nilai PTS & PAS')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-edit"></i> @yield('title')</h3>
                </div>

                <div class="card-body">
                    <div class="callout callout-info">
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Mata Pelajaran</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" value="{{ $pembelajaran->mata_pelajaran->nama }}"
                                    readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Kelas</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" value="{{ $pembelajaran->rombel->nama }}"
                                    readonly>
                            </div>
                        </div>
                    </div>

                    <form id="formEditNilai" action="{{ route('nilaiptspas.update', $pembelajaran->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="bg-warning">
                                    <tr>
                                        <th class="text-center" style="width: 5%;">No</th>
                                        <th class="text-center">Nama Siswa</th>
                                        <th class="text-center">Nilai PTS</th>
                                        <th class="text-center">Nilai PAS</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $no = 1; @endphp
                                    @foreach ($dataAnggotaRombel->siswa_rombel->sortBy('nama_lengkap') as $siswa)
                                        @php
                                            // Mencari nilai berdasarkan siswa_id
                                            $nilai = $dataNilaiPtsPas->firstWhere('siswa_id', $siswa->id);
                                        @endphp
                                        <tr>
                                            <td class="text-center">{{ $no++ }}</td>
                                            <td>{{ $siswa->nama_lengkap ?? '' }}</td>
                                            <input type="hidden" name="siswa_id[]" value="{{ $siswa->id }}">

                                            <td>
                                                <input type="number" class="form-control nilai-input-pts"
                                                    name="nilai_pts[]" min="0" max="100" required
                                                    value="{{ $nilai->nilai_pts ?? '' }}">
                                            </td>
                                            <td>
                                                <input type="number" class="form-control nilai-input-pas"
                                                    name="nilai_pas[]" min="0" max="100" required
                                                    value="{{ $nilai->nilai_pas ?? '' }}">
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                </div>

                <div class="card-footer clearfix">
                    <button type="submit" class="btn btn-warning float-right">Update</button>
                    <a href="{{ route('nilaiptspas.index') }}" class="btn btn-default float-right mr-2">Batal</a>
                </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#formEditNilai').submit(function(event) {
                event.preventDefault();

                Swal.fire({
                    title: 'Memproses...',
                    text: 'Mohon tunggu, sedang menyimpan data.',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                let formData = $(this).serialize();

                $.ajax({
                    url: $(this).attr('action'),
                    method: $(this).attr('method'),
                    data: formData,
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: 'Data nilai berhasil diperbarui.',
                            showConfirmButton: false,
                            timer: 2000
                        }).then(() => {
                            window.location.reload;
                        });
                    },
                    error: function(xhr) {
                        let errorMessage = 'Terjadi kesalahan!';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }

                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: errorMessage
                        });
                    }
                });
            });
        });
    </script>
@endpush
