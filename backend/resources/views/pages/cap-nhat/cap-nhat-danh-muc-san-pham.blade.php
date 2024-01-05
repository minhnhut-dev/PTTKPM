@extends('../layouts.master')

@section('content')

<section class="content-header">
    <div class="container">
        <h2>Cập nhật hình thức thanh toán</h2>
        <hr>

        <form method="POST" action="/danh-muc-san-pham/{{$product_catalogue->id}}" class="was-validated d-flex flex-column input-form"
            id="form-cap-nhat-rap">
            @csrf
            <div class="form-group ">
                <div class="col-12">
                    <label for="ten-rap">Tên danh mục:</label>
                    <input type="text" class="form-control" id="product_catalogue_name" placeholder="Nhập tên danh mục" name="name"
                         value="{{$product_catalogue->name}}" required>
                    <div class="invalid-feedback">Không được bỏ trống trường này</div>
                </div>
                <br />
                <div class="col-12">
                    <label for="ten-rap">Sắp xếp: </label>
                    <input type="text" class="form-control" id="product_catalogue_order" placeholder="Sắp xếp danh mục" name="order"
                         value="{{$product_catalogue->order}}" required>
                    <div class="invalid-feedback">Không được bỏ trống trường này</div>
                </div>

                <div class="col-6">
                    <label for="ten-rap">Trạng thái: </label>
                    {{-- <input type="text" class="form-control" id="product_catalogue_order" placeholder="Sắp xếp danh mục" name="order"
                         value="{{$product_catalogue->order}}" required>
                    <div class="invalid-feedback">Không được bỏ trống trường này</div> --}}
                    <select class="form-control" name="status">
                        @if ($product_catalogue->status == 1)
                            <option value="1" selected>Hoạt động</option>
                            <option value="0">Ngưng hoạt động</option>
                        @else
                            <option value="1" >Hoạt động</option>
                            <option value="0" selected>Ngưng hoạt động</option>
                        @endif
                    </select>
                </div>
            </div>
            <button type="submit" class="btn btn-primary btn-submit-input-form btn-cap-nhat-rap" data-toggle="modal">
                <strong>Cập nhật</strong>
            </button>
            <div class="modal fade modal-them-rap-question" id="popup-them-question">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <!-- Modal body -->
                        <div class="modal-body text-center">
                            <i class="fas fa-info-circle" style="color: #dc3545;"></i>
                            Xác nhận cập nhật vào hệ thống
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>

                        <!-- Modal footer -->
                        <div class="modal-footer d-flex justify-content-center">
                            <button type="submit" class="btn btn-warning btn-xac-nhan-cap-nhat-rap">
                                <strong>Xác nhận</strong>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>
@endsection
