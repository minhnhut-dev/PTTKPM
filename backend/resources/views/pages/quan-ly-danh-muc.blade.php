@extends('../layouts.master')
@section('content')

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Quản Lý danh mục sản phẩm</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Trang chủ</a></li>
                    <li class="breadcrumb-item active">Quản lý danh mục sản phẩm</li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section

<section class="content">
    <div class="container-fluid">
        <!-- /.row -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <a class="btn btn-primary" role="button" href="{{ route('danh-muc-san-pham.create') }}">
                            <i class="fas fa-plus-circle"></i>
                            Thêm mới
                        </a>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body table-responsive p-0" >
                        <table class="table table-head-fixed table-striped">
                            <thead>
                                <tr>
                                    <th>STT</th>
                                    <th>Tên danh mục</th>
                                    <th>Tác vụ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                $stt=1;
                                // if (isset($_GET['page'])) {
                                // $a=$_GET['page'];

                                // }
                                // else{
                                // $a=1;
                                // }
                                // $stt=($a-1)*5;
                                @endphp
                                @php
                                @endphp
                                @foreach ($product_catalogues as $data)
                                <tr>
                                    <td>{{$stt++ }}</td>
                                    <td>
                                        {{$data->name}}
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="/danh-muc-san-pham/{{$data->id}}/edit">
                                                <button type="button" class="btn btn-warning" data-toggle="tooltip"
                                                    data-placement="top" title="Chỉnh sửa">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                            </a>
                                            <a >
                                                    <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#popup-delete-question-{{$data->id}}"
                                                    title="Xóa">
                                                    <i class="far fa-trash-alt"></i>
                                                </button>
                                                </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
        </div>
        @foreach ($product_catalogues as $data)
        <div class="modal fade modal-them-the-loai-phim-question" id="popup-delete-question-{{$data->id}}">
            <div class="modal-dialog modal-dialog-centered">
                <form method="post" action='/danh-muc-san-pham/destroy/{{$data->id}}'>
                    <div class="modal-content">
                        @csrf
                        <!-- Modal body -->
                        <div class="modal-body text-center">
                            <i class="fas fa-info-circle" style="color: #dc3545;"></i>
                            Xác nhận xóa danh mục
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>

                        <!-- Modal footer -->
                        <div class="modal-footer d-flex justify-content-center">
                            <button type="submit" class="btn btn-warning btn-xac-nhan-them-the-loai-phim">
                                <strong>Xác nhận</strong>
                            </button>
                        </div>
                    </div>
                </form>
            </div>

        </div>
        @endforeach
        <!-- /.row -->
    </div><!-- /.container-fluid -->

</section>
@endsection
