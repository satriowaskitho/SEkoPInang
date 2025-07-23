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
        'tren_pekerja', // Pastikan menggunakan snake_case
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
        return 'Rp ' . number_format($this->omzet, 0, ',', '.');
    }

    /**
     * Get the full address including RT/RW
     */
    public function getFullAddressAttribute()
    {
        return $this->alamat . ', RT ' . $this->rt . '/RW ' . $this->rw;
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
        return $this->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan';
    }

    /**
     * Get the tren pekerja text
     */
    public function getTrenPekerjaTextAttribute()
    {
        $tren = [
            'naik' => 'Naik',
            'turun' => 'Turun',
            'tetap' => 'Tetap'
        ];

        return $tren[$this->tren_pekerja] ?? 'Unknown';
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
        return $query->whereBetween('omzet', [$min, $max]);
    }

    /**
     * Scope for filtering by jumlah pekerja range
     */
    public function scopeByJumlahPekerjaRange($query, $min, $max)
    {
        return $query->whereBetween('jumlah_pekerja', [$min, $max]);
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
}
