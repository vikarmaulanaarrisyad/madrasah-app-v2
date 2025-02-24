@extends('layouts.app')

@section('title', 'Data Kenaikan Siswa')

@section('subtitle', 'Proses Kenaikan Siswa')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Data Kenaikan Siswa</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">Proses Kenaikan Siswa</div>
                <div class="card-body">
                    <form id="formKenaikan">
                        @csrf
                        <div class="form-group">
                            <label>Tahun Pelajaran Sebelumnya</label>
                            <input type="text" class="form-control"
                                value="{{ $tahunSebelumnya->nama }} {{ $tahunSebelumnya->semester->nama }}" readonly>
                        </div>
                        <div class="form-group">
                            <label>Tahun Pelajaran Berikutnya (Otomatis)</label>
                            <input type="text" class="form-control"
                                value="{{ $tahunPelajaranAktif->nama }} {{ $tahunPelajaranAktif->semester->nama }}"
                                readonly>
                        </div>
                        <div class="form-group">
                            <label>Kelas Tujuan</label>
                            <select class="form-control" name="kelas_tujuan">
                                {{--  @foreach ($kelas as $k)
                                    <option value="{{ $k->id }}">{{ $k->nama }}</option>
                                @endforeach  --}}
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Rombel Tujuan</label>
                            <select class="form-control" name="rombel_tujuan">
                                @foreach ($rombelBerikutnya as $r)
                                    <option value="{{ $r->id }}">{{ $r->nama }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Daftar Siswa</label>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th><input type="checkbox" id="selectAll"></th>
                                        <th>Nama</th>
                                        <th>NIS</th>
                                        <th>Kelas</th>
                                        <th>Rombel</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($siswa as $s)
                                        <tr>
                                            <td>
                                                <input type="checkbox" name="siswa_ids[]" value="{{ $s->id }}">
                                            </td>
                                            <td>{{ $s->nama_lengkap }}</td>
                                            <td>{{ $s->nis }}</td>
                                            <td>2</td>
                                            <td>2</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <button type="submit" class="btn btn-primary">Proses Kenaikan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Checkbox untuk memilih semua siswa
            $('#selectAll').change(function() {
                $('input[name="siswa_ids[]"]').prop('checked', $(this).prop('checked'));
            });

            $('#formKenaikan').submit(function(e) {
                e.preventDefault();

                let selectedSiswa = $('input[name="siswa_ids[]"]:checked').map(function() {
                    return this.value;
                }).get();

                if (selectedSiswa.length === 0) {
                    Swal.fire('Peringatan!', 'Pilih minimal satu siswa!', 'warning');
                    return;
                }

                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Data siswa akan dinaikkan ke tahun berikutnya.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Proses!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('kenaikan-siswa.proses') }}",
                            type: "POST",
                            data: {
                                _token: "{{ csrf_token() }}",
                                siswa_ids: selectedSiswa,
                                kelas_tujuan: $('select[name="kelas_tujuan"]').val(),
                                rombel_tujuan: $('select[name="rombel_tujuan"]').val(),
                            },
                            success: function(response) {
                                Swal.fire('Berhasil!', response.message, 'success');
                            },
                            error: function(xhr) {
                                Swal.fire('Gagal!', xhr.responseJSON.message, 'error');
                            }
                        });
                    }
                });
            });
        });
    </script>
@endpush
