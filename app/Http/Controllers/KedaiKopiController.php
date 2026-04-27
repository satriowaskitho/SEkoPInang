<?php

namespace App\Http\Controllers;

use App\Models\KedaiKopi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class KedaiKopiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = KedaiKopi::query();

        // Search functionality
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // Filter by kecamatan
        if ($request->filled('kecamatan')) {
            $query->byKecamatan($request->kecamatan);
        }

        // Filter by kelurahan
        if ($request->filled('kelurahan')) {
            $query->byKelurahan($request->kelurahan);
        }

        // Filter by omzet range
        if ($request->filled('omzet_min') && $request->filled('omzet_max')) {
            $query->byOmzetRange($request->omzet_min, $request->omzet_max);
        }

        // Filter by jumlah pekerja range
        if ($request->filled('pekerja_min') && $request->filled('pekerja_max')) {
            $query->byJumlahPekerjaRange($request->pekerja_min, $request->pekerja_max);
        }

        // Filter by location availability
        if ($request->filled('with_location') && $request->with_location == '1') {
            $query->withLocation();
        }

        // Filter by sumber - TAMBAHAN BARU
        if ($request->filled('sumber')) {
            $query->bySumber($request->sumber);
        }

        $kedaiKopi = $query->latest()->paginate(10);

        return view('kedai-kopi.index', compact('kedaiKopi'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('form');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Log semua data yang masuk untuk debugging
        Log::info('Data yang masuk ke store:', $request->all());

        try {
            $validated = $request->validate([
                // Required fields (yang tetap wajib)
                'kode_kecamatan' => 'required|in:010,020,030,040',
                'kode_kelurahan' => 'required|string|max:3',
                'alamat' => 'required|string|max:500',
                'latitude' => 'required|numeric|between:-90,90', // Latitude wajib
                'longitude' => 'required|numeric|between:-180,180', // Longitude wajib
                'nama_kedai' => 'required|string|max:255',

                // Previously optional fields - NOW REQUIRED AGAIN
                'nama_pemilik' => 'required|string|max:255',
                'jenis_kelamin' => 'required|in:L,P',
                'handphone' => 'required|string|min:10|max:20',
                'omzet' => 'required|string',
                'jumlah_pekerja' => 'required|integer|min:1',
                'stan_sewa' => 'required|integer|min:0',
                'tren_pekerja' => 'required|string|in:naik,turun,tetap',
                'sumber' => 'required|string|in:mandiri,mitra', // TAMBAHAN BARU

                // Still optional fields
                'rw' => 'nullable|string|max:3',
                'rt' => 'nullable|string|max:3',
                'catatan' => 'nullable|string|max:1000',
            ], [
                // Required fields validation messages
                'kode_kecamatan.required' => 'Kecamatan wajib dipilih',
                'kode_kecamatan.in' => 'Kecamatan tidak valid',
                'kode_kelurahan.required' => 'Kelurahan wajib dipilih',
                'alamat.required' => 'Alamat kedai wajib diisi',
                'latitude.required' => 'Lokasi GPS wajib dikonfirmasi',
                'latitude.numeric' => 'Latitude harus berupa angka',
                'latitude.between' => 'Latitude tidak valid',
                'longitude.required' => 'Lokasi GPS wajib dikonfirmasi',
                'longitude.numeric' => 'Longitude harus berupa angka',
                'longitude.between' => 'Longitude tidak valid',
                'nama_kedai.required' => 'Nama kedai wajib diisi',

                // Previously optional fields - NOW REQUIRED
                'nama_pemilik.required' => 'Nama pemilik wajib diisi',
                'nama_pemilik.max' => 'Nama pemilik maksimal 255 karakter',
                'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih',
                'jenis_kelamin.in' => 'Jenis kelamin tidak valid',
                'handphone.required' => 'Nomor handphone wajib diisi',
                'handphone.min' => 'Nomor handphone minimal 10 digit',
                'handphone.max' => 'Nomor handphone maksimal 20 digit',
                'omzet.required' => 'Rata-rata omzet wajib diisi',
                'jumlah_pekerja.required' => 'Jumlah pekerja wajib diisi',
                'jumlah_pekerja.integer' => 'Jumlah pekerja harus berupa angka',
                'jumlah_pekerja.min' => 'Jumlah pekerja minimal 1 orang',
                'stan_sewa.required' => 'Jumlah stan sewa wajib diisi',
                'stan_sewa.integer' => 'Jumlah stan sewa harus berupa angka',
                'stan_sewa.min' => 'Jumlah stan sewa tidak boleh kurang dari 0',
                'tren_pekerja.required' => 'Tren pekerja wajib dipilih',
                'tren_pekerja.in' => 'Tren pekerja tidak valid',
                'sumber.required' => 'Sumber data wajib diisi', // TAMBAHAN BARU
                'sumber.in' => 'Sumber data tidak valid', // TAMBAHAN BARU
            ]);

            Log::info('Data setelah validasi:', $validated);

            DB::beginTransaction();

            // Process omzet - remove 'Rp' and dots, convert to integer
            $omzetValue = str_replace(['Rp', '.', ' '], '', $validated['omzet']);
            $omzetValue = (int) $omzetValue;

            Log::info('Omzet setelah diproses:', ['original' => $validated['omzet'], 'processed' => $omzetValue]);

            // Prepare data for saving
            $data = $validated;
            $data['omzet'] = $omzetValue;
            $data['kode_kota'] = '2172'; // Default for Tanjungpinang

            // Handle RT/RW - set default value jika kosong (these remain optional)
            if (empty($data['rt']) || $data['rt'] === '') {
                $data['rt'] = '000'; // Default value
            }
            if (empty($data['rw']) || $data['rw'] === '') {
                $data['rw'] = '000'; // Default value
            }

            // Handle optional catatan field
            if (empty($data['catatan']) || $data['catatan'] === '') {
                $data['catatan'] = null;
            }

            Log::info('Data yang akan disimpan:', $data);

            // Create the record
            $kedaiKopi = KedaiKopi::create($data);

            Log::info('Data berhasil disimpan:', ['id' => $kedaiKopi->id]);

            DB::commit();

            return redirect()->route('form')->with('success', [
                'message' => 'Data kedai kopi berhasil disimpan!',
                'data' => [
                    'nama_kedai' => $kedaiKopi->nama_kedai,
                    'nama_pemilik' => $kedaiKopi->nama_pemilik,
                    'created_at' => Carbon::now('Asia/Jakarta'),
                ]
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation error:', ['errors' => $e->errors()]);
            throw $e; // Re-throw validation exception untuk ditangani Laravel
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error saat menyimpan data kedai kopi:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\KedaiKopi  $kedaiKopi
     * @return \Illuminate\Http\Response
     */
    public function show(KedaiKopi $kedaiKopi)
    {
        return view('kedai-kopi.show', compact('kedaiKopi'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\KedaiKopi  $kedaiKopi
     * @return \Illuminate\Http\Response
     */
    public function edit(KedaiKopi $kedaiKopi)
    {
        return view('kedai-kopi.edit', compact('kedaiKopi'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\KedaiKopi  $kedaiKopi
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, KedaiKopi $kedaiKopi)
    {
        try {
            $validated = $request->validate([
                // Required fields (yang tetap wajib)
                'kode_kecamatan' => 'required|in:010,020,030,040',
                'kode_kelurahan' => 'required|string|max:3',
                'alamat' => 'required|string|max:500',
                'latitude' => 'nullable|numeric|between:-90,90', // Update bisa nullable
                'longitude' => 'nullable|numeric|between:-180,180', // Update bisa nullable
                'nama_kedai' => 'required|string|max:255',

                // Previously optional fields - NOW REQUIRED AGAIN
                'nama_pemilik' => 'required|string|max:255',
                'jenis_kelamin' => 'required|in:L,P',
                'handphone' => 'required|string|min:10|max:20',
                'omzet' => 'required|string',
                'jumlah_pekerja' => 'required|integer|min:1',
                'stan_sewa' => 'required|integer|min:0',
                'tren_pekerja' => 'required|string|in:naik,turun,tetap',
                'sumber' => 'required|string|in:mandiri,mitra', // TAMBAHAN BARU

                // Still optional fields
                'rw' => 'nullable|string|max:3',
                'rt' => 'nullable|string|max:3',
                'catatan' => 'nullable|string|max:1000',
            ]);

            DB::beginTransaction();

            // Process omzet - remove 'Rp' and dots, convert to integer
            $omzetValue = str_replace(['Rp', '.', ' '], '', $validated['omzet']);
            $omzetValue = (int) $omzetValue;

            // Prepare data for updating
            $data = $validated;
            $data['omzet'] = $omzetValue;

            // Handle RT/RW - set default value jika kosong (these remain optional)
            if (empty($data['rt']) || $data['rt'] === '') {
                $data['rt'] = '000'; // Default value
            }
            if (empty($data['rw']) || $data['rw'] === '') {
                $data['rw'] = '000'; // Default value
            }

            // Handle optional catatan field
            if (empty($data['catatan']) || $data['catatan'] === '') {
                $data['catatan'] = null;
            }

            // Update the record
            $kedaiKopi->update($data);

            DB::commit();

            return redirect()->route('kedai-kopi.show', $kedaiKopi)
                ->with('success', 'Data kedai kopi berhasil diperbarui!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error saat update data kedai kopi:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat memperbarui data: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage (soft delete).
     *
     * @param  \App\Models\KedaiKopi  $kedaiKopi
     * @return \Illuminate\Http\Response
     */
    public function destroy(KedaiKopi $kedaiKopi)
    {
        try {
            $kedaiKopi->delete();

            return redirect()->route('kedai-kopi.index')
                ->with('success', 'Data kedai kopi berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menghapus data. Silakan coba lagi.');
        }
    }

    /**
     * Restore the specified resource from soft delete.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        try {
            $kedaiKopi = KedaiKopi::withTrashed()->findOrFail($id);
            $kedaiKopi->restore();

            return redirect()->route('kedai-kopi.index')
                ->with('success', 'Data kedai kopi berhasil dipulihkan!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat memulihkan data. Silakan coba lagi.');
        }
    }

    /**
     * Force delete the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function forceDelete($id)
    {
        try {
            $kedaiKopi = KedaiKopi::withTrashed()->findOrFail($id);
            $kedaiKopi->forceDelete();

            return redirect()->route('kedai-kopi.index')
                ->with('success', 'Data kedai kopi berhasil dihapus permanen!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menghapus data secara permanen. Silakan coba lagi.');
        }
    }

    /**
     * Display public monitoring dashboard - MANDIRI + MITRA DATA.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function monitor(Request $request)
    {
        // Get statistics from ALL data (mandiri + mitra)
        $stats = $this->getStatisticsForMonitor();
        $totalKedai = $stats['total_kedai'];
        $totalPekerja = $stats['total_pekerja'];
        $avgOmzet = $stats['avg_omzet'];
        $kedaiWithLocation = $stats['kedai_with_location'];
        $kedaiByKecamatan = $stats['kedai_by_kecamatan'];
        $totalMandiri = $stats['kedai_mandiri'];
        $totalMitra = $stats['kedai_mitra'];

        $kecamatanNames = [
            '010' => 'Bukit Bestari',
            '020' => 'Tanjungpinang Timur',
            '030' => 'Tanjungpinang Kota',
            '040' => 'Tanjungpinang Barat',
        ];

        $progressVerifikasi = $kedaiWithLocation > 0 && $totalKedai > 0 ?
            ($kedaiWithLocation / $totalKedai) * 100 : 0;
        $tanpaLokasi = $totalKedai - $kedaiWithLocation;

        // Ensure all filter parameters are strings (not arrays)
        $search = $this->ensureString($request->get('search'));
        $kecamatan = $this->ensureString($request->get('kecamatan'));
        $kelurahan = $this->ensureString($request->get('kelurahan'));
        $lokasi = $this->ensureString($request->get('lokasi'));
        $sumber = $this->ensureString($request->get('sumber')); // Filter sumber (mandiri/mitra/semua)
        $sortBy = $this->ensureString($request->get('sort', 'created_at'));
        $sortDir = $this->ensureString($request->get('direction', 'desc'));
        $currentPage = (int) $request->get('page', 1);
        $perPage = 15;

        // Query dengan filter dan sort (PUBLIC VERSION - MANDIRI + MITRA)
        $query = KedaiKopi::select([
            'id',
            'nama_kedai',
            'kode_kecamatan',
            'kode_kelurahan',
            'rt',
            'rw',
            'alamat',
            'latitude',
            'longitude',
            'omzet',
            'jumlah_pekerja',
            'stan_sewa',
            'created_at',
            'catatan',
            'jenis_kelamin',
            'sumber'
            // Exclude: nama_pemilik, handphone (sensitive data)
        ]);
        // No sumber filter by default — show all

        // Apply filters
        if ($search) {
            $query->where('nama_kedai', 'like', "%{$search}%");
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

        // Filter sumber (opsional)
        if ($sumber !== '') {
            $query->where('sumber', $sumber);
        }

        // Apply sorting
        $validSorts = ['nama_kedai', 'omzet', 'jumlah_pekerja', 'stan_sewa', 'created_at', 'sumber'];
        if (in_array($sortBy, $validSorts)) {
            $query->orderBy($sortBy, $sortDir);
        }

        // Get all filtered data for manual pagination
        $allFilteredData = $query->get();
        $totalFiltered = $allFilteredData->count();

        // Manual pagination
        $offset = ($currentPage - 1) * $perPage;
        $currentPageData = $allFilteredData->slice($offset, $perPage);

        // Calculate pagination info
        $totalPages = ceil($totalFiltered / $perPage);
        $hasPages = $totalPages > 1;

        // Add computed properties to each item
        $currentPageData = $currentPageData->map(function ($kedai) use ($kecamatanNames) {
            // Add kecamatan name
            $kedai->kecamatan_name = $kecamatanNames[$kedai->kode_kecamatan] ?? 'Unknown';

            // Add kelurahan name
            $kedai->kelurahan_name = $this->getKelurahanName($kedai->kode_kecamatan, $kedai->kode_kelurahan);

            // Format omzet - handle null values
            $kedai->formatted_omzet = $kedai->omzet > 0 ?
                'Rp ' . number_format($kedai->omzet, 0, ',', '.') : 'Tidak diisi';

            // Gender text - handle null values
            $kedai->gender_text = $kedai->jenis_kelamin ?
                ($kedai->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan') : 'Tidak diisi';

            // Sumber text
            $kedai->sumber_text = $kedai->sumber === 'mitra' ? 'Input Mitra' : 'Input Mandiri';

            return $kedai;
        });

        // Get latest data update timestamp from ALL data
        $latestDataUpdate = KedaiKopi::latest('created_at')->first();
        $lastUpdateTime = $latestDataUpdate ? $latestDataUpdate->created_at : null;

        return view('monitor', compact(
            'totalKedai',
            'totalPekerja',
            'avgOmzet',
            'kedaiWithLocation',
            'kedaiByKecamatan',
            'kecamatanNames',
            'progressVerifikasi',
            'tanpaLokasi',
            'search',
            'kecamatan',
            'kelurahan',
            'lokasi',
            'sumber',
            'totalMandiri',
            'totalMitra',
            'sortBy',
            'sortDir',
            'currentPage',
            'perPage',
            'totalFiltered',
            'currentPageData',
            'totalPages',
            'hasPages',
            'lastUpdateTime'
        ));
    }

    /**
     * Get statistics for public monitor (MANDIRI + MITRA DATA).
     *
     * @return array
     */
    public function getStatisticsForMonitor()
    {
        $stats = [
            'total_kedai' => KedaiKopi::count(),
            'total_pekerja' => KedaiKopi::whereNotNull('jumlah_pekerja')->sum('jumlah_pekerja'),
            'total_omzet' => KedaiKopi::whereNotNull('omzet')->sum('omzet'),
            'total_stan_sewa' => KedaiKopi::whereNotNull('stan_sewa')->sum('stan_sewa'),
            'avg_omzet' => KedaiKopi::whereNotNull('omzet')->where('omzet', '>', 0)->avg('omzet'),
            'avg_pekerja' => KedaiKopi::whereNotNull('jumlah_pekerja')->where('jumlah_pekerja', '>', 0)->avg('jumlah_pekerja'),
            'kedai_by_kecamatan' => KedaiKopi::selectRaw('kode_kecamatan, COUNT(*) as total')
                ->groupBy('kode_kecamatan')
                ->get(),
            'kedai_with_location' => KedaiKopi::whereNotNull('latitude')
                ->whereNotNull('longitude')
                ->count(),
            'kedai_mandiri' => KedaiKopi::where('sumber', 'mandiri')->count(),
            'kedai_mitra' => KedaiKopi::where('sumber', 'mitra')->count(),
        ];

        return $stats;
    }
    /**
     * Ensure the parameter is a string, not an array
     *
     * @param  mixed  $value
     * @return string
     */
    private function ensureString($value)
    {
        if (is_array($value)) {
            // If it's an array, take the first element or return empty string
            return !empty($value) ? (string) $value[0] : '';
        }

        if (is_null($value)) {
            return '';
        }

        return (string) $value;
    }

    /**
     * Get statistics for dashboard.
     *
     * @return array
     */
    public function getStatistics()
    {
        $stats = [
            'total_kedai' => KedaiKopi::count(),
            'total_pekerja' => KedaiKopi::whereNotNull('jumlah_pekerja')->sum('jumlah_pekerja'),
            'total_omzet' => KedaiKopi::whereNotNull('omzet')->sum('omzet'),
            'total_stan_sewa' => KedaiKopi::whereNotNull('stan_sewa')->sum('stan_sewa'),
            'avg_omzet' => KedaiKopi::whereNotNull('omzet')->where('omzet', '>', 0)->avg('omzet'),
            'avg_pekerja' => KedaiKopi::whereNotNull('jumlah_pekerja')->where('jumlah_pekerja', '>', 0)->avg('jumlah_pekerja'),
            'kedai_by_kecamatan' => KedaiKopi::selectRaw('kode_kecamatan, COUNT(*) as total')
                ->groupBy('kode_kecamatan')
                ->get(),
            'kedai_with_location' => KedaiKopi::whereNotNull('latitude')
                ->whereNotNull('longitude')
                ->count(),
            // TAMBAHAN BARU - Stats berdasarkan sumber
            'kedai_by_sumber' => KedaiKopi::selectRaw('sumber, COUNT(*) as total')
                ->groupBy('sumber')
                ->get(),
            'kedai_mandiri' => KedaiKopi::where('sumber', 'mandiri')->count(),
            'kedai_mitra' => KedaiKopi::where('sumber', 'mitra')->count(),
        ];

        return $stats;
    }

    /**
     * Helper method to get kelurahan name
     * @param string $kecamatanCode
     * @param string $kelurahanCode
     * @return string
     */
    private function getKelurahanName($kecamatanCode, $kelurahanCode)
    {
        $kelurahanData = [
            "010" => [
                "001" => "Dompak",
                "002" => "Tanjungpinang Timur",
                "003" => "Tanjung Ayun Sakti",
                "004" => "Sei Jang",
                "005" => "Tanjung Unggat"
            ],
            "020" => [
                "001" => "Batu Sembilan",
                "002" => "Melayu Kota Piring",
                "003" => "Air Raja",
                "004" => "Pinang Kencana",
                "005" => "Kampung Bulang"
            ],
            "030" => [
                "001" => "Tanjungpinang Kota",
                "002" => "Penyengat",
                "003" => "Kampung Bugis",
                "004" => "Senggarang"
            ],
            "040" => [
                "001" => "Tanjungpinang Barat",
                "002" => "Kemboja",
                "003" => "Kampung Baru",
                "004" => "Bukit Cermin"
            ]
        ];

        return $kelurahanData[$kecamatanCode][$kelurahanCode] ?? 'Unknown';
    }
    /**
     * Show the form for creating a new resource (MITRA).
     *
     * @return \Illuminate\Http\Response
     */
    public function createMitra()
    {
        return view('mitra');
    }

    /**
     * Store a newly created resource in storage (MITRA VERSION - FIELD OPSIONAL).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeMitra(Request $request)
    {
        // Log semua data yang masuk untuk debugging
        Log::info('Data yang masuk ke store mitra:', $request->all());

        try {
            $validated = $request->validate([
                // Required fields - HANYA YANG WAJIB UNTUK MITRA
                'kode_kecamatan' => 'required|in:010,020,030,040',
                'kode_kelurahan' => 'required|string|max:3',
                'alamat' => 'required|string|max:500',
                'latitude' => 'required|numeric|between:-90,90', // Latitude wajib
                'longitude' => 'required|numeric|between:-180,180', // Longitude wajib
                'nama_kedai' => 'required|string|max:255',
                'sumber' => 'required|string|in:mandiri,mitra', // Sumber wajib

                // FIELD YANG DIBUAT OPSIONAL UNTUK MITRA
                'nama_pemilik' => 'nullable|string|max:255',
                'jenis_kelamin' => 'nullable|in:L,P',
                'handphone' => 'nullable|string|min:10|max:20',
                'omzet' => 'nullable|string',
                'jumlah_pekerja' => 'nullable|integer|min:0',
                'stan_sewa' => 'nullable|integer|min:0',
                'tren_pekerja' => 'nullable|string|in:naik,turun,tetap',

                // Still optional fields (sama seperti form mandiri)
                'rw' => 'nullable|string|max:3',
                'rt' => 'nullable|string|max:3',
                'catatan' => 'nullable|string|max:1000',
            ], [
                // Required fields validation messages
                'kode_kecamatan.required' => 'Kecamatan wajib dipilih',
                'kode_kecamatan.in' => 'Kecamatan tidak valid',
                'kode_kelurahan.required' => 'Kelurahan wajib dipilih',
                'alamat.required' => 'Alamat kedai wajib diisi',
                'latitude.required' => 'Lokasi GPS wajib dikonfirmasi',
                'latitude.numeric' => 'Latitude harus berupa angka',
                'latitude.between' => 'Latitude tidak valid',
                'longitude.required' => 'Lokasi GPS wajib dikonfirmasi',
                'longitude.numeric' => 'Longitude harus berupa angka',
                'longitude.between' => 'Longitude tidak valid',
                'nama_kedai.required' => 'Nama kedai wajib diisi',
                'sumber.required' => 'Sumber data wajib diisi',
                'sumber.in' => 'Sumber data tidak valid',

                // Optional fields validation messages (jika diisi harus valid)
                'nama_pemilik.max' => 'Nama pemilik maksimal 255 karakter',
                'jenis_kelamin.in' => 'Jenis kelamin tidak valid',
                'handphone.min' => 'Nomor handphone minimal 10 digit',
                'handphone.max' => 'Nomor handphone maksimal 20 digit',
                'jumlah_pekerja.integer' => 'Jumlah pekerja harus berupa angka',
                'jumlah_pekerja.min' => 'Jumlah pekerja tidak boleh kurang dari 0',
                'stan_sewa.integer' => 'Jumlah stan sewa harus berupa angka',
                'stan_sewa.min' => 'Jumlah stan sewa tidak boleh kurang dari 0',
                'tren_pekerja.in' => 'Tren pekerja tidak valid',
            ]);

            Log::info('Data setelah validasi mitra:', $validated);

            DB::beginTransaction();

            // Process omzet - handle null values untuk mitra
            $omzetValue = 0; // Default untuk mitra jika kosong
            if (!empty($validated['omzet'])) {
                $omzetValue = str_replace(['Rp', '.', ' '], '', $validated['omzet']);
                $omzetValue = (int) $omzetValue;
            }

            Log::info('Omzet setelah diproses mitra:', ['original' => $validated['omzet'] ?? 'null', 'processed' => $omzetValue]);

            // Prepare data for saving
            $data = $validated;
            $data['omzet'] = $omzetValue;
            $data['kode_kota'] = '2172'; // Default for Tanjungpinang

            // Handle RT/RW - set default value jika kosong
            if (empty($data['rt']) || $data['rt'] === '') {
                $data['rt'] = '000'; // Default value
            }
            if (empty($data['rw']) || $data['rw'] === '') {
                $data['rw'] = '000'; // Default value
            }

            // Handle optional fields untuk mitra - set NULL jika kosong
            $optionalFields = ['nama_pemilik', 'jenis_kelamin', 'handphone', 'jumlah_pekerja', 'stan_sewa', 'tren_pekerja', 'catatan'];
            foreach ($optionalFields as $field) {
                if (empty($data[$field]) || $data[$field] === '') {
                    $data[$field] = null;
                }
            }

            // Special handling untuk jumlah_pekerja - jika kosong set 0
            if (is_null($data['jumlah_pekerja'])) {
                $data['jumlah_pekerja'] = 0;
            }

            // Special handling untuk stan_sewa - jika kosong set 0
            if (is_null($data['stan_sewa'])) {
                $data['stan_sewa'] = 0;
            }

            Log::info('Data yang akan disimpan mitra:', $data);

            // Create the record
            $kedaiKopi = KedaiKopi::create($data);

            Log::info('Data berhasil disimpan mitra:', ['id' => $kedaiKopi->id]);

            DB::commit();

            return redirect()->route('mitra')->with('success', [
                'message' => 'Data kedai kopi berhasil disimpan melalui form mitra!',
                'data' => [
                    'nama_kedai' => $kedaiKopi->nama_kedai,
                    'nama_pemilik' => $kedaiKopi->nama_pemilik ?? 'Tidak diisi',
                    'created_at' => Carbon::now('Asia/Jakarta'),
                ]
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation error mitra:', ['errors' => $e->errors()]);
            throw $e; // Re-throw validation exception untuk ditangani Laravel
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error saat menyimpan data kedai kopi mitra:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage());
        }
    }
    /**
     * Display peta sebaran kedai kopi (authenticated).
     *
     * @return \Illuminate\Http\Response
     */
    public function peta()
    {
        // Get basic statistics
        $stats = $this->getStatistics();
        $totalKedai = $stats['total_kedai'];
        $kedaiWithLocation = $stats['kedai_with_location'];
        $kedaiByKecamatan = $stats['kedai_by_kecamatan'];

        $kecamatanNames = [
            '010' => 'Bukit Bestari',
            '020' => 'Tanjungpinang Timur',
            '030' => 'Tanjungpinang Kota',
            '040' => 'Tanjungpinang Barat',
        ];
        // Get all kedai data - biarkan yang implement menentukan field apa saja yang diperlukan
        $kedaiData = KedaiKopi::all();

        return view('peta', compact(
            'totalKedai',
            'kedaiWithLocation',
            'kedaiByKecamatan',
            'kedaiData',
            'kecamatanNames'
        ));
    }
}
