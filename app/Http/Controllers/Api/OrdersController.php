<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Model\Users\User;
use App\Model\Orders\Order;
use App\Model\Domains\Domain;
use DB;

class OrdersController extends Controller
{
    // 測試頁
    public function index(Request $request)
    {
        $input = $request->all();
        $res = array(
            'msg' => '成功',
            'code' => 0,
            'data' => $input
        );
        return response()->json($res, 200, [], JSON_PRETTY_PRINT);
    }

    /**
     * 支付查詢
     *
     * /2/cartoon/pay/payNotifyOrder?orderid
     * @param Request $request
     * @return void
     */
    public function query(Request $request)
    {
        $res = array(
            'msg' => '失敗',
            'code' => -1,
            'data' => array(),
        );
        // 接變數
        $order_sn = $request->input('orderid');
        $order = Order::where('order_sn', $order_sn)->first();
        $payment = $order->payment;
        $domain = Domain::active()->where('species', 1)->first();
        if(empty($order) || empty($payment) || empty($domain)){
            // 錯誤
            return response()->json($res, 200, [], JSON_PRETTY_PRINT);
        }
        // 漫畫的狀態不一樣
        switch ($order->status) {
            case 0:
                $order->status = 1;
                break;
            case 1:
                $order->status = 2;
                break;
        }
        $res = array(
            'msg' => '成功',
            'code' => 0,
            'data' => array(
                'id' => $order->id,
                'istemp' => 0,
                'token' => $order->user_id,
                'paymentId' => $order->payment_id,
                'app' => $order->app,
                'isVip' => $order->vip,
                'linkid' => $order->linkid,
                'money' => $order->price,
                'callbackUrl' => sprintf('http://%s/', $domain->domain),//主體域名
                'payok' => '/recharge',//成功網址(會支付會組callbackUrl域名)
                'payok_temp' => 'payok',//payok=拿payok的值來用
                'payerr' => '/recharge',//失敗網址
                'sdk' => $payment->sdk,
                'enableFloatingAmount' => $payment->float,
                'minFloatingAmount' => $payment->min / 100,
                'maxFloatingAmount' => $payment->max / 100,
                'config' => (empty($payment->config))? '{}' : $payment->config,
                'status' => $order->status,
                'request_time' => strtotime($order->created_at),
            ),
        );
        return response()->json($res, 200, [], JSON_PRETTY_PRINT);
    }

    /**
     * 支付完成回吊
     * /2/cartoon/pay/payNotifyOrder?orderid
     * @param Request $request
     * @return void
     */
    public function payNotify(Request $request)
    {
        $res = array(
            'msg' => '失敗',
            'code' => -1,
            'data' => array(),
        );
        // 接變數
        $order_sn = $request->input('outTradeNo');
        $price = $request->input('money');
        $transaction_sn = $request->input('transactionId');
        $transaction_at = $request->input('transactionTime');
        $transaction_at = substr($transaction_at, 0, 10);
        // 查訂單
        $order = Order::where('order_sn', $order_sn)->first();
        //if(empty($order) || $order->price != $price || $order->status != 0){
        if(empty($order)){
            // 錯誤
            $res_ex = array(
                'msg' => '取不到訂單',
                'code' => -1,
                'data' => array(),
            );
            return response()->json($res_ex, 200, [], JSON_PRETTY_PRINT);
        }
        if($order->status == 1){
            // 錯誤
            $res_ex = array(
                'msg' => '訂單已成功',
                'code' => 0,
                'data' => array(),
            );
            return response()->json($res_ex, 200, [], JSON_PRETTY_PRINT);
        }
        // 取會員資料
        $user = User::active()->find($order->user_id);
        if(empty($user)){
            // 錯誤
            $res_ex = array(
                'msg' => '會員錯誤',
                'code' => -1,
                'data' => array(),
            );
            return response()->json($res_ex, 200, [], JSON_PRETTY_PRINT);
        }
        // 加點數
        if($order->points != 0){
            $order->point_old = $user->points;// 原始點數
            $order->point_new = $user->points + $order->points;// 更新點數
            $user->points = $order->point_new;// 使用者點數
        }
        // 加Vip
        if($order->vip == 1){
            $order->vip_at_old = $user->vip_at;// 原始日期
            // 沒日期或已過期
            $now = date('Y-m-d H:i:s');
            if(empty($user->vip_at) || strtotime($user->vip_at) <= strtotime($now)){
                $order->vip_at_new = date('Y-m-d H:i:s', strtotime($now . '+' . $order->vip_day . ' day'));// 更新日期
            }else{
                $order->vip_at_new = date('Y-m-d H:i:s', strtotime($user->vip_at . '+' . $order->vip_day . ' day'));// 更新日期
            }
            $user->vip_at = $order->vip_at_new;// 使用者日期
        }
        // 更新資料
        $order->transaction_sn = $transaction_sn;
        $order->transaction_at = date('Y-m-d H:i:s', $transaction_at);
        $order->status = 1;
        // 交易模式存檔
        $status = DB::transaction(function() use ($user, $order){
            $userStatus = $user->save();
            $orderStatus = $order->save();
            if(empty($userStatus) || empty($orderStatus)){
                // 錯誤
                return response()->json($res, 200, [], JSON_PRETTY_PRINT);
            }
        });
        $res = array(
            'msg' => '成功',
            'code' => 0,
            'data' => '调用成功',
        );
        return response()->json($res, 200, [], JSON_PRETTY_PRINT);
    }
}
