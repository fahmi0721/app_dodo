@extends("home")
@section("title", "PARTAI -")
@section("bardcumb")
<section class="content-header">
    <div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
        <h1>Partai</h1>
        </div>
        <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ url('admin') }}">Home</a></li>
            <li class="breadcrumb-item active">Partai</li>
        </ol>
        </div>
    </div>
    </div><!-- /.container-fluid -->
</section>

@endsection
@section("konten")
    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Detail Data Partai</h3>

          <div class="card-tools">
            <!-- <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
              <i class="fas fa-minus"></i>
            </button> -->
            <a href="{{ url('admin/partai/tambah') }}" class="btn btn-sm btn-primary">
            <i class="fas fa-plus-square"></i> Tambah
            </a> 
          </div>
        </div>
        <div class="card-body">
          <div class='table-responsive'>
                <table class='table table-striped table-bordered' id="TableData">
                    <thead>
                        <tr>
                            <th width="5px">No</th>
                            <th>Nama Partai</th>
                            <th>Keterangan</th>
                            <th width='5%'>Action</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
          </div>
        </div>
        <!-- /.card-body -->
        
      </div>
      <!-- /.card -->

    </section>
    <!-- /.content -->
@endsection

@section('script')
<script>
    $(function() {
       $('#TableData').DataTable({
            stateSave: true,
            processing: true,
            responsive: true,
            serverSide: true,
            ajax: "{{ url('api/partai') }}",
            columns: [
                { data: 'DT_RowIndex', className: "text-center",'searchable':false,"orderable": false },
                { data: 'nama', name: 'nama' },
                { data: 'keterangan', name: 'keterangan' },
                { data: 'action', name: 'action', className: "text-center",'searchable':false,"orderable": false },
            ],
        });
    });

    function hapus_data(id){
        Swal.fire({
            title: "Do you want to delete data?",
            showCancelButton: true,
            confirmButtonText: "Yes",
            }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {
                $.ajax({
                    type	: "POST",
                    dataType: "json",
                    url		: "{{ url('api/partai/delete') }}/"+id,
                    data	: "_method=DELETE&_token="+tokenCSRF,
                    success	:function(data) {
                        console.log(data);
                        var Toast = Swal.mixin({
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 2000
                            });
                        Toast.fire({
                            icon: data.status,
                            title: data.messages
                        }).then((result) => {
                            var table = $('#TableData').DataTable();
                            table.ajax.reload(null, false);  
                        })
                       
                    },
                    error: function(er){
                        console.log(er);
                    }
                });
            }
        });
    }

    
</script>
@endsection