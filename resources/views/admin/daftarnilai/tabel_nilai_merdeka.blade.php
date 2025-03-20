<table class="table table-striped">
    <thead class="bg-success">
        <tr>
            <th rowspan="2">No</th>
            <th rowspan="2">NIM</th>
            <th rowspan="2">Nama</th>

            {{-- Ambil daftar Formatif Bab unik dari database --}}
            @php
                $formatifSum = \App\Models\MerdekaNilaiAkhir::where('rombel_id', $rombel->id)
                    ->where('mata_pelajaran_id', $mataPelajaran->id)
                    ->pluck('sum')
                    ->unique()
                    ->sort()
                    ->toArray();
            @endphp

            {{-- Generate header Formatif Bab --}}
            @foreach ($formatifSum as $sum)
                <th rowspan="2">Formatif Bab {{ $sum }}</th>
            @endforeach

            <th rowspan="2">Sumatif Tengah Semester</th>
            <th rowspan="2">Sumatif Akhir Semester</th>
        </tr>
    </thead>
    <tbody>
        @if ($siswa->isEmpty())
            <tr>
                <td colspan="{{ 3 + count($formatifSum) + 2 }}" class="text-center">
                    Tidak ada data nilai untuk rombel dan mata pelajaran ini.
                </td>
            </tr>
        @else
            @foreach ($siswa as $key => $item)
                @php
                    // Ambil nilai siswa sesuai dengan sum yang tersedia
                    $nilaiSiswa = \App\Models\MerdekaNilaiAkhir::where('siswa_id', $item->id)
                        ->where('rombel_id', $rombel->id)
                        ->where('mata_pelajaran_id', $mataPelajaran->id)
                        ->get()
                        ->groupBy('sum');
                @endphp

                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $item->nis }}</td>
                    <td>{{ $item->nama_lengkap }}</td>

                    {{-- Tampilkan nilai Formatif berdasarkan sum yang tersedia --}}
                    @foreach ($formatifSum as $sum)
                        <td>{{ $nilaiSiswa[$sum]->first()->nilai ?? '-' }}</td>
                    @endforeach

                    <td>{{ $nilaiSiswa->first()?->first()->sumatif_tengah_semester ?? '-' }}</td>
                    <td>{{ $nilaiSiswa->first()?->first()->sumatif_akhir_semester ?? '-' }}</td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>
