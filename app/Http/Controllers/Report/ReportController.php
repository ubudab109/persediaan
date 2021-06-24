<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\DetailBarangKeluar;
use App\Models\DetailBarangMasuk;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class ReportController extends Controller
{
    public $stock, $masuk, $keluar;

    public function __construct(Barang $stock, DetailBarangMasuk $masuk, DetailBarangKeluar $keluar)
    {
        $this->stock = $stock;
        $this->masuk = $masuk;
        $this->keluar = $keluar;
    }

    public function reportStock(Request $request)
    {
        $data = $this->stock->with('kategori')->with('satuan')->get();
        if($request->ajax()){
            return DataTables::of($data)
                               ->addIndexColumn()
                               ->editColumn('harga', function($row){
                                   return rupiah($row->harga);
                               })
                               ->editColumn('kategori', function($row){
                                   return $row->kategori->nama;
                               })
                               ->editColumn('satuan', function($row){
                                   return $row->satuan->nama.' / '.$row->satuan->keterangan;
                               })
                               ->rawColumns(['harga','kategori','satuan'])
                               ->make(true);
        }
    }

    public function stock()
    {
        return view('pages.report.stock');
    }

    public function reportBarangMasuk(Request $request)
    {
        if($request->ajax()){
            $data = $this->masuk->with('masuk')->with('barang.kategori:id,nama')->with('barang.satuan:id,nama,keterangan')->when($request->startDate && $request->endDate, function($query) use($request){
                $query->whereHas('masuk', function ($transaksi) use ($request){
                    $transaksi->whereBetween('tanggal_transaksi', [$request->startDate, $request->endDate]);
                });
            })->get();
            // return $data;
            return DataTables::of($data)
                                   ->addIndexColumn()
                                   ->editColumn('harga', function($row){
                                       return rupiah($row->barang->harga);
                                   })
                                   ->editColumn('kategori', function($row){
                                       return $row->barang->kategori->nama;
                                   })
                                   ->editColumn('satuan', function($row){
                                       return $row->barang->satuan->nama.' / '.$row->barang->satuan->keterangan;
                                   })
                                   ->editColumn('no_referensi', function($row){
                                       return $row->masuk->no_referensi;
                                   })
                                   ->editColumn('tanggal_transaksi', function($row){
                                       return $row->masuk->tanggal_transaksi;
                                   })
                                   ->editColumn('catatan', function($row){
                                       return $row->masuk->catatan;
                                   })
                                   ->editColumn('supplier', function($row){
                                       return $row->masuk->supplier;
                                   })
                                   ->editColumn('status', function($row){
                                    switch($row->masuk->status){
                                        case 0:
                                           return 'Pre Order';
                                        case 1:
                                           return 'Dalam Pengiriman';
                                        case 2:
                                           return 'Diterima';
                                        default:
                                           return '';
                                    }
                                   })
                                   ->editColumn('barang', function($row){
                                       return $row->barang->nama;
                                   })
                                   ->rawColumns(['harga','kategori','satuan','no_referensi','tanggal_transaksi','catatan','supplier','status','barang'])
                                   ->make(true);
        }
    }

    public function barangMasuk()
    {
        return view('pages.report.barang-masuk');
    }

    public function reportBarangKeluar(Request $request)
    {
        if($request->ajax()){
            $data = $this->keluar->with('keluar')->with('barang.kategori:id,nama')->with('barang.satuan:id,nama,keterangan')->when($request->startDate && $request->endDate, function($query) use($request){
                $query->whereHas('keluar', function ($transaksi) use ($request){
                    $transaksi->whereBetween('tanggal_keluar', [$request->startDate, $request->endDate]);
                });
            })->get();
            // return $data;
            return DataTables::of($data)
                                   ->addIndexColumn()
                                   ->editColumn('harga', function($row){
                                       return rupiah($row->barang->harga);
                                   })
                                   ->editColumn('kategori', function($row){
                                       return $row->barang->kategori->nama;
                                   })
                                   ->editColumn('satuan', function($row){
                                       return $row->barang->satuan->nama.' / '.$row->barang->satuan->keterangan;
                                   })
                                   ->editColumn('no_referensi', function($row){
                                       return $row->keluar->no_referensi;
                                   })
                                   ->editColumn('tanggal_keluar', function($row){
                                       return $row->keluar->tanggal_keluar;
                                   })
                                   ->editColumn('catatan', function($row){
                                       return $row->keluar->catatan;
                                   })
                                   ->editColumn('picker', function($row){
                                       return $row->keluar->picker;
                                   })
                                   ->editColumn('status', function($row){
                                    switch($row->keluar->status){
                                        case 0:
                                           return 'Pre Order';
                                        case 1:
                                           return 'Dalam Pengiriman';
                                        case 2:
                                           return 'Diterima';
                                        default:
                                           return '';
                                    }
                                   })
                                   ->editColumn('barang', function($row){
                                       return $row->barang->nama;
                                   })
                                   ->rawColumns(['harga','kategori','satuan','no_referensi','tanggal_keluar','catatan','supplier','status','barang'])
                                   ->make(true);
        }
    }

    public function barangKeluar()
    {
        return view('pages.report.barang-keluar');
    }
}
