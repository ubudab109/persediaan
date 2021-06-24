@extends('layouts.master')
@section('content')
     <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
      <h1 class="h3 mb-0 text-gray-800">Laporan Stock Barang</h1>
    </div>

    <div class="row">
      <div class="col-xl-12 col-lg-">
          <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
              <h6 class="m-0 font-weight-bold text-primary">Data Stock Barang</h6>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table id="barang" class="table table-bordered">
                  <thead>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Satuan</th>
                    <th>Kategori</th>
                    <th>Harga</th>
                    <th>Stok Barang</th>
                  </thead>
                  <tbody></tbody>
                </table>
              </div>
            </div>
          </div>
      </div>
    </div>
@endsection

@section('scripts')
<script type="text/javascript">
  $(document).ready(() => {
    var table = $("#barang").DataTable({
      processing: true,
      serverSide: true,
      ajax: "{{route('data-stock')}}",
      columns: [
        {data:'DT_RowIndex', name:'DT_RowIndex'},
        {data: 'nama',name: 'nama'},
        {data: 'satuan',name: 'satuan'},
        {data: 'kategori',name: 'kategori'},
        {data: 'harga',name: 'harga'},
        {data: 'stock',name: 'stock'},
      ],
      dom: 'Bfrtip',
      buttons : [
                  {
                      extend:'csv',
                      title: `Stock Barang - ${ourDateNow()}`,
                      exportOptions: {
                          columns: [ 1, 2, 3, 4, 5 ]
                      }
                  },
                  {
                      extend: 'excel',
                      title: `Stock Barang - ${ourDateNow()}`,
                      exportOptions: {
                          columns: [ 1, 2, 3, 4, 5 ]
                      }
                  },
      ],
    });
  });
</script>
@endsection