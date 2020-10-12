<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Model\Domains\Domain;
use DB;

class DomainsController extends Controller
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
    public function species(Request $request, $species_id)
    {
        $res = array(
            'msg' => '失敗',
            'code' => -1,
            'data' => array(),
        );
        // 接變數
        $domain = Domain::active()->where('species', $species_id)->first();
        if (empty($domain)) {
            // 錯誤
            return response()->json($res, 200, [], JSON_PRETTY_PRINT);
        }
        $res = array(
            'msg' => '成功',
            'code' => 200,
            'data' => array(
                'id' => $domain->id,
                'species' => $domain->species,
                'ssl' => $domain->ssl,
                'domain' => $domain->domain,
                'remarks' => $domain->remarks,
            ),
        );
        return response()->json($res, 200, [], JSON_PRETTY_PRINT);
    }
}
