<?php

namespace App\Http\Controllers\Barang;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use Illuminate\Http\Request;
use Alert;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Throwable;
use Yajra\DataTables\DataTables;

class BarangController extends Controller
{
    public $model;

    public function __construct(Barang $model)
    {
        $this->model = $model;
    }

    public function getBarang(Request $request)
    {
        $data = $this->model->with('kategori')->with('satuan')->get();
        if($request->ajax()){
            return DataTables::of($data)
                               ->addIndexColumn()
                               ->addColumn('action', function($row){
                                $action = '
                                <a href="'.route('barang.show',['id' => $row->id]).'" class="delete btn btn-info btn-sm"><i class="fas fa-eye"></i></a>
                                <a href="javascript:void(0)" onClick="destroy(' . $row->id . ')" class="delete btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></a>
                                ';
                    
                                return $action;
                               })
                               ->editColumn('harga', function($row){
                                   return rupiah($row->harga);
                               })
                               ->editColumn('kategori', function($row){
                                   return $row->kategori->nama;
                               })
                               ->editColumn('satuan', function($row){
                                   return $row->satuan->nama.' / '.$row->satuan->keterangan;
                               })
                               ->rawColumns(['action','harga','kategori','satuan'])
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
        return view('pages.barang.index');
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
            'kategori_id' => 'required|integer',
            'satuan_id' => 'required|integer',
            'nama' => 'required',
            'deskripsi' => 'nullable',
            'harga' => 'required',
            'stock' => 'required',
            'gambar' => 'nullable|mimes:jpeg,jpg,png|max:2048',
        ]);

        if($validator->fails()){
            Alert::error('Error', 'Silahkan periksa inputan');
        }

        DB::beginTransaction();
        try {
            $input = $request->all();
            if($request->has('gambar')){
                $input['gambar'] = URL::to(Storage::disk('public_uploads')->put('/gambar/barang', $input['gambar']));
            }
            $this->model->create($input);
            Alert::success('Sukses','Data Berhasil Ditambah');
            DB::commit();
            return redirect()->back();
        } catch (Throwable $th) {
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
        $data = $this->model->with('kategori')->with('satuan')->find($id);
        return view('pages.barang.show')->with(['data' => $data]);
    }


    /**
     * Update image
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateImage(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'gambar' => 'nullable|mimes:jpeg,jpg,png|max:2048',
        ]);

        if($validator->fails()){
            Alert::error('Error', 'Silahkan periksa inputan');
            return redirect()->back()->withInput();
        }
        DB::beginTransaction();
        try {
            $input = $request->all();
            $input['gambar'] = URL::to(Storage::disk('public_uploads')->put('/gambar/barang', $input['gambar']));
            $this->model->find($id)->update($input);
            Alert::success('Sukses', 'Data berhasil diubah');
            DB::commit();
            return redirect()->back();
        } catch (Throwable $th) {
            DB::rollBack();
            return redirect()->back()->withInput();
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'kategori_id' => 'required|integer',
            'satuan_id' => 'required|integer',
            'nama' => 'required',
            'deskripsi' => 'nullable',
            'harga' => 'required',
        ]);

        if($validator->fails()){
            Alert::error('Error', 'Silahkan periksa inputan');
            return redirect()->back()->withInput();
        }

        DB::beginTransaction();
        try {
            $input = $request->all();
            $barang = $this->model->find($id);
            $input['kategori_id'] = $request->kategori_id !== null ? $request->kategori_id : $barang->kategori_id;
            $input['satuan_id'] = $request->satuan_id !== null ? $request->satuan_id : $barang->satuan_id;
            $barang->update($input);
            Alert::success('Sukses', 'Data berhasil diubah');
            DB::commit();
            return redirect()->back();
        }catch (Throwable $th) {
            DB::rollBack();
            return redirect()->back()->withInput();
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
