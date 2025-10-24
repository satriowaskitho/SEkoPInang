<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KedaiKopi extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'kedai_kopi';

    protected $fillable = [
        'kode_kota',
        'kode_kecamatan',
        'kode_kelurahan',
        'rw',
        'rt',
        'alamat',
        'latitude',
        'longitude',
        'nama_kedai',
        'nama_pemilik',
        'jenis_kelamin',
        'handphone',
        'omzet',
        'jumlah_pekerja',
        'stan_sewa',
        'catatan',
        'tren_pekerja',
        'sumber', // TAMBAHAN BARU
    ];

    protected $casts = [
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
        'omzet' => 'integer',
        'jumlah_pekerja' => 'integer',
        'stan_sewa' => 'integer',
    ];

    protected $dates = [
        'deleted_at',
    ];

    /**
     * Get the formatted omzet attribute
     */
    public function getFormattedOmzetAttribute()
    {
        if (is_null($this->omzet) || $this->omzet == 0) {
            return 'Tidak diisi';
        }
        return 'Rp ' . number_format($this->omzet, 0, ',', '.');
    }

    /**
     * Get the full address including RT/RW
     */
    public function getFullAddressAttribute()
    {
        $rtDisplay = ($this->rt && $this->rt !== '000') ? $this->rt : '-';
        $rwDisplay = ($this->rw && $this->rw !== '000') ? $this->rw : '-';

        if ($rtDisplay === '-' && $rwDisplay === '-') {
            return $this->alamat;
        }

        return $this->alamat . ', RT ' . $rtDisplay . '/RW ' . $rwDisplay;
    }

    /**
     * Get the kecamatan name
     */
    public function getKecamatanNameAttribute()
    {
        $kecamatan = [
            '010' => 'Bukit Bestari',
            '020' => 'Tanjungpinang Timur',
            '030' => 'Tanjungpinang Kota',
            '040' => 'Tanjungpinang Barat',
        ];

        return $kecamatan[$this->kode_kecamatan] ?? 'Unknown';
    }

    /**
     * Get the kelurahan name
     */
    public function getKelurahanNameAttribute()
    {
        $kelurahan = [
            '010' => [
                '001' => 'Dompak',
                '002' => 'Tanjungpinang Timur',
                '003' => 'Tanjung Ayun Sakti',
                '004' => 'Sei Jang',
                '005' => 'Tanjung Unggat',
            ],
            '020' => [
                '001' => 'Batu Sembilan',
                '002' => 'Melayu Kota Piring',
                '003' => 'Air Raja',
                '004' => 'Pinang Kencana',
                '005' => 'Kampung Bulang',
            ],
            '030' => [
                '001' => 'Tanjungpinang Kota',
                '002' => 'Penyengat',
                '003' => 'Kampung Bugis',
                '004' => 'Senggarang',
            ],
            '040' => [
                '001' => 'Tanjungpinang Barat',
                '002' => 'Kemboja',
                '003' => 'Kampung Baru',
                '004' => 'Bukit Cermin',
            ],
        ];

        return $kelurahan[$this->kode_kecamatan][$this->kode_kelurahan] ?? 'Unknown';
    }

    /**
     * Get the gender text
     */
    public function getGenderTextAttribute()
    {
        if (is_null($this->jenis_kelamin)) {
            return 'Tidak diisi';
        }
        return $this->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan';
    }

    /**
     * Get the tren pekerja text
     */
    public function getTrenPekerjaTextAttribute()
    {
        if (is_null($this->tren_pekerja)) {
            return 'Tidak diisi';
        }

        $tren = [
            'naik' => 'Naik',
            'turun' => 'Turun',
            'tetap' => 'Tetap'
        ];

        return $tren[$this->tren_pekerja] ?? 'Unknown';
    }

    /**
     * Get the sumber text - TAMBAHAN BARU
     */
    public function getSumberTextAttribute()
    {
        $sumber = [
            'mandiri' => 'Input Mandiri',
            'mitra' => 'Input Mitra'
        ];

        return $sumber[$this->sumber] ?? 'Unknown';
    }

    /**
     * Get formatted nama pemilik - NOW REQUIRED, so should always have value
     */
    public function getFormattedNamaPemilikAttribute()
    {
        return $this->nama_pemilik ?? 'Data tidak tersedia';
    }

    /**
     * Get formatted handphone - NOW REQUIRED, so should always have value
     */
    public function getFormattedHandphoneAttribute()
    {
        return $this->handphone ?? 'Data tidak tersedia';
    }

    /**
     * Get formatted jumlah pekerja - NOW REQUIRED, so should always have value
     */
    public function getFormattedJumlahPekerjaAttribute()
    {
        if (is_null($this->jumlah_pekerja)) {
            return 'Data tidak tersedia';
        }
        return $this->jumlah_pekerja . ' orang';
    }

    /**
     * Get formatted stan sewa - NOW REQUIRED, so should always have value
     */
    public function getFormattedStanSewaAttribute()
    {
        if (is_null($this->stan_sewa)) {
            return 'Data tidak tersedia';
        }
        return $this->stan_sewa . ' stan';
    }

    /**
     * Get formatted RT/RW display - Handle null/default values (still optional)
     */
    public function getFormattedRtRwAttribute()
    {
        $rtDisplay = ($this->rt && $this->rt !== '000') ? $this->rt : '-';
        $rwDisplay = ($this->rw && $this->rw !== '000') ? $this->rw : '-';

        if ($rtDisplay === '-' && $rwDisplay === '-') {
            return 'Tidak diisi';
        }

        return 'RT ' . $rtDisplay . ' / RW ' . $rwDisplay;
    }

    /**
     * Get formatted catatan - Handle null values (still optional)
     */
    public function getFormattedCatatanAttribute()
    {
        return $this->catatan ?? 'Tidak ada catatan';
    }

    /**
     * Check if kedai has complete data - UPDATED for new required fields
     */
    public function getIsCompleteDataAttribute()
    {
        $requiredFields = [
            'nama_kedai',
            'alamat',
            'latitude',
            'longitude',
            'nama_pemilik',      // NOW REQUIRED
            'jenis_kelamin',     // NOW REQUIRED
            'handphone',         // NOW REQUIRED
            'omzet',            // NOW REQUIRED
            'jumlah_pekerja',   // NOW REQUIRED
            'stan_sewa',        // NOW REQUIRED
            'tren_pekerja',     // NOW REQUIRED
            'sumber'            // NOW REQUIRED - TAMBAHAN BARU
        ];

        $optionalFields = ['rt', 'rw', 'catatan']; // Only these remain optional

        // Check all required fields
        foreach ($requiredFields as $field) {
            if (
                empty($this->$field) ||
                ($field === 'omzet' && $this->$field == 0) ||
                ($field === 'jumlah_pekerja' && $this->$field == 0)
            ) {
                return false;
            }
        }

        return true; // All required fields are filled
    }

    /**
     * Get completion percentage - UPDATED for new required fields
     */
    public function getCompletionPercentageAttribute()
    {
        $totalFields = 15; // Total fields in form (12 required + 3 optional)
        $filledFields = 0;

        // Required fields (12 fields) - each worth more weight
        $requiredFields = [
            'nama_kedai',
            'alamat',
            'latitude',
            'longitude',
            'nama_pemilik',
            'jenis_kelamin',
            'handphone',
            'omzet',
            'jumlah_pekerja',
            'stan_sewa',
            'tren_pekerja',
            'sumber' // TAMBAHAN BARU
        ];

        foreach ($requiredFields as $field) {
            if (
                !empty($this->$field) &&
                !($field === 'omzet' && $this->$field == 0) &&
                !($field === 'jumlah_pekerja' && $this->$field == 0)
            ) {
                $filledFields++;
            }
        }

        // Optional fields (3 fields)
        $optionalFields = ['rt', 'rw', 'catatan'];
        foreach ($optionalFields as $field) {
            if (!is_null($this->$field) && $this->$field !== '' && $this->$field !== '000') {
                $filledFields++;
            }
        }

        return round(($filledFields / $totalFields) * 100);
    }

    /**
     * Scope for filtering by kecamatan
     */
    public function scopeByKecamatan($query, $kecamatan)
    {
        return $query->where('kode_kecamatan', $kecamatan);
    }

    /**
     * Scope for filtering by kelurahan
     */
    public function scopeByKelurahan($query, $kelurahan)
    {
        return $query->where('kode_kelurahan', $kelurahan);
    }

    /**
     * Scope for filtering by omzet range
     */
    public function scopeByOmzetRange($query, $min, $max)
    {
        return $query->whereNotNull('omzet')->whereBetween('omzet', [$min, $max]);
    }

    /**
     * Scope for filtering by jumlah pekerja range
     */
    public function scopeByJumlahPekerjaRange($query, $min, $max)
    {
        return $query->whereNotNull('jumlah_pekerja')->whereBetween('jumlah_pekerja', [$min, $max]);
    }

    /**
     * Scope for search by nama kedai or nama pemilik
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('nama_kedai', 'like', "%{$search}%")
                ->orWhere('nama_pemilik', 'like', "%{$search}%");
        });
    }

    /**
     * Scope for filtering by location (with coordinates)
     */
    public function scopeWithLocation($query)
    {
        return $query->whereNotNull('latitude')->whereNotNull('longitude');
    }

    /**
     * Scope for filtering by sumber - TAMBAHAN BARU
     */
    public function scopeBySumber($query, $sumber)
    {
        return $query->where('sumber', $sumber);
    }

    /**
     * Scope for complete data - UPDATED since most fields are now required
     */
    public function scopeCompleteData($query)
    {
        return $query->whereNotNull('nama_kedai')
            ->whereNotNull('alamat')
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->whereNotNull('nama_pemilik')
            ->whereNotNull('jenis_kelamin')
            ->whereNotNull('handphone')
            ->whereNotNull('omzet')
            ->whereNotNull('jumlah_pekerja')
            ->whereNotNull('stan_sewa')
            ->whereNotNull('tren_pekerja')
            ->whereNotNull('sumber') // TAMBAHAN BARU
            ->where('omzet', '>', 0)
            ->where('jumlah_pekerja', '>', 0);
    }

    /**
     * Scope for incomplete data - UPDATED for new required fields
     */
    public function scopeIncompleteData($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('nama_kedai')
                ->orWhereNull('alamat')
                ->orWhereNull('latitude')
                ->orWhereNull('longitude')
                ->orWhereNull('nama_pemilik')
                ->orWhereNull('jenis_kelamin')
                ->orWhereNull('handphone')
                ->orWhereNull('omzet')
                ->orWhereNull('jumlah_pekerja')
                ->orWhereNull('stan_sewa')
                ->orWhereNull('tren_pekerja')
                ->orWhereNull('sumber') // TAMBAHAN BARU
                ->orWhere('omzet', '<=', 0)
                ->orWhere('jumlah_pekerja', '<=', 0);
        });
    }

    /**
     * Scope for kedai with business data (since omzet and pekerja are now required)
     */
    public function scopeWithBusinessData($query)
    {
        return $query->whereNotNull('omzet')
            ->whereNotNull('jumlah_pekerja')
            ->where('omzet', '>', 0)
            ->where('jumlah_pekerja', '>', 0);
    }

    /**
     * Scope for kedai with contact info (since handphone is now required)
     */
    public function scopeWithContactInfo($query)
    {
        return $query->whereNotNull('handphone')
            ->whereNotNull('nama_pemilik');
    }

    /**
     * Get average omzet per pekerja
     */
    public function getOmzetPerPekerjaAttribute()
    {
        if (!$this->jumlah_pekerja || $this->jumlah_pekerja == 0) {
            return 0;
        }

        return $this->omzet / $this->jumlah_pekerja;
    }

    /**
     * Check if omzet per pekerja is below threshold
     */
    public function getIsLowOmzetPerPekerjaAttribute()
    {
        return $this->omzet_per_pekerja < 1000000;
    }

    /**
     * Get formatted omzet per pekerja
     */
    public function getFormattedOmzetPerPekerjaAttribute()
    {
        if ($this->omzet_per_pekerja == 0) {
            return 'Data tidak tersedia';
        }

        return 'Rp ' . number_format($this->omzet_per_pekerja, 0, ',', '.');
    }
}
