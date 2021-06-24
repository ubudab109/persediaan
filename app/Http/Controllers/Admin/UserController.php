<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Alert;
use Throwable;
use Yajra\DataTables\DataTables;

class UserController extends Controller
{
    public $model;

    public function __construct(User $model)
    {
        $this->model = $model;
    }

    public function getUser(Request $request)
    {
        if($request->ajax()){

            $data = $this->model->withTrashed()->get();
            return DataTables::of($data)
                             ->addIndexColumn()
                             ->addColumn('action', function($row){
                                if($row->deleted_at !== null){
                                    $btn = '<a class="dropdown-item" href="javascript:void(0)" onClick="restore('.$row->id.')">Aktifkan</a>';
                                }else{
                                    $btn = '<a class="dropdown-item" href="javascript:void(0)" onClick="destroy(' . $row->id . ')">Non Aktifkan</a>';
                                }
                                $action = '<div class="btn-group">
                                    <button type="button" class="btn btn-warning btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        '.$btn.'
                                    </div>
                                  </div>
                                ';
    
                                return $action;
                             })
                             ->editColumn('status', function($row){
                                 if($row->deleted_at !== null){
                                     return '<span class="badge badge-danger">Tidak Aktif</span>';
                                 }else {
                                    return '<span class="badge badge-success">Aktif</span>';
                                 }
                             })
                             ->editColumn('role', function($row){
                                 return $row->getRoleNames()[0];
                             })
                             ->rawColumns(['action','status','role'])
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
        return view('admin.user.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'username' => 'required|unique:users',
            'phone_number' => 'numeric',
            'password' => 'required|same:c_password',
            'c_password' => 'required|same:password',
            'role' => 'required',
        ]);

        if($validator->fails()) {
            Alert::error('Error', $validator->errors());
            return redirect()->back();
        }
        $input = $request->all();
        $input['password'] = Hash::make($input['password']);
        $user = $this->model->create($input);
        $user->assignRole($input['role']);
        Alert::success('Sukses', 'Data berhasil ditambah');
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
    public function update(Request $request, $id)
    {
        //
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
        } catch (Throwable $th){
            DB::rollBack();
            return false;
        }
    }

    public function restore($id)
    {
        DB::beginTransaction();
        try {
            $this->model->withTrashed()->find($id)->restore();
            DB::commit();
            return true;
        } catch (Throwable $th){
            DB::rollBack();
            return false;
        }
    }
}
