<?php

namespace App\Http\Controllers\BarangKeluar;

use App\Http\Controllers\Controller;
use App\Models\BarangKeluar;
use App\Models\DetailBarangKeluar;
use Illuminate\Http\Request;
use Throwable;
use Yajra\DataTables\DataTables;
use Alert;
use App\Models\Barang;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class BarangKeluarController extends Controller
{
    public $model, $detail;

    public function __construct(BarangKeluar $model, DetailBarangKeluar $detail)
    {
        $this->model = $model;
        $this->detail = $detail;
    }

    public function getBarangKeluar(Request $request)
    {
        $data = $this->model->with('detail.barang:id,nama')->withCount('detail as total_barang')->get();
        if($request->ajax()) {
            return DataTables::of($data)
                             ->addIndexColumn()
                             ->addColumn('action', function($row){
                                 $action = '<a href="'.route('barang-keluar.show',['id' => $row->id]).'" class="delete btn btn-info btn-sm"><i class="fas fa-eye"></i></a>
                                 <a href="javascript:void(0)" onClick="destroy(' . $row->id . ')" class="delete btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></a>
                                 <div class="btn-group">
                                    <button type="button" class="btn btn-warning btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="javascript:void(0)" onClick="preOrder('.$row->id.')">Pre Order</a>
                                        <a class="dropdown-item" href="javascript:void(0)" onClick="send('.$row->id.')">Dalam Pengiriman</a>
                                        <a class="dropdown-item" href="javascript:void(0)" onClick="receive('.$row->id.')">Diterima</a>
                                    </div>
                                  </div>';
    
                                 return $action;
                             })
                             ->editColumn('status', function($row){
                                 switch($row->status){
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
                             ->rawColumns(['action','status'])
                             ->make(true);
        }
    }

    public function getBarangKeluarDetail(Request $request, $id)
    {
        $data = $this->detail->where('id_barang_keluar', $id)->with('barang.satuan:id,nama')->get();
        
        if($request->ajax()) {
            return DataTables::of($data)
                             ->addIndexColumn()
                             ->editColumn('barang', function($row){
                                return $row->barang->nama;
                            })
                             ->editColumn('harga', function($row){
                                 return rupiah($row->barang->harga);
                             })
                             ->editColumn('satuan', function($row){
                                return $row->barang->satuan->nama;
                             })
                             ->rawColumns(['action','barang','harga','satuan'])
                             ->make(true);
        }

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('pages.barang-keluar.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $barang = Barang::select('id','nama')->get();
        return view('pages.barang-keluar.create')->with(['barang' => $barang]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'no_referensi' => 'required',
            'tanggal_keluar' => 'required',
            'catatan' => 'required',
            'status' => 'required',
            'picker' => 'required',
            'barang' => 'array|required',
        ]);

        if($validator->fails())
        {
            Alert::error('Gagal','Inputan harus diisi semua');
            return redirect()->back()->withInput();
        }

        DB::beginTransaction();
        try {
            $input = $request->all();
            $barangKeluar = $this->model->create($input);
            foreach($request->barang as $key => $value) {
                $inputDataBarang = [
                    'id_barang' => $request->barang[$key]['id_barang'],
                    'jumlah' => $request->barang[$key]['jumlah'],
                ];
    
                $barangKeluar->detail()->create($inputDataBarang);
            }
            Alert::success('Sukses', 'Data Berhasil Diinput');
            DB::commit();
            return redirect()->route('barang-keluar.index');
        } catch (Throwable $th) {
            Alert::error('Error','Terjadi Kesalahan Silahkan Ulangin');
            DB::rollBack();
            return redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = $this->model->with('detail.barang:id,nama')->with('detail.barang.satuan:id,nama')->withCount('detail as total_barang')->find($id);

        return view ('pages.barang-keluar.show')->with(['data' => $data]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateBarang(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'id_barang' => 'required',
            'jumlah' => 'required'
        ]);

        if ($validator->fails()){
            Alert::error('Gagal','Inputan harus diisi semua');
            return redirect()->back()->withInput();
        }

        $input = $request->all();
        $this->model->find($id)->detail()->create($input);
        Alert::success('Sukses', 'Data Berhasil Diinput');
        // DB::commit();
        return redirect()->back();
    }

    /**
     * Update status.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateStatus(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'integer',
        ]);

        if($validator->fails())
        {
            return false;
        }

        DB::beginTransaction();
        try {
            $input = $request->all();
            $this->model->find($id)->update($input);
            DB::commit();
            return true;
        } catch (Throwable $th) {
            DB::rollBack();
            return false;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $this->model->find($id)->delete();
            DB::commit();
            return true;
        } catch (Throwable $th) {
            DB::rollBack();
            return false;
        }
    }
}
