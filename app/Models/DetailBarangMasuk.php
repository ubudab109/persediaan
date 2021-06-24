<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailBarangMasuk extends Model
{
    use HasFactory;

    protected $table = 'detail_barang_masuk';
    protected $fillable = ['id_barang_masuk','id_barang','jumlah'];
    protected $primaryKey = 'id';
    protected $hidden = ['id_barang_masuk','id_barang','created_at','updated_at'];

    public function masuk()
    {
        return $this->belongsTo(BarangMasuk::class, 'id_barang_masuk','id');
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'id_barang','id');
    }
    
}
