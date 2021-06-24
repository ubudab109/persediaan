@extends('layouts.master')

@section('content')
     <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
      <h1 class="h3 mb-0 text-gray-800">Kategori Barang</h1>
    </div>
    {{--  MODAL TAMBAH KATEGORI --}}
    <div class="modal fade" id="tambah" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
      <div class="modal-content">
          <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Tambah Kategori</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
          </button>
          </div>
          <form action="{{ route('kategori.store') }}" method="POST">
          @csrf
          <div class="modal-body">
              <div class="form-group">
                  <label for="nama_kategori">Nama Kategori</label>
                  <input type="text" class="form-control" required placeholder="Nama Kategori..." name="nama">
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

  {{--  MODAL EDIT KATEGORI --}}
  <div class="modal fade" id="edit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Edit Kategori</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        </div>
        <form id="edit_kategori">
        <div class="modal-body">
            <div class="form-group">
                <label for="nama_kategori">Nama Kategori</label>
                <input type="text" class="form-control" id="edit_nama" required placeholder="Nama Kategori..." name="nama">
                <input type="hidden" name="id" id="id_kategori">
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
              <h6 class="m-0 font-weight-bold text-primary">Data Kategori Barang</h6>
            </div>
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
              <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#tambah">Tambah Kategori</button>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table id="kategori" class="table table-bordered">
                  <thead>
                    <th>Nama</th>
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
        var table = $("#kategori").DataTable({
          processing: true,
          serverSide: true,
          ajax: "{{route('data.kategori')}}",
          columns: [
            {data: 'nama',name: 'nama'},
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
        var url = '{{ route("kategori.show", ":id") }}';
            url = url.replace(':id', id );
        $.ajax({
          type: 'GET',
          url: url,
          success: function (data) {
            document.getElementById('edit_nama').value = data.nama;
            document.getElementById('id_kategori').value = data.id;
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
                        var url = '{{ route("kategori.delete", ":id") }}';
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

        $("#edit_kategori").on('submit',(e) => {
            e.preventDefault();
            var id = document.getElementById('id_kategori').value;
            var nama = document.getElementById('edit_nama').value;
            var url = '{{ route("kategori.update", ":id") }}';
                url = url.replace(':id', id );
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "POST",
                    url: url,
                    data:{
                        nama: nama,
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