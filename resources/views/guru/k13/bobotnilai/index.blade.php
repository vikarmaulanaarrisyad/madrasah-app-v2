@extends('layouts.app')

@section('title', 'Setting Bobot Penilaian')
@section('subtile', 'Setting Bobot Penilaian')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Setting Bobot Penilaian</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <x-card>
                <x-slot name="header">
                    <h3 class="card-title"><i class="fas fa-list-alt"></i> @yield('title')</h3>
                </x-slot>

                <div class="table-responsive">
                    <x-table id="data-table">
                        <x-slot name="thead">
                            <tr>
                                <th rowspan="2" class="text-center" style="width: 100px;">No</th>
                                <th rowspan="2" class="text-center">Mata Pelajaran</th>
                                <th rowspan="2" class="text-center">Kelas</th>
                                <th colspan="3" class="text-center" style="width: 300px">Bobot Penilaian</th>
                                <th rowspan="2" class="text-center" style="width: 100px;">Aksi</th>
                            </tr>
                            <tr>
                                <th class="text-center" style="width: 100px;">PH</th>
                                <th class="text-center" style="width: 100px;">PTS</th>
                                <th class="text-center" style="width: 100px;">PAS</th>
                            </tr>
                        </x-slot>
                    </x-table>
                </div>
            </x-card>
        </div>
    </div>
    @include('guru.k13.bobotnilai.form')
@endsection

@include('includes.datatables')

@push('scripts')
    <script>
        let modal = '#modal-form';
        let button = '#submitBtn';
        let table = $('#data-table').DataTable({
            processing: false,
            serverSide: true,
            paging: false, // Menonaktifkan pagination (tampilkan semua data)
            searching: false, // Matikan fitur pencarian
            lengthChange: false, // Sembunyikan dropdown jumlah data per halaman
            ajax: "{{ route('bobotnilai.data') }}", // Sesuaikan dengan route yang mengarah ke fungsi data()
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false,
                    className: 'text-center'
                },
                {
                    data: 'mata_pelajaran',
                    name: 'mata_pelajaran',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'rombel',
                    name: 'rombel',
                },
                {
                    data: 'bobot_ph',
                    name: 'bobot_ph',
                    className: 'text-center',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'bobot_pts',
                    name: 'bobot_pts',
                    className: 'text-center',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'bobot_pas',
                    name: 'bobot_pas',
                    className: 'text-center',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'aksi',
                    name: 'aksi',
                    orderable: false,
                    searchable: false,
                    className: 'text-center'
                }
            ],
            columnDefs: [{
                targets: [3, 4, 5],
                className: "text-center"
            }]
        });

        function addForm(url, id, title = 'Data Bobot Nilai') {
            console.log("ID yang dikirim:", id); // Debugging ID
            $(modal).modal('show');
            $(`${modal} .modal-title`).text(title);
            $(`${modal} form`).attr('action', url);
            $(`${modal} [name=_method]`).val('post');
            resetForm(`${modal} form`);

            // Tambahkan ID ke dalam form jika diperlukan
            $('#pembelajaran_id').val(id);
        }

        function editForm(url, title = 'Data Bobot Nilai') {
            Swal.fire({
                title: "Memuat...",
                text: "Mohon tunggu sebentar...",
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => {
                    Swal.showLoading(); // Menampilkan spinner loading
                }
            });

            $.get(url)
                .done(response => {
                    Swal.close(); // Tutup loading setelah sukses
                    $(modal).modal('show');
                    $(`${modal} .modal-title`).text(title);
                    $(`${modal} form`).attr('action', url);
                    $(`${modal} [name=_method]`).val('put');

                    resetForm(`${modal} form`);

                    loopForm(response);

                    // Pastikan response.data memiliki nilai bobot_ph sebelum mengaksesnya
                    $('[name=bobot_ph]').val(response.bobot_ph ?? 0);
                    $('[name=bobot_pts]').val(response.bobot_pts ?? 0);
                    $('[name=bobot_pas]').val(response.bobot_pas ?? 0);

                })
                .fail(errors => {
                    Swal.close(); // Tutup loading jika terjadi error
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops! Gagal',
                        text: errors.responseJSON?.message || 'Terjadi kesalahan saat memuat data.',
                        showConfirmButton: true,
                    });

                    if (errors.status == 422) {
                        loopErrors(errors.responseJSON.errors);
                    }
                });
        }

        function editForm1(url) {
            $('#modal-form').modal('show'); // Menampilkan modal edit
            $('#modal-form form')[0].reset(); // Reset form sebelum memuat data
            $('#modal-form form').attr('action', url); // Atur action form ke URL edit

            $.get(url, function(data) { // Mengambil data dari server
                $('#id_field').val(data.id); // Set ID
                $('#bobot_ph').val(data.bobot_ph); // Isi nilai bobot PH
                $('#bobot_pts').val(data.bobot_pts); // Isi nilai bobot PTS
                $('#bobot_pas').val(data.bobot_pas); // Isi nilai bobot PAS
            }).fail(function() {
                alert('Tidak dapat mengambil data.');
            });
        }


        function submitForm(originalForm) {
            $(button).prop('disabled', true);

            // Menampilkan Swal loading
            Swal.fire({
                title: 'Mohon Tunggu...',
                text: 'Sedang memproses data',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading(); // Menampilkan animasi loading
                }
            });

            $.ajax({
                url: $(originalForm).attr('action'),
                type: $(originalForm).attr('method') || 'POST', // Gunakan method dari form
                data: new FormData(originalForm),
                dataType: 'JSON',
                contentType: false,
                cache: false,
                processData: false,
                success: function(response, textStatus, xhr) {
                    Swal.close(); // Tutup Swal Loading

                    if (xhr.status === 201 || xhr.status === 200) {
                        $(modal).modal('hide');

                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: response.message,
                            showConfirmButton: false,
                            timer: 3000
                        }).then(() => {
                            $(button).prop('disabled', false);
                            table.ajax.reload(); // Reload DataTables
                        });
                    }
                },
                error: function(xhr) {
                    Swal.close(); // Tutup Swal Loading
                    $(button).prop('disabled', false);

                    let errorMessage = "Terjadi kesalahan!";
                    if (xhr.responseJSON?.message) {
                        errorMessage = xhr.responseJSON.message;
                    }

                    Swal.fire({
                        icon: 'error',
                        title: 'Oops! Gagal',
                        text: errorMessage,
                        showConfirmButton: false,
                        timer: 3000,
                    });

                    if (xhr.status === 422) {
                        loopErrors(xhr.responseJSON.errors);
                    }
                }
            });
        }
    </script>
@endpush
