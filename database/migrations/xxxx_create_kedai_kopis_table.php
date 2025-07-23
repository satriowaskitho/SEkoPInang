<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kedai_kopi', function (Blueprint $table) {
            $table->id();

            // Keterangan Lokasi
            $table->string('kode_kota', 4)->default('2172'); // Tanjungpinang
            $table->string('kode_kecamatan', 3);
            $table->string('kode_kelurahan', 3);
            $table->string('rw', 3);
            $table->string('rt', 3);
            $table->text('alamat');
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();

            // Keterangan Usaha
            $table->string('nama_kedai');
            $table->string('nama_pemilik');
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->string('handphone');
            $table->bigInteger('omzet'); // Stored as integer (rupiah)
            $table->integer('jumlah_pekerja');

            // Keterangan Tenant/Stand
            $table->integer('stan_sewa')->default(0);
            $table->integer('total_stan')->default(0);
            $table->string('trenPekerja');

            // Catatan
            $table->text('catatan')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index(['kode_kecamatan', 'kode_kelurahan']);
            $table->index(['nama_kedai']);
            $table->index(['nama_pemilik']);
            $table->index(['deleted_at']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('kedai_kopi');
    }
};
