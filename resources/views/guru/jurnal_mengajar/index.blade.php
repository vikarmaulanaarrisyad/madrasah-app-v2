@extends('layouts.app')

@section('title', 'Jurnal Mengajar')

@section('subtitle', 'Jurnal Mengajar')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Jurnal</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <x-card>
                <x-slot name="header">
                    <div class="card-tools">
                        <button onclick="addForm(`{{ route('jurnalmengajar.store') }}`)" class="btn btn-sm btn-primary"><i
                                class="fas fa-plus-circle"></i> Tambah Data</button>
                    </div>
                </x-slot>

                <div class="row">
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label for="tanggal">Filter Tanggal <span class="text-danger">*</span></label>

                        </div>
                    </div>
                </div>
            </x-card>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <x-card>
                <x-table>
                    <x-slot name="thead">
                        <th>No</th>
                        <th>Mata Pelajaran</th>
                        <th>Materi</th>
                        <th>Aksi</th>
                    </x-slot>
                </x-table>
            </x-card>
        </div>
    </div>

    @include('guru.jurnal_mengajar.form')
@endsection
@include('includes.datatables')
@include('includes.datepicker')
@include('includes.select2')

@push('scripts')
    <script>
        let table;
        let modal = '#modal-form';
        let button = '#submitBtn';

        table = $('.table').DataTable({
            processing: false,
            serverSide: true,
            autoWidth: false,
            responsive: true,
            ajax: {
                url: '{{ route('jurnalmengajar.data') }}',
                data: function(d) {
                    d.tanggal = $('#tanggal').val()
                }
            },
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'mapel'
                },
                {
                    data: 'materi'
                },
                {
                    data: 'aksi',
                    name: 'aksi',
                    orderable: false,
                    searchable: false
                },
            ]
        })

        $('#tanggal').on('change', function() {
            table.ajax.reload();
        })

        function addForm(url, title = 'Jurnal Mengajar') {
            $(modal).modal('show');
            $(`${modal} .modal-title`).text(title);
            $(`${modal} form`).attr('action', url);
            $(`${modal} [name=_method]`).val('post');
            resetForm(`${modal} form`);

        }

        // Fungsi Fetch Mata Pelajaran Berdasarkan Rombel
        function fetchMataPelajaran(rombelId, selectedId = null, selectedName = null) {
            let $mataPelajaranSelect = $('#mata_pelajaran');

            return new Promise((resolve, reject) => {
                $.ajax({
                    url: `ajax/jurnalmengajar/matapelajaran/${rombelId}`, // Sesuaikan dengan endpoint
                    type: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        let selectedValue = $mataPelajaranSelect.val(); // Simpan pilihan sebelumnya

                        $mataPelajaranSelect.empty().append(
                            '<option value="">Pilih Mata Pelajaran</option>');

                        response.data.forEach(mataPelajaran => {
                            let isSelected = (selectedValue == mataPelajaran.id || selectedId ==
                                mataPelajaran.id) ? 'selected' : '';
                            $mataPelajaranSelect.append(
                                `<option value="${mataPelajaran.id}" ${isSelected}>${mataPelajaran.nama}</option>`
                            );
                        });

                        // Jika opsi belum ada, tambahkan secara manual
                        if (selectedId && !$mataPelajaranSelect.find(`option[value="${selectedId}"]`)
                            .length) {
                            let option = new Option(selectedName, selectedId, true, true);
                            $mataPelajaranSelect.append(option);
                        }

                        $mataPelajaranSelect.trigger('change'); // Refresh Select2
                        resolve(); // Resolusi sukses
                    },
                    error: function() {
                        reject(); // Resolusi gagal
                    }
                });
            });
        }

        $('#rombel_id').change(async function() {
            let rombelId = $(this).val();

            if (rombelId) {
                Swal.fire({
                    title: "Memuat...",
                    text: "Mengambil data mata pelajaran...",
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                try {
                    $('#mata_pelajaran').empty().prop('disabled', false);
                    await fetchMataPelajaran(rombelId);
                    Swal.close(); // Tutup loading setelah sukses
                } catch (error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops! Gagal',
                        text: 'Terjadi kesalahan saat mengambil data mata pelajaran.',
                        showConfirmButton: true,
                    });
                }
            } else {
                $('#mata_pelajaran').empty().prop('disabled', true);
            }
        });


        async function editForm(url, title = 'Jurnal Mengajar') {
            Swal.fire({
                title: "Memuat...",
                text: "Mohon tunggu sebentar...",
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            try {
                let response = await $.get(url);
                Swal.close();

                $(modal).modal('show');
                $(`${modal} .modal-title`).text(title);
                $(`${modal} form`).attr('action', url);
                $(`${modal} [name=_method]`).val('put');
                resetForm(`${modal} form`);
                loopForm(response.data);

                let rombelId = response.data.rombel_id || '';
                let mataPelajaranId = response.data.mata_pelajaran?.id || '';
                let mataPelajaranNama = response.data.mata_pelajaran?.nama || '';

                let $rombelSelect = $('#rombel_id');

                // Hapus event change sementara agar tidak memicu fetch ulang
                $rombelSelect.off('change');

                // Set nilai rombel_id tanpa memicu event change
                $rombelSelect.val(rombelId).trigger('change.select2');

                // Panggil fetchMataPelajaran dengan data yang sudah ada
                await fetchMataPelajaran(rombelId, mataPelajaranId, mataPelajaranNama);

                // Tambahkan kembali event change setelah perubahan selesai
                setTimeout(() => {
                    $rombelSelect.on('change', async function() {
                        let rombelId = $(this).val();
                        if (rombelId) {
                            await fetchMataPelajaran(rombelId);
                        }
                    });
                }, 1000);
            } catch (errors) {
                Swal.close();
                Swal.fire({
                    icon: 'error',
                    title: 'Oops! Gagal',
                    text: errors.responseJSON?.message || 'Terjadi kesalahan saat memuat data.',
                    showConfirmButton: true,
                });

                if (errors.status == 422) {
                    loopErrors(errors.responseJSON.errors);
                }
            }
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

        $('#checkAll').on('change', function() {
            $('tbody input:checkbox').prop('checked', this.checked);
        });

        function deleteMultiple() {
            let ids = [];

            // Ambil semua checkbox yang dicentang
            $('.table tbody input:checkbox:checked').each(function() {
                ids.push($(this).val()); // Mengambil nilai dari checkbox (ID data)
            });

            if (ids.length === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Tidak Ada Data Terpilih',
                    text: 'Silakan pilih minimal satu data untuk dihapus!',
                    showConfirmButton: true,
                });
                return;
            }

            // Tampilkan konfirmasi dengan SweetAlert
            Swal.fire({
                title: 'Hapus Data Terpilih?',
                text: `Anda yakin ingin menghapus ${ids.length} data yang dipilih?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
            }).then((result) => {
                if (result.isConfirmed) {
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
                        url: '{{ route('harilibur.destroyMultiple') }}', // Pastikan route ini tersedia di backend
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            ids: ids
                        },
                        success: function(response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: response.message,
                                showConfirmButton: false,
                                timer: 3000
                            }).then(() => {
                                table.ajax.reload(); // Reload tabel setelah berhasil
                                $('#checkAll').prop('checked', false)
                            });
                        },
                        error: function(xhr) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops! Gagal',
                                text: xhr.responseJSON?.message || 'Terjadi kesalahan!',
                                showConfirmButton: true,
                            });
                        }
                    });
                }
            });
        }
    </script>
@endpush
