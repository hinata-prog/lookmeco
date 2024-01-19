<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use App\Models\Order;

class PaymentController extends Controller
{
    public function verifyKhaltiPayment(Request $request){
        $transactionId = $request->transactionId;
        $orderValue=Order::where('transaction_uuid', $transactionId)->first();
        $token = $request->token;
        $amount = $request->amount;

        $args = http_build_query(array(
        'token' => $token,
        'amount'  => $amount
        ));

        $url = "https://khalti.com/api/v2/payment/verify/";

        # Make the call using API.
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,$args);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $secretKey = config('app.khalti_secret_key');

        $headers = ["Authorization: Key $secretKey" ];
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        // Response
        $responseData = curl_exec($ch);
        $response = json_decode($responseData);

        $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if($response->amount == $amount){

            $orderValue->payment_status = 'paid';
            $orderValue->save();


            //send confirmation sms
            orderSMS($request->id);

            Cart::destroy();

            //Update Product Stock
            foreach (Cart::content() as $item) {
                $productData = Product::find($item->id);
                if ($productData->track_qty == 'Yes') {
                    $currentQty = $productData->qty;
                    $updatedQty = $currentQty - $item->qty;
                    $productData->qty = $updatedQty;
                    $productData->save();
                }
            }

            $message = "You have successfully placed your order.";
            session()->flash("success", $message);

            return response()->json([
                'status' => true,
                'message'=> $message
            ]);

        }else{
            $message = "Order not successfully placed due to payment failure.";
            session()->flash("error", $message);

            return response()->json([
                'status' => false,
                'message'=> $message
            ]);
        }



    }

    public function esewaPaymentForm($transactionId){
        $order = Order::where('transaction_uuid',$transactionId)->first();
        return view('esewa.form', compact('order'));
    }

    public function esewaSuccess(){
        return view('esewa.success');
    }

    public function esewaFailure(){
        return view('esewa.failure');
    }


    public function verifyEsewaPayment(Request $request){
        $encodedData = $request->data;
        $decodedData = base64_decode($encodedData);
        $jsonData = json_decode($decodedData, true);
        
        $order = Order::where('transaction_uuid', $jsonData['transaction_uuid'])->first();
        
        // Use bcmul for precise multiplication with a precision of 2
        $amount = floatval(str_replace(",", "", $jsonData['total_amount']));
        
        // dd($order->grand_total, $amount);       

        if ($amount == $order->grand_total && $jsonData['status'] == "COMPLETE") {
            $orderValue = Order::where('transaction_uuid',$jsonData['transaction_uuid'])->first();
            $orderValue->payment_status = 'paid';
            $orderValue->save();


            //send confirmation sms
            orderSMS($request->id);

            Cart::destroy();

            //Update Product Stock
            foreach (Cart::content() as $item) {
                $productData = Product::find($item->id);
                if ($productData->track_qty == 'Yes') {
                    $currentQty = $productData->qty;
                    $updatedQty = $currentQty - $item->qty;
                    $productData->qty = $updatedQty;
                    $productData->save();
                }
            }

            $message = "You have successfully placed your order.";
            return redirect()->route('front.thankyou',$order->id)->with("success", $message);
        }
        $message = "Order not successfully placed due to payment failure.";
        return redirect()->route('front.checkout')->with('error', $message);

    }
}
