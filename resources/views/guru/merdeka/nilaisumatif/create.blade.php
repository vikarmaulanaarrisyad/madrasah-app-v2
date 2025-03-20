@extends('layouts.app')

@section('title', 'Nilai Formatif')
@section('subtile', 'Nilai Formatif')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Nilai Formatif</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <x-card>
                <x-slot name="header">
                    <h5 class="card-title mb-2">Input Nilai Formatif
                        <span class="text-muted text-sm">{{ $rombel->kelas->nama }} {{ $rombel->nama }}</span>
                        <p class="text-xs">{{ $mataPelajaran->nama }}</p>
                    </h5>
                </x-slot>

                <form id="formNilai">
                    @csrf
                    <input type="hidden" name="rombel_id" value="{{ $rombel->id }}">
                    <input type="hidden" name="mata_pelajaran_id" value="{{ $mataPelajaran->id }}">

                    <div class="form-group">
                        <label for="materi">Materi</label>
                        <textarea name="materi" id="materi" cols="2" rows="2" class="form-control"
                            placeholder="Materi singkat..."></textarea>
                    </div>

                    <div class="table-responsive">
                        <x-table>
                            <x-slot name="thead">
                                <th class="text-center">No</th>
                                <th class="text-center">Nama Siswa</th>
                                <th class="text-center">
                                    Nilai <input type="number" name="semua_nilai" id="semua_nilai" min="0"
                                        max="100" class="form-control" style="width: 80px; display: inline-block;"
                                        placeholder="0">
                                </th>
                            </x-slot>
                            @foreach ($rombel->siswa_rombel as $index => $siswa)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td>{{ $siswa->nama_lengkap }}</td>
                                    <td>
                                        <input type="hidden" name="siswa_id[]" value="{{ $siswa->id }}">
                                        <input type="number" class="form-control nilai" name="nilai[]" min="0"
                                            max="100" required placeholder="0">
                                    </td>
                                </tr>
                            @endforeach
                        </x-table>
                    </div>

                    <div class="mt-3 text-right">
                        <button type="button" class="btn btn-primary" id="btnSimpan">Simpan</button>
                    </div>
                </form>
            </x-card>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Ketika nilai diinputkan di "Nilai Semua Siswa"
            $('#semua_nilai').on('input', function() {
                let nilaiSemua = $(this).val();

                // Pastikan nilai valid (0 - 100)
                if (nilaiSemua < 0 || nilaiSemua > 100) {
                    $(this).addClass('is-invalid');
                    return;
                } else {
                    $(this).removeClass('is-invalid');
                }

                // Set semua input nilai siswa dengan nilai yang diinputkan
                $('.nilai').val(nilaiSemua);
            });

            // Hapus invalid class ketika input berubah
            $('.nilai').on('input', function() {
                $(this).removeClass('is-invalid');
            });
        });

        $(document).ready(function() {
            $('#btnSimpan').on('click', function() {
                let isValid = true;
                let siswa_ids = [];
                let nilai_values = [];

                $('.nilai').each(function(index) {
                    let nilai = $(this).val();
                    let siswa_id = $('input[name="siswa_id[]"]').eq(index).val();

                    if (nilai === "" || nilai < 0 || nilai > 100) {
                        isValid = false;
                        $(this).addClass('is-invalid');
                    } else {
                        $(this).removeClass('is-invalid');
                    }

                    siswa_ids.push(siswa_id);
                    nilai_values.push(nilai);
                });

                if (!isValid) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Perhatian!',
                        text: 'Pastikan semua nilai sudah diisi dengan angka antara 0 - 100.',
                    });
                    return;
                }

                Swal.fire({
                    title: 'Menyimpan...',
                    text: 'Mohon tunggu sebentar.',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                $('#btnSimpan').prop('disabled', true);

                $.ajax({
                    url: "{{ route('nilaiformatif.store') }}",
                    type: "POST",
                    contentType: "application/json",
                    data: JSON.stringify({
                        _token: "{{ csrf_token() }}",
                        rombel_id: "{{ $rombel->id }}",
                        mata_pelajaran_id: "{{ $mataPelajaran->id }}",
                        siswa_id: siswa_ids, // Array siswa_id
                        nilai: nilai_values, // Array nilai
                        materi: $('[name=materi]').val() || null // Pastikan tidak undefined
                    }),
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Sukses',
                            text: 'Nilai berhasil disimpan!',
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            //window.history.back();
                            // Ambil nilai rombel_id dan mata_pelajaran_id
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
                            title: 'Oops...',
                            text: xhr.responseJSON?.message ||
                                'Terjadi kesalahan saat menyimpan data!',
                        });
                    },
                    complete: function() {
                        $('#btnSimpan').prop('disabled', false);
                    }
                });
            });

            $('.nilai').on('input', function() {
                $(this).removeClass('is-invalid');
            });
        });
    </script>
@endpush
