@extends("home")
@section("title", "EDIT PEMILIH PASTI -")
@section("bardcumb")
<section class="content-header">
    <div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
        <h1>Pemilih Pasti</h1>
        </div>
        <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ url('admin') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ url('admin/pemilih') }}">Pemilih Pasti</a></li>
            <li class="breadcrumb-item active">Edit</li>
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
    <div class='row'>
    <div class='col-sm-6'>
      <div class="card card-primary">
        <div class="card-header">
          <h3 class="card-title">Edit Data Pemilih Pasti</h3>

          <div class="card-tools">
            <!-- <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
              <i class="fas fa-minus"></i>
            </button> -->
          </div>
        </div>
        <div class="card-body">
            <form action="javascript:void(0)" id='FormData'>
                @csrf
                @method("put")
                <input type="hidden" id="id" name="id">
                <div class="form-group">
                    <label for="no_ktp" autocomplete=off class='control-label'>No KTP <span class='text-danger'>*</span></label>
                    <input type="text" placeholder="Entri No KTP" name='no_ktp' id='no_ktp' class='form-control FormIsi'>
                </div>

                <div class="form-group">
                    <label for="nama" autocomplete=off class='control-label'>Nama <span class='text-danger'>*</span></label>
                    <input type="text" placeholder="Entri Nama" name='nama' id='nama' class='form-control FormIsi'>
                </div>

                <div class="form-group">
                    <label for="id_kabupaten" autocomplete=off class='control-label'>Kabupaten </label>
                    <select name="id_kabupaten" id="id_kabupaten" class='form-control'>
                        <option value=""></option>
                        @foreach($kabupatens as $kab)
                        <option value="{{ $kab->id }}">{{ $kab->nama }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="id_kecamatan" autocomplete=off class='control-label'>Kecamatan <span class='text-danger'>*</span></label>
                    <select name="id_kecamatan" id="id_kecamatan" class='form-control'></select>
                </div>

                <div class="form-group">
                    <label for="id_desa" autocomplete=off class='control-label'>Desa <span class='text-danger'>*</span></label>
                    <select name="id_desa" id="id_desa" class='form-control'></select>
                </div>

                <div class="form-group">
                    <label for="id_tps" autocomplete=off class='control-label'>TPS <span class='text-danger'>*</span></label>
                    <select name="id_tps" id="id_tps" class='form-control'></select>
                </div>

                <div class="form-group">
                    <label for="alamat" autocomplete=off class='control-label'>Alamat <span class='text-danger'>*</span></label>
                    <input type="text" placeholder="Entri Alamat" name='alamat' id='alamat' class='form-control FormIsi'>
                </div>

                <div class="form-group">
                    <label for="keterangan" autocomplete=off class='control-label'>Keterangan <span class='text-danger'>*</span></label>
                    <input type="text" placeholder="Entri Keterangan" name='keterangan' id='keterangan' class='form-control FormIsi'>
                </div>
                
                <hr>
                <div class="form-group">
                    <button  class='btn btn-sm btn-success btn-flat'><i class='fa fa-save'></i> Submit</button>
                    <a class='btn btn-sm btn-danger' href="{{ url('admin/pemilih') }}" data-toggle='tooltip' title='Kembali'><i class="fa fa-reply"></i> Kembali</a>
                </div>

            </form>
        </div>
        <!-- /.card-body -->
        
      </div>
      </div>
      </div>
      <!-- /.card -->

    </section>
    <!-- /.content -->
@endsection

@section('script')
<script>
    $(document).ready(function(){
        getData("{{ Request::segment(4) }}");

        $('#id_kabupaten').select2({
            theme: 'bootstrap4',
            placeholder: 'Pilih Kabupaten',
            allowClear: true,
        });
        $('#level').select2({
            theme: 'bootstrap4',
            placeholder: 'Pilih Level',
            allowClear: true,
        });

        $("#id_kabupaten").on("change", function(e){
            var id = $("#id_kabupaten").val();
            get_kecamatan(id);
        })
        $("#id_kecamatan").on("change", function(e){
            var id = $("#id_kecamatan").val();
            get_desa(id);
        })
        $("#id_desa").on("change", function(e){
            var id = $("#id_desa").val();
            console.log(id);
            get_tps(id);
        })
    })

    function get_kecamatan(id_kab){
        var id_kabupaten = btoa(id_kab);
        $('#id_kecamatan').select2({
            theme: 'bootstrap4',
            placeholder: 'Pilih Kecamatan',
            allowClear: true,
            ajax: {
                url: "{{ url('api/kecamatan/get_data_by_kab/') }}/"+id_kabupaten,
                processResults: function (data) {
                    return {
                        results: data.data
                    };
                }
            }
        })
    }

    function selected_data(id,id_data,text){
        $("#"+id).append("<option value='"+id_data+"'>"+text+"</option>");
    }

    function get_desa(id_kec){
        var id_kecamatan = btoa(id_kec);
        $('#id_desa').select2({
            theme: 'bootstrap4',
            placeholder: 'Pilih Desa / Kelurahan',
            allowClear: true,
            ajax: {
                url: "{{ url('api/kel-desa/get_data_by_kec/') }}/"+id_kecamatan,
                processResults: function (data) {
                    return {
                        results: data.data
                    };
                }
            }
        })
    }

    function get_tps(id){
        var id_desa = btoa(id);
        $('#id_tps').select2({
            theme: 'bootstrap4',
            placeholder: 'Pilih TPS',
            allowClear: true,
            ajax: {
                url: "{{ url('api/tps/get_data_by_des/') }}/"+id_desa,
                processResults: function (data) {
                    return {
                        results: data.data
                    };
                }
            }
        })
    }

    function getData(id_data){
        $.ajax({
            type : "GET",
            dataType: "json",
            url : "{{ url('api/pemilih-pasti/get_data') }}/"+id_data,
            data : "input=[]",
            error: function(er){
                console.log(er)
            },
            success: function(data){
                $("#id").val(id_data);
                $("#nama").val(data.data.nama)
                $("#no_ktp").val(data.data.no_ktp)
                $("#keterangan").val(data.data.keterangan)
                $("#alamat").val(data.data.alamat)
                $("#id_kabupaten").val(data.data.id_kabupaten).trigger("change");
                get_kecamatan(data.data.id_kabupaten);
                get_desa(data.data.id_kecamatan);
                selected_data("id_kecamatan",data.data.id_kecamatan,data.data.kecamatan);
                selected_data("id_desa",data.data.id_desa,data.data.desa);
                selected_data("id_tps",data.data.id_tps,data.data.tps);
                

            }
        })
    }
    
    function sweetAlertSucc(status,message){
        var Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 1000
            });
        Toast.fire({
            icon: status,
            title: message
        });
    }

    function sweetAlertErr(status,message){
        var Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 2000
            });
        Toast.fire({
            icon: status,
            title: message
        });
    }

    $("#FormData").submit(function(e){
        e.preventDefault();
        SubmitData();
    })


    function SubmitData() {
        var idata =new FormData($('#FormData')[0]);
        $.ajax({
            type	: "POST",
            dataType: "json",
            url		: "{{ url('api/pemilih-pasti/update') }}",
            data	: idata,
            processData: false,
            contentType: false,
            cache 	: false,
            beforeSend: function () { 
                // in_load();
            },
            success	:function(data) {
                sweetAlertSucc(data.status,data.messages);
            },
            error: function (error) {
                console.log(error)
                if(error.responseJSON.status){
                    sweetAlertErr(error.responseJSON.status,error.responseJSON.messages)
                }else{
                    sweetAlertErr("warning",error.responseJSON.message)
                }
            }
        });
    }

    
</script>
@endsection