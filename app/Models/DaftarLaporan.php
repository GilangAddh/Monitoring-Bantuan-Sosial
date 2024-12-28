<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DaftarLaporan extends Model
{
    use HasFactory;
    protected $table = 'daftar_laporan';
    protected $fillable = ['nama_program', 'jumlah_penerima', 'provinsi', 'kabupaten', 'kecamatan', 'kode_provinsi', 'kode_kabupaten', 'kode_kecamatan', 'bukti_penyaluran', 'catatan'];
}
