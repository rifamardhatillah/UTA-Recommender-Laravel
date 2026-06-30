<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('nilais', function (Blueprint $table) {
            $table->id(); // ID unik untuk setiap entri nilai

            // Foreign Key ke tabel 'alternatifs'
            $table->foreignId('alternatif_id')
                  ->constrained('alternatifs') // Memastikan merujuk ke ID di tabel 'alternatifs'
                  ->onUpdate('cascade')      // Jika ID alternatif diupdate, update juga di sini
                  ->onDelete('cascade');      // Jika alternatif dihapus, hapus juga nilai terkait

            // Foreign Key ke tabel 'kriterias'
            $table->foreignId('kriteria_id')
                  ->constrained('kriterias')   // Memastikan merujuk ke ID di tabel 'kriterias'
                  ->onUpdate('cascade')      // Jika ID kriteria diupdate, update juga di sini
                  ->onDelete('cascade');      // Jika kriteria dihapus, hapus juga nilai terkait

            // Kolom untuk menyimpan nilai performa aktual dari alternatif terhadap kriteria
            // Tipe data ini sangat penting dan tergantung pada skala pengukuran kriteria Anda.
            // Bisa jadi float, integer, atau bahkan string jika nilainya kategorikal (meskipun untuk UTA biasanya numerik).
            $table->float('nilai', 8, 3); // Contoh: jika harga, bisa 2000000; jika rating, bisa 4.5

            // Anda MUNGKIN memerlukan kolom untuk nilai utility, tapi ini biasanya dihitung oleh metode UTA
            // dan mungkin tidak disimpan secara permanen di sini, atau disimpan setelah proses UTA.
            // $table->float('nilai_utility')->nullable();

            $table->timestamps();

            // Membuat kombinasi alternatif_id dan kriteria_id unik.
            // Ini memastikan bahwa satu alternatif hanya memiliki satu nilai untuk satu kriteria spesifik.
            // Ini sangat direkomendasikan untuk SPK.
            $table->unique(['alternatif_id', 'kriteria_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nilais');
    }
};