@extends('layouts.app')

@section('title', 'Input Nilai PTS & PAS')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-list-ol"></i> @yield('title')</h3>
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
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Jumlah Siswa</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control"
                                    value="{{ $pembelajaran->rombel->siswa_rombel->count() }} Siswa" readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Guru</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" value="{{ $pembelajaran->guru->nama_lengkap }}"
                                    readonly>
                            </div>
                        </div>
                    </div>

                    <form id="formNilai" action="{{ route('nilaiptspas.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="pembelajaran_id" value="{{ $pembelajaran->id }}">

                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Isi Semua Nilai PTS</label>
                            <div class="col-sm-8">
                                <input type="number" id="nilaiSemuaPTS" class="form-control" min="0" max="100"
                                    placeholder="Masukkan nilai PTS">
                            </div>
                            <div class="col-sm-2">
                                <button type="button" class="btn btn-success btn-block"
                                    onclick="isiSemuaNilai('PTS')">Isi</button>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Isi Semua Nilai PAS</label>
                            <div class="col-sm-8">
                                <input type="number" id="nilaiSemuaPAS" class="form-control" min="0" max="100"
                                    placeholder="Masukkan nilai PAS">
                            </div>
                            <div class="col-sm-2">
                                <button type="button" class="btn btn-success btn-block"
                                    onclick="isiSemuaNilai('PAS')">Isi</button>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="bg-primary">
                                    <tr>
                                        <th class="text-center" style="width: 5%;">No</th>
                                        <th class="text-center">Nama Siswa</th>
                                        <th class="text-center">Nilai Tengah Semester (PTS)</th>
                                        <th class="text-center">Nilai Akhir Semester (PAS)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($dataAnggotaRombel as $rombel)
                                        @foreach ($rombel->siswa_rombel->sortBy('nama_lengkap') as $siswa)
                                            <tr>
                                                <td class="text-center">{{ $loop->iteration }}</td>
                                                <td>{{ $siswa->nama_lengkap ?? '' }}</td>
                                                <input type="hidden" name="anggota_rombel_id[]"
                                                    value="{{ $rombel->id }}">
                                                <input type="hidden" name="siswa_id[]" value="{{ $siswa->id }}">
                                                <td><input type="number" class="form-control nilai-input-pts"
                                                        name="nilai_pts[]" min="0" max="100" required></td>
                                                <td><input type="number" class="form-control nilai-input-pas"
                                                        name="nilai_pas[]" min="0" max="100" required></td>
                                            </tr>
                                        @endforeach
                                    @endforeach

                                </tbody>

                            </table>
                        </div>
                </div>

                <div class="card-footer clearfix">
                    <button type="submit" class="btn btn-primary float-right">Simpan</button>
                    <a href="{{ route('nilaiptspas.index') }}" class="btn btn-default float-right mr-2">Batal</a>
                </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function isiSemuaNilai(jenis) {
            let nilai;
            if (jenis === 'PTS') {
                nilai = document.getElementById('nilaiSemuaPTS').value;
                if (nilai === "" || nilai < 0 || nilai > 100) {
                    Swal.fire("Error!", "Masukkan nilai PTS antara 0 sampai 100!", "error");
                    return;
                }
                document.querySelectorAll('.nilai-input-pts').forEach(input => {
                    input.value = nilai;
                });
            } else if (jenis === 'PAS') {
                nilai = document.getElementById('nilaiSemuaPAS').value;
                if (nilai === "" || nilai < 0 || nilai > 100) {
                    Swal.fire("Error!", "Masukkan nilai PAS antara 0 sampai 100!", "error");
                    return;
                }
                document.querySelectorAll('.nilai-input-pas').forEach(input => {
                    input.value = nilai;
                });
            }
        }

        $(document).ready(function() {
            $('#formNilai').submit(function(event) {
                event.preventDefault(); // Mencegah form dikirim secara default

                Swal.fire({
                    title: 'Menyimpan...',
                    text: 'Mohon tunggu, sedang memproses data.',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                let formData = $(this).serialize(); // Ambil data form

                $.ajax({
                    url: $(this).attr('action'),
                    method: $(this).attr('method'),
                    data: formData,
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: 'Data nilai berhasil disimpan.',
                            showConfirmButton: false,
                            timer: 2000
                        }).then(() => {
                            window.location.href = "{{ route('nilaiptspas.index') }}";
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
