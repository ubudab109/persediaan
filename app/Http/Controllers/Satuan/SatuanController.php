<?php

namespace App\Http\Controllers\Satuan;

use App\Http\Controllers\Controller;
use App\Models\SatuanBarang;
use Illuminate\Http\Request;
use Alert;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Throwable;
use Yajra\DataTables\DataTables;

class SatuanController extends Controller 
{

    public $model;

    public function __construct(SatuanBarang $model)
    {
        $this->model = $model;
    }

    public function getSatuan(Request $request)
    {
        if($request->ajax())
        {
            $data = $this->model->all();
            return DataTables::of($data)
                   ->addIndexColumn()
                   ->addColumn('action', function($row){
                        $action = '<a href="javascript:void(0)" class="edit btn btn-success btn-sm" onClick="show(' . $row->id . ')" data-toggle="modal" data-target="#edit"><i class="fas fa-edit"></i></a>
                        <a href="javascript:void(0)" onClick="destroy(' . $row->id . ')" class="delete btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></a>';
            
                        return $action;
                   })
                   ->rawColumns(['action'])
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
        return view('pages.satuan.index');
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
            'nama' => 'required',
            'keterangan' => 'required',
        ]);

        if($validator->fails())
        {
            Alert::error('Error', 'Silahkan isi semua inputan');
            return redirect()->back()->withInput();
        }

        DB::beginTransaction();
        try {
            $input = $request->all();
            $this->model->create($input);
            Alert::success('Sukses','Data Berhasil Ditambah');
            DB::commit();
            return redirect()->back();
        }catch (Throwable $th) {
            Alert::error('Error','Terjadi kesalahan silahkan ulangi');
            DB::rollBack();
            return redirect()->back()->withInput();
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
        return $this->model->findOrFail($id);
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
            'nama' => 'required',
            'keterangan' => 'required',
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
        }catch (Throwable $th) {
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
        }catch (Throwable $th) {
            DB::rollBack();
            return false;
        }
    }
}
