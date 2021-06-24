@extends('layouts.master')
@php
    $barang = \App\Models\Barang::select('id','nama')->get();
@endphp
@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <h1 class="h3 mb-0 text-gray-800">Detail {{$data->no_referensi}}</h1>
</div>
{{--  MODAL TAMBAH BARANG --}}
<div class="modal fade" id="tambah" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
  <div class="modal-content">
      <div class="modal-header">
      <h5 class="modal-title" id="exampleModalLabel">Tambah Barang</h5>
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
      </button>
      </div>
      <form action="{{ route('barang-masuk.update-barang', ['id' => $data->id]) }}" method="POST">
      @csrf
      <div class="modal-body">
          <div class="form-group">
            <select class="form-control selectpicker" id="barang" name="id_barang" data-live-search="true">
              @foreach ($barang as $item)
                <option value="{{$item->id}}">{{$item->nama}}</option>
              @endforeach
            </select>
          </div>
          <div class="form-group col-md-4">
            <label for="inputZip">Jumlah</label>
            <input type="number" id="jumlah" name="jumlah" class="form-control" id="inputZip">
          </div>
      </div>
      <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
          <button type="submit" class="btn btn-primary">Tambah</button>
      </div>
  </form>
  </div>
  </div>
</div>
{{-- END --}}
<input type="hidden" name="" id="id_detail" value="{{$data->id}}">
<div class="row">
  <div class="col-xl-6 col-lg-4">
      <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
          <h6 class="m-0 font-weight-bold text-primary">Data Barang</h6>
        </div>
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
          <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#tambah">Tambah Barang</button>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table id="barang_masuk" class="table table-bordered">
              <thead>
                <th>No</th>
                <th>Barang</th>
                <th>Harga</th>
                <th>Jumlah</th>
                <th>Satuan</th>
              </thead>
              <tbody></tbody>
            </table>
          </div>
        </div>
      </div>
  </div>
  <div class="col-xl-6 col-lg-4">
    <div class="card shadow mb-4">
      <!-- Card Header - Dropdown -->
      <div
          class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
          <h6 class="m-0 font-weight-bold text-primary">Detail</h6>
      </div>
      <!-- Card Body -->
      <div class="card-body">
        <div class="row">
          <div class="col-4">No Referensi </div>
          <div class="col-4">: {{$data->no_referensi}}</div>
        </div>
        <hr />
        <div class="row">
          <div class="col-4">Supplier </div>
          <div class="col-4">: {{$data->supplier}}</div>
        </div>
        <hr />
        <div class="row">
          <div class="col-4">Tanggal Transaksi </div>
          <div class="col-4">: {{$data->tanggal_transaksi}}</div>
        </div>
        <hr />
        <div class="row">
          <div class="col-4">Catatan </div>
          <div class="col-4">: {{$data->catatan}}</div>
        </div>
        <hr />
        <div class="row">
          <div class="col-4">Total Barang </div>
          <div class="col-4">: {{$data->total_barang}}</div>
        </div>
        <hr />
        <div class="row">
          <div class="col-4">Status </div>
          <div class="col-4">: {{$data->status == 0 ? 'Pre Order' : ($data->status == 1 ? 'Dalam Pengiriman' : 'Diterima')}}</div>
        </div>
        <hr />
        <div class="text-center">
          <a class="btn btn-primary" href="{{route('barang-masuk.index')}}" type="button">Kembali</a>
        </div>
      </div>
  </div>
</div>
@endsection

@section('scripts')
  <script type="text/javascript">
    $(document).ready(() => {
    var id = document.getElementById('id_detail').value;
    var url = '{{ route("data.detail-barang-masuk", ":id") }}';
        url = url.replace(':id', id);
    var table = $("#barang_masuk").DataTable({
      processing: true,
      serverSide: true,
      ajax: url,
      columns: [
        {data:'DT_RowIndex', name:'DT_RowIndex'},
        {data: 'barang',name: 'barang'},
        {data: 'harga',name: 'harga'},
        {data: 'jumlah',name: 'jumlah'},
        {data: 'satuan',name: 'satuan'},
      ],
    });
  });
  </script>
@endsection