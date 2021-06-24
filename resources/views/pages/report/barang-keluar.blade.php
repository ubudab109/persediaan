@extends('layouts.master')
@section('content')
     <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
      <h1 class="h3 mb-0 text-gray-800">Laporan Barang Keluar</h1>
    </div>

    <div class="row">
      <div class="col-12">
          <div class="card">
              <div class="card-body">
                  <div class="row input-daterange">
                      <div class="col-md-4">
                          <input type="text" name="from_date" id="from_date" class="form-control" placeholder="From Date" readonly />
                      </div>
                      <div class="col-md-4">
                          <input type="text" name="to_date" id="to_date" class="form-control" placeholder="To Date" readonly />
                      </div>
                      <div class="col-md-4">
                          <button type="button" name="filter" id="filter" class="btn btn-primary">Filter</button>
                          <button type="button" name="refresh" id="refresh" class="btn btn-default">Refresh</button>
                      </div>
                  </div>
              </div>
          </div>
      </div>
  </div>
    <div class="row">
      <div class="col-xl-12 col-lg-">
          <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
              <h6 class="m-0 font-weight-bold text-primary">Data Barang Masuk</h6>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table id="barang-keluar" class="table table-bordered">
                  <thead>
                    <th>No</th>
                    <th>Nomor Referensi</th>
                    <th>Nama Barang</th>
                    <th>Kategori</th>
                    <th>Harga</th>
                    <th>Jumlah</th>
                    <th>Tanggal Transaksi</th>
                    <th>Catatan</th>
                    <th>Picker</th>
                    <th>Status</th>
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

    $('.input-daterange').datepicker({
        todayBtn:'linked',
        format:'yyyy-mm-dd',
        autoclose:true
    });

    load_data();

    function load_data(from_date = '', to_date = '') {
      $("#barang-keluar").DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{route('data-keluar')}}",
            data:{startDate:from_date, endDate:to_date}
        },
        columns: [
          {data:'DT_RowIndex', name:'DT_RowIndex'},
          {data: 'no_referensi',name: 'no_referensi'},
          {data: 'barang',name: 'barang'},
          {data: 'kategori',name: 'kategori'},
          {data: 'harga',name: 'harga'},
          {data: 'jumlah',name: 'jumlah'},
          {data: 'tanggal_keluar',name: 'tanggal_keluar'},
          {data: 'catatan',name: 'catatan'},
          {data: 'picker',name: 'picker'},
          {data: 'status',name: 'status'},
        ],
        dom: 'Bfrtip',
        buttons : [
                    {
                        extend:'csv',
                        title: `Barang Keluar - ${ourDateNow()}`,
                        exportOptions: {
                            columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 8, 9 ]
                        }
                    },
                    {
                        extend: 'excel',
                        title: `Barang Keluar - ${ourDateNow()}`,
                        exportOptions: {
                            columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 8, 9 ]
                        }
                    },
        ],
      });
    }

    $('#filter').click(function(){
        var from_date = $('#from_date').val();
        var to_date = $('#to_date').val();
        if(from_date != '' &&  to_date != ''){
            $('#barang-keluar').DataTable().destroy();
            load_data(from_date, to_date);
        }
        else{
            alert('Both Date is required');
        }
    });

    $('#refresh').click(function(){
        $('#from_date').val('');
        $('#to_date').val('');
        $('#barang-keluar').DataTable().destroy();
        load_data();
    });
  });
</script>
@endsection