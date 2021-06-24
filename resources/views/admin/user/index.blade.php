@extends('layouts.master')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
      <h1 class="h3 mb-0 text-gray-800">Data User</h1>
    </div>
    {{--  MODAL TAMBAH KATEGORI --}}
    <div class="modal fade" id="tambah" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
      <div class="modal-content">
          <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Tambah User</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
          </button>
          </div>
          <form action="{{ route('user.store') }}" method="POST">
          @csrf
          <div class="modal-body">
                <div class="form-group">
                  <label>Nama User</label>
                  <input type="text" class="form-control" required placeholder="Nama User..." name="name">
              </div>
              <div class="form-group">
                  <label>Role User</label>
                  <select name="role" class="form-control">
                      <option disabled selected>Pilih Role</option>
                      <option value="admin">Admin</option>
                      <option value="operator">Operator</option>
                  </select>
              </div>
              <div class="form-group">
                <label>Email User</label>
                <input type="email" class="form-control" required placeholder="Email User..." name="email">
              </div>
              <div class="form-group">
                <label>Username</label>
                <input type="text" class="form-control" required placeholder="Username..." name="username">
              </div>
              <div class="form-group">
                <label>Nomor Handphone</label>
                <input type="number" class="form-control" required placeholder="Nomor Handphone..." name="phone_number">
              </div>
              <div class="form-group">
                <label>Password</label>
                <input type="password" class="form-control" required name="password">
              </div>
              <div class="form-group">
                <label>Ulangi Password</label>
                <input type="password" class="form-control" required name="c_password">
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
  {{-- END --}}
    <div class="row">
      <div class="col-xl-12 col-lg-">
          <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
              <h6 class="m-0 font-weight-bold text-primary">Data User</h6>
            </div>
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
              <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#tambah">Tambah User</button>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table id="user" class="table table-bordered">
                  <thead>
                    <th>Id</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Username</th>
                    <th>Role</th>
                    <th>Nomor Handphone</th>
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
        var table = $("#user").DataTable({
          processing: true,
          serverSide: true,
          ajax: "{{route('data.user')}}",
          columns: [
            {data: 'id',name: 'id'},
            {data: 'name',name: 'name'},
            {data: 'email',name: 'email'},
            {data: 'username',name: 'username'},
            {data: 'role',name: 'role'},
            {data: 'phone_number',name: 'phone_number'},
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
    <script>
      function destroy(id){
            Swal.fire({
                    title: "Anda yakin ingin menonaktifkan user ini?",
                    text: "Anda bisa membuat aktif kembali user ini!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Iya!'
                })
                .then((willDelete) => {
                    if (willDelete.value) {
                        var url = '{{ route("user.delete", ":id") }}';
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
                                    text: "User Dinonaktifkan!",
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

        function restore(id){
            Swal.fire({
                    title: "Anda yakin ingin mengaktifkan kembali user ini?",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Iya!'
                })
                .then((willDelete) => {
                    if (willDelete.value) {
                        var url = '{{ route("user.restore", ":id") }}';
                        url = url.replace(':id', id );
                        $.ajax({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            type: "PATCH",
                            url: url,
                            success: function (data) {
                                swal.fire({
                                    title: "Sukses!",
                                    text: "User Diaktifkan Kembali!",
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
    </script>
@endsection