<x-app-layout>
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.css" />
    <!-- Flowbite CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.1/flowbite.min.css" rel="stylesheet" />

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">

            <!-- AREA IMPLEMENTASI -->
            <div
                class="overflow-hidden border shadow-xl bg-white/95 backdrop-blur-sm rounded-xl border-primary-orange/20 sm:rounded-lg">
                <div class="p-6 lg:p-8">
                    <div class="flex items-center mb-6">
                        <svg class="w-6 h-6 text-primary-orange" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7">
                            </path>
                        </svg>
                        <h1 class="ml-2 text-2xl font-medium text-dark-brown">
                            Dashboard Peta Kedai Kopi
                        </h1>
                    </div>

                    <!-- STATUS DATA -->
                    <div class="grid grid-cols-1 gap-4 mb-6 md:grid-cols-4">
                        <div class="p-4 border rounded-lg bg-primary-orange/10 border-primary-orange/20">
                            <h3 class="font-semibold text-dark-brown">Total Kedai</h3>
                            <p class="text-2xl font-bold text-primary-orange" id="stats-total">{{ $totalKedai }}</p>
                        </div>
                        <div class="p-4 border rounded-lg bg-bright-orange/10 border-bright-orange/20">
                            <h3 class="font-semibold text-dark-brown">Input Mandiri</h3>
                            <p class="text-2xl font-bold text-bright-orange" id="stats-mandiri">
                                {{ $kedaiData->where('sumber', 'mandiri')->count() }}
                            </p>
                        </div>
                        <div class="p-4 border rounded-lg bg-medium-brown/10 border-medium-brown/20">
                            <h3 class="font-semibold text-dark-brown">Input Mitra</h3>
                            <p class="text-2xl font-bold text-medium-brown" id="stats-mitra">
                                {{ $kedaiData->where('sumber', 'mitra')->count() }}
                            </p>
                        </div>
                        <div class="p-4 border rounded-lg bg-light-brown/10 border-light-brown/20">
                            <h3 class="font-semibold text-dark-brown">Dengan GPS</h3>
                            <p class="text-2xl font-bold text-light-brown" id="stats-gps">{{ $kedaiWithLocation }}</p>
                        </div>
                    </div>

                    <!-- FILTER PANEL -->
                    <div class="py-2">
                        <div class="mx-auto max-w-7xl sm:px-2 lg:px-3">

                            <!-- AREA IMPLEMENTASI -->
                            <div
                                class="overflow-hidden border shadow-xl bg-white/95 backdrop-blur-sm rounded-xl border-primary-orange/20 sm:rounded-lg">
                                <div class="p-6 lg:p-8">

                                    <!-- FILTER PANEL - 1 BARIS -->
                                    <div class="grid grid-cols-1 gap-4 mb-6 md:grid-cols-4">
                                        <!-- Kecamatan Filter -->
                                        <div
                                            class="p-4 border rounded-lg bg-primary-orange/10 border-primary-orange/20">
                                            <label for="kecamatan"
                                                class="block text-sm font-semibold text-dark-brown">Kecamatan</label>
                                            <select id="kecamatan"
                                                class="w-full p-2 mt-2 border rounded-md border-primary-orange/30 focus:ring-2 focus:ring-primary-orange focus:border-primary-orange">
                                                <option value="">Pilih Kecamatan</option>
                                                @foreach ($kecamatanNames as $kode => $nama)
                                                    <option value="{{ $kode }}">{{ $nama }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <!-- Kelurahan Filter -->
                                        <div class="p-4 border rounded-lg bg-bright-orange/10 border-bright-orange/20">
                                            <label for="kelurahan"
                                                class="block text-sm font-semibold text-dark-brown">Kelurahan</label>
                                            <select id="kelurahan"
                                                class="w-full p-2 mt-2 border rounded-md border-bright-orange/30 focus:ring-2 focus:ring-bright-orange focus:border-bright-orange">
                                                <option value="">Pilih Kelurahan</option>
                                                <!-- Kelurahan options will be updated based on Kecamatan -->
                                            </select>
                                        </div>

                                        <!-- Sumber Filter -->
                                        <div class="p-4 border rounded-lg bg-medium-brown/10 border-medium-brown/20">
                                            <label for="sumber"
                                                class="block text-sm font-semibold text-dark-brown">Sumber</label>
                                            <select id="sumber"
                                                class="w-full p-2 mt-2 border rounded-md border-medium-brown/30 focus:ring-2 focus:ring-medium-brown focus:border-medium-brown">
                                                <option value="">Pilih Sumber</option>
                                                <option value="mandiri">Mandiri</option>
                                                <option value="mitra">Mitra</option>
                                            </select>
                                        </div>

                                        <!-- Reset Filter Button -->
                                        <div class="p-4 border rounded-lg bg-light-brown/10 border-light-brown/20">
                                            <label class="block text-sm font-semibold text-dark-brown">Action</label>
                                            <button id="resetFilters"
                                                class="w-full p-2 mt-2 text-white transition-colors rounded-md bg-primary-orange hover:bg-bright-orange">Reset
                                                Filters</button>
                                        </div>
                                    </div>

                                    <!-- PETA INTERAKTIF -->
                                    <div class="border-2 rounded-lg border-cream-yellow/30">
                                        <div id="map" style="height: 400px; width: 100%;"></div>
                                    </div>

                                    <!-- Filter Status -->

                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Leaflet JS -->
                    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.js"></script>
                    <!-- Flowbite JS -->
                    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.1/flowbite.min.js"></script>

                    <style>
                        /* Custom marker styles with project theme */
                        .custom-marker-mandiri {
                            background: linear-gradient(135deg, #e44012, #f58741);
                            border: 3px solid #f5cf76;
                            border-radius: 50% 50% 50% 0;
                            box-shadow: 0 4px 12px rgba(228, 64, 18, 0.4);
                            transform: rotate(-45deg);
                            cursor: pointer;
                            transition: all 0.3s ease;
                            width: 28px;
                            height: 28px;
                        }

                        .custom-marker-mitra {
                            background: linear-gradient(135deg, #581908, #8e4917);
                            border: 3px solid #f5cf76;
                            border-radius: 50% 50% 50% 0;
                            box-shadow: 0 4px 12px rgba(88, 25, 8, 0.4);
                            transform: rotate(-45deg);
                            cursor: pointer;
                            transition: all 0.3s ease;
                            width: 28px;
                            height: 28px;
                        }

                        .custom-marker-mandiri:hover,
                        .custom-marker-mitra:hover {
                            transform: rotate(-45deg) scale(1.1);
                            box-shadow: 0 6px 16px rgba(63, 16, 0, 0.6);
                        }

                        .custom-marker-mandiri i,
                        .custom-marker-mitra i {
                            transform: rotate(45deg);
                            display: block;
                            margin-top: 5px;
                            margin-left: 1px;
                            color: #f5cf76;
                            font-size: 14px;
                        }

                        /* Leaflet popup customization */
                        .leaflet-popup-content-wrapper {
                            background: linear-gradient(135deg, #f5cf76 0%, #ffffff 100%) !important;
                            border-radius: 12px !important;
                            box-shadow: 0 8px 25px rgba(63, 16, 0, 0.25) !important;
                            border: 2px solid #f58741 !important;
                        }

                        .leaflet-popup-content {
                            color: #3f1000 !important;
                            font-family: 'Poppins', sans-serif !important;
                            font-weight: 500 !important;
                            margin: 12px 16px !important;
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
                    </style>

                    <script>
                        // Data kedai untuk JavaScript
                        const kedaiData = @json($kedaiData);

                        // Data struktur kelurahan untuk dropdown dinamis
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

                        // Nama kecamatan
                        const kecamatanNames = {
                            '010': 'Bukit Bestari',
                            '020': 'Tanjungpinang Timur',
                            '030': 'Tanjungpinang Kota',
                            '040': 'Tanjungpinang Barat'
                        };

                        // Initialize the map
                        const map = L.map('map').setView([0.9078, 104.4583], 13);

                        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                        }).addTo(map);

                        // Function to create custom markers berdasarkan sumber
                        function createCustomMarker(kedai) {
                            const markerClass = kedai.sumber === 'mandiri' ? 'custom-marker-mandiri' : 'custom-marker-mitra';

                            const customIcon = L.divIcon({
                                className: markerClass,
                                html: '<i class="fas fa-coffee"></i>',
                                iconSize: [28, 28],
                                iconAnchor: [14, 28],
                                popupAnchor: [0, -28]
                            });

                            return L.marker([kedai.latitude, kedai.longitude], {
                                icon: customIcon
                            });
                        }

                        // Function to update markers based on filters
                        function updateMarkers() {
                            const kecamatanFilter = document.getElementById('kecamatan').value;
                            const kelurahanFilter = document.getElementById('kelurahan').value;
                            const sumberFilter = document.getElementById('sumber').value;

                            const filteredData = kedaiData.filter(kedai => {
                                const matchesKecamatan = kecamatanFilter ? kedai.kode_kecamatan === kecamatanFilter : true;
                                const matchesKelurahan = kelurahanFilter ? kedai.kode_kelurahan === kelurahanFilter : true;
                                const matchesSumber = sumberFilter ? kedai.sumber === sumberFilter : true;
                                return matchesKecamatan && matchesKelurahan && matchesSumber;
                            });

                            map.eachLayer(layer => {
                                if (layer instanceof L.Marker) {
                                    map.removeLayer(layer);
                                }
                            });

                            filteredData.forEach(kedai => {
                                if (kedai.latitude && kedai.longitude) {
                                    const marker = createCustomMarker(kedai);
                                    marker.bindPopup(
                                        `<strong>${kedai.nama_kedai}</strong><br>${kedai.alamat}<br><small>Sumber: ${kedai.sumber === 'mandiri' ? 'Input Mandiri' : 'Input Mitra'}</small>`
                                    );
                                    marker.addTo(map);
                                }
                            });

                            if (filteredData.length > 0) {
                                const bounds = new L.LatLngBounds(filteredData.map(kedai => [kedai.latitude, kedai.longitude]));
                                map.fitBounds(bounds);
                            }
                        }

                        // Function to populate Kelurahan options based on selected Kecamatan
                        document.getElementById('kecamatan').addEventListener('change', function() {
                            const selectedKecamatan = this.value;
                            const kelurahanSelect = document.getElementById('kelurahan');
                            kelurahanSelect.innerHTML = '<option value="">Semua Kelurahan</option>';

                            if (selectedKecamatan && kelurahanData[selectedKecamatan]) {
                                kelurahanData[selectedKecamatan].forEach(kelurahan => {
                                    const option = document.createElement('option');
                                    option.value = kelurahan.kode;
                                    option.textContent = kelurahan.nama;
                                    kelurahanSelect.appendChild(option);
                                });
                            }

                            updateMarkers();
                        });

                        document.getElementById('kelurahan').addEventListener('change', updateMarkers);
                        document.getElementById('sumber').addEventListener('change', updateMarkers);

                        // Reset filters button
                        document.getElementById('resetFilters').addEventListener('click', function() {
                            document.getElementById('kecamatan').value = '';
                            document.getElementById('kelurahan').innerHTML = '<option value="">Semua Kelurahan</option>';
                            document.getElementById('sumber').value = '';
                            updateMarkers();
                        });

                        // Initial call to update markers
                        updateMarkers();
                    </script>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
