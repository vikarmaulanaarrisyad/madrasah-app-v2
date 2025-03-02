@extends('layouts.app')

@section('title', 'Data Kompetensi Dasar')

@section('subtitle', 'Data Kompetensi Dasar')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Data Kompetensi Dasar</li>
@endsection


@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-clipboard-list"></i> @yield('title')</h3>
                </div>

                <div class="card-body">
                    <div class="callout callout-info">
                        <form action="{{ route('k13kd.create') }}" method="GET">
                            @csrf
                            <div class="form-group row">
                                <label for="mapel_id" class="col-sm-2 col-form-label">Mata Pelajaran</label>
                                <div class="col-sm-4">
                                    <select id="mapel_id" class="form-control select2" name="mapel_id" style="width: 100%;"
                                        required>
                                        <option value="" disabled>-- Pilih Mapel --</option>
                                    </select>
                                </div>
                                <label for="tingkatan_kelas" class="col-sm-2 col-form-label">Tingkatan Kelas</label>
                                <div class="col-sm-4">
                                    <select id="kelas_id" class="form-control" name="tingkatan_kelas" style="width: 100%;"
                                        required>
                                        <option value="" disabled>-- Pilih Tingkatan Kelas --</option>
                                    </select>
                                </div>
                            </div>

                        </form>
                    </div>

                    <form id="dynamic_form" action="{{ route('k13kd.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="mapel_id">
                        <input type="hidden" name="tingkatan_kelas">
                        <input type="hidden" name="semester" value="{{ $tapel->semester->id }}">

                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th style="width: 250px;">Jenis Kompetensi</th>
                                        <th style="width: 100px;">Kode KD</th>
                                        <th>Kompetensi Dasar</th>
                                        <th>Ringkasan Kompetensi</th>
                                        <th style="width: 40px;">Baris</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!--  -->
                                </tbody>
                            </table>
                        </div>
                </div>

                <div class="card-footer clearfix">
                    <button type="submit" id="btn-submit" class="btn btn-primary float-right">Simpan</button>

                    <a href="{{ route('k13kd.index') }}" class="btn btn-default float-right mr-2">Batal</a>
                </div>
                </form>
            </div>
            <!-- /.card -->
        </div>

    </div>
    <!-- /.row -->
@endsection

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function() {

            var count = 1;

            dynamic_field(count);

            function dynamic_field(number) {
                html = '<tr>';
                html += `<td>
                  <select class="form-control" name="jenis_kompetensi[]" style="width: 100%;" required oninvalid="this.setCustomValidity('silakan pilih item dalam daftar')" oninput="setCustomValidity('')">
                    <option value="">-- Pilih Kompetensi -- </option>
                    <option value="1">Sikap Spiritual</option>
                    <option value="2">Sikap Sosial</option>
                    <option value="3">Pengetahuan</option>
                    <option value="4">Keterampilan</option>
                  </select>
              </td>`;
                html += `<td>
                  <input type="text" class="form-control" name="kode_kd[]" required oninvalid="this.setCustomValidity('data tidak boleh kosong')" oninput="setCustomValidity('')">
              </td>`;
                html += `<td>
                  <textarea class="form-control" name="kompetensi_dasar[]" rows="2" required oninvalid="this.setCustomValidity('data tidak boleh kosong')" oninput="setCustomValidity('')"></textarea>
              </td>`;
                html += `<td>
                  <textarea class="form-control" name="ringkasan_kompetensi[]" rows="2" required oninvalid="this.setCustomValidity('data tidak boleh kosong')" oninput="setCustomValidity('')"></textarea>
              </td>`;

                if (number > 1) {
                    html +=
                        '<td><button type="button" name="remove" class="btn btn-danger shadow btn-xs sharp remove"><i class="fa fa-trash"></i></button></td></tr>';
                    $('tbody').append(html);
                } else {
                    html +=
                        '<td><button type="button" name="add" id="add" class="btn btn-primary shadow btn-xs sharp"><i class="fa fa-plus"></i></button></td></tr>';
                    $('tbody').html(html);
                }
            }

            $(document).on('click', '#add', function() {
                count++;
                dynamic_field(count);
            });

            $(document).on('click', '.remove', function() {
                count--;
                $(this).closest("tr").remove();
            });

        });
    </script>
    <script>
        $(document).ready(function() {
            let kelasSelect = $('#kelas_id');
            let mataPelajaranSelect = $('#mapel_id');

            // Disable mata pelajaran saat halaman dimuat
            mataPelajaranSelect.prop('disabled', true);

            // Load daftar kelas saat halaman dimuat
            $.ajax({
                url: '{{ route('kelas.get') }}',
                type: 'GET',
                success: function(data) {
                    kelasSelect.empty().append('<option value="">-- Pilih Kelas --</option>');
                    data.forEach(kelas => {
                        kelasSelect.append(
                            `<option value="${kelas.id}">${kelas.nama}</option>`);
                    });
                }
            });

            // Saat kelas dipilih, load mata pelajaran berdasarkan kelas
            kelasSelect.change(function() {
                let kelasId = $(this).val();

                // Disable mata pelajaran kembali saat mengganti kelas
                mataPelajaranSelect.prop('disabled', true).empty().append(
                    '<option value="">-- Pilih Mata Pelajaran --</option>'
                );

                if (kelasId) {
                    $.ajax({
                        url: `{{ route('matapelajaran.get', '') }}/${kelasId}`,
                        type: 'GET',
                        success: function(data) {
                            mataPelajaranSelect.empty().append(
                                '<option value="">-- Pilih Mata Pelajaran --</option>'
                            );
                            data.forEach(mapel => {
                                mataPelajaranSelect.append(
                                    `<option value="${mapel.id}">${mapel.nama}</option>`
                                );
                            });

                            // Enable kembali setelah data tersedia
                            mataPelajaranSelect.prop('disabled', false);
                        }
                    });
                }
            });

            // Saat Mata Pelajaran dipilih, isi value ke dalam input hidden
            $('#mapel_id').change(function() {
                $('input[name="mapel_id"]').val($(this).val());
            });

            // Saat Tingkatan Kelas dipilih, isi value ke dalam input hidden
            $('#kelas_id').change(function() {
                $('input[name="tingkatan_kelas"]').val($(this).val());
            });

            $('#dynamic_form').submit(function(e) {
                e.preventDefault(); // Mencegah form submit default

                Swal.fire({
                    title: 'Mohon Tunggu',
                    text: 'Sedang menyimpan data...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                let formData = $(this).serialize(); // Ambil data form
                let actionUrl = $(this).attr('action');

                $.ajax({
                    url: actionUrl,
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: 'Data telah disimpan.',
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            window.location.href = "{{ route('k13kd.index') }}";
                        });
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: 'Terjadi kesalahan, silakan coba lagi.',
                        });
                    }
                });
            });
        });
    </script>
@endpush
