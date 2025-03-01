@extends('layouts.app')

@section('title', 'Butir-Butir Sikap')

@section('subtitle', 'Butir-Butir Sikap')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Butir-Butir Sikap</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <x-card>
                <x-slot name="header">
                    <h3 class="card-title"><i class="fas fa-clipboard"></i> @yield('title')</h3>
                    <div class="card-tools">
                        <div class="d-flex align-items-center">
                            <div>
                                <button onclick="confirmExport()" type="button" class="btn btn-danger btn-sm"><i
                                        class="fas fa-download"></i>
                                    Download
                                </button>

                                <button onclick="confirmImport()" type="button" class="btn btn-success btn-sm"><i
                                        class="fas fa-file-excel"></i>
                                    Import
                                </button>

                                <button onclick="addForm(`{{ route('k13sikap.store') }}`)" class="btn btn-sm btn-primary">
                                    <i class="fas fa-plus-circle"></i> Tambah Data
                                </button>
                            </div>
                        </div>
                    </div>
                </x-slot>
                <x-table>
                    <x-slot name="thead">
                        <th>No</th>
                        <th>Kompetensi</th>
                        <th>Kode Butir Sikap</th>
                        <th>Butir-Butir Sikap</th>
                        <th>Aksi</th>
                    </x-slot>
                </x-table>
            </x-card>
        </div>
    </div>
    @include('admin.k13.butir_sikap.form')
    @include('admin.k13.butir_sikap.import-excel')
@endsection

@include('includes.datatables')

@push('scripts')
    <script>
        let table;
        let modal = '#modal-form';
        let importExcel = '#importExcelModal';
        let button = '#submitBtn';

        table = $('.table').DataTable({
            processing: false,
            serverSide: true,
            autoWidth: false,
            responsive: true,
            ajax: {
                url: '{{ route('k13sikap.data') }}',
            },
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'jenis_kompetensi'
                },
                {
                    data: 'kode'
                },
                {
                    data: 'butir_sikap'
                },
                {
                    data: 'aksi',
                    name: 'aksi',
                    orderable: false,
                    searchable: false
                },
            ]
        })

        function addForm(url, title = 'Butir-Butir Sikap') {
            $(modal).modal('show');
            $(`${modal} .modal-title`).text(title);
            $(`${modal} form`).attr('action', url);
            $(`${modal} [name=_method]`).val('post');

            resetForm(`${modal} form`);
        }

        function editForm(url, title = 'Butir-Butir Sikap') {
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
                    loopForm(response.data);

                    $('#mata_pelajaran_id').val(response.data.mata_pelajaran_id).trigger('change').prop('disabled',
                        true);
                    $('#kelas_id').val(response.data.kelas_id).trigger('change').prop('disabled', true);

                    $('#modal-form [name=kkm]').val(response.data.kkm).prop('disabled', false);
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

        function deleteData(url, name) {
            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                },
                buttonsStyling: true,
            });

            swalWithBootstrapButtons.fire({
                title: 'Delete Data!',
                text: 'Apakah Anda yakin ingin menghapus ' + name + ' ?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#aaa',
                confirmButtonText: 'Iya!',
                cancelButtonText: 'Batalkan',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Tampilkan Swal loading sebelum menghapus
                    Swal.fire({
                        title: 'Menghapus...',
                        text: 'Mohon tunggu sebentar',
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    $.ajax({
                        type: "DELETE",
                        url: url,
                        dataType: "json",
                        success: function(response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: response.message,
                                showConfirmButton: false,
                                timer: 3000
                            }).then(() => {
                                table.ajax.reload(); // Reload DataTables setelah penghapusan
                            });
                        },
                        error: function(xhr, status, error) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops! Gagal',
                                text: xhr.responseJSON ? xhr.responseJSON.message :
                                    'Terjadi kesalahan!',
                                showConfirmButton: true,
                            }).then(() => {
                                table.ajax.reload(); // Reload tabel jika terjadi error
                            });
                        }
                    });
                }
            });
        }

        function confirmExport() {
            Swal.fire({
                title: 'Konfirmasi',
                html: `
                <p>Apakah Anda yakin ingin mengunduh file? Pastikan Anda telah memahami risiko yang ada.</p>
                <label>
                    <input type="checkbox" id="agreeCheckbox" onchange="toggleDownload()"> Saya setuju dengan risiko yang ada
                </label>
            `,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Download',
                cancelButtonText: 'Batal',
                didOpen: () => {
                    const confirmButton = Swal.getConfirmButton();
                    confirmButton.disabled = true; // Disable tombol saat pertama kali muncul

                    document.getElementById('agreeCheckbox').addEventListener('change', function() {
                        confirmButton.disabled = !this
                            .checked; // Aktifkan tombol jika checkbox dicentang
                    });
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    exportEXCEL();
                }
            });
        }

        function exportEXCEL() {
            Swal.fire({
                title: 'Sedang Memproses...',
                text: 'Mohon tunggu sementara file sedang diproses.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            window.location.href = '{{ route('guru.exportEXCEL') }}';

            // Tutup loading setelah beberapa detik (opsional)
            setTimeout(() => {
                Swal.close();
            }, 3000);
        }

        function confirmImport() {
            $(importExcel).modal('show');
        }
    </script>
@endpush
