<?php

namespace App\Http\Controllers;

use App\AnhSanPham;
use App\CauHinh;
use App\DonHang;
use App\ChiTietDonHang;
use App\Comment;
use App\Http\Controllers\OrderController;
use App\Components\Recursion;
use Illuminate\Http\Request;
use App\SanPham;
use App\LoaiSanPham;
use App\ProductCatalogue;
use Hamcrest\Core\HasToString;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Expr\Cast\Object_;

class SanPhamController extends Controller
{
    //
    private $LoaiSanPham;
    private $htmlselect;

    public function __construct(LoaiSanPham $LoaiSanPham)
    {
        $this->htmlselect = '';
        $this->LoaiSanPham = $LoaiSanPham;
    }
    public function index(Request $request)
    {

        $listsanpham = SanPham::where('TrangThai', 1);
        $listsanpham =   $listsanpham->join('product_catalogues', 'product_catalogues.id', '=', 'san_phams.product_catalogues_id')->where('product_catalogues.status', '=',1)->select('san_phams.*', 'product_catalogues.name');
        if($request->search) $listsanpham=$listsanpham->where('TenSanPham', 'LIKE','%'.$request->search.'%');
        if($request->category>0) {
            $listsanpham=$listsanpham->where('loai_san_phams_id',$request->category);
        }
        $listsanpham=$listsanpham->paginate(5);
        $categories = LoaiSanPham::where('TrangThai', 1)->get();
        return view('pages.quan-ly-san-pham', compact('listsanpham', 'categories'));
    }

    public function indexTrash(Request $request)
    {
        $listsanpham = SanPham::where('TrangThai','<>', 1);
        if ($request->amount>-1) {
            if ($request->amount > 0) {

                switch ($request->amount) {
                    case 1: {
                            $begin = 0;
                            $end = 5;
                            $listsanpham = $listsanpham->where('Soluong', '>=', $begin)->where('Soluong', '<=', $end);
                            break;
                        }
                    case 2: {
                            $begin = 5;
                            $end = 10;
                            $listsanpham = $listsanpham->where('Soluong', '>=', $begin)->where('Soluong', '<=', $end);
                            break;
                        }
                    case 3: {
                            $begin = 10;
                            $end = 50;
                            $listsanpham = $listsanpham->where('Soluong', '>=', $begin)->where('Soluong', '<=', $end);
                            break;
                        }

                    case 4: {
                            $begin = 50;
                            $end = 100;
                            $listsanpham = $listsanpham->where('Soluong', '>=', $begin)->where('Soluong', '<=', $end);
                            break;
                        }
                    case 5: {
                            $begin = 100;
                            $end = 250;
                            $listsanpham = $listsanpham->where('Soluong', '>=', $begin)->where('Soluong', '<=', $end);
                            break;
                        }
                    case 6: {
                            $begin = 250;
                            $end = 500;
                            $listsanpham = $listsanpham->where('Soluong', '>=', $begin)->where('Soluong', '<=', $end);
                            break;
                        }
                    case 7: {
                            $begin = 500;
                            $listsanpham = $listsanpham->where('Soluong', '>=', $begin);
                            break;
                        }
                    default: {
                            //
                        }
                }
            }

            if($request->search) $listsanpham=$listsanpham->where('TenSanPham', 'LIKE','%'.$request->search.'%');


              if($request->category>0) $listsanpham=$listsanpham->where('loai_san_phams_id',$request->category);
        }
        $listsanpham=$listsanpham->paginate(5);
        $categories = LoaiSanPham::where('TrangThai', 1)->get();
        return view('pages.thung-rac-san-pham', compact('listsanpham', 'categories'));
    }

    public function ThemSanPham()
    {
        $data = LoaiSanPham::where('TrangThai', 1)->get();
        $dataOption = $this->LoaiSanPham::where('TrangThai', 1)->get();
        $Recursion = new Recursion($dataOption);

        $configs = DB::table('chi_tiet_cau_hinhs')
            ->join('cau_hinhs', 'chi_tiet_cau_hinhs.cau_hinhs_id', '=', 'cau_hinhs.id')
            ->where('chi_tiet_cau_hinhs.loai_san_phams_id', '=', $dataOption[0]->id)->get();
        $html = '';

        foreach ($configs as $item) {
            $html .= '<div class="form-group col-12"><label for="' . $item->KeyName . '">'
                . $item->TenCauHinh . ':</label><input type="text" class="form-control" id="'
                . $item->KeyName . '" placeholder="Nhập tên '
                . $item->TenCauHinh . '"name="' . $item->KeyName . '" ></div>';
        }
        $htmlOption = $Recursion->cat_parent();
        $productCatalogues = ProductCatalogue::where('status', 1)->get();
        return view('pages.them.them-san-pham', compact('data', 'htmlOption', 'html', 'productCatalogues'));
    }

    public function SuaSanPham($id)
    {

        $data = SanPham::where('san_phams.id', $id)
            // ->join('loai_san_phams','loai_san_phams.id','san_phams.loai_san_phams_id')
            // ->select('san_phams.id')
            ->get();
        $category = LoaiSanPham::where('id', $data[0]->loai_san_phams_id)->first();
        $dataOption = $this->LoaiSanPham::where('TrangThai', 1)->get();
        $configs = json_decode($data[0]->CauHinh);
        $html = '';

        $configs_by_category = DB::table('chi_tiet_cau_hinhs')
            ->join('cau_hinhs', 'chi_tiet_cau_hinhs.cau_hinhs_id', '=', 'cau_hinhs.id')
            ->where('chi_tiet_cau_hinhs.loai_san_phams_id', '=', $data[0]->loai_san_phams_id)->get();
        $len = sizeof($configs_by_category);

        if ($len > 0)

            for ($i = 0; $i < $len; $i++) {
                $key = $configs_by_category[$i]->KeyName;
                $cont = null;
                try {
                    $content = $configs->$key;
                    $cont = $content->content;
                } catch (\ErrorException $e) {
                    $cont = null;
                }

                $html .= '<div class="form-group col-12"><label for="'
                    . $key . '">' . $configs_by_category[$i]->TenCauHinh . ':</label><input type="text" class="form-control" id="'
                    . $key . '" placeholder="Nhập ' . $configs_by_category[$i]->TenCauHinh . '"name="'
                    . $key . '" value="' . $cont . '"></div>';
            }
        else {
            $html = '<div class="card-header"><label>Loại sản phẩm này chưa có cấu hình vui lòng thêm cấu hình<label></div>';
        }


        $Recursion = new Recursion($dataOption);
        $htmlOption = $Recursion->catParentSelected(0,'',$category->id);
        $productCatalogues = ProductCatalogue::where('status', 1)->get();
        $imageProduct=ImageProductController::showImage($id);
        // return $imageProduct;
        return view('pages.cap-nhat.cap-nhat-san-pham', compact('data', 'htmlOption', 'category', 'html','imageProduct', 'productCatalogues'));
    }

    public function InsertProducts(Request $request)
    {

        $data = new SanPham;

        $products = SanPham::where('TrangThai',1)->where('TenSanPham',$request->ten_san_pham)->first();
        if($products) return redirect()->back()->with('error','Tên sản phẩm'.$request->ten_san_pham.' đã tồn tại !');
        $data->TenSanPham = $request->ten_san_pham;
        $data->ThongTin = $request->detail? $request->detail:'Thông tin';
        $data->HangSanXuat = $request->HangSanXuat;
        $data->GiaCu = $request->GiaCu;
        $data->GiaKM = $request->GiaKM;
        $data->SoLuong = $request->SoLuong;
        $data->loai_san_phams_id = $request->LoaiSanPham;
        if( $data->GiaKM> $data->GiaCu)  $data->GiaKM=  $data->GiaCu;

        if ($request->hasFile('AnhDaiDien')) {
            $image = $request->file('AnhDaiDien');
            $name = time() . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('/images');
            $image->move($destinationPath, $name);
            $HinhAnh = $name;
        } else {
            $HinhAnh = "meo.jpg"; // nếu k thì có thì chọn tên ảnh mặc định ảnh mặc định
        }
        $data->AnhDaiDien = $HinhAnh;

        $configs = DB::table('chi_tiet_cau_hinhs')
            ->join('cau_hinhs', 'chi_tiet_cau_hinhs.cau_hinhs_id', '=', 'cau_hinhs.id')
            ->where('chi_tiet_cau_hinhs.loai_san_phams_id', '=', $request->LoaiSanPham)->get();

        $configJson = array();

        foreach ($configs as $item) {
            $config = array();
            $key = $config['key'] = $item->KeyName;
            $config['config_name'] = $item->TenCauHinh;

            $config['content'] = $request->$key;

            $configJson[$key] = $config;
        }


        $cauhinhString = json_encode($configJson);

        $data->CauHinh = $cauhinhString;
        $data->product_catalogues_id = $request->product_catalogues_id;
        $data->save();

        if ($request->hasFile('imageFile')) {
            foreach ($request->file('imageFile') as $image) {
                $imageProduct = new AnhSanPham;
                $nameimages = time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path() . '/images/', $nameimages);
                $imageProduct->AnhSanPham = $nameimages;
                $imageProduct->san_phams_id = $data->id;
                $imageProduct->save();
            }
        }

        return redirect('/quan-ly-san-pham');
    }
    public static function AffterOrder($id, $soluong)
    {

        $data = SanPham::find($id);
        if ($data->SoLuong < $soluong) {
            return ['result' => false, 'amount' => $data->SoLuong, 'name' => $data->TenSanPham];
        } else {
            $amount = $data->SoLuong - $soluong;
            $data->SoLuong =  $amount;
            $data->save();
            return ['result' => true, 'amount' => $data->SoLuong];
        }
        return ['result' => false, 'amount' => $data->SoLuong, 'name' => $data->TenSanPham];
    }
    public function UpdateProduct(Request $request, $id)
    {
        $data = SanPham::find($id);
        // $data1 = CauHinh::find(1);
        $products = SanPham::where('TrangThai',1)->where('TenSanPham',$request->ten_san_pham)->where('id','<>',$id)->first();

        if($products) return redirect()->back()->with('error_duplicate','Tên sản phẩm'.$request->ten_san_pham.' đã tồn tại !');
        $data->ThongTin = $request->detail? $request->detail:'Thông tin';

        $data->TenSanPham = $request->ten_san_pham;
        $data->HangSanXuat = $request->HangSanXuat;
        $data->GiaCu = $request->GiaCu;
        $data->GiaKM = $request->GiaKM;
        $data->SoLuong = $request->SoLuong;
        if( $data->GiaKM> $data->GiaCu)  $data->GiaKM=  $data->GiaCu;
        $configs = DB::table('chi_tiet_cau_hinhs')
            ->join('cau_hinhs', 'chi_tiet_cau_hinhs.cau_hinhs_id', '=', 'cau_hinhs.id')
            ->where('chi_tiet_cau_hinhs.loai_san_phams_id', '=', $request->LoaiSanPham)->get();

        $configJson = array();
        if (sizeof($configs) > 0)
            foreach ($configs as $item) {
                $config = array();
                $key = $config['key'] = $item->KeyName;
                $config['config_name'] = $item->TenCauHinh;

                $config['content'] = $request->$key;

                $configJson[$key] = $config;
            }
            if ($request->hasFile('AnhDaiDien')) {
                $image = $request->file('AnhDaiDien');
                $name = time() . '.' . $image->getClientOriginalExtension();
                $destinationPath = public_path('/images');
                $image->move($destinationPath, $name);
                $HinhAnh = $name;
            } else {
                $HinhAnh = $request->AnhDaiDien; // nếu k thì có thì chọn tên ảnh mặc định ảnh mặc định
            }
            $data->AnhDaiDien = $HinhAnh;
        $cauhinhString = json_encode($configJson);
        $data->CauHinh = $cauhinhString;
        $data->product_catalogues_id = $request->product_catalogues_id;
        $data->save();
        //     $ImageProduct=AnhSanPham::where('san_phams_id',$id);
        //     $imageProduct->
        return redirect('/quan-ly-san-pham/update/'.$data->id);
    }

    public function DeleteProduct(Request $request,$id)
    {
        $data = SanPham::find($id);
        $data->TrangThai = 0;
        $data->save();
        if($request->page) $page='page='.$request->page;
        else $page='';
        return redirect("/quan-ly-san-pham?$page");
    }

    public function RecoverProduct(Request $request,$id)
    {
        $data = SanPham::find($id);
        $data->TrangThai = 1;
        $data->save();
        if($request->page) $page='page='.$request->page;
        else $page='';
        return redirect("/quan-ly-san-pham/thung-rac?$page");
    }
    public function ConfigByCategory($id)
    {
        $configs = DB::table('chi_tiet_cau_hinhs')
            ->join('cau_hinhs', 'chi_tiet_cau_hinhs.cau_hinhs_id', '=', 'cau_hinhs.id')
            ->where('chi_tiet_cau_hinhs.loai_san_phams_id', '=', $id)->get();
        $html = '';

        foreach ($configs as $item) {
            $html .= '<div class="form-group col-12"><label for="'
                . $item->KeyName . '">'
                . $item->TenCauHinh . ':</label> <input type="text" class="form-control" id="'
                . $item->KeyName . '" placeholder="Nhập tên '
                . $item->TenCauHinh . '"name="'
                . $item->KeyName . '" > </div>';
        }
        if (sizeof($configs) > 0)
            return response()->json(['message' => 'success', 'html' => $html]);
        return response()->json(['message' => 'unsuccessful','html' => '']);
    }

    // api
    public function GetProductSeal()
    {
        $Product = SanPham::OrderBy('giaKM', 'ASC')->get();
        return response()->json($Product, 200);
    }
    public function GetImageProductByid($id)
    {
        $imageProducts = DB::select('SELECT anh_san_phams.AnhSanPham
        FROM anh_san_phams
        WHERE anh_san_phams.san_phams_id=?', [$id]);
        return response($imageProducts, 200);
    }
    public function GetProductById($id)
    {
        $products = SanPham::find($id);
        return response()->json($products);
    }

    public function GetAccessories()
    {
        $accessories = DB::select('SELECT *
        FROM loai_san_phams
        WHERE loai_san_phams.parent_id=5');
        return response()->json($accessories, 200);
    }

    public function getTypeProductById($id)
    {
        $data = DB::table('san_phams')
            ->join('loai_san_phams', 'san_phams.loai_san_phams_id', '=', 'loai_san_phams.id')
            ->where('san_phams.loai_san_phams_id', '=', $id)->orWhere('loai_san_phams.parent_id', '=', $id)
            ->select('san_phams.*')
            ->paginate(10);
        return response()->json($data, 200);
    }

    public function GetAllProduct()
    {
        $data = SanPham::get();
        return response()->json($data, 200);
    }
    public function getProductByTypeProductId($id)
    {
          $listProduct = DB::table('san_phams')
          ->join('loai_san_phams', 'san_phams.loai_san_phams_id','=', 'loai_san_phams.id')
          -> where('san_phams.loai_san_phams_id' , '=', $id) ->orWhere('loai_san_phams.parent_id', '=', $id)
          -> select('san_phams.*')
          ->paginate(5);
          return $listProduct;
        return response()->json($listProduct, 200);
    }
    public function search($keyword)
    {
        $data = DB::select('select * from san_phams where TenSanPham  like concat("%",?,"%") ', [$keyword]);
        return response()->json($data, 200);
    }

    public function test($id)
    {

        $data=Comment::where('san_phams_id',$id)->with(['user'])->get()->toArray();
        return $data;
        // $data = LoaiSanPham::with(['products', 'childrenRecursive', 'childrenRecursive.products'])->where('id', $id)->get()->toArray();
        // return $data;
        // $categories = LoaiSanPham::where('parent_id', $id)
        //                           ->orWhere('id', $id)

        //                             ->join('san_phams','san_phams.loai_san_phams_id','=','loai_san_phams.id')
        //                           ->select('san_phams.*')
        //                           ->latest()
        //                           ->get();
        //                           return $categories;
        // $listProduct=DB::select('SELECT san_phams.*
        // FROM san_phams
        // JOIN loai_san_phams ON san_phams.loai_san_phams_id = loai_san_phams.id
        // WHERE san_phams.loai_san_phams_id = ? OR loai_san_phams.parent_id =?', [$id,$id]);
        // return $listProduct;
        // $user = DB::table('san_phams')->select('loai_san_phams_id')->where('id', $id)->get();
        // $typeId = (object)$user[0]->loai_san_phams_id;

        // return response()->json($typeId);
    }

    public function SuggestProduct($id)
    {
        $data = DB::table('san_phams')
            ->join('loai_san_phams', 'san_phams.loai_san_phams_id', '=', 'loai_san_phams.id')
            ->where('loai_san_phams.id', '=', $id)
            ->select('san_phams.*')
            ->orderBy('GiaKM', 'ASC')
            ->get();
        return response()->json($data, 200);

    }
    public static function recoveryProduct($id){
        $orderDetails = ChiTietDonHang::where('don_hangs_id',$id)->get();

        foreach ($orderDetails as $item) {
            try{
                $product= SanPham::find($item->san_phams_id);
                $product->SoLuong = $product->SoLuong+$item->SoLuong;
                $product->save();

            }catch(\Exception $e){
                    return false;
            }

        }
        return true;
    }
    public function getComments($id){
        $data=Comment::where('san_phams_id',$id)->with(['user'])->get()->toArray();
        return response()->json($data);
    }
    public function userComments(Request $request)
    {
        $data= Comment::get();
        foreach($data as $item)
        {
            if($request->nguoi_dungs_id == $item->nguoi_dungs_id && $request->san_phams_id == $item->san_phams_id)
                {
                    return response()->json(['message' =>'Hình như bạn đã bình luận sản phẩm này rồi'],400);
                }

        }
        $comment=new Comment();
        $comment->content=$request->content;
                $comment->nguoi_dungs_id=$request->nguoi_dungs_id;
                $comment->san_phams_id=$request->san_phams_id;
                $comment->save();
                return response()->json(['message' =>'Thành công','comment'=>$comment]);


    }
    public function topProductsHot(Request $request)
    {
        $limit  =$request->limit? $request->limit:10;
        switch($request->type) {
            case 'amount': {
                $prods = [];
                $products = DB::table('chi_tiet_don_hangs')->select('san_phams_id')->groupBy('san_phams_id')->get();
                foreach ($products as $item) {
                    $amount = (int)OrderController::getProductOrdersByProduct($item->san_phams_id);
                    $sp = SanPham::find($item->san_phams_id);
                    $sp->amount = $amount;
                    array_push($prods, $sp);
                }
                usort(
                    $prods,
                    function ($a, $b) {
                        if ($a == $b) {
                            return 0;
                        }
                        return ($a->amount > $b->amount) ? -1 : 1;
                    }
                );
                $prods= array_slice($prods,0,$limit);
                return response()->json($prods);
            } break;
            case 'discount':{
                $promotion_products = DB::table('san_phams')->get()->toArray();
                foreach ($promotion_products as $item) {
                    $discount_price = ceil(($item->GiaCu- $item->GiaKM)/$item->GiaCu*100);
                    $item->discount_price=$discount_price;
                }
                usort(
                    $promotion_products,
                    function ($a, $b) {
                        if ($a == $b) {
                            return 0;
                        }
                        return ($a->discount_price > $b->discount_price) ? -1 : 1;
                    }
                );
                $promotion_products= array_slice($promotion_products,0,$limit);

                return response()->json($promotion_products);
            }break;
            default : {
                $prods = [];
                $products = DB::table('chi_tiet_don_hangs')->select('san_phams_id')->groupBy('san_phams_id')->get();
                foreach ($products as $item) {
                    $amount = (int)OrderController::getProductOrdersByProduct($item->san_phams_id);
                    $sp = SanPham::find($item->san_phams_id);
                    array_push($prods, $sp);
                }
                usort(
                    $prods,
                    function ($a, $b) {
                        if ($a == $b) {
                            return 0;
                        }
                        return ($a->SoLuong > $b->SoLuong) ? -1 : 1;
                    }
                );
                $prods= array_slice($prods,0,$limit);
                return response()->json($prods);
            }   break;
        }


    }

    public function getProductbyProductCatalogueId($id){
        $products = SanPham::where('TrangThai', 1);
        $products = $products->join('product_catalogues', 'product_catalogues.id', '=', 'san_phams.product_catalogues_id')->where('product_catalogues.status', '=', 1)->where('product_catalogues_id',[$id])->select('san_phams.*', 'product_catalogues.name')->paginate(10);
        return  response()->json(['data' =>  $products]);
    }

}
