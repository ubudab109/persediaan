@extends('layouts.master')

@section('content')
  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Tambah Barang Masuk</h1>
  </div>
  <div class="row">
    <div class="col-xl-6 col-lg-4">
      <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">Tambah Barang</h6>
      </div>
      <div class="card-body">
        <form id="tambah_barang">
          <div class="form-row">
            <div class="form-group col-md-4">
              <label for="inputState">Barang</label>
              <select class="form-control selectpicker" required id="barang" name="barang[0][id_barang]" data-live-search="true">
                <option disabled selected>Pilih Disini</option>
                @foreach ($barang as $item)
                  <option value="{{$item->id}}">{{$item->nama}}</option>
                @endforeach
              </select>
            </div>
            <div class="form-group col-md-4">
              <label for="inputZip">Jumlah</label>
              <input type="number" required id="jumlah" name="barang[0][jumlah]" class="form-control" id="inputZip">
            </div>
          </div>
          <button type="submit" class="btn btn-primary">Tambah</button>
        </form>
      </div>
    </div>
    <div class="col-xl-6 col-lg-4">
      <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">Input Detail Pengiriman</h6>
      </div>
      <div class="card-body">
        <form method="POST" action="{{route('barang-masuk.store')}}">
          @csrf
          <div class="form-row">
            <div class="form-group col-md-6">
              <label for="inputEmail4">No Referensi</label>
              <input type="text" name="no_referensi" class="form-control" required placeholder="Nomor Referensi">
            </div>
            <div class="form-group col-md-6">
              <label for="inputPassword4">Tanggal</label>
              <input type="date" name="tanggal_transaksi" class="form-control" id="inputPassword4" required>
            </div>
          </div>
          <div class="form-group">
            <label for="inputAddress">Catatan</label>
            <input type="text" class="form-control" name="catatan" id="inputAddress" placeholder="Catatan Tambahan..." required>
          </div>
          <div class="form-group">
            <label for="inputAddress">Supplier</label>
            <input type="text" class="form-control" name="supplier" id="inputAddress" placeholder="Supplier..." required>
          </div>
          <div class="form-group">
            <label for="inputAddress2">Status</label>
            <select class="form-control" name="status">
                <option selected disabled>Pilih Status</option>
                <option value="0">Pre Order</option>
                <option value="1">Dalam Pengiriman</option>
                <option value="2">Sudah Diterima</option>
            </select>
          </div>
          {{-- TABLE HERE --}}
          <table class="table table-bordered" id="barang_data">
              <thead>
                <th>Barang</th>
                <th>Jumlah</th>
                <th>Aksi</th>
              </thead>
              <tbody>

              </tbody>
          </table>
          <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
      </div>
    </div>
  </div>
@endsection

@section('scripts')
    <script type="text/javascript">
        $("#tambah_barang").on('submit', (e) => {
          e.preventDefault();
          var arr = 0;
          var i = arr++;
          var content = $("#barang_data tbody");
          var size = jQuery('#barang_data >tbody >tr').length + 1;
          var dataBarang = $("#barang").val();
          var namaBarang = $("#barang option:selected").text();
          var jumlah = $("#jumlah").val();
          var tr = `
            <tr id="${size}">
              <td>
                ${namaBarang}
                <input type="hidden" readOnly name="barang[${size}][id_barang]" value="${dataBarang}"/>
              </td>
              <td>
                ${jumlah}
                <input type="hidden" readOnly name="barang[${size}][jumlah]" value="${jumlah}"/>
              </td>
              <td>
                <button type="button" class="btn btn-danger btn-sm hapus" data-id="${size}">Hapus</button>
              </td>
            </tr>
          `;
          content.append(tr);
        })
        
        $(document).on('click', '.hapus', function() {
            $(this).parent().parent('tr').remove();
        });
    </script>
@endsection