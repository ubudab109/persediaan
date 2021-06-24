<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailBarangKeluar extends Model
{
    use HasFactory;

    protected $table = 'detail_barang_keluar';
    protected $fillable = ['id_barang_keluar','id_barang','jumlah'];
    protected $primaryKey = 'id';
    protected $hidden = ['id_barang_keluar','id_barang','created_at','updated_at'];

    public function keluar()
    {
        return $this->belongsTo(BarangKeluar::class, 'id_barang_keluar','id');
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'id_barang','id');
    }
}
