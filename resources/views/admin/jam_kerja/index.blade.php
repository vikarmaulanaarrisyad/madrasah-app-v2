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
                                <th>Jam Masuk (Biasa)</th>
                                <th>Jam Keluar (Biasa)</th>
                                <th>Jam Masuk (Ramadhan)</th>
                                <th>Jam Keluar (Ramadhan)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach (['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'] as $hari)
                                @php
                                    $jamNormal = $jamKerja->where('hari', $hari)->where('is_ramadhan', false)->first();
                                    $jamRamadhan = $jamKerja->where('hari', $hari)->where('is_ramadhan', true)->first();
                                @endphp
                                <tr>
                                    <td>{{ $hari }}</td>
                                    <td><input type="time" name="jam_masuk[{{ $hari }}]"
                                            data-hari="{{ $hari }}" class="form-control jam-masuk"
                                            value="{{ $jamNormal->jam_masuk ?? '' }}"></td>
                                    <td><input type="time" name="jam_keluar[{{ $hari }}]"
                                            data-hari="{{ $hari }}" class="form-control jam-keluar"
                                            value="{{ $jamNormal->jam_keluar ?? '' }}"></td>
                                    <td><input type="time" name="jam_masuk_ramadhan[{{ $hari }}]"
                                            data-hari="{{ $hari }}" class="form-control jam-masuk-ramadhan"
                                            value="{{ $jamRamadhan->jam_masuk ?? '' }}"></td>
                                    <td><input type="time" name="jam_keluar_ramadhan[{{ $hari }}]"
                                            data-hari="{{ $hari }}" class="form-control jam-keluar-ramadhan"
                                            value="{{ $jamRamadhan->jam_keluar ?? '' }}"></td>
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
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            function validateTimeInput(selector, type) {
                $(selector).on('change', function() {
                    let hari = $(this).data('hari');
                    let jamMasuk = $(`.jam-masuk[data-hari="${hari}"]`).val();
                    let jamKeluar = $(`.jam-keluar[data-hari="${hari}"]`).val();
                    let jamMasukRamadhan = $(`.jam-masuk-ramadhan[data-hari="${hari}"]`).val();
                    let jamKeluarRamadhan = $(`.jam-keluar-ramadhan[data-hari="${hari}"]`).val();

                    if (type === 'normal' && jamMasuk && jamKeluar && jamKeluar <= jamMasuk) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Jam Tidak Valid',
                            text: `Jam keluar harus lebih besar dari jam masuk untuk ${hari}.`,
                            confirmButtonText: 'OK'
                        });
                        $(`.jam-keluar[data-hari="${hari}"]`).val('');
                    }

                    if (type === 'ramadhan' && jamMasukRamadhan && jamKeluarRamadhan && jamKeluarRamadhan <=
                        jamMasukRamadhan) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Jam Tidak Valid',
                            text: `Jam keluar harus lebih besar dari jam masuk untuk ${hari} selama Ramadhan.`,
                            confirmButtonText: 'OK'
                        });
                        $(`.jam-keluar-ramadhan[data-hari="${hari}"]`).val('');
                    }
                });
            }

            validateTimeInput('.jam-masuk, .jam-keluar', 'normal');
            validateTimeInput('.jam-masuk-ramadhan, .jam-keluar-ramadhan', 'ramadhan');

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
                            if (item.is_ramadhan) {
                                $(`.jam-masuk-ramadhan[data-hari="${item.hari}"]`).val(item
                                    .jam_masuk);
                                $(`.jam-keluar-ramadhan[data-hari="${item.hari}"]`).val(item
                                    .jam_keluar);
                            } else {
                                $(`.jam-masuk[data-hari="${item.hari}"]`).val(item.jam_masuk);
                                $(`.jam-keluar[data-hari="${item.hari}"]`).val(item.jam_keluar);
                            }
                        });
                    }
                });
            }

            loadJamKerja();
        });
    </script>
@endpush
