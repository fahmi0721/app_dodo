@extends("home")
@section("title", "REKAPITULASI SUARA -")
@section("bardcumb")
<section class="content-header">
    <div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
        <h1>Rekapitulasi Suara</h1>
        </div>
        <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ url('admin') }}">Home</a></li>
            <li class="breadcrumb-item active">Rekapitulasi Suara</li>
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
        <div class='col-sm-4'>
        <div class="card card-primary">
            <div class="card-header">
            <h3 class="card-title">Rekapitulasi Suara</h3>

            <div class="card-tools">
                <!-- <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                <i class="fas fa-minus"></i>
                </button> -->
            </div>
            </div>
            <div class="card-body">
                <form action="javascript:void(0)" id='FormData'>
                    @csrf
                    <div class="form-group">
                        <label for="berdasarkan" autocomplete=off class='control-label'>Berdasarkan <span class='text-danger'>*</span></label>
                        <select name="berdasarkan" id="berdasarkan" onchange="open_form(this.value)" class='form-control'>
                            <option value="kabupaten">Kabupaten</option>
                            <option value="kecamatan">Kecamatan</option>
                            <option value="desa">Desa/Kelurahan</option>
                            <option value="tps">Tps</option>
                        </select>
                    </div>

                    <div class="form-group kecamatan desa tps form">
                        <label for="id_kabupaten" autocomplete=off class='control-label'>Kabupaten <span class='text-danger'>*</span></label>
                        <select name="id_kabupaten" id="id_kabupaten" class='form-control'><option value="">Pilih Kecamatan</option></select>
                    </div>

                    <div class="form-group desa tps form">
                        <label for="id_kecamatan" autocomplete=off class='control-label'>Kecamatan <span class='text-danger'>*</span></label>
                        <select name="id_kecamatan" id="id_kecamatan" class='form-control'><option value="">Pilih Desa / Kelurahan</option></select>
                    </div>

                    <div class="form-group tps form">
                        <label for="id_desa" autocomplete=off class='control-label'>Desa <span class='text-danger'>*</span></label>
                        <select name="id_desa" id="id_desa" class='form-control'><option value="">Pilih Desa / Kelurahan</option></select>
                    </div>

                    <hr>
                    <div class="form-group">
                        <button  class='btn btn-sm btn-success btn-flat'><i class='fa fa-save'></i> Submit</button>
                        <a id='btn_download' href="javascript:void(0)" onclick="download_data()"  class='btn btn-sm btn-primary btn-flat'><i class='fa fa-download'></i> Download Excel</a>
                    </div>

                </form>
            </div>
            <!-- /.card-body -->
            
        </div>
        </div>
        <div  class="col-sm-8">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Grafik Rekapitulasi Suara</h3>

                    <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-tool" data-card-widget="remove">
                        <i class="fas fa-times"></i>
                    </button>
                    </div>
                </div>
                <div class="card-body" id="canvas-body">
                    <canvas id="grafik_suara" style="min-height: 250px;  max-height: 250px; max-width: 100%;"></canvas>
                </div>
                <!-- /.card-body -->
            </div>
        </div>
      
      
      <!-- /.card -->

</section>
    <!-- /.content -->
@endsection

@section('script')
<script>
    $(document).ready(function(){
        // grafik_bar();
        $(".form").hide();
        $("#btn_download").hide();  
        $('#berdasarkan').select2({
            theme: 'bootstrap4',
            placeholder: 'Pilih Berdasarkan',
            allowClear: true,
        });
        get_kabupaten();

        $('#id_kecamatan').select2({
            theme: 'bootstrap4',
            placeholder: 'Pilih Kecamatan',
            allowClear: true,
        });

        $('#id_desa').select2({
            theme: 'bootstrap4',
            placeholder: 'Pilih Desa',
            allowClear: true,
        });

        $("#berdasarkan").on("change", function(e){
            $("#canvas-body > canvas").remove();
            $("#canvas-body").html("<canvas id='grafik_suara' style='min-height: 250px;  max-height: 250px; max-width: 100%;'></canvas>")
            $("#id_kabupaten").val("").trigger("change");
            $("#id_kecamatan").val("").trigger("change");
            $("#id_desa").val("").trigger("change");
        })

        $("#id_kabupaten").on("change", function(e){
            var id = $("#id_kabupaten").val();
            $("#id_kecamatan").val("").trigger("change");
            $("#id_desa").val("").trigger("change");
            get_kecamatan(id);
        })
        $("#id_kecamatan").on("change", function(e){
            var id = $("#id_kecamatan").val();
            get_desa(id);
        })
    })

    function grafik_bar(iData){
        const labels = iData.labels;
        const data = {
            labels: labels,
            datasets: [
                    {   
                        label: 'Jumlah Suara AYP',
                        data: iData.suara_ayp,
                        backgroundColor: '#2d2f93',
                        borderWidth: 1
                    },
                    {
                        label: 'Jumlah Suara Pasti',
                        data: iData.pemilih_pasti,
                        backgroundColor: 'rgba(255, 99, 132, 0.8)',
                        borderWidth: 1
                    },
                    {
                        label: 'Jumlah Pemilih',
                        data: iData.data_pemilih,
                        backgroundColor: 'rgba(255, 159, 64,0.8)',
                        borderWidth: 1
                    }
            
            ]
        };
        const config  =  {
            type: 'bar',
            data,
            options: {
                indexAxis: 'y',
            }
        }; 
        var canvas = $('#grafik_suara').get(0).getContext('2d')
        new Chart(canvas,config);
    }

    function get_kabupaten(){
        $('#id_kabupaten').select2({
            theme: 'bootstrap4',
            placeholder: 'Pilih Kabupaten',
            allowClear: true,
            ajax: {
                url: "{{ url('api/kabupaten/get_data_kab/') }}",
                processResults: function (data) {
                    return {
                        results: data.data
                    };
                }
            }
        })
    }

    function open_form(str){
        if(str == "kabupaten"){
            $(".form").hide();
        }else if(str == "kecamatan"){
            $(".form").hide();
            $(".kecamatan").show();
        }else if(str == "desa"){
            $(".form").hide();
            $(".desa").show();
        }else if(str == "tps"){
            $(".form").hide();
            $(".tps").show();
        }else{
            $(".form").hide();
        }
    }

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
        })
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
            url		: "{{ url('api/suara/rekap') }}",
            data	: idata,
            processData: false,
            contentType: false,
            cache 	: false,
            beforeSend: function () { 
                // in_load();
            },
            success	:function(data) {
                grafik_bar(data.data);
                sweetAlertSucc(data.status,data.messages);
                $("#btn_download").show();  
            },
            error: function (error) {
                sweetAlertErr(error.responseJSON.status,error.responseJSON.messages)
            }
        });
    }

    function download_data(){
        var data = $("#FormData").serialize();
        window.open("{{ url('dwonload') }}?"+data, '_blank');
    }

    
</script>
@endsection