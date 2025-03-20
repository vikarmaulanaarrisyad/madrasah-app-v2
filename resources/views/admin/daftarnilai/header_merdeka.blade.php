<thead>
    <tr>
        <th>No</th>
        <th>NIM</th>
        <th>Nama</th>

        {{-- Ambil sum unik dari database --}}
        @php
            $formatifSum = \App\Models\MerdekaNilaiAkhir::where('rombel_id', $rombel->id)
                ->where('mata_pelajaran_id', $mataPelajaran->id)
                ->pluck('sum')
                ->unique()
                ->sort()
                ->toArray();
        @endphp

        {{-- Generate header Formatif Bab secara otomatis --}}
        @foreach ($formatifSum as $sum)
            <th>Formatif Bab {{ $sum }}</th>
        @endforeach

        <th>Sumatif Tengah Semester</th>
        <th>Sumatif Akhir Semester</th>
    </tr>
</thead>
