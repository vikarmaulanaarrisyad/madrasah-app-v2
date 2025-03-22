@extends('layouts.app')

@push('css')
    <style>
        .sortable-menu,
        .nested-sortable {
            list-style-type: none;
            padding-left: 15px;
        }

        .sortable-menu>li {
            margin-bottom: 10px;
            /* Jarak antar menu utama */
            padding: 8px;
            background: #f8f9fa;
            border-radius: 5px;
        }

        .nested-sortable>li {
            margin-bottom: 8px;
            /* Jarak antar submenu dengan parent yang sama */
            padding: 6px;
            border-radius: 4px;
            /* Opsional: menandai submenu */
        }
    </style>
@endpush

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <x-card>
                <x-slot name="header">
                    <div class="d-flex justify-content-between align-items-center">
                        <ul class="nav nav-pills">
                            <li class="nav-item">
                                <a class="nav-link active" href="#struktur-menu" data-toggle="tab">Struktur Menu</a>
                            </li>
                        </ul>

                        <button onclick="addForm(`{{ route('manage-menu.store') }}`)" class="btn btn-sm btn-primary">
                            <i class="fas fa-plus-circle"></i> Tambah Data
                        </button>
                    </div>
                </x-slot>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="tab-content">
                            <div class="tab-pane active" id="struktur-menu">
                                <ul class="sortable-menu todo-list menu-list" data-widget="todo-list">
                                    @foreach ($menus->where('menu_parent_id', 0) as $menu)
                                        <li id="{{ $menu->id }}" data-id="{{ $menu->id }}">
                                            <span class="handle ui-sortable-handle">
                                                <i class="fas fa-ellipsis-v"></i>
                                                <i class="fas fa-ellipsis-v"></i>
                                            </span>
                                            <span class="text">{{ $menu->menu_title }}</span>
                                            <div class="tools">
                                                <i onclick="editForm('{{ route('manage-menu.show', $menu->id) }}')"
                                                    class="fas fa-edit text-primary"></i>
                                                <i onclick="deleteData('{{ route('manage-menu.destroy', $menu->id) }}', '{{ $menu->menu_title }}')"
                                                    class="fas fa-trash-alt text-danger cursor-pointer"></i>

                                            </div>

                                            @if ($menus->where('menu_parent_id', $menu->id)->count() > 0)
                                                <ul class="nested-sortable">
                                                    @foreach ($menus->where('menu_parent_id', $menu->id) as $submenu)
                                                        <li id="{{ $submenu->id }}" data-id="{{ $submenu->id }}">
                                                            <span class="handle ui-sortable-handle">
                                                                <i class="fas fa-ellipsis-v"></i>
                                                                <i class="fas fa-ellipsis-v"></i>
                                                            </span>
                                                            <span class="text">{{ $submenu->menu_title }}</span>
                                                            <div class="tools">
                                                                <i onclick="editForm('{{ route('manage-menu.show', $submenu->id) }}')"
                                                                    class="fas fa-edit text-primary"></i>
                                                                <i onclick="deleteData('{{ route('manage-menu.destroy', $submenu->id) }}', '{{ $submenu->menu_title }}')"
                                                                    class="fas fa-trash-alt text-danger cursor-pointer"></i>

                                                            </div>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>

                                <button id="saveMenuOrder" class="btn btn-primary mt-3">Simpan Perubahan</button>
                            </div>
                            <div class="tab-pane" id="timeline">
                                <!-- Konten Kelola Menu -->
                            </div>
                        </div>
                    </div>
                </div>
            </x-card>
        </div>
    </div>

    @include('admin.menu.form')
@endsection

@push('scripts')
    <script src="{{ asset('AdminLTE/plugins/jquery-ui/jquery-ui.min.js') }}"></script>
    <script>
        let table;
        let modal = '#modal-form';
        let button = '#submitBtn';

        $(function() {
            $(".sortable-menu, .nested-sortable").sortable({
                handle: ".handle",
                placeholder: "ui-state-highlight",
                connectWith: ".sortable-menu, .nested-sortable", // Izinkan perpindahan antar level
            }).disableSelection();

            $("#saveMenuOrder").on("click", function() {
                let sortedData = [];

                $(".sortable-menu > li").each(function(index) {
                    let item = {
                        id: $(this).data('id'),
                        parent_id: 0, // Menu utama tidak punya parent
                        position: index + 1 // Posisi menu utama
                    };
                    sortedData.push(item);

                    // Cek submenu
                    $(this).find("> ul > li").each(function(subIndex) {
                        sortedData.push({
                            id: $(this).data('id'),
                            parent_id: $(this).closest('ul').closest('li').data(
                                'id') || 0,
                            position: subIndex + 1 // Posisi submenu
                        });
                    });
                });

                // Kirim perubahan ke backend saat tombol diklik
                $.ajax({
                    url: "{{ route('manage-menu.updateOrder') }}",
                    method: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        menu: sortedData
                    },
                    success: function(response) {
                        //alert("Struktur menu berhasil diperbarui!");
                        toastr.success(response.message, "Berhasil!", {
                            timeOut: 2000
                        });
                    },
                    error: function() {
                        //alert("Terjadi kesalahan saat menyimpan!");
                        toastr.error("Terjadi kesalahan saat menyimpan!", "Gagal!", {
                            timeOut: 2000
                        });
                    }
                });
            });
        });

        function addForm(url, title = 'Form Data Kelas') {
            $(modal).modal('show');
            $(`${modal} .modal-title`).text(title);
            $(`${modal} form`).attr('action', url);
            $(`${modal} [name=_method]`).val('post');

            resetForm(`${modal} form`);
        }

        function editForm(url, title = 'Form Data Kelas') {
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
                            window.location.reload(); // Reload DataTables
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

        function deleteData(url, menuTitle) {
            Swal.fire({
                title: "Apakah Anda yakin?",
                text: `Menu "${menuTitle}" akan dihapus secara permanen!`,
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Ya, Hapus!",
                cancelButtonText: "Batal",
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(url, {
                            method: "DELETE",
                            headers: {
                                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute(
                                    "content"),
                                "Content-Type": "application/json"
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: data.message,
                                showConfirmButton: false,
                                timer: 3000
                            }).then(() => {
                                // Refresh halaman atau hapus elemen dari tampilan
                                window.location.reload();
                            });

                        })
                        .catch(error => {
                            Swal.fire("Error!", "Terjadi kesalahan saat menghapus data.", "error");
                        });
                }
            });
        }
    </script>
@endpush
