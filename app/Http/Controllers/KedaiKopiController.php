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
        return view('form'); // Test dengan welcome dulu
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
                'kode_kecamatan' => 'required|in:010,020,030,040',
                'kode_kelurahan' => 'required|string|max:3',
                'rw' => 'required|string|max:3',
                'rt' => 'required|string|max:3',
                'alamat' => 'required|string|max:500',
                'latitude' => 'nullable|numeric|between:-90,90',
                'longitude' => 'nullable|numeric|between:-180,180',
                'nama_kedai' => 'required|string|max:255',
                'nama_pemilik' => 'required|string|max:255',
                'jenis_kelamin' => 'required|in:L,P',
                'handphone' => 'required|string|min:10|max:20',
                'omzet' => 'required|string',
                'jumlah_pekerja' => 'required|integer|min:1',
                'stan_sewa' => 'required|integer|min:0',
                'catatan' => 'nullable|string|max:1000',
                'tren_pekerja' => 'required|string|in:naik,turun,tetap'
            ], [
                'kode_kecamatan.required' => 'Kecamatan wajib dipilih',
                'kode_kecamatan.in' => 'Kecamatan tidak valid',
                'kode_kelurahan.required' => 'Kelurahan wajib dipilih',
                'rw.required' => 'RW wajib diisi',
                'rt.required' => 'RT wajib diisi',
                'alamat.required' => 'Alamat kedai wajib diisi',
                'nama_kedai.required' => 'Nama kedai wajib diisi',
                'nama_pemilik.required' => 'Nama pemilik wajib diisi',
                'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih',
                'jenis_kelamin.in' => 'Jenis kelamin tidak valid',
                'handphone.required' => 'Nomor handphone wajib diisi',
                'handphone.min' => 'Nomor handphone minimal 10 digit',
                'handphone.max' => 'Nomor handphone maksimal 20 digit',
                'omzet.required' => 'Omzet wajib diisi',
                'jumlah_pekerja.required' => 'Jumlah pekerja wajib diisi',
                'jumlah_pekerja.min' => 'Jumlah pekerja minimal 1 orang',
                'stan_sewa.required' => 'Jumlah stan sewa wajib diisi',
                'stan_sewa.min' => 'Jumlah stan sewa tidak boleh kurang dari 0',
                'tren_pekerja.required' => 'Tren pekerja wajib dipilih',
                'tren_pekerja.in' => 'Tren pekerja tidak valid',
            ]);

            Log::info('Data setelah validasi:', $validated);

            DB::beginTransaction();

            // Process omzet - remove 'Rp' and dots, convert to integer
            $omzetValue = $validated['omzet'];
            $omzetValue = str_replace(['Rp', '.', ' '], '', $omzetValue);
            $omzetValue = (int) $omzetValue;

            Log::info('Omzet setelah diproses:', ['original' => $validated['omzet'], 'processed' => $omzetValue]);

            // Prepare data for saving
            $data = $validated;
            $data['omzet'] = $omzetValue;
            $data['kode_kota'] = '2172'; // Default for Tanjungpinang

            // Remove total_stan dari data jika ada (karena tidak ada di fillable)
            unset($data['total_stan']);

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
                'kode_kecamatan' => 'required|in:010,020,030,040',
                'kode_kelurahan' => 'required|string|max:3',
                'rw' => 'required|string|max:3',
                'rt' => 'required|string|max:3',
                'alamat' => 'required|string|max:500',
                'latitude' => 'nullable|numeric|between:-90,90',
                'longitude' => 'nullable|numeric|between:-180,180',
                'nama_kedai' => 'required|string|max:255',
                'nama_pemilik' => 'required|string|max:255',
                'jenis_kelamin' => 'required|in:L,P',
                'handphone' => 'required|string|min:10|max:20',
                'omzet' => 'required|string',
                'jumlah_pekerja' => 'required|integer|min:1',
                'stan_sewa' => 'required|integer|min:0',
                'catatan' => 'nullable|string|max:1000',
                'tren_pekerja' => 'required|string|in:naik,turun,tetap',
            ]);

            DB::beginTransaction();

            // Process omzet - remove 'Rp' and dots, convert to integer
            $omzetValue = $validated['omzet'];
            $omzetValue = str_replace(['Rp', '.', ' '], '', $omzetValue);
            $omzetValue = (int) $omzetValue;

            // Prepare data for updating
            $data = $validated;
            $data['omzet'] = $omzetValue;

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
     * Get statistics for dashboard.
     *
     * @return array
     */
    public function getStatistics()
    {
        $stats = [
            'total_kedai' => KedaiKopi::count(),
            'total_pekerja' => KedaiKopi::sum('jumlah_pekerja'),
            'total_omzet' => KedaiKopi::sum('omzet'),
            'total_stan_sewa' => KedaiKopi::sum('stan_sewa'),
            'avg_omzet' => KedaiKopi::avg('omzet'),
            'avg_pekerja' => KedaiKopi::avg('jumlah_pekerja'),
            'kedai_by_kecamatan' => KedaiKopi::selectRaw('kode_kecamatan, COUNT(*) as total')
                ->groupBy('kode_kecamatan')
                ->get(),
            'kedai_with_location' => KedaiKopi::withLocation()->count(),
        ];

        return $stats;
    }
}
