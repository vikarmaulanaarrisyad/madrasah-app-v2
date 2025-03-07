@extends('layouts.app')

@section('content')
    <div class="row mt-3">
        <div class="col-lg-12">
            <x-card>
                <div class="mb-3">
                    <label for="filterRombel">Pilih Rombel:</label>
                    <select id="filterRombel" class="form-control">
                        <option value="">-- Pilih Rombel --</option>
                        @foreach ($rombels as $rombel)
                            <option value="{{ $rombel->id }}">{{ $rombel->nama }}</option>
                        @endforeach
                    </select>
                </div>

                <table class="table table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th>Mata Pelajaran</th>
                            <th>Guru</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="dataContainer">
                        <!-- Data mata pelajaran akan dimuat dengan AJAX -->
                    </tbody>
                </table>
            </x-card>
        </div>
    </div>

    <!-- Modal Pilih Guru -->
    <div class="modal fade" id="modalGuru" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Pilih Guru</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <ul id="listGuru" class="list-group">
                        <!-- Data guru akan dimuat dengan AJAX -->
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Saat rombel dipilih, muat data mata pelajaran
            $('#filterRombel').change(function() {
                let rombel_id = $(this).val();
                if (rombel_id) {
                    $.ajax({
                        url: "{{ route('pembelajaran.getMapelByRombel') }}",
                        type: "GET",
                        data: {
                            rombel_id: rombel_id
                        },
                        beforeSend: function() {
                            Swal.fire({
                                title: "Memuat data...",
                                allowOutsideClick: false,
                                didOpen: () => Swal.showLoading()
                            });
                        },
                        success: function(response) {
                            console.log(response.data.pembelajaran)
                            Swal.close(); // Tutup loading
                            let html = '';
                            response.data.forEach(mapel => {
                                html += `<tr>
                                        <td>${mapel.nama}</td>
                                        <td>
                                            <button class="btn btn-success btn-sm pilih-guru"
                                                    data-id="${mapel.id}"
                                                    data-guru="${mapel.guru ? mapel.guru.nama_lengkap : 'Belum Ditentukan'}">
                                                ${mapel.guru ? mapel.guru.nama_lengkap : 'Belum Ditentukan'}
                                            </button>
                                        </td>
                                        <td>
                                            <button class="btn btn-danger btn-sm">Hapus</button>
                                        </td>
                                    </tr>`;
                            });
                            $('#dataContainer').html(html);
                        },
                        error: function() {
                            Swal.fire({
                                icon: "error",
                                title: "Gagal memuat data!",
                                text: "Terjadi kesalahan saat mengambil data."
                            });
                        }
                    });
                } else {
                    $('#dataContainer').html('');
                }
            });

            // Tampilkan modal Pilih Guru
            $(document).on('click', '.pilih-guru', function() {
                let mapel_id = $(this).data('id');
                let button = $(this);

                $.ajax({
                    url: "{{ route('pembelajaran.getGuru') }}",
                    type: "GET",
                    beforeSend: function() {
                        Swal.fire({
                            title: "Memuat daftar guru...",
                            allowOutsideClick: false,
                            didOpen: () => Swal.showLoading()
                        });
                    },
                    success: function(response) {
                        Swal.close();
                        let html = '';
                        response.forEach(guru => {
                            html += `<li class="list-group-item pilih-guru-item" data-id="${mapel_id}" data-guru="${guru.nama_lengkap}">
                                    ${guru.nama_lengkap}
                                 </li>`;
                        });

                        $('#listGuru').html(html);
                        $('#modalGuru').modal('show');
                        $('#modalGuru').data('button', button);
                    },
                    error: function() {
                        Swal.fire({
                            icon: "error",
                            title: "Gagal memuat guru!",
                            text: "Terjadi kesalahan, silakan coba lagi."
                        });
                    }
                });
            });

            // Pilih Guru dan update tampilan
            $(document).on('click', '.pilih-guru-item', function() {
                let rombel_id = $('#filterRombel').val();
                let mapel_id = $(this).data('id');
                let guru_nama = $(this).data('guru');
                let button = $('#modalGuru').data('button');

                Swal.fire({
                    title: "Memproses...",
                    text: "Mohon tunggu sebentar",
                    allowOutsideClick: false,
                    didOpen: () => Swal.showLoading()
                });

                $.ajax({
                    url: "{{ route('pembelajaran.setGuru') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        mapel_id: mapel_id,
                        rombel_id: rombel_id,
                        guru_nama: guru_nama
                    },
                    success: function(response) {
                        Swal.close();
                        if (response.success) {
                            button.text(guru_nama);
                            Swal.fire({
                                icon: "success",
                                title: "Berhasil!",
                                text: response.message,
                                timer: 2000,
                                showConfirmButton: false
                            });
                        } else {
                            Swal.fire({
                                icon: "error",
                                title: "Gagal!",
                                text: response.message
                            });
                        }
                        $('#modalGuru').modal('hide');
                    },
                    error: function() {
                        Swal.fire({
                            icon: "error",
                            title: "Terjadi Kesalahan",
                            text: "Silakan coba lagi atau periksa koneksi Anda."
                        });
                    }
                });
            });
        });
    </script>
@endpush
