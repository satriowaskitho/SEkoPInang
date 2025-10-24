<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SEkoPInang - Harmonisasi Jelang SE: Data Kedai Kopi di Tanjungpinang</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <link rel="shortcut icon" href="{{ asset('images/logo.png') }}">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Flowbite CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.1/flowbite.min.css" rel="stylesheet" />

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.css" />

    <!-- Tailwind Config -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'primary-orange': '#e44012',
                        'dark-brown': '#3f1000',
                        'medium-brown': '#581908',
                        'light-brown': '#8e4917',
                        'cream-yellow': '#f5cf76',
                        'bright-orange': '#f58741',
                    },
                    fontFamily: {
                        'poppins': ['Poppins', 'sans-serif'],
                    },
                }
            }
        }
    </script>

    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }

        .coffee-gradient {
            background: linear-gradient(135deg, #f5cf76 0%, #f58741 100%);
        }

        .coffee-pattern {
            background-image: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="20" cy="20" r="2" fill="%23e44012" opacity="0.1"/><circle cx="80" cy="80" r="3" fill="%233f1000" opacity="0.1"/><circle cx="40" cy="60" r="1.5" fill="%23581908" opacity="0.1"/><circle cx="70" cy="30" r="2.5" fill="%238e4917" opacity="0.1"/></svg>');
            animation: float 20s ease-in-out infinite;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0px) rotate(0deg);
            }

            50% {
                transform: translateY(-20px) rotate(180deg);
            }
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .slide-in {
            animation: slideIn 0.3s ease;
        }

        /* Custom marker styles */
        .custom-marker {
            background: linear-gradient(135deg, #e44012, #8e4917);
            border: 4px solid #f5cf76;
            border-radius: 50% 50% 50% 0;
            box-shadow: 0 6px 15px rgba(63, 16, 0, 0.4);
            transform: rotate(-45deg);
            cursor: move;
            transition: all 0.3s ease;
            width: 30px;
            height: 30px;
        }

        .custom-marker:hover {
            transform: rotate(-45deg) scale(1.1);
            box-shadow: 0 8px 20px rgba(63, 16, 0, 0.6);
            background: linear-gradient(135deg, #8e4917, #3f1000);
        }

        .custom-marker i {
            transform: rotate(45deg);
            display: block;
            margin-top: 6px;
            margin-left: 2px;
            color: #f5cf76;
        }

        /* Leaflet popup customization */
        .leaflet-popup-content-wrapper {
            background: #f5cf76 !important;
            border-radius: 12px !important;
            box-shadow: 0 4px 15px rgba(63, 16, 0, 0.2) !important;
            border: 2px solid #f58741 !important;
        }

        .leaflet-popup-content {
            color: #3f1000 !important;
            font-family: 'Poppins', sans-serif !important;
            font-weight: 500 !important;
        }

        .leaflet-popup-tip {
            background: #f5cf76 !important;
            border: 1px solid #f58741 !important;
        }

        .leaflet-control-zoom a {
            background: #e44012 !important;
            color: #f5cf76 !important;
            border: 1px solid #f58741 !important;
        }

        .leaflet-control-zoom a:hover {
            background: #8e4917 !important;
        }

        /* Error styles */
        .error-border {
            border-color: #ef4444 !important;
            background-color: #fef2f2 !important;
        }

        .error-text {
            color: #ef4444;
            font-size: 0.75rem;
            margin-top: 0.25rem;
        }

        .warning-text {
            color: #f59e0b;
            font-size: 0.75rem;
            margin-top: 0.25rem;
        }

        .warning-border {
            border-color: #f59e0b !important;
            background-color: #fffbeb !important;
        }
    </style>
</head>

<body class="relative min-h-screen overflow-x-hidden coffee-gradient">
    <!-- Background Pattern -->
    <div class="fixed inset-0 coffee-pattern -z-10"></div>

    <div class="container max-w-4xl p-5 mx-auto">
        <!-- Header -->
        <div class="relative mb-12 text-center">
            <div class="flex items-center justify-center gap-6 mb-6">
                <!-- Logo Container dengan backdrop -->
                <div
                    class="flex items-center gap-6 px-8 py-6 overflow-hidden border rounded-full shadow-lg bg-white/95 backdrop-blur-sm border-primary-orange/20">
                    <!-- Logo BPS -->
                    <img src="{{ asset('images/logo-bps.png') }}" alt="BPS Logo"
                        class="object-contain w-20 h-20 transition-transform duration-300 filter drop-shadow-lg hover:scale-105 md:w-24 md:h-24"
                        onerror="this.style.display='none'">

                    <!-- Logo SEkoPInang -->
                    <img src="{{ asset('images/logo.png') }}" alt="SEkoPInang Logo"
                        class="object-contain w-20 h-20 transition-transform duration-300 filter drop-shadow-lg hover:scale-105 md:w-24 md:h-24"
                        onerror="this.style.display='none'">
                </div>
            </div>
            <h1 class="mb-4 text-dark-brown text-shadow-sm">
                <span
                    class="block mb-2 text-4xl font-black leading-none tracking-wider text-primary-orange md:text-6xl">
                    SEkoPInang
                </span>
                <span class="block text-lg font-medium leading-tight tracking-wide text-light-brown md:text-xl">
                    Harmonisasi Jelang Sensus Ekonomi: Data Kedai Kopi di Tanjungpinang
                </span>
            </h1>
        </div>

        <!-- Success Message -->
        @if (session('success'))
            <div
                class="px-6 py-4 mb-6 text-green-700 border border-green-200 shadow-lg bg-gradient-to-r from-green-50 to-green-100 rounded-2xl slide-in">
                <div class="text-center">
                    <i class="mr-2 text-2xl fas fa-check-circle"></i>
                    <div>
                        <strong>{{ session('success.message') }}</strong><br>
                        <small class="text-green-600">
                            {{ session('success.data.nama_kedai') }}
                            @if (session('success.data.nama_pemilik'))
                                - {{ session('success.data.nama_pemilik') }}
                            @endif
                            <br>
                            Disimpan pada: {{ session('success.data.created_at') }}
                        </small>
                    </div>
                </div>
            </div>
        @endif

        <!-- Error Message -->
        @if (session('error'))
            <div
                class="px-6 py-4 mb-6 text-red-700 border border-red-200 shadow-lg bg-gradient-to-r from-red-50 to-red-100 rounded-2xl slide-in">
                <div class="text-center">
                    <i class="mr-2 text-2xl fas fa-exclamation-triangle"></i>
                    <div>
                        <strong>{{ session('error') }}</strong>
                    </div>
                </div>
            </div>
        @endif

        <!-- Validation Errors -->
        @if ($errors->any())
            <div
                class="px-6 py-4 mb-6 text-red-700 border border-red-200 shadow-lg bg-gradient-to-r from-red-50 to-red-100 rounded-2xl slide-in">
                <div class="text-center">
                    <i class="mr-2 text-2xl fas fa-exclamation-triangle"></i>
                    <div>
                        <strong>Ada kesalahan dalam form:</strong>
                        <ul class="mt-2 text-sm text-red-600">
                            @foreach ($errors->all() as $error)
                                <li>• {{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <!-- Informasi Survei -->
        <div
            class="mb-8 overflow-hidden border shadow-lg bg-white/95 backdrop-blur-sm rounded-xl border-primary-orange/20">
            <div class="p-6">
                <h3 class="flex items-center mb-4 text-lg font-semibold font-poppins text-dark-brown">
                    <svg class="w-5 h-5 mr-2 text-primary-orange" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Informasi Survei
                </h3>

                <div class="space-y-4 text-sm text-medium-brown">
                    <p class="leading-relaxed">
                        Hai, yuk ikut berpartisipasi dalam Pendataan Kedai Kopi yang diselenggarakan oleh Badan Pusat
                        Statistik (BPS) Kota Tanjungpinang. Anda hanya perlu meluangkan waktu <strong
                            class="text-dark-brown">maksimal</strong> 5 (lima) menit untuk mengisi survei ini.
                    </p>

                    <p class="leading-relaxed">
                        Pendataan ini dilakukan sebagai pemetaan awal terhadap kegiatan ekonomi berbasis UMKM, khususnya
                        sektor usaha kedai kopi yang saat ini berkembang cukup pesat dan menjadi bagian dari dinamika
                        ekonomi perkotaan di Kota Tanjungpinang.
                    </p>

                    <p class="leading-relaxed">
                        Kerahasiaan jawaban Anda dilindungi Undang-undang No.16 Tahun 1997 tentang Statistik.
                        Partisipasi dan jawaban Anda sangat bermanfaat untuk menentukan arah kebijakan perekonomian
                        khususnya di Kota Tanjungpinang.
                    </p>
                </div>
            </div>
        </div>

        <form id="businessForm" method="POST" action="{{ route('kedai-kopi.store') }}" class="space-y-8">
            @csrf

            <!-- Input Hidden untuk Sumber - TAMBAHAN BARU -->
            <input type="hidden" name="sumber" value="mandiri">

            <!-- Blok 1: Keterangan Lokasi -->
            <div
                class="relative p-6 overflow-hidden transition-all duration-300 border shadow-xl bg-white/95 backdrop-blur-md rounded-3xl md:p-8 border-primary-orange/10 hover:shadow-2xl hover:-translate-y-1">
                <div
                    class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-primary-orange via-light-brown to-dark-brown rounded-t-3xl">
                </div>

                <div class="flex items-center gap-3 mb-6">
                    <div
                        class="flex items-center justify-center w-12 h-12 text-xl text-white rounded-full bg-gradient-to-br from-primary-orange to-light-brown">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-dark-brown">I. Keterangan Lokasi</h3>
                </div>

                <div class="grid grid-cols-1 gap-6 mb-6 md:grid-cols-2">
                    <div>
                        <label class="block mb-2 text-sm font-medium text-dark-brown">Kota</label>
                        <input type="hidden" name="kode_kota" value="2172">
                        <input type="text" value="Tanjungpinang" readonly
                            class="bg-white-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-orange focus:border-primary-orange block w-full p-2.5 cursor-not-allowed">
                    </div>
                    <div>
                        <label for="kecamatan" class="block mb-2 text-sm font-medium text-dark-brown">
                            Kecamatan <span class="font-bold text-red-500">*</span>
                        </label>
                        <select id="kecamatan" name="kode_kecamatan" required
                            class="bg-white-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-orange focus:border-primary-orange block w-full p-2.5 {{ $errors->has('kode_kecamatan') ? 'error-border' : '' }}">
                            <option value="">Pilih Kecamatan</option>
                            <option value="010" {{ old('kode_kecamatan') == '010' ? 'selected' : '' }}>Bukit
                                Bestari
                            </option>
                            <option value="020" {{ old('kode_kecamatan') == '020' ? 'selected' : '' }}>
                                Tanjungpinang
                                Timur</option>
                            <option value="030" {{ old('kode_kecamatan') == '030' ? 'selected' : '' }}>
                                Tanjungpinang
                                Kota</option>
                            <option value="040" {{ old('kode_kecamatan') == '040' ? 'selected' : '' }}>
                                Tanjungpinang
                                Barat</option>
                        </select>
                        @if ($errors->has('kode_kecamatan'))
                            <p class="error-text">{{ $errors->first('kode_kecamatan') }}</p>
                        @endif
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-6 mb-6 md:grid-cols-3">
                    <div>
                        <label for="kelurahan" class="block mb-2 text-sm font-medium text-dark-brown">
                            Kelurahan <span class="font-bold text-red-500">*</span>
                        </label>
                        <select id="kelurahan" name="kode_kelurahan" required disabled
                            class="bg-white-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-orange focus:border-primary-orange block w-full p-2.5 disabled:cursor-not-allowed disabled:opacity-50 {{ $errors->has('kode_kelurahan') ? 'error-border' : '' }}">
                            <option value="">Pilih Kelurahan</option>
                        </select>
                        @if ($errors->has('kode_kelurahan'))
                            <p class="error-text">{{ $errors->first('kode_kelurahan') }}</p>
                        @endif
                    </div>
                    <div>
                        <label for="rw" class="block mb-2 text-sm font-medium text-dark-brown">
                            RW
                        </label>
                        <div class="relative">
                            <input type="text" id="rw" name="rw" placeholder="001 (opsional)"
                                maxlength="3" pattern="[0-9]{3}" value="{{ old('rw') }}"
                                class="bg-white-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-orange focus:border-primary-orange block w-full p-2.5 pr-10 {{ $errors->has('rw') ? 'error-border' : '' }}">
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <i class="fas fa-hashtag text-light-brown"></i>
                            </div>
                        </div>
                        @if ($errors->has('rw'))
                            <p class="error-text">{{ $errors->first('rw') }}</p>
                        @else
                            <p class="mt-1 text-xs text-light-brown/70">Format: 001, 012, 123 (opsional)</p>
                        @endif
                    </div>
                    <div>
                        <label for="rt" class="block mb-2 text-sm font-medium text-dark-brown">
                            RT
                        </label>
                        <div class="relative">
                            <input type="text" id="rt" name="rt" placeholder="001 (opsional)"
                                maxlength="3" pattern="[0-9]{3}" value="{{ old('rt') }}"
                                class="bg-white-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-orange focus:border-primary-orange block w-full p-2.5 pr-10 {{ $errors->has('rt') ? 'error-border' : '' }}">
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <i class="fas fa-hashtag text-light-brown"></i>
                            </div>
                        </div>
                        @if ($errors->has('rt'))
                            <p class="error-text">{{ $errors->first('rt') }}</p>
                        @else
                            <p class="mt-1 text-xs text-light-brown/70">Format: 001, 012, 123 (opsional)</p>
                        @endif
                    </div>
                </div>

                <div class="mb-6">
                    <label for="alamat" class="block mb-2 text-sm font-medium text-dark-brown">
                        Alamat Kedai <span class="font-bold text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input type="text" id="alamat" name="alamat"
                            placeholder="Masukkan alamat lengkap kedai kopi" required value="{{ old('alamat') }}"
                            class="bg-white-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-orange focus:border-primary-orange block w-full p-2.5 pr-10 {{ $errors->has('alamat') ? 'error-border' : '' }}">
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                            <i class="fas fa-map-marker-alt text-light-brown"></i>
                        </div>
                    </div>
                    @if ($errors->has('alamat'))
                        <p class="error-text">{{ $errors->first('alamat') }}</p>
                    @endif
                </div>

                <!-- Geo Tagging Section -->
                <div>
                    <label class="block mb-2 text-sm font-medium text-dark-brown">
                        <i class="fas fa-map-pin"></i> Lokasi (Koordinat GPS) <span
                            class="font-bold text-red-500">*</span>
                    </label>
                    <div
                        class="p-6 border-2 bg-gradient-to-br from-cream-yellow to-white/90 rounded-2xl border-primary-orange/10">
                        <div class="flex flex-col gap-4 mb-4 sm:flex-row">
                            <button type="button" id="getCurrentLocation"
                                class="px-6 py-3 text-sm font-medium text-center text-white transition-all duration-300 rounded-full bg-gradient-to-r from-primary-orange to-light-brown hover:from-light-brown hover:to-dark-brown focus:ring-4 focus:outline-none focus:ring-primary-orange/50 hover:-translate-y-1 hover:shadow-lg">
                                <i class="mr-2 fas fa-crosshairs"></i> Deteksi Lokasi Saya
                            </button>
                            <button type="button" id="confirmLocation"
                                class="px-6 py-3 text-sm font-medium text-center text-white transition-all duration-300 rounded-full bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 focus:ring-4 focus:outline-none focus:ring-green-300 hover:-translate-y-1 hover:shadow-lg">
                                <i class="mr-2 fas fa-check-circle"></i> Konfirmasi Lokasi
                            </button>
                        </div>

                        <div id="locationInfo"
                            class="flex items-center gap-3 p-4 mb-4 text-sm border text-dark-brown bg-primary-orange/10 rounded-xl border-primary-orange/20">
                            <i class="text-lg fas fa-info-circle"></i>
                            <span>Tekan "Deteksi Lokasi Saya" untuk mencari koordinat otomatis (wajib)</span>
                        </div>

                        <div id="mapContainer" class="hidden mb-4">
                            <div
                                class="relative overflow-hidden border-2 shadow-lg h-96 rounded-xl border-primary-orange/20">
                                <div id="map" class="w-full h-full"></div>
                                <div
                                    class="absolute top-3 right-3 bg-black/80 text-white px-3 py-2 rounded-full text-xs flex items-center gap-2 z-[1000]">
                                    <i class="fas fa-hand-paper"></i> Geser pin atau klik peta untuk mengatur lokasi
                                    kedai
                                </div>
                            </div>
                        </div>

                        <div id="coordinatesDisplay"
                            class="flex flex-col justify-center hidden gap-8 p-4 border bg-white/90 rounded-xl sm:flex-row border-primary-orange/20">
                            <div class="text-center">
                                <p class="mb-1 text-xs font-medium text-light-brown">Latitude:</p>
                                <p id="latValue" class="font-mono text-base font-semibold text-dark-brown">-</p>
                            </div>
                            <div class="text-center">
                                <p class="mb-1 text-xs font-medium text-light-brown">Longitude:</p>
                                <p id="lngValue" class="font-mono text-base font-semibold text-dark-brown">-</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Blok 2: Keterangan Usaha -->
            <div
                class="relative p-6 overflow-hidden transition-all duration-300 border shadow-xl bg-white/95 backdrop-blur-md rounded-3xl md:p-8 border-primary-orange/10 hover:shadow-2xl hover:-translate-y-1">
                <div
                    class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-primary-orange via-light-brown to-dark-brown rounded-t-3xl">
                </div>

                <div class="flex items-center gap-3 mb-6">
                    <div
                        class="flex items-center justify-center w-12 h-12 text-xl text-white rounded-full bg-gradient-to-br from-primary-orange to-light-brown">
                        <i class="fas fa-coffee"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-dark-brown">II. Keterangan Usaha</h3>
                </div>

                <div class="grid grid-cols-1 gap-6 mb-6 md:grid-cols-2">
                    <div>
                        <label for="namaUsaha" class="block mb-2 text-sm font-medium text-dark-brown">
                            Nama Kedai Kopi <span class="font-bold text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="text" id="namaUsaha" name="nama_kedai"
                                placeholder="Masukkan nama kedai kopi" required value="{{ old('nama_kedai') }}"
                                class="bg-white-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-orange focus:border-primary-orange block w-full p-2.5 pr-10 {{ $errors->has('nama_kedai') ? 'error-border' : '' }}">
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <i class="fas fa-coffee text-light-brown"></i>
                            </div>
                        </div>
                        @if ($errors->has('nama_kedai'))
                            <p class="error-text">{{ $errors->first('nama_kedai') }}</p>
                        @endif
                    </div>
                    <div>
                        <label for="namaPemilik" class="block mb-2 text-sm font-medium text-dark-brown">
                            Nama Pemilik Usaha <span class="font-bold text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="text" id="namaPemilik" name="nama_pemilik" required
                                placeholder="Masukkan nama pemilik usaha" value="{{ old('nama_pemilik') }}"
                                class="bg-white-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-orange focus:border-primary-orange block w-full p-2.5 pr-10 {{ $errors->has('nama_pemilik') ? 'error-border' : '' }}">
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <i class="fas fa-user text-light-brown"></i>
                            </div>
                        </div>
                        @if ($errors->has('nama_pemilik'))
                            <p class="error-text">{{ $errors->first('nama_pemilik') }}</p>
                        @endif
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-6 mb-6 md:grid-cols-2">
                    <div>
                        <label for="jenisKelamin" class="block mb-2 text-sm font-medium text-dark-brown">
                            Jenis Kelamin <span class="font-bold text-red-500">*</span>
                        </label>
                        <select id="jenisKelamin" name="jenis_kelamin" required
                            class="bg-white-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-orange focus:border-primary-orange block w-full p-2.5 {{ $errors->has('jenis_kelamin') ? 'error-border' : '' }}">
                            <option value="">Pilih Jenis Kelamin</option>
                            <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki
                            </option>
                            <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan
                            </option>
                        </select>
                        @if ($errors->has('jenis_kelamin'))
                            <p class="error-text">{{ $errors->first('jenis_kelamin') }}</p>
                        @endif
                    </div>
                    <div>
                        <label for="handphone" class="block mb-2 text-sm font-medium text-dark-brown">
                            Handphone (Nomor HP) <span class="font-bold text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="tel" id="handphone" name="handphone" required
                                placeholder="08xxxxxxxxxx" value="{{ old('handphone') }}"
                                class="bg-white-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-orange focus:border-primary-orange block w-full p-2.5 pr-10 {{ $errors->has('handphone') ? 'error-border' : '' }}">
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <i class="fas fa-mobile-alt text-light-brown"></i>
                            </div>
                        </div>
                        @if ($errors->has('handphone'))
                            <p class="error-text">{{ $errors->first('handphone') }}</p>
                        @else
                            <p class="mt-1 text-xs text-light-brown/70">Contoh: 08123456789 (min 10 digit, max 13
                                digit)</p>
                            <p class="mt-1 text-xs text-gray-500">Provider yang didukung: Telkomsel, Indosat, XL, Tri,
                                Smartfren</p>
                        @endif
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-6 mb-6 md:grid-cols-2">
                    <div>
                        <label for="omzet" class="block mb-2 text-sm font-medium text-dark-brown">
                            Rata-rata Omzet Sebulan selama 2025 <span class="font-bold text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="text" id="omzet" name="omzet" placeholder="Rp 0" required
                                value="{{ old('omzet') }}"
                                class="bg-white-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-orange focus:border-primary-orange block w-full p-2.5 pr-10 {{ $errors->has('omzet') ? 'error-border' : '' }}">
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <i class="fas fa-money-bill-wave text-light-brown"></i>
                            </div>
                        </div>
                        @if ($errors->has('omzet'))
                            <p class="error-text">{{ $errors->first('omzet') }}</p>
                        @else
                            <div class="mt-1 space-y-1">
                                <p class="text-xs text-light-brown/70">Omzet = Pendapatan kotor (seluruh pendapatan
                                    yang diterima sebelum dikurangi biaya usaha)</p>
                                <p class="text-xs text-light-brown/70">Termasuk pendapatan sewa (jika ada)</p>
                            </div>
                        @endif
                        <div id="omzetWarning" class="hidden">
                            <p class="warning-text">⚠️ WARNING: Rata-rata omzet sebulan/Jumlah pekerja HARUS lebih
                                besar dari 1 juta Rp</p>
                        </div>
                    </div>
                    <div>
                        <label for="jumlahPekerja" class="block mb-2 text-sm font-medium text-dark-brown">
                            Jumlah Pekerja Sebulan Terakhir <span class="font-bold text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="number" id="jumlahPekerja" name="jumlah_pekerja" placeholder="0"
                                min="1" required value="{{ old('jumlah_pekerja') }}"
                                class="bg-white-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-orange focus:border-primary-orange block w-full p-2.5 pr-10 {{ $errors->has('jumlah_pekerja') ? 'error-border' : '' }}">
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <i class="fas fa-users text-light-brown"></i>
                            </div>
                        </div>
                        @if ($errors->has('jumlah_pekerja'))
                            <p class="error-text">{{ $errors->first('jumlah_pekerja') }}</p>
                        @else
                            <p class="mt-1 text-xs text-light-brown/70">Posisi jumlah pekerja akhir bulan lalu
                                (termasuk pemilik kedai kopi) - satuan: orang</p>
                        @endif
                        <div id="pekerjaWarning" class="hidden">
                            <p class="warning-text">⚠️ WARNING: Rata-rata omzet sebulan/Jumlah pekerja HARUS lebih
                                besar dari 1 juta Rp</p>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-6 md:grid-cols-1">
                    <div>
                        <label for="trenPekerja" class="block mb-2 text-sm font-medium text-dark-brown">
                            Jumlah Pekerja Dibanding Akhir Tahun 2024 <span class="font-bold text-red-500">*</span>
                        </label>
                        <select id="trenPekerja" name="tren_pekerja" required
                            class="bg-white-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-orange focus:border-primary-orange block w-full p-2.5 {{ $errors->has('tren_pekerja') ? 'error-border' : '' }}">
                            <option value="">Pilih Tren Pekerja</option>
                            <option value="naik" {{ old('tren_pekerja') == 'naik' ? 'selected' : '' }}>Naik</option>
                            <option value="turun" {{ old('tren_pekerja') == 'turun' ? 'selected' : '' }}>Turun
                            </option>
                            <option value="tetap" {{ old('tren_pekerja') == 'tetap' ? 'selected' : '' }}>Tetap
                            </option>
                        </select>
                        @if ($errors->has('tren_pekerja'))
                            <p class="error-text">{{ $errors->first('tren_pekerja') }}</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Blok 3: Keterangan Tenant/Stand Penjualan Makanan -->
            <div
                class="relative p-6 overflow-hidden transition-all duration-300 border shadow-xl bg-white/95 backdrop-blur-md rounded-3xl md:p-8 border-primary-orange/10 hover:shadow-2xl hover:-translate-y-1">
                <div
                    class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-primary-orange via-light-brown to-dark-brown rounded-t-3xl">
                </div>

                <div class="flex items-center gap-3 mb-6">
                    <div
                        class="flex items-center justify-center w-12 h-12 text-xl text-white rounded-full bg-gradient-to-br from-primary-orange to-light-brown">
                        <i class="fas fa-building"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-dark-brown">III. Keterangan Tenant/Stand Penjualan Makanan
                    </h3>
                </div>

                <div class="grid grid-cols-1 gap-6 mb-6 md:grid-cols-1">
                    <div>
                        <label for="stanSewa" class="block mb-2 text-sm font-medium text-dark-brown">
                            Jumlah Stan/Tenant yang Disewakan <span class="font-bold text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="number" id="stanSewa" name="stan_sewa" placeholder="0" min="0"
                                required value="{{ old('stan_sewa') }}"
                                class="bg-white-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-orange focus:border-primary-orange block w-full p-2.5 pr-10 {{ $errors->has('stan_sewa') ? 'error-border' : '' }}">
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <i class="fas fa-handshake text-light-brown"></i>
                            </div>
                        </div>
                        @if ($errors->has('stan_sewa'))
                            <p class="error-text">{{ $errors->first('stan_sewa') }}</p>
                        @else
                            <p class="mt-1 text-xs text-light-brown/70">Satuan: stan/tenant</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Blok 4: Catatan -->
            <div
                class="relative p-6 overflow-hidden transition-all duration-300 border shadow-xl bg-white/95 backdrop-blur-md rounded-3xl md:p-8 border-primary-orange/10 hover:shadow-2xl hover:-translate-y-1">
                <div
                    class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-primary-orange via-light-brown to-dark-brown rounded-t-3xl">
                </div>

                <div class="flex items-center gap-3 mb-6">
                    <div
                        class="flex items-center justify-center w-12 h-12 text-xl text-white rounded-full bg-gradient-to-br from-primary-orange to-light-brown">
                        <i class="fas fa-sticky-note"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-dark-brown">Catatan Tambahan</h3>
                </div>

                <div>
                    <label for="catatan" class="block mb-2 text-sm font-medium text-dark-brown">Catatan</label>
                    <textarea id="catatan" name="catatan" rows="4" placeholder="Masukkan catatan tambahan (opsional)"
                        class="block p-2.5 w-full text-sm text-gray-900 bg-white-50 rounded-lg border border-gray-300 focus:ring-primary-orange focus:border-primary-orange {{ $errors->has('catatan') ? 'error-border' : '' }}">{{ old('catatan') }}</textarea>
                    @if ($errors->has('catatan'))
                        <p class="error-text">{{ $errors->first('catatan') }}</p>
                    @endif
                </div>
            </div>

            <!-- Submit Button -->
            <div class="pt-8 text-center">
                <button type="submit" id="submitBtn" disabled
                    class="relative px-12 py-4 overflow-hidden text-lg font-semibold text-center text-white transition-all duration-300 transform rounded-full opacity-50 cursor-not-allowed group bg-gradient-to-r from-primary-orange to-light-brown hover:from-light-brown hover:to-dark-brown focus:ring-4 focus:outline-none focus:ring-primary-orange/50">
                    <span
                        class="absolute inset-0 transition-transform duration-700 transform -translate-x-full -skew-x-12 bg-gradient-to-r from-transparent via-white/30 to-transparent group-hover:translate-x-full"></span>
                    <span class="relative flex items-center justify-center gap-3">
                        <i class="fas fa-paper-plane"></i>
                        Kirim Data
                    </span>
                </button>
                <p class="mt-2 text-sm text-gray-600">
                    <i class="fas fa-info-circle"></i> Tombol akan aktif setelah semua field bertanda <span
                        class="font-bold text-red-500">*</span> (wajib) diisi dengan benar
                </p>
            </div>
        </form>

        <!-- Footer -->
        <footer class="mt-16 mb-8">
            <div
                class="relative overflow-hidden border shadow-lg bg-white/95 backdrop-blur-md rounded-3xl border-primary-orange/10">
                <div
                    class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-primary-orange via-light-brown to-dark-brown rounded-t-3xl">
                </div>

                <div class="p-6 text-center">
                    <div class="flex flex-col items-center gap-4 md:flex-row md:justify-center md:gap-6">
                        <!-- Logo BPS -->
                        <div class="flex items-center gap-3">
                            <div
                                class="flex items-center justify-center w-12 h-12 text-white rounded-full bg-gradient-to-br from-primary-orange to-light-brown">
                                <i class="text-xl fas fa-chart-bar"></i>
                            </div>
                            <div class="text-left">
                                <h4 class="text-sm font-semibold text-dark-brown">Badan Pusat Statistik</h4>
                                <p class="text-xs text-light-brown">Kota Tanjungpinang</p>
                            </div>
                        </div>

                        <!-- Divider -->
                        <div class="hidden w-px h-8 md:block bg-light-brown/30"></div>

                        <!-- Copyright -->
                        <div class="text-center md:text-left">
                            <p class="text-sm text-dark-brown">
                                © 2025 <strong>BPS Kota Tanjungpinang</strong>
                            </p>
                            <p class="mt-1 text-xs text-light-brown">
                                Harmonisasi Jelang Sensus Ekonomi 2026
                            </p>
                        </div>

                        <!-- Divider -->
                        <div class="hidden w-px h-8 md:block bg-light-brown/30"></div>

                        <!-- Social Media -->
                        <div class="flex flex-col gap-2">
                            <a href="https://instagram.com/bps_tanjungpinang" target="_blank"
                                class="flex items-center gap-2 px-3 py-1 text-xs transition-colors rounded-full text-light-brown hover:text-primary-orange hover:bg-primary-orange/10">
                                <i class="fab fa-instagram"></i>
                                <span>@bps_tanjungpinang</span>
                            </a>
                            <a href="http://wa.me/6281363228686" target="_blank"
                                class="flex items-center gap-2 px-3 py-1 text-xs transition-colors rounded-full text-light-brown hover:text-green-600 hover:bg-green-50">
                                <i class="fab fa-whatsapp"></i>
                                <span>081363228686</span>
                            </a>
                        </div>
                    </div>

                    <!-- Additional Info -->
                    <div class="pt-4 mt-4 border-t border-light-brown/20">
                        <div
                            class="flex flex-col gap-2 text-xs text-light-brown/80 md:flex-row md:justify-center md:gap-6">
                            <div class="flex items-center justify-center gap-1">
                                <i class="fas fa-map-marker-alt"></i>
                                <span>Jl. W. R. Supratman No. 01, Km. X, Air Raja, Kota Tanjungpinang</span>
                            </div>
                            <div class="flex items-center justify-center gap-1">
                                <i class="fas fa-phone"></i>
                                <span>0771 7101020</span>
                            </div>
                            <div class="flex items-center justify-center gap-1">
                                <i class="fas fa-envelope"></i>
                                <span>bps.kotatanjungpinang@gmail.com</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    <!-- Warning Modal -->
    <div id="warningModal" class="fixed inset-0 z-50 hidden bg-black bg-opacity-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="w-full max-w-md p-8 mx-auto transition-all transform bg-white rounded-3xl">
                <div class="text-center">
                    <div class="mb-4">
                        <i class="text-6xl text-yellow-500 fas fa-exclamation-triangle"></i>
                    </div>
                    <h3 class="mb-4 text-xl font-semibold text-dark-brown">Peringatan!</h3>
                    <p class="mb-6 text-gray-700">
                        <strong>Rata-rata omzet sebulan/Jumlah pekerja kurang dari 1 juta Rp.</strong><br>
                        Apakah Anda yakin data yang dimasukkan sudah benar?
                    </p>
                    <div class="flex gap-4">
                        <button type="button" id="cancelSubmit"
                            class="flex-1 px-6 py-3 text-sm font-medium text-gray-700 transition-colors bg-gray-200 rounded-full hover:bg-gray-300">
                            Batal, Periksa Kembali
                        </button>
                        <button type="button" id="confirmSubmit"
                            class="flex-1 px-6 py-3 text-sm font-medium text-white transition-colors rounded-full bg-gradient-to-r from-primary-orange to-light-brown hover:from-light-brown hover:to-dark-brown">
                            Ya, Lanjutkan
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Leaflet JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.js"></script>

    <!-- Flowbite JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.1/flowbite.min.js"></script>

    <script>
        // Data kelurahan berdasarkan kecamatan dengan kode yang benar
        const kelurahanData = {
            "010": [ // Bukit Bestari
                {
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
            "020": [ // Tanjungpinang Timur
                {
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
            "030": [ // Tanjungpinang Kota
                {
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
            "040": [ // Tanjungpinang Barat
                {
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

        // Element references
        const kecamatanSelect = document.getElementById('kecamatan');
        const kelurahanSelect = document.getElementById('kelurahan');
        const rtInput = document.getElementById('rt');
        const rwInput = document.getElementById('rw');
        const omzetInput = document.getElementById('omzet');
        const handphoneInput = document.getElementById('handphone');
        const jumlahPekerjaInput = document.getElementById('jumlahPekerja');
        const omzetWarning = document.getElementById('omzetWarning');
        const pekerjaWarning = document.getElementById('pekerjaWarning');
        const submitBtn = document.getElementById('submitBtn');
        const warningModal = document.getElementById('warningModal');
        const cancelSubmit = document.getElementById('cancelSubmit');
        const confirmSubmit = document.getElementById('confirmSubmit');

        // Geo tagging variables - RESET SEMUA
        let map = null;
        let marker = null;
        let currentLat = null;
        let currentLng = null;
        let isConfirmed = false;

        // Geo tagging elements
        const getCurrentLocationBtn = document.getElementById('getCurrentLocation');
        const confirmLocationBtn = document.getElementById('confirmLocation');
        const locationInfo = document.getElementById('locationInfo');
        const mapContainer = document.getElementById('mapContainer');
        const coordinatesDisplay = document.getElementById('coordinatesDisplay');
        const latValue = document.getElementById('latValue');
        const lngValue = document.getElementById('lngValue');

        // Initialize form on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Restore kelurahan options if kecamatan is selected
            if (kecamatanSelect.value) {
                populateKelurahan(kecamatanSelect.value);
            }

            // Add form validation listeners
            addFormValidationListeners();

            // Initialize confirm button state
            if (confirmLocationBtn) {
                confirmLocationBtn.disabled = true;
                confirmLocationBtn.classList.add('opacity-50', 'cursor-not-allowed');
            }

            // EVENT LISTENERS - SIMPLE & CLEAN
            getCurrentLocationBtn.addEventListener('click', function(e) {
                e.preventDefault();
                console.log('Detect location button clicked'); // Debug
                detectLocation();
            });

            confirmLocationBtn.addEventListener('click', function(e) {
                e.preventDefault();
                console.log('Confirm location button clicked'); // Debug
                confirmLocationManually();
            });

            // Add event listener for jumlah pekerja to check warning
            jumlahPekerjaInput.addEventListener('input', checkWarning);
            jumlahPekerjaInput.addEventListener('change', checkWarning);

            // Initial validation check
            setTimeout(updateSubmitButton, 100);

            // Auto-hide success/error messages after 5 seconds
            setTimeout(() => {
                const successMsg = document.querySelector('.bg-gradient-to-r.from-green-50');
                const errorMsg = document.querySelector('.bg-gradient-to-r.from-red-50');
                if (successMsg) successMsg.style.display = 'none';
                if (errorMsg) errorMsg.style.display = 'none';
            }, 5000);
        });

        // Handle kecamatan change
        kecamatanSelect.addEventListener('change', function() {
            const selectedKecamatan = this.value;
            populateKelurahan(selectedKecamatan);
        });

        // Populate kelurahan based on kecamatan
        function populateKelurahan(kecamatan) {
            kelurahanSelect.innerHTML = '<option value="">Pilih Kelurahan</option>';

            if (kecamatan && kelurahanData[kecamatan]) {
                kelurahanSelect.disabled = false;
                kelurahanData[kecamatan].forEach(kelurahan => {
                    const option = document.createElement('option');
                    option.value = kelurahan.kode;
                    option.textContent = kelurahan.nama;
                    // Restore selected value
                    if (option.value === '{{ old('kode_kelurahan') }}') {
                        option.selected = true;
                    }
                    kelurahanSelect.appendChild(option);
                });
            } else {
                kelurahanSelect.disabled = true;
            }
        }

        // Format currency inputs
        function formatCurrency(input) {
            input.addEventListener('input', function() {
                let value = this.value.replace(/[^\d]/g, '');
                if (value) {
                    const numericValue = parseInt(value);
                    const formattedValue = numericValue.toLocaleString('id-ID');
                    this.value = 'Rp ' + formattedValue;
                } else {
                    this.value = '';
                }
                checkWarning();
            });
        }

        formatCurrency(omzetInput);

        // Format phone number with strict validation (NOW REQUIRED)
        function formatPhoneNumber(input) {
            input.addEventListener('input', function() {
                let value = this.value.replace(/[^\d]/g, '');

                // Ensure it starts with 08
                if (value.length > 0 && !value.startsWith('08')) {
                    if (value.startsWith('8')) {
                        value = '0' + value;
                    } else if (value.startsWith('0') && !value.startsWith('08')) {
                        value = '08' + value.substring(1);
                    } else {
                        value = '08' + value;
                    }
                }

                // Limit to 13 digits
                if (value.length > 13) {
                    value = value.substring(0, 13);
                }

                this.value = value;

                // Validate phone number
                validatePhoneNumber(this, value);
                updateSubmitButton();
            });

            input.addEventListener('blur', function() {
                validatePhoneNumber(this, this.value);
                updateSubmitButton();
            });
        }

        function validatePhoneNumber(input, value) {
            const phoneErrorElement = input.parentElement.parentElement.querySelector('.phone-error') ||
                createPhoneErrorElement(input.parentElement.parentElement);

            // Reset styles
            input.classList.remove('error-border');
            phoneErrorElement.classList.add('hidden');

            if (!value) {
                // Empty is not valid anymore (required field)
                input.classList.add('error-border');
                phoneErrorElement.textContent = 'Nomor handphone wajib diisi';
                phoneErrorElement.classList.remove('hidden');
                phoneValidationState.isValid = false;
                return false;
            }

            // Check if starts with 08
            if (!value.startsWith('08')) {
                input.classList.add('error-border');
                phoneErrorElement.textContent = 'Nomor handphone harus dimulai dengan 08';
                phoneErrorElement.classList.remove('hidden');
                phoneValidationState.isValid = false;
                return false;
            }

            // Check length (10-13 digits)
            if (value.length < 10) {
                input.classList.add('error-border');
                phoneErrorElement.textContent = 'Nomor handphone minimal 10 digit';
                phoneErrorElement.classList.remove('hidden');
                phoneValidationState.isValid = false;
                return false;
            }

            if (value.length > 13) {
                input.classList.add('error-border');
                phoneErrorElement.textContent = 'Nomor handphone maksimal 13 digit';
                phoneErrorElement.classList.remove('hidden');
                phoneValidationState.isValid = false;
                return false;
            }

            // Check for valid Indonesian mobile prefixes
            const validPrefixes = ['0811', '0812', '0813', '0814', '0815', '0816', '0817', '0818', '0819',
                '0821', '0822', '0823', '0831', '0832', '0833', '0838', '0851', '0852',
                '0853', '0855', '0856', '0857', '0858', '0859', '0877', '0878', '0879',
                '0881', '0882', '0883', '0884', '0885', '0886', '0887', '0888', '0889',
                '0895', '0896', '0897', '0898', '0899'
            ];

            const prefix4 = value.substring(0, 4);
            if (!validPrefixes.includes(prefix4)) {
                input.classList.add('error-border');
                phoneErrorElement.textContent = 'Nomor handphone tidak valid untuk provider Indonesia';
                phoneErrorElement.classList.remove('hidden');
                phoneValidationState.isValid = false;
                return false;
            }

            // If all validations pass
            phoneValidationState.isValid = true;
            return true;
        }

        function createPhoneErrorElement(parent) {
            const errorElement = document.createElement('p');
            errorElement.className = 'phone-error error-text hidden';
            parent.appendChild(errorElement);
            return errorElement;
        }

        // Phone validation state
        const phoneValidationState = {
            isValid: false // Default to false since it's required now
        };

        formatPhoneNumber(handphoneInput);

        // Format RT/RW to 3-digit (still optional)
        function formatRTRW(input) {
            input.addEventListener('input', function(e) {
                let value = this.value.replace(/[^\d]/g, '');
                if (value.length > 3) {
                    value = value.substring(0, 3);
                }
                this.value = value;
            });

            input.addEventListener('blur', function() {
                let value = this.value.replace(/[^\d]/g, '');
                if (value && value.length > 0) {
                    const numValue = parseInt(value);
                    if (numValue >= 1 && numValue <= 999) {
                        this.value = numValue.toString().padStart(3, '0');
                    }
                }
            });
        }

        formatRTRW(rtInput);
        formatRTRW(rwInput);

        // Check warning for omzet per pekerja
        function checkWarning() {
            const omzetValue = omzetInput.value.replace(/[^\d]/g, '');
            const pekerjaValue = jumlahPekerjaInput.value;

            // Check if both have values and are not 0
            if (omzetValue && pekerjaValue && omzetValue !== '0' && pekerjaValue !== '0') {
                const omzetNum = parseInt(omzetValue);
                const pekerjaNum = parseInt(pekerjaValue);
                const omzetPerPekerja = omzetNum / pekerjaNum;

                if (omzetPerPekerja < 1000000) {
                    // Show warning
                    omzetWarning.classList.remove('hidden');
                    pekerjaWarning.classList.remove('hidden');
                    omzetInput.classList.add('warning-border');
                    jumlahPekerjaInput.classList.add('warning-border');
                } else {
                    // Hide warning
                    omzetWarning.classList.add('hidden');
                    pekerjaWarning.classList.add('hidden');
                    omzetInput.classList.remove('warning-border');
                    jumlahPekerjaInput.classList.remove('warning-border');
                }
            } else {
                // Hide warning when one of the fields is empty or 0
                omzetWarning.classList.add('hidden');
                pekerjaWarning.classList.add('hidden');
                omzetInput.classList.remove('warning-border');
                jumlahPekerjaInput.classList.remove('warning-border');
            }
        }

        // Add required field validation (UPDATED FOR NEW REQUIRED FIELDS)
        function validateRequiredFields() {
            const requiredFields = [{
                    id: 'kecamatan',
                    name: 'Kecamatan'
                },
                {
                    id: 'kelurahan',
                    name: 'Kelurahan'
                },
                {
                    id: 'alamat',
                    name: 'Alamat'
                },
                {
                    id: 'namaUsaha',
                    name: 'Nama Kedai'
                },
                // NEW REQUIRED FIELDS
                {
                    id: 'namaPemilik',
                    name: 'Nama Pemilik'
                },
                {
                    id: 'jenisKelamin',
                    name: 'Jenis Kelamin'
                },
                {
                    id: 'handphone',
                    name: 'Handphone'
                },
                {
                    id: 'omzet',
                    name: 'Omzet'
                },
                {
                    id: 'jumlahPekerja',
                    name: 'Jumlah Pekerja'
                },
                {
                    id: 'trenPekerja',
                    name: 'Tren Pekerja'
                },
                {
                    id: 'stanSewa',
                    name: 'Stan Sewa'
                }
            ];

            let allValid = true;
            const errors = [];

            requiredFields.forEach(field => {
                const element = document.getElementById(field.id);
                if (!element || !element.value.trim()) {
                    allValid = false;
                    errors.push(field.name + ' wajib diisi');
                }
            });

            // Special validation for phone number
            if (!phoneValidationState.isValid) {
                allValid = false;
            }

            // Check if location is confirmed (WAJIB)
            if (!isConfirmed) {
                allValid = false;
                errors.push('Lokasi GPS wajib dikonfirmasi');
            }

            return {
                valid: allValid,
                errors: errors
            };
        }

        function updateSubmitButton() {
            const validation = validateRequiredFields();
            if (validation.valid) {
                submitBtn.disabled = false;
                submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                submitBtn.classList.add('hover:-translate-y-2', 'hover:shadow-2xl');
            } else {
                submitBtn.disabled = true;
                submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
                submitBtn.classList.remove('hover:-translate-y-2', 'hover:shadow-2xl');
            }
        }

        // Add event listeners to all form fields for real-time validation
        function addFormValidationListeners() {
            const formElements = document.querySelectorAll(
                '#businessForm input, #businessForm select, #businessForm textarea');
            formElements.forEach(element => {
                element.addEventListener('input', updateSubmitButton);
                element.addEventListener('change', updateSubmitButton);
                element.addEventListener('blur', updateSubmitButton);
            });
        }

        // Handle form submission
        document.getElementById('businessForm').addEventListener('submit', function(e) {
            e.preventDefault();

            // Final validation check
            const validation = validateRequiredFields();
            if (!validation.valid) {
                // Show validation errors
                alert('Mohon lengkapi semua field yang wajib diisi:\n' + validation.errors.join('\n'));
                return;
            }

            // Check phone number validation specifically
            if (!phoneValidationState.isValid) {
                alert('Nomor handphone tidak valid. Mohon periksa kembali.');
                handphoneInput.focus();
                return;
            }

            // Check if location is confirmed
            if (!isConfirmed) {
                alert('Lokasi GPS wajib dikonfirmasi. Mohon tekan tombol "Deteksi Lokasi Saya" terlebih dahulu.');
                getCurrentLocationBtn.focus();
                return;
            }

            const omzetValue = omzetInput.value.replace(/[^\d]/g, '');
            const pekerjaValue = jumlahPekerjaInput.value;

            // Show warning if omzet per pekerja is too low
            if (omzetValue && pekerjaValue && omzetValue !== '0' && pekerjaValue !== '0') {
                const omzetNum = parseInt(omzetValue);
                const pekerjaNum = parseInt(pekerjaValue);
                const omzetPerPekerja = omzetNum / pekerjaNum;

                if (omzetPerPekerja < 1000000) {
                    warningModal.classList.remove('hidden');
                    return;
                }
            }

            this.submit();
        });

        // Modal event listeners
        cancelSubmit.addEventListener('click', function() {
            warningModal.classList.add('hidden');
        });

        confirmSubmit.addEventListener('click', function() {
            warningModal.classList.add('hidden');
            document.getElementById('businessForm').submit();
        });

        // ALGORITMA BARU - SIMPLE & CLEAN
        function detectLocation() {
            // Update UI
            getCurrentLocationBtn.disabled = true;
            getCurrentLocationBtn.textContent = 'Mencari Lokasi...';

            // Get GPS location
            navigator.geolocation.getCurrentPosition(
                function(position) {
                    // Simpan koordinat
                    currentLat = position.coords.latitude;
                    currentLng = position.coords.longitude;

                    // LANGSUNG KONFIRMASI (sesuai requirement)
                    isConfirmed = true;
                    saveLocationToForm();

                    // Show map
                    showMapWithLocation();

                    // Update UI - SUCCESS
                    getCurrentLocationBtn.disabled = false;
                    getCurrentLocationBtn.textContent = 'Deteksi Ulang';
                    confirmLocationBtn.disabled = true;
                    confirmLocationBtn.textContent = 'Lokasi Dikonfirmasi';

                    locationInfo.className =
                        'flex items-center gap-3 p-4 mb-4 text-sm text-green-800 bg-green-50 rounded-xl border border-green-200';
                    locationInfo.innerHTML =
                        '<i class="text-lg fas fa-check-circle"></i> <span>Lokasi berhasil dikonfirmasi dan disimpan!</span>';

                    updateSubmitButton();
                },
                function(error) {
                    // Update UI - ERROR
                    getCurrentLocationBtn.disabled = false;
                    getCurrentLocationBtn.textContent = 'Deteksi Lokasi Saya';

                    locationInfo.className =
                        'flex items-center gap-3 p-4 mb-4 text-sm text-red-800 bg-red-50 rounded-xl border border-red-200';
                    locationInfo.innerHTML =
                        '<i class="text-lg fas fa-exclamation-triangle"></i> <span>Gagal mendapatkan lokasi. Silakan coba lagi.</span>';
                }, {
                    enableHighAccuracy: true,
                    timeout: 10000,
                    maximumAge: 60000
                }
            );
        }

        function showMapWithLocation() {
            // Show map container
            mapContainer.classList.remove('hidden');
            coordinatesDisplay.classList.remove('hidden');

            // Initialize map jika belum ada
            if (!map) {
                map = L.map('map').setView([currentLat, currentLng], 16);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

                // Custom marker
                const customIcon = L.divIcon({
                    className: 'custom-marker',
                    html: '<i class="fas fa-map-marker-alt" style="color: #f5cf76; font-size: 16px;"></i>',
                    iconSize: [30, 30],
                    iconAnchor: [15, 30]
                });

                marker = L.marker([currentLat, currentLng], {
                    icon: customIcon,
                    draggable: true
                }).addTo(map);

                // Event saat marker digeser
                marker.on('dragend', function(e) {
                    const pos = e.target.getLatLng();
                    currentLat = pos.lat;
                    currentLng = pos.lng;

                    console.log('Marker dragged to:', currentLat, currentLng); // Debug

                    // Reset confirmation
                    isConfirmed = false;

                    // Update UI - HARUS KONFIRMASI
                    confirmLocationBtn.disabled = false;
                    confirmLocationBtn.textContent = 'Konfirmasi Lokasi';
                    confirmLocationBtn.classList.remove('opacity-50', 'cursor-not-allowed');

                    locationInfo.className =
                        'flex items-center gap-3 p-4 mb-4 text-sm text-blue-800 bg-blue-50 rounded-xl border border-blue-200';
                    locationInfo.innerHTML =
                        '<i class="text-lg fas fa-info-circle"></i> <span>Lokasi diubah! Klik "Konfirmasi Lokasi" untuk menyimpan.</span>';

                    updateCoordinatesDisplay();
                    updateSubmitButton();
                });

                // Event saat map diklik
                map.on('click', function(e) {
                    currentLat = e.latlng.lat;
                    currentLng = e.latlng.lng;
                    marker.setLatLng([currentLat, currentLng]);

                    console.log('Map clicked at:', currentLat, currentLng); // Debug

                    // Reset confirmation
                    isConfirmed = false;

                    // Update UI - HARUS KONFIRMASI
                    confirmLocationBtn.disabled = false;
                    confirmLocationBtn.textContent = 'Konfirmasi Lokasi';
                    confirmLocationBtn.classList.remove('opacity-50', 'cursor-not-allowed');

                    locationInfo.className =
                        'flex items-center gap-3 p-4 mb-4 text-sm text-blue-800 bg-blue-50 rounded-xl border border-blue-200';
                    locationInfo.innerHTML =
                        '<i class="text-lg fas fa-info-circle"></i> <span>Lokasi diubah! Klik "Konfirmasi Lokasi" untuk menyimpan.</span>';

                    updateCoordinatesDisplay();
                    updateSubmitButton();
                });
            } else {
                // Update existing map
                map.setView([currentLat, currentLng], 16);
                marker.setLatLng([currentLat, currentLng]);
            }

            updateCoordinatesDisplay();

            // Resize map
            setTimeout(() => {
                map.invalidateSize();
            }, 300);
        }

        function confirmLocationManually() {
            console.log('Confirm button clicked!'); // Debug
            console.log('Current coords:', currentLat, currentLng, 'Confirmed:', isConfirmed);

            if (currentLat && currentLng && !isConfirmed) {
                console.log('Confirming location...'); // Debug

                // Konfirmasi
                isConfirmed = true;
                saveLocationToForm();

                // Update UI - SUCCESS
                confirmLocationBtn.disabled = true;
                confirmLocationBtn.textContent = 'Lokasi Dikonfirmasi';
                confirmLocationBtn.classList.add('opacity-50', 'cursor-not-allowed');

                locationInfo.className =
                    'flex items-center gap-3 p-4 mb-4 text-sm text-green-800 bg-green-50 rounded-xl border border-green-200';
                locationInfo.innerHTML =
                    '<i class="text-lg fas fa-check-circle"></i> <span>Lokasi berhasil dikonfirmasi dan disimpan!</span>';

                updateSubmitButton();

                console.log('Location confirmed successfully!'); // Debug
            } else {
                console.log('Cannot confirm - missing data or already confirmed'); // Debug
            }
        }

        function updateCoordinatesDisplay() {
            if (currentLat && currentLng) {
                latValue.textContent = currentLat.toFixed(6);
                lngValue.textContent = currentLng.toFixed(6);
            }
        }

        function saveLocationToForm() {
            if (!currentLat || !currentLng || !isConfirmed) return;

            // Remove existing inputs
            const existingLat = document.querySelector('input[name="latitude"]');
            const existingLng = document.querySelector('input[name="longitude"]');
            if (existingLat) existingLat.remove();
            if (existingLng) existingLng.remove();

            // Add new inputs
            const latInput = document.createElement('input');
            latInput.type = 'hidden';
            latInput.name = 'latitude';
            latInput.value = currentLat;

            const lngInput = document.createElement('input');
            lngInput.type = 'hidden';
            lngInput.name = 'longitude';
            lngInput.value = currentLng;

            document.getElementById('businessForm').appendChild(latInput);
            document.getElementById('businessForm').appendChild(lngInput);
        }
    </script>
</body>

</html>
