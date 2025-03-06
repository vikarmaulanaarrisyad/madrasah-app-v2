@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <x-card>
                <x-slot name="header">
                    <h5>Tahun Pelajaran {{ $tapel->nama }} {{ $tapel->semester->nama }}</h5>
                </x-slot>
                <form id="formJamPresensi">
                    @csrf
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Hari</th>
                                <th>Jam Masuk</th>
                                <th>Jam Keluar</th>
                            </tr>
                        </thead>
                        <tbody id="jamKerjaTableBody">
                            @foreach (['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'] as $hari)
                                @php
                                    $jamData = $jamKerja->where('hari', $hari)->first();
                                @endphp
                                <tr>
                                    <td>{{ $hari }}</td>
                                    <td>
                                        <input type="time" name="jam_masuk[{{ $hari }}]"
                                            class="form-control jam-masuk" data-hari="{{ $hari }}"
                                            value="{{ $jamData->jam_masuk ?? '' }}">
                                    </td>
                                    <td>
                                        <input type="time" name="jam_keluar[{{ $hari }}]"
                                            class="form-control jam-keluar" data-hari="{{ $hari }}"
                                            value="{{ $jamData->jam_keluar ?? '' }}">
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </form>
            </x-card>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('.jam-masuk, .jam-keluar').on('change', function() {
                let hari = $(this).data('hari');
                let jamMasuk = $(`.jam-masuk[data-hari="${hari}"]`).val();
                let jamKeluar = $(`.jam-keluar[data-hari="${hari}"]`).val();

                if (jamMasuk && jamKeluar && jamKeluar <= jamMasuk) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Jam Tidak Valid',
                        text: `Jam keluar harus lebih besar dari jam masuk untuk ${hari}.`,
                        confirmButtonText: 'OK'
                    });

                    $(`.jam-keluar[data-hari="${hari}"]`).val('');
                }
            });
        });

        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Validasi agar jam masuk lebih kecil dari jam keluar
            $('.jam-masuk, .jam-keluar').on('change', function() {
                let hari = $(this).data('hari');
                let jamMasuk = $(`.jam-masuk[data-hari="${hari}"]`).val();
                let jamKeluar = $(`.jam-keluar[data-hari="${hari}"]`).val();

                if (jamMasuk && jamKeluar && jamMasuk >= jamKeluar) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Kesalahan!',
                        text: 'Jam masuk harus lebih awal dari jam keluar.',
                        confirmButtonText: 'OK'
                    });

                    $(this).val('');
                }
            });

            $('#formJamPresensi').on('submit', function(e) {
                e.preventDefault();
                let formData = new FormData(this);

                $.ajax({
                    url: "{{ route('jamkerja.store') }}",
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    beforeSend: function() {
                        Swal.fire({
                            title: 'Menyimpan...',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                    },
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: response.message,
                            confirmButtonText: 'OK'
                        });

                        response.data.forEach(item => {
                            $(`.jam-masuk[data-hari="${item.hari}"]`).val(item
                                .jam_masuk);
                            $(`.jam-keluar[data-hari="${item.hari}"]`).val(item
                                .jam_keluar);
                        });
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: 'Terjadi kesalahan saat menyimpan data.',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            });

            function loadJamKerja() {
                $.ajax({
                    url: "{{ route('jamkerja.data') }}",
                    type: "GET",
                    success: function(data) {
                        data.forEach(item => {
                            $(`.jam-masuk[data-hari="${item.hari}"]`).val(item.jam_masuk);
                            $(`.jam-keluar[data-hari="${item.hari}"]`).val(item.jam_keluar);
                        });
                    }
                });
            }

            loadJamKerja();
        });
    </script>
@endpush
