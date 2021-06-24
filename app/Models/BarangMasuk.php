<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangMasuk extends Model
{
    use HasFactory;

    protected $table = 'barang_masuk';
    protected $fillable = ['no_referensi','id_barang','jumlah','tanggal_transaksi','catatan','status','supplier'];

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'id_barang', 'id');
    }

    public function detail()
    {
        return $this->hasMany(DetailBarangMasuk::class, 'id_barang_masuk','id');
    }
}
