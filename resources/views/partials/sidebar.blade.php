<aside class="main-sidebar elevation-4 sidebar-light-primary">
    <!-- Brand Logo -->
    <a href="{{ url('/') }}" class="brand-link bg-primary">
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
                    <li class="nav-header">HASIL RAPORT</li>
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
                    </li>

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
                    <li class="nav-header">PRESENSI</li>
                    <li class="nav-item">
                        <a href="{{ route('presensisiswa.index') }}" class="nav-link">
                            <i class="nav-icon fas fa-fingerprint"></i>
                            <p>
                                Presensi Siswa
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('presensigtk.index') }}" class="nav-link">
                            <i class="nav-icon fas fa-fingerprint"></i>
                            <p>
                                Presensi Gtk
                            </p>
                        </a>
                    </li>
                @endif
            </ul>
        </nav>
    </div>
</aside>
