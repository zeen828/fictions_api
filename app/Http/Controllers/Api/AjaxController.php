<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Model\Promotes\Channel;
use App\Model\Promotes\Apk;
use App\Model\Promotes\ChannelApk;

class AjaxController extends Controller
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

    // 測試頁
    public function package(Request $request, $channel_id, $apk_id)
    {
        $res = array(
            'msg' => '失敗',
            'code' => -1,
            'data' => array(),
        );

        $dataSave = array(
            'channel_id' => '0',
            'apk_id' => $apk_id,
            'status' => '0',
        );
        // APK
        $apk = Apk::active()->find($apk_id);
        if (empty($apk)) {
            $res['msg'] = '客戶端發生錯誤';
            return response()->json($res, 200, [], JSON_PRETTY_PRINT);
        }

        // 渠道
        $query = Channel::active();
        // channel_id = 0全部更新
        if ($channel_id != 0) {
            $query->where('id', $channel_id);
        }
        $channels = $query->get();
        if ($channels->isEmpty()) {
            $res['msg'] = '渠道發生錯誤';
            return response()->json($res, 200, [], JSON_PRETTY_PRINT);
        }
        foreach ($channels as $channel) {
            $dataSave['channel_id'] = $channel->id;
            $res['data'][] = $dataSave;
            ChannelApk::create($dataSave);
        }

        $res['msg'] = '成功';
        $res['code'] = 200;
        return response()->json($res, 200, [], JSON_PRETTY_PRINT);
    }
}
