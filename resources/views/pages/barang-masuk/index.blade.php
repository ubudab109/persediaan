@extends('layouts.master')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
      <h1 class="h3 mb-0 text-gray-800">Barang Masuk</h1>
    </div>

    <div class="row">
      <div class="col-xl-12 col-lg-">
          <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
              <h6 class="m-0 font-weight-bold text-primary">Data Barang</h6>
            </div>
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
              <a href="{{route('barang-masuk.create')}}" class="btn btn-primary">Tambah Barang Masuk</a>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table id="barang_masuk" class="table table-bordered">
                  <thead>
                    <th>Tanggal Transaksi</th>
                    <th>No Referensi</th>
                    <th>Supplier</th>
                    <th>Jumlah Barang</th>
                    <th>Status</th>
                    <th>Aksi</th>
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
        var table = $("#barang_masuk").DataTable({
          processing: true,
          serverSide: true,
          ajax: "{{route('data.barang-masuk')}}",
          columns: [
            {data: 'tanggal_transaksi',name: 'tanggal_transaksi'},
            {data: 'no_referensi',name: 'no_referensi'},
            {data: 'supplier',name: 'supplier'},
            {data: 'total_barang',name: 'total_barang'},
            {data: 'status',name: 'status'},
            {
                data: 'action',
                name: 'action',
                orderable: true,
                searchable: true
            },
          ],
        });
      });
    </script>
    <script type="text/javascript">
    function destroy(id){
            Swal.fire({
                    title: "Anda yakin ingin menghapus data ini?",
                    text: "Saat terhapus, data tidak bisa dikembalikan!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Iya, hapus!'
                })
                .then((willDelete) => {
                    if (willDelete.value) {
                        var url = '{{ route("barang-masuk.delete", ":id") }}';
                        url = url.replace(':id', id );
                        $.ajax({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            type: "DELETE",
                            url: url,
                            success: function (data) {
                                swal.fire({
                                    title: "Sukses!",
                                    text: "Data Berhasil Dihapus!",
                                    icon: "success",
                                }).then((value) => {
                                    location.reload()
                                });
                            },
                            error: function (data){
                                swal.fire({
                                    title: "Gagal",
                                    text: "Silahkan ulangi!",
                                    icon: "error",
                                });
                            }
                        });
                    } else {
                        return false;
                    }
                });
        }
        function preOrder(id) {
          Swal.fire({
                    title: "Ubah Status",
                    text: "Anda yakin ingin mengubah status menjadi Pre Order?",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Iya, Ubah!'
                }).then((willDelete) => {
                  var url = '{{ route("barang-masuk.update-status", ":id") }}';
                        url = url.replace(':id', id );
                    $.ajax({
                      headers: {
                          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                      },
                      type: 'POST',
                      url: url,
                      data: {
                        status: 0
                      },
                      success: function (data) {
                        Swal.fire({
                              title: "Sukses!",
                              text: "Status Data Berhasil Diubah!",
                              icon: "success",
                          }).then((value) => {
                              location.reload()
                          });
                      },
                      error: function (data){
                        Swal.fire({
                              title: "Gagal",
                              text: "Silahkan Ulangi!",
                              icon: "error",
                          });
                      }
                    });
                });
          }

          function send(id){
          Swal.fire({
                    title: "Ubah Status",
                    text: "Anda yakin ingin mengubah status menjadi Dalam Pengiriman?",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Iya, Ubah!'
                }).then((willDelete) => { 
                    var url = '{{ route("barang-masuk.update-status", ":id") }}';
                        url = url.replace(':id', id );
                    $.ajax({
                      headers: {
                          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                      },
                      type: 'POST',
                      url: url,
                      data: {
                        status: 1
                      },
                      success: function (data) {
                        Swal.fire({
                              title: "Sukses!",
                              text: "Status Data Berhasil Diubah!",
                              icon: "success",
                          }).then((value) => {
                              location.reload()
                          });
                      },
                      error: function (data){
                        Swal.fire({
                              title: "Gagal",
                              text: "Silahkan Ulangi!",
                              icon: "error",
                          });
                      }
                    });
                });
            }

            
        

        function receive(id){
          Swal.fire({
                    title: "Ubah Status",
                    text: "Anda yakin ingin mengubah status menjadi Diterima?",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Iya, Ubah!'
                })
                .then((willDelete) => { 
                    var url = '{{ route("barang-masuk.update-status", ":id") }}';
                        url = url.replace(':id', id );
                    $.ajax({
                      headers: {
                          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                      },
                      type: 'POST',
                      url: url,
                      data: {
                        status: 2
                      },
                      success: function (data) {
                        Swal.fire({
                              title: "Sukses!",
                              text: "Status Data Berhasil Diubah!",
                              icon: "success",
                          }).then((value) => {
                              location.reload()
                          });
                      },
                      error: function (data){
                        Swal.fire({
                              title: "Gagal",
                              text: "Silahkan Ulangi!",
                              icon: "error",
                          });
                      }
                    });
                });
            }
    </script>
@endsection