<?php

namespace App\Http\Controllers\Api\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Model\Books\Bookchapter;
use DB;
use Redis;

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

    // 渠道包打包APK發起
    public function chapterOptions(Request $request)
    {
        $bookID = $request->get('q');
        return Bookchapter::active()->where('book_id', $bookID)->get(['id', DB::raw('name as text')]);
    }
}
