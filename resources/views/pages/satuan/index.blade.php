@extends('layouts.master')

@section('content')
     <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
      <h1 class="h3 mb-0 text-gray-800">Satuan</h1>
    </div>
    {{--  MODAL TAMBAH SATUAN --}}
    <div class="modal fade" id="tambah" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
      <div class="modal-content">
          <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Tambah Satuan</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
          </button>
          </div>
          <form action="{{ route('satuan.store') }}" method="POST">
          @csrf
          <div class="modal-body">
              <div class="form-group">
                  <label for="nama_kategori">Nama Satuan</label>
                  <input type="text" class="form-control" required placeholder="Nama Satuan..." name="nama">
              </div>
              <div class="form-group">
                <label for="nama_kategori">Keterangan</label>
                <input type="text" class="form-control" required placeholder="Keterangan..." name="keterangan">
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

  {{--  MODAL EDIT SATUAN --}}
  <div class="modal fade" id="edit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Edit Satuan</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        </div>
        <form id="edit_satuan">
        <div class="modal-body">
            <div class="form-group">
                <label for="nama_kategori">Nama Satuan</label>
                <input type="text" class="form-control" id="edit_nama" required placeholder="Nama Satuan..." name="nama">
                <input type="hidden" name="id" id="id_satuan">
            </div>
            <div class="form-group">
              <label for="nama_kategori">Keterangan</label>
              <input type="text" class="form-control" id="edit_keterangan" required placeholder="Keterangan..." name="keterangan">
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
      <div class="col-xl-12 col-lg-">
          <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
              <h6 class="m-0 font-weight-bold text-primary">Data Satuan</h6>
            </div>
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
              <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#tambah">Tambah Satuan</button>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table id="satuan" class="table table-bordered">
                  <thead>
                    <th>Nama</th>
                    <th>Keterangan</th>
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
        var table = $("#satuan").DataTable({
          processing: true,
          serverSide: true,
          ajax: "{{route('data.satuan')}}",
          columns: [
            {data: 'nama',name: 'nama'},
            {data: 'keterangan',name: 'keterangan'},
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
      function show(id) {
        var url = '{{ route("satuan.show", ":id") }}';
            url = url.replace(':id', id );
        $.ajax({
          type: 'GET',
          url: url,
          success: function (data) {
            document.getElementById('edit_nama').value = data.nama;
            document.getElementById('edit_keterangan').value = data.keterangan;
            document.getElementById('id_satuan').value = data.id;
          }
        })
      }
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
                        var url = '{{ route("satuan.delete", ":id") }}';
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

        $("#edit_satuan").on('submit',(e) => {
            e.preventDefault();
            var id = document.getElementById('id_satuan').value;
            var nama = document.getElementById('edit_nama').value;
            var keterangan = document.getElementById('edit_keterangan').value;
            var url = '{{ route("satuan.update", ":id") }}';
                url = url.replace(':id', id );
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "POST",
                    url: url,
                    data:{
                        nama: nama,
                        keterangan: keterangan,
                    },
                    success: function (data) {
                      Swal.fire({
                            title: "Sukses!",
                            text: "Data Berhasil Diubah!",
                            icon: "success",
                        }).then((value) => {
                            location.reload()
                        });
                    },
                    error: function (data){
                      Swal.fire({
                            title: "Gagal",
                            text: "Periksa Inputan!",
                            icon: "error",
                        });
                    }
                });
        });
    </script>
@endsection