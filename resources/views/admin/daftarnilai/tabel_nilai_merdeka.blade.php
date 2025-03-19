 @if ($siswa->isEmpty())
     <tr>
         <td colspan="10" class="text-center">Tidak ada data nilai untuk rombel ini.</td>
     </tr>
 @else
     @foreach ($siswa as $key => $item)
         <tr>
             <td>{{ $key + 1 }}</td>
             <td>{{ $item->nis }}</td>
             <td>{{ $item->nama_lengkap }}</td>
             <td>{{ $item->nilai->formatif_1 ?? '-' }}</td>
             <td>{{ $item->nilai->formatif_2 ?? '-' }}</td>
             <td>{{ $item->nilai->formatif_3 ?? '-' }}</td>
             <td>{{ $item->nilai->formatif_4 ?? '-' }}</td>
             <td>{{ $item->nilai->formatif_5 ?? '-' }}</td>
             <td>{{ $item->nilai->sumatif_tengah ?? '-' }}</td>
             <td>{{ $item->nilai->sumatif_akhir ?? '-' }}</td>
         </tr>
     @endforeach
 @endif
