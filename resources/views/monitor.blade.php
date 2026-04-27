<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monitor Kedai Kopi - SEkoPinang</title>
    <!-- Compiled Assets (Tailwind via Vite) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        .bg-pattern {
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23e44012' fill-opacity='0.05'%3E%3Ccircle cx='30' cy='30' r='4'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }

        .kelurahan-details {
            transition: max-height 0.3s ease-in-out;
            overflow: hidden;
            max-height: 0px;
        }

        .kelurahan-details.show {
            max-height: 1000px;
        }

        .data-card {
            transition: all 0.3s ease;
        }

        .data-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<body class="bg-gradient-to-br from-cream-yellow/30 to-white bg-pattern font-poppins">

    <!-- Header -->
    <header class="border-b shadow-sm bg-white/90 backdrop-blur-sm border-primary-orange/20">
        <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center space-x-4">
                    <div
                        class="flex items-center justify-center w-10 h-10 shadow-lg bg-gradient-to-br from-primary-orange to-bright-orange rounded-xl">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold text-dark-brown">Monitor SEkoPinang</h1>
                        <p class="text-sm text-medium-brown">Data Kedai Kopi Tanjungpinang</p>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="px-3 py-1 text-xs font-medium text-white rounded-full bg-primary-orange">
                        📊 Dashboard Publik
                    </span>
                    <a href="{{ route('form') }}"
                        class="px-4 py-2 text-sm font-medium text-white transition-colors rounded-lg bg-bright-orange hover:bg-primary-orange">
                        + Daftar Kedai
                    </a>
                </div>
            </div>
        </div>
    </header>

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
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                            </div>
                            <div>
                                <h1 class="text-3xl font-bold font-poppins text-dark-brown">Monitor SEkoPinang</h1>
                                <p class="mt-1 font-medium text-medium-brown">Harmonisasi Jelang SE: Data Kedai Kopi di
                                    Tanjungpinang</p>
                                <p class="mt-2 text-sm text-medium-brown/80">Dashboard publik untuk monitoring
                                    perkembangan data kedai kopi real-time</p>
                            </div>
                        </div>
                        <div class="hidden lg:block">
                            <div class="text-right">
                                <div class="text-sm text-medium-brown">Data terakhir update</div>
                                @if ($lastUpdateTime)
                                    <div class="text-lg font-semibold text-dark-brown">
                                        {{ $lastUpdateTime->format('d M Y, H:i') }} WIB</div>
                                @else
                                    <div class="text-lg font-semibold text-dark-brown">Belum ada data</div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="h-2 bg-gradient-to-r from-primary-orange via-bright-orange to-cream-yellow"></div>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 gap-6 mb-8 md:grid-cols-2 lg:grid-cols-4">
                <div
                    class="overflow-hidden border shadow-lg data-card bg-white/90 backdrop-blur-sm rounded-xl border-primary-orange/20">
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
                                <dd class="text-xs text-medium-brown">Terdaftar</dd>
                            </div>
                        </div>
                    </div>
                </div>

                <div
                    class="overflow-hidden border shadow-lg data-card bg-white/90 backdrop-blur-sm rounded-xl border-bright-orange/20">
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
                                @php
                                    $progressVerifikasi =
                                        $kedaiWithLocation > 0 && $totalKedai > 0
                                            ? ($kedaiWithLocation / $totalKedai) * 100
                                            : 0;
                                @endphp
                                <dd class="text-xs text-medium-brown">{{ number_format($progressVerifikasi, 1) }}%
                                    terverifikasi</dd>
                            </div>
                        </div>
                    </div>
                </div>

                @php
                    // Hitung stats untuk data opsional
                    $kedaiWithPekerja = App\Models\KedaiKopi::whereNotNull('jumlah_pekerja')->count();
                    $kedaiWithOmzet = App\Models\KedaiKopi::whereNotNull('omzet')->where('omzet', '>', 0)->count();
                @endphp

                <div
                    class="overflow-hidden border shadow-lg data-card bg-white/90 backdrop-blur-sm rounded-xl border-cream-yellow/40">
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
                                <dd class="text-xs text-medium-brown">{{ $kedaiWithPekerja }} kedai melaporkan</dd>
                            </div>
                        </div>
                    </div>
                </div>

                <div
                    class="overflow-hidden border shadow-lg data-card bg-white/90 backdrop-blur-sm rounded-xl border-light-brown/30">
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
                                    @if ($avgOmzet && $avgOmzet > 0)
                                        Rp {{ number_format($avgOmzet / 1000000, 1) }}M
                                    @else
                                        <span class="text-sm text-gray-500">Dalam proses</span>
                                    @endif
                                </dd>
                                <dd class="text-xs text-medium-brown">{{ $kedaiWithOmzet }} kedai melaporkan</dd>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Distribusi per Kecamatan dan Kelurahan -->
            <div
                class="mb-8 overflow-hidden border shadow-lg bg-white/90 backdrop-blur-sm rounded-xl border-primary-orange/20">
                <div class="p-6">
                    <h3 class="flex items-center mb-6 text-lg font-semibold font-poppins text-dark-brown">
                        <svg class="w-5 h-5 mr-2 text-primary-orange" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                            </path>
                        </svg>
                        Distribusi Kedai Kopi per Wilayah
                    </h3>

                    <div class="space-y-4">
                        @php
                            $kelurahanData = [
                                '010' => [
                                    ['001', 'Dompak'],
                                    ['002', 'Tanjungpinang Timur'],
                                    ['003', 'Tanjung Ayun Sakti'],
                                    ['004', 'Sei Jang'],
                                    ['005', 'Tanjung Unggat'],
                                ],
                                '020' => [
                                    ['001', 'Batu Sembilan'],
                                    ['002', 'Melayu Kota Piring'],
                                    ['003', 'Air Raja'],
                                    ['004', 'Pinang Kencana'],
                                    ['005', 'Kampung Bulang'],
                                ],
                                '030' => [
                                    ['001', 'Tanjungpinang Kota'],
                                    ['002', 'Penyengat'],
                                    ['003', 'Kampung Bugis'],
                                    ['004', 'Senggarang'],
                                ],
                                '040' => [
                                    ['001', 'Tanjungpinang Barat'],
                                    ['002', 'Kemboja'],
                                    ['003', 'Kampung Baru'],
                                    ['004', 'Bukit Cermin'],
                                ],
                            ];

                            // Hitung data kedai per kelurahan dari SEMUA data, bukan hanya halaman saat ini
                            $kedaiPerKelurahan = collect();

                            // SESUDAH (✅ Benar):
                            $allKedaiByKelurahan = DB::table('kedai_kopi')
                                ->whereNull('deleted_at')
                                ->where('sumber', 'mandiri') // ✅ Tambahkan filter sumber yang sama
                                ->select('kode_kecamatan', 'kode_kelurahan', DB::raw('count(*) as total'))
                                ->groupBy('kode_kecamatan', 'kode_kelurahan')
                                ->get();

                            // Convert ke format yang mudah diakses
                            foreach ($allKedaiByKelurahan as $data) {
                                $key = $data->kode_kecamatan . '-' . $data->kode_kelurahan;
                                $kedaiPerKelurahan[$key] = $data->total;
                            }
                        @endphp

                        @foreach ($kecamatanNames as $kodeKecamatan => $namaKecamatan)
                            @php
                                $totalKecamatan = $kedaiByKecamatan->where('kode_kecamatan', $kodeKecamatan)->first();
                                $jumlahKecamatan = $totalKecamatan ? $totalKecamatan->total : 0;
                                $persentaseKecamatan = $totalKedai > 0 ? ($jumlahKecamatan / $totalKedai) * 100 : 0;
                            @endphp

                            <div class="p-4 border rounded-lg data-card bg-cream-yellow/5 border-cream-yellow/20">
                                <!-- Header Kecamatan dengan Tombol Plus -->
                                <div class="flex items-center justify-between p-2 transition-colors rounded-lg cursor-pointer hover:bg-cream-yellow/10"
                                    onclick="toggleKelurahan('{{ $kodeKecamatan }}')">
                                    <div class="flex items-center space-x-3">
                                        <div
                                            class="flex items-center justify-center w-8 h-8 rounded-lg bg-primary-orange/10">
                                            <svg class="w-4 h-4 text-primary-orange" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                                </path>
                                            </svg>
                                        </div>
                                        <div>
                                            <h4 class="font-semibold text-dark-brown">{{ $namaKecamatan }}</h4>
                                            <p class="text-sm text-medium-brown">{{ $jumlahKecamatan }} kedai kopi
                                                ({{ number_format($persentaseKecamatan, 1) }}%)</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-3">
                                        <!-- Progress Bar Mini -->
                                        <div class="w-20 h-2 rounded-full bg-cream-yellow/20">
                                            <div class="h-2 transition-all duration-500 rounded-full bg-gradient-to-r from-primary-orange to-bright-orange"
                                                style="width: {{ $persentaseKecamatan }}%"></div>
                                        </div>
                                        <!-- Tombol Plus/Minus -->
                                        <button
                                            class="flex items-center justify-center w-8 h-8 transition-colors rounded-full bg-primary-orange/10 hover:bg-primary-orange/20"
                                            id="toggle-btn-{{ $kodeKecamatan }}">
                                            <svg class="w-4 h-4 transition-transform duration-200 text-primary-orange"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                                id="plus-icon-{{ $kodeKecamatan }}">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>

                                <!-- Detail Kelurahan (Hidden by default) -->
                                <div class="mt-4 space-y-2 kelurahan-details" id="kelurahan-{{ $kodeKecamatan }}">
                                    @if (isset($kelurahanData[$kodeKecamatan]))
                                        @foreach ($kelurahanData[$kodeKecamatan] as $kelurahan)
                                            @php
                                                $kodeKelurahan = $kelurahan[0];
                                                $namaKelurahan = $kelurahan[1];
                                                $key = $kodeKecamatan . '-' . $kodeKelurahan;
                                                $jumlahKelurahan = $kedaiPerKelurahan[$key] ?? 0;
                                                $persentaseKelurahan =
                                                    $jumlahKecamatan > 0
                                                        ? ($jumlahKelurahan / $jumlahKecamatan) * 100
                                                        : 0;
                                            @endphp

                                            <div
                                                class="flex items-center justify-between p-3 border rounded-lg bg-white/70 border-cream-yellow/10">
                                                <div class="flex items-center space-x-3">
                                                    <div class="w-2 h-2 rounded-full bg-bright-orange"></div>
                                                    <div>
                                                        <span
                                                            class="text-sm font-medium text-dark-brown">{{ $namaKelurahan }}</span>
                                                        <span
                                                            class="ml-2 text-xs text-medium-brown">({{ $kodeKelurahan }})</span>
                                                    </div>
                                                </div>
                                                <div class="flex items-center space-x-3">
                                                    <div class="w-20 h-2 bg-gray-200 rounded-full">
                                                        <div class="h-2 transition-all duration-300 rounded-full bg-bright-orange"
                                                            style="width: {{ $persentaseKelurahan }}%"></div>
                                                    </div>
                                                    <div class="text-right min-w-[50px]">
                                                        <div class="text-sm font-semibold text-dark-brown">
                                                            {{ $jumlahKelurahan }}</div>
                                                        @if ($jumlahKecamatan > 0)
                                                            <div class="text-xs text-medium-brown">
                                                                {{ number_format($persentaseKelurahan, 1) }}%</div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        @endforeach

                        @if ($kedaiByKecamatan->isEmpty())
                            <div class="py-12 text-center">
                                <div
                                    class="flex items-center justify-center w-16 h-16 mx-auto mb-4 rounded-full bg-medium-brown/10">
                                    <svg class="w-8 h-8 text-medium-brown" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                                        </path>
                                    </svg>
                                </div>
                                <h4 class="mb-2 text-lg font-medium text-dark-brown">Belum Ada Data</h4>
                                <p class="text-sm text-medium-brown">Belum ada data kedai kopi yang terdaftar</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

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
                    <form method="GET" action="{{ route('monitor') }}" class="mb-6">
                        <div class="grid grid-cols-1 gap-4 p-4 rounded-lg md:grid-cols-4 bg-cream-yellow/10">
                            <div>
                                <label class="block mb-1 text-xs font-medium text-dark-brown">Search Nama Kedai</label>
                                <input type="text" name="search" value="{{ $search }}"
                                    placeholder="Cari nama kedai..."
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
                            <a href="{{ route('monitor') }}"
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
                                            RT/RW</th>
                                        <th
                                            class="px-4 py-3 text-xs font-medium tracking-wider text-left uppercase text-dark-brown">
                                            Alamat</th>
                                        <th
                                            class="px-4 py-3 text-xs font-medium tracking-wider text-left uppercase text-dark-brown">
                                            Kecamatan</th>
                                        <th
                                            class="px-4 py-3 text-xs font-medium tracking-wider text-left uppercase text-dark-brown">
                                            Kelurahan</th>
                                        <th
                                            class="px-4 py-3 text-xs font-medium tracking-wider text-center uppercase text-dark-brown">
                                            Lokasi GPS</th>
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
                                            <td class="px-4 py-3 text-sm whitespace-nowrap text-dark-brown">
                                                @php
                                                    $rtDisplay = $kedai->rt && $kedai->rt !== '000' ? $kedai->rt : '-';
                                                    $rwDisplay = $kedai->rw && $kedai->rw !== '000' ? $kedai->rw : '-';
                                                @endphp
                                                @if ($rtDisplay === '-' && $rwDisplay === '-')
                                                    <span class="text-xs text-gray-400">Tidak diisi</span>
                                                @else
                                                    RT {{ $rtDisplay }} / RW {{ $rwDisplay }}
                                                @endif
                                            </td>
                                            <td class="px-4 py-3 text-sm text-dark-brown">{{ $kedai->alamat }}</td>
                                            <td class="px-4 py-3 text-sm whitespace-nowrap text-dark-brown">
                                                {{ $kedai->kecamatan_name }}</td>
                                            <td class="px-4 py-3 text-sm whitespace-nowrap text-dark-brown">
                                                {{ $kedai->kelurahan_name }}</td>
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
                                    Menampilkan {{ ($currentPage - 1) * $perPage + 1 }} sampai
                                    {{ min($currentPage * $perPage, $totalFiltered) }} dari {{ $totalFiltered }} hasil
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
                            @if ($lastUpdateTime)
                                <span>Terakhir diperbarui: {{ $lastUpdateTime->format('d M Y, H:i') }} WIB</span>
                            @else
                                <span>Belum ada data</span>
                            @endif
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
                                <a href="{{ route('monitor') }}"
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

    <!-- Footer -->
    <footer class="py-8 mt-12 border-t bg-white/80 backdrop-blur-sm border-primary-orange/20">
        <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="flex flex-col items-center justify-between md:flex-row">
                <div class="mb-4 md:mb-0">
                    <p class="text-sm text-medium-brown">
                        © 2025 SEkoPinang - Harmonisasi Jelang SE: Data Kedai Kopi di Tanjungpinang
                    </p>
                    <p class="mt-1 text-xs text-medium-brown/70">
                        Badan Pusat Statistik Kota Tanjungpinang
                    </p>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="px-3 py-1 text-xs font-medium rounded-full text-primary-orange bg-primary-orange/10">
                        🌐 Dashboard Publik
                    </span>
                    <a href="{{ route('form') }}"
                        class="text-sm font-medium text-primary-orange hover:text-bright-orange">
                        Daftar Kedai Baru
                    </a>
                </div>
            </div>
        </div>
    </footer>

    <!-- JavaScript untuk filter dinamis dan toggle kelurahan -->
    <script>
        // Data kelurahan yang konsisten dengan struktur Laravel
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

        // Function untuk toggle expand/collapse kelurahan dengan error handling
        function toggleKelurahan(kodeKecamatan) {
            try {
                const kelurahanSection = document.getElementById('kelurahan-' + kodeKecamatan);
                const plusIcon = document.getElementById('plus-icon-' + kodeKecamatan);

                if (!kelurahanSection || !plusIcon) {
                    console.error('Elements not found for kecamatan:', kodeKecamatan);
                    return;
                }

                if (kelurahanSection.classList.contains('show')) {
                    // Hide kelurahan details
                    kelurahanSection.classList.remove('show');

                    // Change minus to plus
                    plusIcon.innerHTML =
                        '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>';
                    plusIcon.classList.remove('rotate-180');
                } else {
                    // Show kelurahan details
                    kelurahanSection.classList.add('show');

                    // Change plus to minus
                    plusIcon.innerHTML =
                        '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 12H6"></path>';
                    plusIcon.classList.add('rotate-180');
                }
            } catch (error) {
                console.error('Error in toggleKelurahan:', error);
            }
        }

        // Initialize dropdown filters
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Initializing dropdown filters...');

            const filterKecamatan = document.getElementById('filterKecamatan');
            const filterKelurahan = document.getElementById('filterKelurahan');

            if (!filterKecamatan || !filterKelurahan) {
                console.error('Filter elements not found');
                return;
            }

            function populateKelurahan(kecamatan, selectedKelurahan = '') {
                try {
                    // Clear existing options
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
                } catch (error) {
                    console.error('Error populating kelurahan:', error);
                }
            }

            // Get current values from URL or form
            const urlParams = new URLSearchParams(window.location.search);
            const currentKecamatan = urlParams.get('kecamatan') || filterKecamatan.value || '';
            const currentKelurahan = urlParams.get('kelurahan') || '';

            // Populate kelurahan on page load
            populateKelurahan(currentKecamatan, currentKelurahan);

            // Handle kecamatan change
            filterKecamatan.addEventListener('change', function() {
                populateKelurahan(this.value);
            });

            console.log('Dropdown filters initialized successfully');
        });

        // Auto refresh setiap 5 menit untuk data real-time
        setTimeout(function() {
            try {
                location.reload();
            } catch (error) {
                console.error('Error during auto refresh:', error);
            }
        }, 300000); // 5 menit

        // Error handling untuk global errors
        window.addEventListener('error', function(e) {
            console.error('Global error:', e.error);
        });
    </script>
</body>

</html>
