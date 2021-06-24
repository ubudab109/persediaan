<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangKeluar extends Model
{
    use HasFactory;

    protected $table = 'barang_keluar';
    protected $fillable = ['no_referensi','tanggal_keluar','catatan','status','picker'];

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'id_barang', 'id');
    }

    public function detail()
    {
        return $this->hasMany(DetailBarangKeluar::class, 'id_barang_keluar','id');
    }

}
