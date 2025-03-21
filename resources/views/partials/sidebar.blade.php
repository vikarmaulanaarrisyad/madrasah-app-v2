<aside class="main-sidebar elevation-4 sidebar-light-success">
    <!-- Brand Logo -->
    <a href="{{ url('/') }}" class="brand-link bg-success">
        @if ($sekolah->logo != null)
            <img src="{{ Storage::url($sekolah->logo) ?? '' }}" alt="Logo"
                class="brand-image img-circle elevation-3 bg-light" style="opacity: .8">
            <span class="brand-text font-weight-light">{{ $aplikasi->singkatan }}</span>
        @else
            <img src="{{ asset('images/logo-madrasah1.png') }}" alt="Logo"
                class="brand-image img-circle elevation-3 bg-light" style="opacity: .8">
            <span class="brand-text font-weight-light">{{ $aplikasi->singkatan }}</span>
        @endif

    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                @if (!empty(auth()->user()->foto) && Storage::disk('public')->exists(auth()->user()->foto))
                    <img src="{{ Storage::url(auth()->user()->foto) }}" alt="logo" class="img-circle elevation-2"
                        style="width: 35px; height: 35px;">
                @else
                    <img src="{{ asset('AdminLTE/dist/img/user1-128x128.jpg') }}" alt="logo"
                        class="img-circle elevation-2" style="width: 35px; height: 35px;">
                @endif
            </div>
            <div class="info">
                <a href="{{ route('profile.show') }}" class="d-block" data-toggle="tooltip" data-placement="top"
                    title="Edit Profil">
                    {{ auth()->user()->name }}
                    <i class="fas fa-pencil-alt ml-2 text-sm text-primary"></i>
                </a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}" class="nav-link">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Dashboard
                        </p>
                    </a>
                </li>
                @if (Auth::user()->hasRole('admin'))
                    <li class="nav-header">MASTER DATA</li>
                    <li class="nav-item">
                        <a href="{{ route('tahunpelajaran.index') }}" class="nav-link">
                            <i class="nav-icon fas fa-calendar-alt"></i>
                            <p>
                                Tahun Pelajaran
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('jamkerja.index') }}" class="nav-link">
                            <i class="nav-icon fas fa-clock"></i>
                            <p>Jam Kerja</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('harilibur.index') }}" class="nav-link">
                            <i class="nav-icon fas fa-clock"></i>
                            <p>Setting Hari Libur</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('kurikulum.index') }}" class="nav-link">
                            <i class="nav-icon fas fa-book-open"></i>
                            <p>Kurikulum</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('matapelajaran.index') }}" class="nav-link">
                            <i class="nav-icon fas fa-book"></i>
                            <p>Mata Pelajaran</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('guru.index') }}" class="nav-link">
                            <i class="nav-icon fas fa-user-friends"></i>
                            <p>
                                GTK
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('kelas.index') }}" class="nav-link">
                            <i class="nav-icon fab fa-instalod"></i>
                            <p>
                                Kelas
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('siswa.index') }}" class="nav-link">
                            <i class="nav-icon fas fa-user-graduate"></i>
                            <p>
                                Siswa
                            </p>
                        </a>
                    </li>

                    @php
                        // Ambil tahun pelajaran aktif
                        $tahunPelajaranAktif = \App\Models\TahunPelajaran::aktif()->first();

                        // Ambil tahun sebelumnya yang memiliki semester "Genap"
                        $tahunSebelumnya = $tahunPelajaranAktif
                            ? \App\Models\TahunPelajaran::where('id', '<', $tahunPelajaranAktif->id)
                                ->whereHas('semester', function ($query) {
                                    $query->where('nama', 'Genap');
                                })
                                ->orderBy('id', 'desc')
                                ->first()
                            : null;
                    @endphp
                    @if ($tahunSebelumnya)
                        <li class="nav-item">
                            <a href="{{ route('kenaikan-siswa.index') }}" class="nav-link">
                                <i class="nav-icon fas fa-graduation-cap"></i>
                                <p>Proses Kenaikan</p>
                            </a>
                        </li>
                    @endif
                    <li class="nav-item">
                        <a href="{{ route('rombel.index') }}" class="nav-link">
                            <i class="nav-icon fas fa-users"></i>
                            <p>
                                Rombongan Belajar
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('pembelajaran.index') }}" class="nav-link">
                            <i class="nav-icon fas fa-book"></i>
                            <p>
                                Pembelajaran
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('jampelajaran.index') }}" class="nav-link">
                            <i class="nav-icon fas fa-clock"></i>
                            <p>
                                Jam Pelajaran
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('jadwalpelajaran.index') }}" class="nav-link">
                            <i class="nav-icon fas fa-calendar"></i>
                            <p>
                                Jadwal Pelajaran
                            </p>
                        </a>
                    </li>
                    <li class="nav-header">SETTING RAPORT</li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-cogs"></i>
                            <p>
                                Setting Raport
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('k13kkm.index') }}" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>KKM Mapel</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('k13interval.index') }}" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Interval Predikat</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('k13sikap.index') }}" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Butir Sikap</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('k13kd.index') }}" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Data Kompetensi Dasar</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('k13tglraport.index') }}" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Input Tanggal Raport</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    {{--  <li class="nav-header">HASIL RAPORT</li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-cogs"></i>
                            <p>
                                Hasil Penilaian
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('k13statuspenilaian.index') }}" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Status Penilaian</p>
                                </a>
                            </li>
                        </ul>
                    </li>  --}}

                    <li class="nav-header">REKAP PRESENSI</li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-fingerprint"></i>
                            <p>
                                Rekapitulasi Presensi
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('presensi.siswa.index') }}" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Siswa</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('presensi.guru.index') }}" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Guru</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-header">JURNAL MENGAJAR</li>
                    <li class="nav-item">
                        <a href="{{ route('jurnal.index') }}" class="nav-link">
                            <i class="nav-icon fas fa-book-reader"></i>
                            <p>
                                Jurnal Guru
                            </p>
                        </a>
                    </li>
                    <li class="nav-header">REPORT</li>
                    <li class="nav-item">
                        <a href="{{ route('bukuinduk.index') }}" class="nav-link">
                            <i class="nav-icon fas fa-print"></i>
                            <p>Cetak Buku Induk Siswa</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('cetakdaftarnilai.index') }}" class="nav-link">
                            <i class="nav-icon fas fa-print"></i>
                            <p>Cetak Daftar Nilai Siswa</p>
                        </a>
                    </li>

                    <li class="nav-header">MANAJEMEN WEBSITE</li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-globe-asia"></i>
                            <p>
                                Website
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview" style="display: none;">
                            <li class="nav-item">
                                <a href="{{ route('manage-menu.index') }}" class="nav-link">
                                    <i class="nav-icon fas fa-list-alt"></i>
                                    <p>Menu</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('pages.index') }}" class="nav-link">
                                    <i class="nav-icon fas fa-globe"></i>
                                    <p>Kelola Halaman Menu</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('kategori.index') }}" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Kategori</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('artikel.index') }}" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Artikel</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('event.index') }}" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Event</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('platform.index') }}" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Best Platform</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('fasilitas.index') }}" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Fasilitas</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('ppdb.index') }}" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>PPDB</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('album.index') }}" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Album</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('prestasi.index') }}" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Prestasi Madrasah</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-header">MANAGEMEN PENGGUNA</li>
                    <li class="nav-item">
                        <a href="{{ route('users.index') }}" class="nav-link">
                            <i class="nav-icon fas fa-users"></i>
                            <p>Managemen User</p>
                        </a>
                    </li>

                    <li class="nav-header">PENGATURAN</li>
                    <li class="nav-item">
                        <a href="{{ route('sekolah.index') }}" class="nav-link">
                            <i class="nav-icon fas fa-school"></i>
                            <p>Madrasah</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('aplikasi.index') }}" class="nav-link">
                            <i class="nav-icon fas fa-sliders-h"></i>
                            <p>Aplikasi</p>
                        </a>
                    </li>
                    <li class="nav-item mb-5">
                        <a href="#" class="nav-link" onclick="document.querySelector('#form-logout').submit()">
                            <i class="nav-icon fas fa-sign-out-alt"></i>
                            <p>Keluar</p>

                            <form action="{{ route('logout') }}" method="post" id="form-logout">
                                @csrf
                            </form>
                        </a>
                    </li>
                @endif

                @if (Auth::user()->hasRole('guru'))
                    <li class="nav-header">ADMINISTRASI GURU</li>
                    @php
                        // Ambil data guru berdasarkan user yang sedang login
                        $guru = \App\Models\Guru::where('user_id', Auth::id())->first();

                        // Periksa apakah guru ada di tabel Rombel
                        $adaDiRombel = $guru ? \App\Models\Rombel::where('wali_kelas_id', $guru->id)->exists() : false;
                    @endphp

                    @if ($adaDiRombel)
                        <li class="nav-item">
                            <a href="{{ route('presensisiswa.index') }}" class="nav-link">
                                <i class="nav-icon fas fa-fingerprint"></i>
                                <p>Presensi Siswa</p>
                            </a>
                        </li>
                    @endif

                    <li class="nav-item">
                        <a href="{{ route('presensigtk.index') }}" class="nav-link">
                            <i class="nav-icon fas fa-fingerprint"></i>
                            <p>
                                Presensi Gtk
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('jurnalmengajar.index') }}" class="nav-link">
                            <i class="nav-icon fas fa-book-reader"></i>
                            <p>
                                Jurnal Mengajar
                            </p>
                        </a>
                    </li>
                    <li class="nav-header">RAPORT</li>
                    <li class="nav-item">
                        <a href="{{ route('bobotnilai.index') }}" class="nav-link">
                            <i class="fas fa-check-circle nav-icon"></i>
                            <p>
                                Bobot PH PTS dan PAS
                            </p>
                        </a>
                    </li>
                    {{--  <li class="nav-item">
                        <a href="{{ route('rencanapengetahuan.index') }}" class="nav-link">
                            <i class="fas fa-check-circle nav-icon"></i>
                            <p>
                                Nilai Pengetahuan
                            </p>
                        </a>
                    </li>  --}}
                    {{--  <li class="nav-item">
                        <a href="{{ route('nilaipengetahuan.index') }}" class="nav-link">
                            <i class="fas fa-check-circle nav-icon"></i>
                            <p>
                                Nilai Pengetahuan
                            </p>
                        </a>
                    </li>  --}}


                    <li class="nav-header">PENILAIAN ANDA</li>
                    @php
                        $tapel = \App\Models\TahunPelajaran::aktif()->first();
                        $guru = \App\Models\Guru::where('user_id', Auth::id())->first();
                        $rombelId = \App\Models\Rombel::where('tahun_pelajaran_id', $tapel->id)->pluck('id');

                        $pembelajarans = \App\Models\Pembelajaran::where('guru_id', $guru->id)
                            ->whereIn('rombel_id', $rombelId)
                            ->where('status', 1)
                            ->orderBy('mata_pelajaran_id', 'ASC')
                            ->orderBy('rombel_id', 'ASC')
                            ->get()
                            ->groupBy(function ($item) {
                                return $item->mata_pelajaran->nama; // Grouping berdasarkan nama mata pelajaran
                            });
                    @endphp

                    @foreach ($pembelajarans as $mataPelajaranNama => $pembelajaranGroup)
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-book"></i>
                                <p>
                                    {{ $mataPelajaranNama }} <!-- Nama Mata Pelajaran -->
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview" style="display: none;">
                                @php
                                    // Ambil kelas unik dan urutkan secara numerik
                                    $kelasUnik = $pembelajaranGroup
                                        ->map(function ($p) {
                                            return [
                                                'id' => $p->rombel->id, // ID rombel untuk route
                                                'mata_pelajaran_id' => $p->mata_pelajaran_id, // ID mata pelajaran agar unik
                                                'nama' => $p->rombel->kelas->nama . ' ' . $p->rombel->nama,
                                                'kurikulum' => $p->rombel->kurikulum->nama ?? 'Tidak Ada Kurikulum',
                                                'urutan' => (int) filter_var(
                                                    $p->rombel->kelas->nama,
                                                    FILTER_SANITIZE_NUMBER_INT,
                                                ), // Ambil angka dari nama kelas
                                            ];
                                        })
                                        ->unique(function ($item) {
                                            return $item['id'] . '-' . $item['mata_pelajaran_id']; // Gabungkan ID rombel dan mata pelajaran agar unik
                                        })
                                        ->sortBy('urutan'); // Urutkan berdasarkan angka dalam nama kelas
                                @endphp

                                {{--  @foreach ($kelasUnik as $kelas)
                                    @php
                                        // Cek apakah nilai sudah dikirim berdasarkan rombel dan mata pelajaran
                                        $nilaiTerkirim = \App\Models\NilaiHarian::where('rombel_id', $kelas['id'])
                                            ->where('mata_pelajaran_id', $kelas['mata_pelajaran_id'])
                                            ->where('status', 'terkirim') // Misalkan status 1 berarti sudah dikirim
                                            ->exists();
                                    @endphp
                                    <li class="nav-item">
                                        <a href="{{ route('nilaipengetahuan.index', ['rombel_id' => $kelas['id'], 'mata_pelajaran_id' => $kelas['mata_pelajaran_id']]) }}"
                                            class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>{{ $kelas['nama'] }}

                                                @if ($nilaiTerkirim)
                                                    <i class="fas fa-check-circle text-success"></i>
                                                    <!-- Centang jika sudah dikirim -->
                                                @endif
                                            </p>
                                        </a>
                                    </li>
                                @endforeach  --}}

                                @foreach ($kelasUnik as $kelas)
                                    @php
                                        // Ambil informasi rombel dan kurikulum dalam satu query
                                        $rombel = \App\Models\Rombel::with('kurikulum')->find($kelas['id']);
                                        $kurikulum = $rombel->kurikulum->nama ?? 'Lainnya';

                                        // Tentukan route berdasarkan kurikulum
                                        $routeName =
                                            $kurikulum == 'Kurikulum Merdeka'
                                                ? 'nilaiformatif.index'
                                                : 'nilaipengetahuan.index';

                                        // Cek apakah nilai sudah dikirim berdasarkan kurikulum
                                        if ($kurikulum == 'Kurikulum Merdeka') {
                                            $nilaiTerkirim = \App\Models\MerdekaNilaiFormatif::where([
                                                ['rombel_id', $kelas['id']],
                                                ['mata_pelajaran_id', $kelas['mata_pelajaran_id']],
                                                ['status', 'terkirim'],
                                            ])->exists();
                                        } else {
                                            $nilaiTerkirim = \App\Models\NilaiHarian::where([
                                                ['rombel_id', $kelas['id']],
                                                ['mata_pelajaran_id', $kelas['mata_pelajaran_id']],
                                                ['status', 'terkirim'],
                                            ])->exists();
                                        }
                                    @endphp

                                    <li class="nav-item">
                                        <a href="{{ route($routeName, ['rombel_id' => $kelas['id'], 'mata_pelajaran_id' => $kelas['mata_pelajaran_id']]) }}"
                                            class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>
                                                {{ $kelas['nama'] }} ({{ $kurikulum }})
                                                @if ($nilaiTerkirim)
                                                    <i class="fas fa-check-circle text-success"></i>
                                                    <!-- Tanda centang jika sudah dikirim -->
                                                @endif
                                            </p>
                                        </a>
                                    </li>
                                @endforeach


                            </ul>
                        </li>
                    @endforeach

                    <li class="nav-item">
                        <a href="{{ route('nilaiptspas.index') }}" class="nav-link">
                            <i class="fas fa-check-circle nav-icon"></i>
                            <p>
                                Nilai PTS dan PAS
                            </p>
                        </a>
                    </li>
                @endif
            </ul>
        </nav>
    </div>
</aside>
