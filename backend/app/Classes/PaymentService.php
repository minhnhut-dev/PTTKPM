<?php
namespace App\Classes;
use Illuminate\Support\Facades\Http;

class PaymentService
{
    public static function Momo($order){
        $endpoint = "https://test-payment.momo.vn/v2/gateway/api/create";
        $partnerCode = 'MOMO6KRQ20210610';
        $accessKey = 'MYc8b7Wo8858OGUg';
        $secretKey = 'zG3fTEcy3voCjcyLAWr81b4mBcmYG8DD';
        $orderInfo = "Thanh toán qua MoMo";
        $orderId = $order->id;
        $redirectUrl = env('RETURN_URL');        ;
        $ipnUrl = "https://webhook.site/b3088a6a-2d17-4f8d-a383-71389a6c600b";
        $extraData = "";
        $request_type = 'captureWallet';
        $rawHash = "accessKey=" . $accessKey . "&amount=" . $order->Tongtien . "&extraData=" . $extraData . "&ipnUrl=" . $ipnUrl . "&orderId=" . $orderId . "&orderInfo=" . $orderInfo . "&partnerCode=" . $partnerCode . "&redirectUrl=" . $redirectUrl . "&requestId=" . $orderId . "&requestType=" . $request_type;
        $signature = hash_hmac("sha256", $rawHash, $secretKey);
        $data = array('partnerCode' => $partnerCode,
        'partnerName' => "Ha Giang Store",
        "storeId" => "MomoTestStore",
        'requestId' => $orderId,
        'amount' => $order->Tongtien,
        'orderId' => $orderId,
        'orderInfo' => $orderInfo,
        'redirectUrl' => $redirectUrl,
        'ipnUrl' => $ipnUrl,
        'lang' => 'vi',
        'extraData' => $extraData,
        'requestType' => $request_type,
        'signature' => $signature);
        $response = Http::post($endpoint, $data);
        return $response;
    }
}
