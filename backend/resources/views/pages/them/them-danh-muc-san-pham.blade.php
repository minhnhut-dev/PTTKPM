@extends('../layouts.master')
@section('content')
<section class="content-header">
    <div class="container">
        <h2>Thêm danh mục mới</h2>
        <hr>
        {{-- <form method="POST" action="{{ url('quan-ly-the-loai-phim/formAdd') }}" --}}
        <form method="POST" action="{{route('danh-muc-san-pham.store')}}"
            class="was-validated d-flex flex-column input-form" id="form-them-the-loai-phim">
            @csrf
            <div class="form-group col-12">
                <label for="ten-the-loai-phim">Tên danh mục</label>
                <input type="text" class="form-control"  placeholder="Nhập tên  loại sản phẩm"
                    name="name" required>
                <div class="invalid-feedback">Không được bỏ trống trường này</div>
            </div>

            <div class="form-group col-12">
                <label for="ten-the-loai-phim">Sắp xếp</label>
                <input type="text" class="form-control" placeholder="Sắp xếp danh muc"
                    name="order" required>
                <div class="invalid-feedback">Không được bỏ trống trường này</div>
            </div>

            <div class="col-3 select">
                <label for="status">Trạng thái:</label>
                <select class="form-control" name="status" style="background-image: none;" >
                    <option value="1">Hoạt động</option>
                    <option value="0">Ngừng hoạt động</option>

                </select>
            </div>
            <button type="submit" class="btn btn-primary btn-submit-input-form btn-them-phim" data-toggle="modal">
                <strong>Tiến hành thêm</strong>
            </button>

            <div class="modal fade modal-them-the-loai-phim-question" id="popup-them-question">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <!-- Modal body -->
                        <div class="modal-body text-center">
                            <i class="fas fa-info-circle" style="color: #dc3545;"></i>
                            Xác nhận thêm loại sản phẩm vào hệ thống
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>

                        <!-- Modal footer -->
                        <div class="modal-footer d-flex justify-content-center">
                            <button type="button" class="btn btn-warning btn-xac-nhan-them-the-loai-phim">
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
