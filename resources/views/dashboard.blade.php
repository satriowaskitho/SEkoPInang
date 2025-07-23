<x-app-layout>
    {{-- <x-slot name="header">
        Dashboard
    </x-slot> --}}

    <div class="relative z-10 py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <!-- Welcome Card -->
            <div
                class="relative z-0 mb-8 overflow-hidden border shadow-xl bg-gradient-to-br from-white/95 to-cream-yellow/30 backdrop-blur-sm rounded-2xl border-primary-orange/20">
                <div class="p-8">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <div
                                class="flex items-center justify-center w-16 h-16 shadow-lg bg-gradient-to-br from-primary-orange to-bright-orange rounded-xl">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                            </div>
                            <div>
                                <h1 class="text-3xl font-bold font-poppins text-dark-brown">Selamat Datang di SEkoPInang
                                </h1>
                                <p class="mt-1 font-medium text-medium-brown">Harmonisasi Jelang SE: Data Kedai Kopi di
                                    Tanjungpinang</p>
                                <p class="mt-2 text-sm text-medium-brown/80">Halo, <span
                                        class="font-semibold text-dark-brown">{{ Auth::user()->name }}</span>! Kelola
                                    data kedai kopi dengan mudah.</p>
                            </div>
                        </div>
                        <div class="hidden lg:block">
                            <img src="{{ asset('images/logo.png') }}" alt="Logo"
                                class="object-contain w-20 h-20 opacity-80">
                        </div>
                    </div>
                </div>
                <div class="h-2 bg-gradient-to-r from-primary-orange via-bright-orange to-cream-yellow"></div>
            </div>

            @php
                $stats = app('App\Http\Controllers\KedaiKopiController')->getStatistics();
                $totalKedai = $stats['total_kedai'];
                $totalPekerja = $stats['total_pekerja'];
                $avgOmzet = $stats['avg_omzet'];
                $kedaiWithLocation = $stats['kedai_with_location'];
                $kedaiByKecamatan = $stats['kedai_by_kecamatan'];
                $kecamatanNames = [
                    '010' => 'Bukit Bestari',
                    '020' => 'Tanjungpinang Timur',
                    '030' => 'Tanjungpinang Kota',
                    '040' => 'Tanjungpinang Barat',
                ];
                $progressVerifikasi =
                    $kedaiWithLocation > 0 && $totalKedai > 0 ? ($kedaiWithLocation / $totalKedai) * 100 : 0;
                $tanpaLokasi = $totalKedai - $kedaiWithLocation;

                // Ambil parameter filter dan sort dari request
                $search = request('search', '');
                $kecamatan = request('kecamatan', '');
                $kelurahan = request('kelurahan', '');
                $lokasi = request('lokasi', '');
                $sortBy = request('sort', 'created_at');
                $sortDir = request('direction', 'desc');
                $currentPage = request('page', 1);
                $perPage = 25;

                // Query dengan filter dan sort ke SEMUA data
                $query = App\Models\KedaiKopi::query();

                // Apply filters
                if ($search) {
                    $query->where(function ($q) use ($search) {
                        $q->where('nama_kedai', 'like', "%{$search}%")->orWhere('nama_pemilik', 'like', "%{$search}%");
                    });
                }

                if ($kecamatan) {
                    $query->where('kode_kecamatan', $kecamatan);
                }

                if ($kelurahan) {
                    $query->where('kode_kelurahan', $kelurahan);
                }

                if ($lokasi !== '') {
                    if ($lokasi == '1') {
                        $query->whereNotNull('latitude')->whereNotNull('longitude');
                    } else {
                        $query->where(function ($q) {
                            $q->whereNull('latitude')->orWhereNull('longitude');
                        });
                    }
                }

                // Apply sorting ke semua data
                $validSorts = ['nama_kedai', 'nama_pemilik', 'omzet', 'jumlah_pekerja', 'stan_sewa', 'created_at'];
                if (in_array($sortBy, $validSorts)) {
                    $query->orderBy($sortBy, $sortDir);
                }

                // Get all filtered data
                $allFilteredData = $query->get();
                $totalFiltered = $allFilteredData->count();

                // Manual pagination dari collection
                $offset = ($currentPage - 1) * $perPage;
                $currentPageData = $allFilteredData->slice($offset, $perPage);

                // Calculate pagination info
                $totalPages = ceil($totalFiltered / $perPage);
                $hasPages = $totalPages > 1;
            @endphp

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 gap-6 mb-8 md:grid-cols-2 lg:grid-cols-4">
                <div
                    class="overflow-hidden transition-all duration-300 transform border shadow-lg bg-white/90 backdrop-blur-sm rounded-xl border-primary-orange/20 hover:shadow-xl hover:-translate-y-1">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="flex items-center justify-center w-12 h-12 rounded-lg bg-primary-orange/10">
                                    <svg class="w-6 h-6 text-primary-orange" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                        </path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <dt class="text-sm font-medium text-medium-brown">Total Kedai Kopi</dt>
                                <dd class="text-2xl font-bold text-dark-brown">{{ number_format($totalKedai) }}</dd>
                            </div>
                        </div>
                    </div>
                </div>

                <div
                    class="overflow-hidden transition-all duration-300 transform border shadow-lg bg-white/90 backdrop-blur-sm rounded-xl border-bright-orange/20 hover:shadow-xl hover:-translate-y-1">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="flex items-center justify-center w-12 h-12 rounded-lg bg-bright-orange/10">
                                    <svg class="w-6 h-6 text-bright-orange" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                        </path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <dt class="text-sm font-medium text-medium-brown">Data Berlokasi</dt>
                                <dd class="text-2xl font-bold text-dark-brown">{{ number_format($kedaiWithLocation) }}
                                </dd>
                            </div>
                        </div>
                    </div>
                </div>

                <div
                    class="overflow-hidden transition-all duration-300 transform border shadow-lg bg-white/90 backdrop-blur-sm rounded-xl border-cream-yellow/40 hover:shadow-xl hover:-translate-y-1">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="flex items-center justify-center w-12 h-12 rounded-lg bg-cream-yellow/20">
                                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                        </path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <dt class="text-sm font-medium text-medium-brown">Total Pekerja</dt>
                                <dd class="text-2xl font-bold text-dark-brown">{{ number_format($totalPekerja) }}</dd>
                            </div>
                        </div>
                    </div>
                </div>

                <div
                    class="overflow-hidden transition-all duration-300 transform border shadow-lg bg-white/90 backdrop-blur-sm rounded-xl border-light-brown/30 hover:shadow-xl hover:-translate-y-1">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="flex items-center justify-center w-12 h-12 rounded-lg bg-light-brown/10">
                                    <svg class="w-6 h-6 text-light-brown" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                        </path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <dt class="text-sm font-medium text-medium-brown">Rata-rata Omzet</dt>
                                <dd class="text-lg font-bold text-dark-brown">
                                    {{ $avgOmzet > 0 ? 'Rp ' . number_format($avgOmzet, 0, ',', '.') : 'Rp 0' }}</dd>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Distribusi per Kecamatan -->
            <div
                class="mb-8 overflow-hidden border shadow-lg bg-white/90 backdrop-blur-sm rounded-xl border-primary-orange/20">
                <div class="p-6">
                    <h3 class="flex items-center mb-4 text-lg font-semibold font-poppins text-dark-brown">
                        <svg class="w-5 h-5 mr-2 text-primary-orange" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                            </path>
                        </svg>
                        Distribusi Data per Kecamatan
                    </h3>
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-4">
                        @foreach ($kedaiByKecamatan as $data)
                            @php
                                $kecamatanName = $kecamatanNames[$data->kode_kecamatan] ?? 'Unknown';
                                $percentage = $totalKedai > 0 ? ($data->total / $totalKedai) * 100 : 0;
                            @endphp
                            <div class="p-4 text-center rounded-lg bg-cream-yellow/10">
                                <div class="mb-1 text-2xl font-bold text-dark-brown">{{ $data->total }}</div>
                                <div class="mb-2 text-sm font-medium text-medium-brown">{{ $kecamatanName }}</div>
                                <div class="w-full h-2 rounded-full bg-cream-yellow/20">
                                    <div class="h-2 transition-all duration-300 rounded-full bg-gradient-to-r from-primary-orange to-bright-orange"
                                        style="width: {{ $percentage }}%"></div>
                                </div>
                                <div class="mt-1 text-xs text-medium-brown">{{ number_format($percentage, 1) }}%</div>
                            </div>
                        @endforeach

                        @if ($kedaiByKecamatan->isEmpty())
                            <div class="py-8 text-center col-span-full">
                                <div class="text-sm text-medium-brown">Belum ada data kedai kopi</div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Status Verifikasi Lokasi -->

            <!-- Tabel Data Kedai Kopi -->
            <div
                class="overflow-hidden border shadow-lg bg-white/90 backdrop-blur-sm rounded-xl border-primary-orange/20">
                <div class="p-6">
                    <div class="flex flex-col mb-6 sm:flex-row sm:items-center sm:justify-between">
                        <h3 class="flex items-center mb-4 text-lg font-semibold font-poppins text-dark-brown sm:mb-0">
                            <svg class="w-5 h-5 mr-2 text-primary-orange" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                </path>
                            </svg>
                            Data Kedai Kopi
                            @if ($totalFiltered != $totalKedai)
                                ({{ $totalFiltered }} dari {{ $totalKedai }} kedai)
                            @else
                                ({{ $totalKedai }} kedai)
                            @endif
                        </h3>
                    </div>

                    <!-- Filter dan Search -->
                    <form method="GET" action="{{ route('dashboard') }}" class="mb-6">
                        <div class="grid grid-cols-1 gap-4 p-4 rounded-lg md:grid-cols-4 bg-cream-yellow/10">
                            <div>
                                <label class="block mb-1 text-xs font-medium text-dark-brown">Search</label>
                                <input type="text" name="search" value="{{ $search }}"
                                    placeholder="Cari nama kedai atau pemilik..."
                                    class="w-full px-3 py-2 text-sm border rounded-lg border-light-brown/30 focus:ring-2 focus:ring-primary-orange focus:border-transparent">
                            </div>
                            <div>
                                <label class="block mb-1 text-xs font-medium text-dark-brown">Kecamatan</label>
                                <select name="kecamatan" id="filterKecamatan"
                                    class="w-full px-3 py-2 text-sm border rounded-lg border-light-brown/30 focus:ring-2 focus:ring-primary-orange focus:border-transparent">
                                    <option value="">Semua Kecamatan</option>
                                    <option value="010" {{ $kecamatan == '010' ? 'selected' : '' }}>Bukit Bestari
                                    </option>
                                    <option value="020" {{ $kecamatan == '020' ? 'selected' : '' }}>Tanjungpinang
                                        Timur</option>
                                    <option value="030" {{ $kecamatan == '030' ? 'selected' : '' }}>Tanjungpinang
                                        Kota</option>
                                    <option value="040" {{ $kecamatan == '040' ? 'selected' : '' }}>Tanjungpinang
                                        Barat</option>
                                </select>
                            </div>
                            <div>
                                <label class="block mb-1 text-xs font-medium text-dark-brown">Kelurahan</label>
                                <select name="kelurahan" id="filterKelurahan"
                                    class="w-full px-3 py-2 text-sm border rounded-lg border-light-brown/30 focus:ring-2 focus:ring-primary-orange focus:border-transparent">
                                    <option value="">Semua Kelurahan</option>
                                </select>
                            </div>
                            <div>
                                <label class="block mb-1 text-xs font-medium text-dark-brown">Status Lokasi</label>
                                <select name="lokasi"
                                    class="w-full px-3 py-2 text-sm border rounded-lg border-light-brown/30 focus:ring-2 focus:ring-primary-orange focus:border-transparent">
                                    <option value="">Semua Status</option>
                                    <option value="1" {{ $lokasi == '1' ? 'selected' : '' }}>Ada Lokasi</option>
                                    <option value="0" {{ $lokasi == '0' ? 'selected' : '' }}>Tanpa Lokasi
                                    </option>
                                </select>
                            </div>
                        </div>

                        <!-- Hidden inputs untuk maintain sort dan page -->
                        <input type="hidden" name="sort" value="{{ $sortBy }}">
                        <input type="hidden" name="direction" value="{{ $sortDir }}">
                        <input type="hidden" name="page" value="1">

                        <div class="flex gap-2 mt-4">
                            <button type="submit"
                                class="px-4 py-2 text-sm font-medium text-white transition-colors rounded-lg bg-primary-orange hover:bg-bright-orange">
                                Filter & Cari
                            </button>
                            <a href="{{ route('dashboard') }}"
                                class="px-4 py-2 text-sm font-medium text-white transition-colors bg-gray-500 rounded-lg hover:bg-gray-600">
                                Reset
                            </a>
                        </div>
                    </form>

                    @if ($totalFiltered > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-light-brown/20">
                                <thead class="bg-gradient-to-r from-primary-orange/10 to-bright-orange/10">
                                    <tr>
                                        <th
                                            class="px-4 py-3 text-xs font-medium tracking-wider text-left uppercase text-dark-brown">
                                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'nama_kedai', 'direction' => $sortBy == 'nama_kedai' && $sortDir == 'asc' ? 'desc' : 'asc', 'page' => 1]) }}"
                                                class="flex items-center hover:text-primary-orange transition-colors {{ $sortBy == 'nama_kedai' ? 'text-primary-orange bg-primary-orange/10 px-2 py-1 rounded' : '' }}">
                                                Nama Kedai
                                                @if ($sortBy == 'nama_kedai')
                                                    <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        @if ($sortDir == 'asc')
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M5 15l7-7 7 7"></path>
                                                        @else
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                        @endif
                                                    </svg>
                                                @else
                                                    <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path>
                                                    </svg>
                                                @endif
                                            </a>
                                        </th>
                                        <th
                                            class="px-4 py-3 text-xs font-medium tracking-wider text-left uppercase text-dark-brown">
                                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'nama_pemilik', 'direction' => $sortBy == 'nama_pemilik' && $sortDir == 'asc' ? 'desc' : 'asc', 'page' => 1]) }}"
                                                class="flex items-center hover:text-primary-orange transition-colors {{ $sortBy == 'nama_pemilik' ? 'text-primary-orange bg-primary-orange/10 px-2 py-1 rounded' : '' }}">
                                                Pemilik
                                                @if ($sortBy == 'nama_pemilik')
                                                    <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        @if ($sortDir == 'asc')
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M5 15l7-7 7 7"></path>
                                                        @else
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                        @endif
                                                    </svg>
                                                @else
                                                    <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path>
                                                    </svg>
                                                @endif
                                            </a>
                                        </th>
                                        <th
                                            class="px-4 py-3 text-xs font-medium tracking-wider text-left uppercase text-dark-brown">
                                            RT/RW & Alamat</th>
                                        <th
                                            class="px-4 py-3 text-xs font-medium tracking-wider text-left uppercase text-dark-brown">
                                            Kecamatan</th>
                                        <th
                                            class="px-4 py-3 text-xs font-medium tracking-wider text-left uppercase text-dark-brown">
                                            Kelurahan</th>
                                        <th
                                            class="px-4 py-3 text-xs font-medium tracking-wider text-left uppercase text-dark-brown">
                                            No. HP</th>
                                        <th
                                            class="px-4 py-3 text-xs font-medium tracking-wider text-left uppercase text-dark-brown">
                                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'omzet', 'direction' => $sortBy == 'omzet' && $sortDir == 'asc' ? 'desc' : 'asc', 'page' => 1]) }}"
                                                class="flex items-center hover:text-primary-orange transition-colors {{ $sortBy == 'omzet' ? 'text-primary-orange bg-primary-orange/10 px-2 py-1 rounded' : '' }}">
                                                Omzet
                                                @if ($sortBy == 'omzet')
                                                    <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        @if ($sortDir == 'asc')
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M5 15l7-7 7 7"></path>
                                                        @else
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                        @endif
                                                    </svg>
                                                @else
                                                    <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path>
                                                    </svg>
                                                @endif
                                            </a>
                                        </th>
                                        <th
                                            class="px-4 py-3 text-xs font-medium tracking-wider text-left uppercase text-dark-brown">
                                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'jumlah_pekerja', 'direction' => $sortBy == 'jumlah_pekerja' && $sortDir == 'asc' ? 'desc' : 'asc', 'page' => 1]) }}"
                                                class="flex items-center hover:text-primary-orange transition-colors {{ $sortBy == 'jumlah_pekerja' ? 'text-primary-orange bg-primary-orange/10 px-2 py-1 rounded' : '' }}">
                                                Pekerja
                                                @if ($sortBy == 'jumlah_pekerja')
                                                    <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        @if ($sortDir == 'asc')
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M5 15l7-7 7 7"></path>
                                                        @else
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                        @endif
                                                    </svg>
                                                @else
                                                    <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path>
                                                    </svg>
                                                @endif
                                            </a>
                                        </th>
                                        <th
                                            class="px-4 py-3 text-xs font-medium tracking-wider text-left uppercase text-dark-brown">
                                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'stan_sewa', 'direction' => $sortBy == 'stan_sewa' && $sortDir == 'asc' ? 'desc' : 'asc', 'page' => 1]) }}"
                                                class="flex items-center hover:text-primary-orange transition-colors {{ $sortBy == 'stan_sewa' ? 'text-primary-orange bg-primary-orange/10 px-2 py-1 rounded' : '' }}">
                                                Stan Sewa
                                                @if ($sortBy == 'stan_sewa')
                                                    <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        @if ($sortDir == 'asc')
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M5 15l7-7 7 7"></path>
                                                        @else
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                        @endif
                                                    </svg>
                                                @else
                                                    <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path>
                                                    </svg>
                                                @endif
                                            </a>
                                        </th>
                                        <th
                                            class="px-4 py-3 text-xs font-medium tracking-wider text-center uppercase text-dark-brown">
                                            Lokasi GPS</th>
                                        <th
                                            class="px-4 py-3 text-xs font-medium tracking-wider text-left uppercase text-dark-brown">
                                            Catatan</th>
                                        <th
                                            class="px-4 py-3 text-xs font-medium tracking-wider text-left uppercase text-dark-brown">
                                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'created_at', 'direction' => $sortBy == 'created_at' && $sortDir == 'asc' ? 'desc' : 'asc', 'page' => 1]) }}"
                                                class="flex items-center hover:text-primary-orange transition-colors {{ $sortBy == 'created_at' ? 'text-primary-orange bg-primary-orange/10 px-2 py-1 rounded' : '' }}">
                                                Dibuat
                                                @if ($sortBy == 'created_at')
                                                    <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        @if ($sortDir == 'asc')
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M5 15l7-7 7 7"></path>
                                                        @else
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                        @endif
                                                    </svg>
                                                @else
                                                    <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path>
                                                    </svg>
                                                @endif
                                            </a>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-light-brown/10">
                                    @foreach ($currentPageData as $kedai)
                                        <tr class="transition-colors duration-150 hover:bg-cream-yellow/10">
                                            <td class="px-4 py-3 whitespace-nowrap">
                                                <div class="text-sm font-medium text-dark-brown">
                                                    {{ $kedai->nama_kedai }}</div>
                                                <div class="text-xs text-medium-brown">{{ $kedai->gender_text }}</div>
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap">
                                                <div class="text-sm text-dark-brown">{{ $kedai->nama_pemilik }}</div>
                                                <div class="text-xs text-medium-brown">Pemilik</div>
                                            </td>
                                            <td class="px-4 py-3">
                                                <div class="text-sm font-medium text-dark-brown">
                                                    RT {{ $kedai->rt }} / RW {{ $kedai->rw }}</div>
                                                <div class="text-xs text-medium-brown">{{ $kedai->alamat }}</div>
                                            </td>
                                            <td class="px-4 py-3 text-sm whitespace-nowrap text-dark-brown">
                                                {{ $kedai->kecamatan_name }}</td>
                                            <td class="px-4 py-3 text-sm whitespace-nowrap text-dark-brown">
                                                {{ $kedai->kelurahan_name }}</td>
                                            <td class="px-4 py-3 whitespace-nowrap">
                                                <div class="text-sm text-dark-brown">{{ $kedai->handphone }}</div>
                                            </td>
                                            <td class="px-4 py-3 text-sm whitespace-nowrap text-dark-brown">
                                                {{ $kedai->formatted_omzet }}</td>
                                            <td class="px-4 py-3 text-sm whitespace-nowrap text-dark-brown">
                                                {{ $kedai->jumlah_pekerja }} orang</td>
                                            <td class="px-4 py-3 text-sm whitespace-nowrap text-dark-brown">
                                                {{ $kedai->stan_sewa }} stan</td>
                                            <td class="px-4 py-3 text-center whitespace-nowrap">
                                                @if ($kedai->latitude && $kedai->longitude)
                                                    <a href="https://www.google.com/maps?q={{ $kedai->latitude }},{{ $kedai->longitude }}"
                                                        target="_blank"
                                                        class="inline-flex items-center px-3 py-2 text-sm font-medium text-white rounded-lg bg-primary-orange hover:bg-bright-orange focus:outline-none focus:ring-2 focus:ring-bright-orange focus:ring-offset-2">
                                                        <svg class="w-4 h-4 mr-1" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                                            </path>
                                                        </svg>
                                                        Lihat Map
                                                    </a>
                                                    <div class="mt-1 text-xs text-medium-brown">
                                                        {{ number_format($kedai->latitude, 6) }},
                                                        {{ number_format($kedai->longitude, 6) }}
                                                    </div>
                                                @else
                                                    <span
                                                        class="inline-flex items-center px-2 py-1 text-xs font-medium text-gray-800 bg-gray-100 rounded-full">
                                                        <svg class="w-3 h-3 mr-1" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                        </svg>
                                                        Tidak Ada
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="max-w-xs px-4 py-3">
                                                @if ($kedai->catatan)
                                                    <div class="text-sm text-dark-brown"
                                                        title="{{ $kedai->catatan }}">
                                                        {{ Str::limit($kedai->catatan, 50) }}
                                                    </div>
                                                @else
                                                    <span class="text-xs text-gray-400">-</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3 text-sm whitespace-nowrap text-dark-brown">
                                                {{ $kedai->created_at->format('d/m/Y') }}
                                                <div class="text-xs text-medium-brown">
                                                    {{ $kedai->created_at->format('H:i') }}</div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        @if ($hasPages)
                            <div class="flex items-center justify-between mt-6">
                                <div class="text-sm text-medium-brown">
                                    Menampilkan {{ $offset + 1 }} sampai
                                    {{ min($offset + $perPage, $totalFiltered) }} dari {{ $totalFiltered }} hasil
                                    @if ($totalFiltered != $totalKedai)
                                        ({{ $totalKedai }} total kedai)
                                    @endif
                                </div>

                                <div class="flex items-center space-x-2">
                                    @if ($currentPage > 1)
                                        <a href="{{ request()->fullUrlWithQuery(['page' => $currentPage - 1]) }}"
                                            class="px-3 py-1 text-sm transition-colors bg-white border rounded-lg border-primary-orange/30 text-dark-brown hover:bg-primary-orange/10">
                                            ← Sebelumnya
                                        </a>
                                    @endif

                                    @php
                                        $start = max(1, $currentPage - 2);
                                        $end = min($totalPages, $currentPage + 2);
                                    @endphp

                                    @for ($page = $start; $page <= $end; $page++)
                                        @if ($page == $currentPage)
                                            <span
                                                class="px-3 py-1 text-sm text-white rounded-lg bg-primary-orange">{{ $page }}</span>
                                        @else
                                            <a href="{{ request()->fullUrlWithQuery(['page' => $page]) }}"
                                                class="px-3 py-1 text-sm transition-colors bg-white border rounded-lg border-primary-orange/30 text-dark-brown hover:bg-primary-orange/10">
                                                {{ $page }}
                                            </a>
                                        @endif
                                    @endfor

                                    @if ($currentPage < $totalPages)
                                        <a href="{{ request()->fullUrlWithQuery(['page' => $currentPage + 1]) }}"
                                            class="px-3 py-1 text-sm transition-colors bg-white border rounded-lg border-primary-orange/30 text-dark-brown hover:bg-primary-orange/10">
                                            Selanjutnya →
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @endif

                        <div
                            class="flex items-center justify-between px-4 py-2 mt-4 text-sm rounded-lg text-medium-brown bg-cream-yellow/10">
                            <span>Total: {{ $totalFiltered }} kedai kopi ditampilkan</span>
                            <span>Terakhir diperbarui: {{ now()->format('d M Y, H:i') }}</span>
                        </div>
                    @else
                        <div class="py-12 text-center">
                            <div
                                class="flex items-center justify-center w-16 h-16 mx-auto mb-4 rounded-full bg-medium-brown/10">
                                <svg class="w-8 h-8 text-medium-brown" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4">
                                    </path>
                                </svg>
                            </div>
                            <h4 class="mb-2 text-lg font-medium text-dark-brown">Tidak Ada Data</h4>
                            <p class="mb-6 text-sm text-medium-brown">
                                @if ($search || $kecamatan || $kelurahan || $lokasi !== '')
                                    Tidak ada data yang sesuai dengan filter yang dipilih
                                @else
                                    Belum ada data kedai kopi di sistem
                                @endif
                            </p>
                            @if ($search || $kecamatan || $kelurahan || $lokasi !== '')
                                <a href="{{ route('dashboard') }}"
                                    class="font-medium text-primary-orange hover:text-bright-orange">
                                    Reset Filter
                                </a>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        const kelurahanData = {
            "010": [{
                    kode: "001",
                    nama: "Dompak"
                },
                {
                    kode: "002",
                    nama: "Tanjungpinang Timur"
                },
                {
                    kode: "003",
                    nama: "Tanjung Ayun Sakti"
                },
                {
                    kode: "004",
                    nama: "Sei Jang"
                },
                {
                    kode: "005",
                    nama: "Tanjung Unggat"
                }
            ],
            "020": [{
                    kode: "001",
                    nama: "Batu Sembilan"
                },
                {
                    kode: "002",
                    nama: "Melayu Kota Piring"
                },
                {
                    kode: "003",
                    nama: "Air Raja"
                },
                {
                    kode: "004",
                    nama: "Pinang Kencana"
                },
                {
                    kode: "005",
                    nama: "Kampung Bulang"
                }
            ],
            "030": [{
                    kode: "001",
                    nama: "Tanjungpinang Kota"
                },
                {
                    kode: "002",
                    nama: "Penyengat"
                },
                {
                    kode: "003",
                    nama: "Kampung Bugis"
                },
                {
                    kode: "004",
                    nama: "Senggarang"
                }
            ],
            "040": [{
                    kode: "001",
                    nama: "Tanjungpinang Barat"
                },
                {
                    kode: "002",
                    nama: "Kemboja"
                },
                {
                    kode: "003",
                    nama: "Kampung Baru"
                },
                {
                    kode: "004",
                    nama: "Bukit Cermin"
                }
            ]
        };

        document.addEventListener('DOMContentLoaded', function() {
            const filterKecamatan = document.getElementById('filterKecamatan');
            const filterKelurahan = document.getElementById('filterKelurahan');

            function populateKelurahan(kecamatan, selectedKelurahan = '') {
                filterKelurahan.innerHTML = '<option value="">Semua Kelurahan</option>';

                if (kecamatan && kelurahanData[kecamatan]) {
                    filterKelurahan.disabled = false;
                    kelurahanData[kecamatan].forEach(kelurahan => {
                        const option = document.createElement('option');
                        option.value = kelurahan.kode;
                        option.textContent = kelurahan.nama;
                        if (kelurahan.kode === selectedKelurahan) {
                            option.selected = true;
                        }
                        filterKelurahan.appendChild(option);
                    });
                } else {
                    filterKelurahan.disabled = true;
                }
            }

            // Populate kelurahan on page load
            populateKelurahan('{{ $kecamatan }}', '{{ $kelurahan }}');

            // Handle kecamatan change
            filterKecamatan.addEventListener('change', function() {
                populateKelurahan(this.value);
            });
        });
    </script>
</x-app-layout>
