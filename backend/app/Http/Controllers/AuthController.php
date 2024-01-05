<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Classes\ActivationService;
use App\Classes\ResetPasswordService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\NguoiDung;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Events\Registered;

class AuthController extends Controller
{
    protected $activationService;
    protected $resetPasswordService;
    public function __construct(ActivationService $activationService, ResetPasswordService $resetPasswordService)
    {
        $this->middleware('guest');
        $this->activationService = $activationService;
        $this->resetPasswordService = $resetPasswordService;
    }
    //
    public function Register(Request $request)
    {
        $rule = [
            "Email" => "required|unique:nguoi_dungs",
            "username" => "required|unique:nguoi_dungs|min:5",
            "password" => "regex:/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/",
        ];
        $customMessage = [
            "Email.unique" => "Email đã tồn tại !",
            "username.unique" => "Tên tài khoản đã tồn tại !",
            "username.min" => "Tên tài khoản phải lớn hơn 5 ký tự !",
            "Email.required" => "Email không được bỏ trống !",
            "username.required" => "Tên tài khoản không được bỏ trống",
            "password.regex" => "Mật khẩu gồm 8 ký tự và Có 1 chữ viết hoa"
        ];
        $validator = Validator::make($request->all(), $rule, $customMessage);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
        $user = new NguoiDung;
        $user->Email = $request->Email;
        $user->TenNguoiDung = $request->TenNguoidung;
        $user->SDT = $request->SDT;
        $user->DiaChi = $request->DiaChi;
        $user->GioiTinh = $request->GioiTinh;
        $user->username = $request->username;
        $user->password = Hash::make($request->password);
        $user->loai_nguoi_dungs_id = 2;
        $user->Anh= $request->Anh;
        $user->save();
        event(new Registered($user));
        $result = $this->activationService->sendActivationMail($user);

        return response()->json(['message' => 'Tài khoản được tạo thành công'], 200);
    }
    public function Login(Request  $request)
    {
        $credentials = $request->only('username', 'password');
        if (!Auth::attempt($credentials)) {
            return response()->json(["message" => "Sai Tài khoản hoặc mật khẩu"], 401);
        }
         if(Auth::user()->loai_nguoi_dungs_id ==1)
        {
            return response()->json(["message" => "Tài khoản không hợp lệ"], 400);

        }
        $user = $request->user();
        $tokenResult = $user->createToken('Access Token');

        $token = $tokenResult->token;

        $token->expires_at = Carbon::now()->addWeek(1);
        $token->save();
        return response()->json([
            'data' => [
                'user' => Auth::user(),
                'access_token' => $tokenResult->accessToken,
                'token_type' => 'Bearer',
                'expires_at' => Carbon::parse($tokenResult->token->expires_at)->toDateTimeString()
            ]
        ]);
    }
    public function activateUser($token)
    {
        if ($user = $this->activationService->activateUser($token)) {
            return redirect('http://localhost:3000/Login/'.$token);
            // rediect front end
        }
    }
    public function resetPasswordUser($token)
    {
       return redirect('http://localhost:3000/account/configPassword/' . $token);
         // rediect front end
    }
    public function resetPasswordUserClient(Request $request, $token)
    {
        $password = $request->password;
        if ($user = $this->resetPasswordService->resetPasswordUser($token, $password)) {
            return response()->json(['message' => 'success'],200);
        } else {
            return response()->json(['message' => 'unsuccess'],400);
        }
    }
    public function ForgotPassword(Request $request)
    {
        $user = NguoiDung::where('Email',$request->Email)->first();

        if ($user ){

            event(new Registered($user ));
            $result = $this->resetPasswordService->sendResetPasswordMail($user);
                return response()->json(['message' => 'Email khôi phục mật khẩu đã được gửi'],200);
        } else {
            return response()->json(['message' => 'Không tìm thấy Email'],400);
        }
    }

    public function reActiveUser(Request $request)
    {
        // $credentials = $request->only('username', 'password', 'loai_nguoi_dungs_id' == 2);
        // if (!Auth::attempt($credentials)) {
        //     return response()->json(["message" => "Sai Tài khoản hoặc mật khẩu"], 401);
        // }
        // $user = $request->user();

        // event(new Registered($user));
        // $result = $this->activationService->sendActivationMail($user);
        // return response()->json(['message' => 'Vui lòng kiểm tra mail của bạn !', 'result' => $result], 200)->header("Access-Control-Allow-Origin",  "*");
        $user = NguoiDung::where('Email',$request->Email)->first();
        if ($user ){

            event(new Registered($user ));
            $result = $this->activationService->sendActivationMail($user);
                return response()->json(['message' => 'Email kích hoạt đã được gửi'],200);
        } else {
            return response()->json(['message' => 'Không tìm thấy Email'],400);
        }

    }
    public function checkLoginGoogle(Request $request)
    {
        $data=NguoiDung::all();
        foreach($data as $item)
        {
            if($request->Email == $item->Email)
            {
                return response()->json(['User'=>$item]);
            }
        }
        $user = new NguoiDung;
        $user->Email = $request->Email;
        $user->TenNguoidung = $request->TenNguoidung;
        $user->loai_nguoi_dungs_id = 2;
        $user->Anh= $request->Anh;
        $user->active =1;
        $user->save();
        return response()->json(['Message'=>$user],200);
    }

}
