@extends('layouts.master')
@php
    $kategori = \App\Models\KategoriBarang::select('id','nama')->get();
    $satuan = \App\Models\SatuanBarang::select('id','nama','keterangan')->get();
@endphp
@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
      <h1 class="h3 mb-0 text-gray-800">Detail {{$data->nama}}</h1>
    </div>
    {{-- MODAL EDIT GAMBAR --}}
    <div class="modal fade" id="edit_gambar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
          <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Ubah Gambar</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
          </button>
          </div>
          <form action="{{ route('barang.update-image', ['id' => $data->id]) }}" method="POST" enctype="multipart/form-data">
          @csrf
          <div class="modal-body">
              <div class="form-group custom-file">
                <input type="file" name="gambar" class="custom-file-input" id="imgInp">
                <label class="custom-file-label" for="customFile">Pilih Gambar (MAKSIMAL 2MB)</label>
              </div>
              <div class="img-fluid" id="preview">

              </div>
          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
              <button type="submit" class="btn btn-primary">Simpan</button>
          </div>
      </form>
      </div>
      </div>
    </div>
    {{-- END --}}
    {{--  MODAL EDIT BARANG --}}
    <div class="modal fade" id="edit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
          <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Edit Barang</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
          </button>
          </div>
          <form action="{{ route('barang.update', ['id' => $data->id]) }}" method="POST">
          @csrf
          <div class="modal-body">
              <div class="form-group">
                  <label for="nama_kategori">Nama Kategori</label>
                  <select class="form-control" name="kategori_id">
                     <option selected disabled>Pilih Kategori</option>
                     @foreach ($kategori as $item)
                        <option value="{{$item->id}}" {{$item->id === $data->kategori_id ? 'selected' : ''}}>{{$item->nama}}</option>
                     @endforeach
                  </select>
              </div>
              <div class="form-group">
                <label>Satuan</label>
                <select class="form-control" name="satuan_id">
                   <option disabled>Pilih Satuan</option>
                   @foreach ($satuan as $item)
                      <option value="{{$item->id}}" {{$item->id === $data->satuan_id ? 'selected' : ''}}>{{$item->nama}} | {{$item->keterangan}}</option>
                   @endforeach
                </select>
              </div>
              <div class="form-group">
                <label>Nama Barang</label>
                <input type="text" name="nama" value="{{$data->nama}}" class="form-control" />
              </div>
              <div class="form-group">
                <label>Deskripsi</label>
                <textarea class="form-control" name="deskripsi">{{$data->deskripsi}}</textarea>
              </div>
              <div class="form-group">
                <label>Harga</label>
                <input type="number" name="harga" value="{{$data->harga}}" class="form-control" />
              </div>
              <div class="form-group">
                <label>Stock</label>
                <input type="number" name="stock" class="form-control" value="{{$data->stock}}" />
              </div>
          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
              <button type="submit" class="btn btn-primary">Simpan</button>
          </div>
      </form>
      </div>
      </div>
    </div>
    {{-- END --}}
    <div class="row">
      <div class="col-xl-4 col-lg-5">
        <img src="{{$data->gambar != null ? $data->gambar : URL::asset('gambar/barang/noimage.png')}}" alt="Responsive image" class="img-thumbnail" />
        <div class="text-center">
          <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#edit_gambar" type="button">Ganti Gambar</button>
        </div>
      </div>
      <div class="col-xl-8 col-lg-7">
        <div class="card shadow mb-4">
          <!-- Card Header - Dropdown -->
          <div
              class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
              <h6 class="m-0 font-weight-bold text-primary">Detail</h6>
          </div>
          <!-- Card Body -->
          <div class="card-body">
            <div class="row">
              <div class="col-4">Nama </div>
              <div class="col-4">: {{$data->nama}}</div>
            </div>
            <hr />
            <div class="row">
              <div class="col-4">Kategori </div>
              <div class="col-4">: {{$data->kategori->nama}}</div>
            </div>
            <hr />
            <div class="row">
              <div class="col-4">Satuan </div>
              <div class="col-4">: {{$data->satuan->nama}}</div>
            </div>
            <hr />
            <div class="row">
              <div class="col-4">Harga </div>
              <div class="col-4">: {{rupiah($data->harga)}}</div>
            </div>
            <hr />
            <div class="row">
              <div class="col-4">Deskripsi </div>
              <div class="col-4">: {{$data->deskripsi}}</div>
            </div>
            <hr />
            <div class="row">
              <div class="col-4">Stock </div>
              <div class="col-4">: {{$data->stock}}</div>
            </div>
            <hr />
            <div class="text-center">
              <button class="btn btn-warning" data-toggle="modal" data-target="#edit" type="button">Edit</button>
              <a class="btn btn-primary" href="{{route('barang.index')}}" type="button">Kembali</a>
            </div>
          </div>
      </div>
      </div>
    </div>
@endsection

@section('scripts')
    <script type="text/javascript">
      imgInp.onchange = evt => {
        const [file] = imgInp.files
        if (file) {
          document.getElementById('preview').innerHTML = `<img id="preview" src="${URL.createObjectURL(file)}" alt="your image" width="auto" class="img-thumbnail" />`
        }
      }
    </script>
@endsection