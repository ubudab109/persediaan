<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;

    protected $table = 'barang';
    protected $fillable = ['kategori_id','satuan_id','nama','deskripsi','harga','gambar','stock'];

    public function kategori()
    {
        return $this->belongsTo(KategoriBarang::class, 'kategori_id','id');
    }

    public function satuan()
    {
        return $this->belongsTo(SatuanBarang::class, 'satuan_id', 'id');
    }

    public function keluar()
    {
        return $this->hasMany(BarangKeluar::class, 'id_barang', 'id');
    }

    public function masuk()
    {
        return $this->hasMany(DetailBarangMasuk::class, 'id_barang', 'id');
    }
}
