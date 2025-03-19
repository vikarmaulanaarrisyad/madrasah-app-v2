@if ($siswa->isEmpty())
    <tr>
        <td colspan="8" class="text-center">Tidak ada data nilai untuk rombel ini.</td>
    </tr>
@else
    @foreach ($siswa as $key => $item)
        <tr>
            <td>{{ $key + 1 }}</td>
            <td>{{ $item->nis }}</td>
            <td>{{ $item->nama_lengkap }}</td>
            <td>{{ $item->nilai->pengetahuan ?? '-' }}</td>
            <td>{{ $item->nilai->keterampilan ?? '-' }}</td>
            <td>{{ $item->nilai->sikap ?? '-' }}</td>
            <td>{{ $item->nilai->uh ?? '-' }}</td>
            <td>{{ $item->nilai->pts ?? '-' }}</td>
        </tr>
    @endforeach
@endif
