@extends('layouts.app')

@section('title', 'Nilai Harian')
@section('subtile', 'Nilai Harian')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Nilai Harian</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <x-card>
                <x-slot name="header">
                    <h5 class="card-title mb-2">
                        Nilai Harian
                        <span class="text-muted text-sm">{{ $rombel->kelas->nama }} {{ $rombel->nama }}</span>
                        <p class="text-xs">{{ $mataPelajaran->nama }}</p>
                    </h5>
                    <div class="btn-group text-center text-md-right">
                        <a href="#" class="btn btn-danger mb-1">Kembali</a>
                        <a href="{{ route('nilaipengetahuan.create', [$rombel->id, $mataPelajaran->id]) }}"
                            class="btn btn-primary mb-1">Tambah</a>
                        <a href="#" class="btn btn-success mb-1">Upload</a>
                        <a href="#" class="btn btn-warning mb-1">Export</a>
                    </div>
                </x-slot>

                @php
                    $data = \App\Models\Rombel::find($rombel->id)?->siswa_rombel ?? collect();

                    $nilaiPH = \App\Models\NilaiHarian::where('rombel_id', $rombel->id)
                        ->where('mata_pelajaran_id', $mataPelajaran->id)
                        ->orderBy('ph')
                        ->get()
                        ->groupBy('ph');
                @endphp

                <div class="table-responsive">
                    <x-table id="nilai-harian-table" data-rombel-id="{{ $rombel->id }}"
                        data-mapel-id="{{ $mataPelajaran->id }}">
                        <x-slot name="thead">
                            <tr>
                                <th rowspan="2" class="text-center" style="width: 50px;">No</th>
                                <th rowspan="2" class="text-center">Nama Siswa</th>
                                @if ($nilaiPH->isNotEmpty())
                                    @foreach ($nilaiPH as $ph => $nilai)
                                        <th class="text-center">
                                            <div class="d-flex justify-content-between align-items-center flex-wrap">
                                                <span>PH{{ $ph }}</span>
                                                <div class="btn-group">
                                                    <button class="btn btn-xs btn-warning"><i
                                                            class="fas fa-edit"></i></button>
                                                    <button class="btn btn-xs btn-danger"
                                                        onclick="hapusNilai({{ $nilai->first()->id ?? "'Nilai'" }})">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </th>
                                    @endforeach
                                @else
                                    <th class="text-center">
                                        Nilai
                                    </th>
                                @endif

                            </tr>
                        </x-slot>

                        @foreach ($data as $index => $siswa)
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td>{{ $siswa->nama_lengkap }}</td>
                                @if ($nilaiPH->isNotEmpty())
                                    @foreach ($nilaiPH as $ph => $nilai)
                                        @php
                                            $nilaiSiswa = $nilai->where('siswa_id', $siswa->id)->first()->nilai ?? null;
                                        @endphp
                                        <td class="text-center">
                                            {{ $nilaiSiswa !== null ? $nilaiSiswa : '' }}
                                        </td>
                                    @endforeach
                                @else
                                    <td class="text-center"></td>
                                @endif
                            </tr>
                        @endforeach

                    </x-table>
                </div>
            </x-card>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function hapusNilai(id) {
            if (!id) {
                Swal.fire("Error", "ID nilai tidak valid!", "error");
                return;
            }

            Swal.fire({
                title: "Konfirmasi",
                text: "Apakah Anda yakin ingin menghapus nilai ini?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Ya, Hapus!"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "nilaipengetahuan/" + id,
                        type: "DELETE",
                        headers: {
                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                        },
                        success: function(response) {
                            Swal.fire("Berhasil", response.message, "success").then(() => {
                                location.reload();
                            });
                        },
                        error: function(xhr) {
                            Swal.fire("Error", xhr.responseJSON.message || "Terjadi kesalahan",
                                "error");
                        }
                    });
                }
            });
        }
    </script>
@endpush
