@extends('layouts.template')

@section('title')
    Rooms
@endsection

@section('head')
    <!-- Datatale CSS -->
    <link href="{{asset('dist/vendors/bower_components/datatables/media/css/jquery.dataTables.min.css')}}"
          rel="stylesheet" type="text/css"/>

    <link href="{{asset('dist/vendors/bower_components/datatables.net-responsive/css/responsive.dataTables.min.css')}}"
          rel="stylesheet" type="text/css"/>

@endsection

@section('content')
    <div class="container" style="min-height: 550px;margin-top:20px;">
        <div class="row heading-bg">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h5 class="txt-dark">Room</h5>
            </div>
            <!-- Breadcrumb -->
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                <ol class="breadcrumb">
                    <li><a href="/">Home</a></li>
                    <li><a href="#"><span>Room Master</span></a></li>
                    <li class="active"><a href="/rooms/create"><span>Room</span></a></li>
                </ol>
            </div>
            <!-- /Breadcrumb -->
        </div>

        <!-- Row -->

        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-default border-panel card-view">
                    <div class="panel-heading">
                        <div class="pull-left">
                            <h6 class="panel-title txt-dark">Rooms</h6>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="panel-wrapper collapse in">
                        <div class="panel-body">
                            <div class="form-wrap">
                                    {!! Form::open(["method"=>"post","url" =>"rooms", "id" =>"rooms", "class" => "form-inline","files" => "true"]) !!}
                                    <input type="hidden" id="token" name="_token" value="{{ csrf_token() }}">

                                    @include('RoomMaster.Room._form')
                                    {{ Form::button('Add', ['type' => 'button', 'id' => 'add', 'class' => 'btn btn-orange  btn-anim pull-right'] )  }}
                                {!! Form::close() !!}
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <hr style="border-top: 3px solid #4267b2;">
                    </div>
                    <div class="panel-wrapper collapse in">
                        <div class="panel-body">
                            <div class="table-wrap">
                                <div class="table-responsive">
                                    <table id="datable_1" class="table table-hover display  pb-30" >
                                        <thead>
                                        <tr>
                                            <th>Room No</th>
                                            <th>Room Type</th>
                                            <th>Capacity</th>
                                            <th>Extension No.</th>
                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection


@section('js_script')

    <!-- Data table JavaScript -->
    <script src="{{asset('dist/vendors/bower_components/datatables/media/js/jquery.dataTables.min.js')}}"></script>

    <script>
        $(document).ready(function (e) {
            var table = $('#datable_1').DataTable({
                "ajax": {
                    "url": "/rooms",
                    "dataSrc": function (json) {
                        var return_data = new Array();
                        for(var i=0;i< json.length; i++){
                            return_data.push({
                                'roomtype_id': json[i].roomtype_id,
                                'room_no': json[i].room_no,
                                'capacity': json[i].capacity,
                                'extension_no': json[i].extension_no,
                                'action': '<a class="btn btn-primary" href="/rooms/'+json[i].id+'/edit">Edit</a>',
                            })
                        }
                        return return_data;
                    }
                },
                "columns": [
                    { "data": "roomtype_id" },
                    { "data": "room_no" },
                    { "data": "capacity" },
                    { "data": "extension_no" },
                    { "data": "action","width": "40px" }
                ],
                "order": [[ 1, "asc" ]]
            });


            $("#add").click(function () {
                var roomtype_id = $("#roomtype_id").val();
                var room_no = $("#room_no").val();
                var capacity = $("#capacity").val();
                var extension_no = $("#extension_no").val();
                var token = $("#token").val();

                $.ajax(
                    {
                        url: "/rooms",
                        type: 'POST',
                        data: {
                            "roomtype_id": roomtype_id,
                            "room_no": room_no,
                            "capacity": capacity,
                            "extension_no": extension_no,
                            "_token": token
                        },

                        success: function (result) {
                            table.ajax.reload();
                            $("#roomtype_id").val("");
                            $("#room_no").val("");
                            $("#capacity").val("");
                            $("#extension_no").val("");
                        },
                        error: function (request, status, error) {
                            var data = request.responseText;
                            $.each(request.responseJSON.errors, function (key, value) {
                                $('.'+key+'_inline').html('*'+key);
                                $('.'+key+'_inline').css('color','red');
                            });
                        }
                    });
            });
        });
    </script>
@stop