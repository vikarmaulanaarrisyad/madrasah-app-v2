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
                @if (!empty(auth()->user()->avatar) && Storage::disk('public')->exists(auth()->user()->avatar))
                    <img src="{{ Storage::url(auth()->user()->avatar) }}" alt="logo" class="img-circle elevation-2"
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
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-user-graduate"></i>
                            <p>
                                Siswa
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-users"></i>
                            <p>
                                Rombongan Belajar
                            </p>
                        </a>
                    </li>
                    <li class="nav-header">JURNAL MENGAJAR</li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-book-reader"></i>
                            <p>
                                Jurnal Guru
                            </p>
                        </a>
                    </li>
                    <li class="nav-header">KEHADIRAN</li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-calendar-check"></i>
                            <p>
                                Rekapitulasi GTK
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-chart-bar"></i>
                            <p>
                                Prosentase GTK
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-user-check"></i>
                            <p>
                                Rekapitulasi Siswa
                            </p>
                        </a>
                    </li>
                    <li class="nav-header">REPORT</li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-print"></i>
                            <p>Cetak Absensi Siswa</p>
                        </a>
                    </li>
                    <li class="nav-header">MANAGEMEN PENGGUNA</li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-users"></i>
                            <p>Managemen User</p>
                        </a>
                    </li>

                    <li class="nav-header">PENGATURAN</li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-school"></i>
                            <p>Madrasah</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-sliders-h"></i>
                            <p>Aplikasi</p>
                        </a>
                    </li>
                    <li class="nav-item mb-5">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-sign-out-alt"></i>
                            <p>Keluar</p>
                        </a>
                    </li>
                @endif
            </ul>
        </nav>
    </div>
</aside>
