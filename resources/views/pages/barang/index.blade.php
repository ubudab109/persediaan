@extends('layouts.master')
@php
    $kategori = \App\Models\KategoriBarang::select('id','nama')->get();
    $satuan = \App\Models\SatuanBarang::select('id','nama','keterangan')->get();
@endphp
@section('content')
     <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
      <h1 class="h3 mb-0 text-gray-800">Barang</h1>
    </div>
    {{--  MODAL TAMBAH BARANG --}}
    <div class="modal fade" id="tambah" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
          <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Tambah Barang</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
          </button>
          </div>
          <form action="{{ route('barang.store') }}" method="POST" enctype="multipart/form-data">
          @csrf
          <div class="modal-body">
              <div class="form-group">
                  <label for="nama_kategori">Nama Kategori</label>
                  <select class="form-control" name="kategori_id">
                     <option selected disabled>Pilih Kategori</option>
                     @foreach ($kategori as $item)
                        <option value="{{$item->id}}">{{$item->nama}}</option>
                     @endforeach
                  </select>
              </div>
              <div class="form-group">
                <label>Satuan</label>
                <select class="form-control" name="satuan_id">
                   <option selected disabled>Pilih Satuan</option>
                   @foreach ($satuan as $item)
                      <option value="{{$item->id}}">{{$item->nama}} | {{$item->keterangan}}</option>
                   @endforeach
                </select>
              </div>
              <div class="form-group">
                <label>Nama Barang</label>
                <input type="text" name="nama" class="form-control" />
              </div>
              <div class="form-group">
                <label>Deskripsi</label>
                <textarea class="form-control" name="deskripsi"></textarea>
              </div>
              <div class="form-group">
                <label>Harga</label>
                <input type="number" name="harga" class="form-control" />
              </div>
              <div class="form-group">
                <label>Stock</label>
                <input type="number" name="stock" class="form-control" />
              </div>
              <div class="form-group custom-file">
                <input type="file" name="gambar" class="custom-file-input" id="imgInp">
                <label class="custom-file-label" for="customFile">Pilih Gambar (MAKSIMAL 2MB)</label>
              </div>
              <div class="img-fluid" id="preview">

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

    <div class="row">
      <div class="col-xl-12 col-lg-">
          <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
              <h6 class="m-0 font-weight-bold text-primary">Data Barang</h6>
            </div>
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
              <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#tambah">Tambah Barang</button>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table id="barang" class="table table-bordered">
                  <thead>
                    <th>Nama</th>
                    <th>Satuan</th>
                    <th>Kategori</th>
                    <th>Harga</th>
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
        var table = $("#barang").DataTable({
          processing: true,
          serverSide: true,
          ajax: "{{route('data.barang')}}",
          columns: [
            {data: 'nama',name: 'nama'},
            {data: 'satuan',name: 'satuan'},
            {data: 'kategori',name: 'kategori'},
            {data: 'harga',name: 'harga'},
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
      imgInp.onchange = evt => {
        const [file] = imgInp.files
        if (file) {
          document.getElementById('preview').innerHTML = `<img id="preview" src="${URL.createObjectURL(file)}" alt="your image" width="auto" class="img-thumbnail" />`
        }
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
                        var url = '{{ route("barang.delete", ":id") }}';
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
    </script>
@endsection