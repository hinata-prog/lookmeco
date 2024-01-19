<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index(Request $request){
        $orders = Order::with('user')->where('payment_status','paid');
        // dd($orders);

        if ($request->has('keyword') && $request->keyword != "") {
            $orders = $orders->where(function ($query) use ($request) {
                $query->where('id', $request->keyword)->orWhere('mobile',$request->keyword)->orWhere('email',$request->keyword)
                    ->orWhereHas('user', function ($userQuery) use ($request) {
                        $userQuery->where('name', 'like', '%' . $request->keyword . '%')
                            ->orWhere('phone_number', 'like', '%' . $request->keyword . '%');
                    });
            });
        }

        // Fetch the data from the database
        $orders = $orders->paginate(10);

        return view("admin.orders.list", compact('orders'));
    }


    public function detail($orderId){
        $order = Order::with('province')->with('district')->with('orderItems')->where('id',$orderId)->first();

        return view("admin.orders.detail",compact("order"));
    }

    public function changeOrderStatus(Request $request, $orderId){
        $order = Order::where("id",$orderId)->first();
        $order->status = $request->status;
        $order->shipped_date = $request->shipped_date;
        $order->save();

        $message='Order status updated successfully!';
        session()->flash("success",$message);
        return response()->json([
            "status"=> true,
            "message"=> $message
        ]);

    }

    public function sendInvoiceSMS(Request $request, $orderId)
    {
        if ($request->user == 'customer') {
            orderSMS($orderId);

            return response()->json([
                'status' => true,
                'message' => 'SMS for customer initiated successfully',
            ]);
        } elseif ($request->user == 'admin') {
            $this->orderSMSToAdmin($orderId);

            return response()->json([
                'status' => true,
                'message' => 'SMS for admin initiated successfully',
            ]);
        } else {

            return response()->json([
                'status' => false,
                'message' => 'User is not correct',
            ]);
        }
    }


    function orderSMSToAdmin($orderId){
        $order = Order::where('id', $orderId)->with('orderItems')->first();
        $user = Auth::user();
        if ($order) {
            try {
                $invoiceLink = route('front.invoice',$orderId); // Replace with the actual route name for your invoice

                $client = new Client();
                $response = $client->post('https://sms.aakashsms.com/sms/v3/send', [
                    'form_params' => [
                        'auth_token' => 'c1eecbd817abc78626ee119a530b838ef57f8dad9872d092ab128776a00ed31d',
                        'to' => $user->phone_number,
                        'text' => "You have recieved an order::: $invoiceLink",
                    ],
                ]);

                if ($response->getStatusCode() === 200) {
                    $message = 'SMS sent to admin successfully';
                    session()->flash('success',$message);

                    return response()->json(['status' => true, 'message' => $message]);
                } else {
                    $message = 'Failed to send SMS';
                    session()->flash('error',$message);
                    return response()->json(['error' => $message, 'status' => false]);
                }
            } catch (\Exception $e) {
                $message = 'Failed to send SMS. Check your Internet Connection.';
                session()->flash('error',$message);
                return response()->json(['error' => 'Failed to send SMS: ' . $e->getMessage(), 'message' => $message]);
            }
        }

    }
}
