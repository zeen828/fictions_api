<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Model\Books\Bookchapter;
use App\Model\Promotes\Channel;
use App\Model\Promotes\Apk;
use App\Model\Promotes\ChannelApk;
use Redis;

use ZipArchive;

// 檔案處理
use Storage;
// OSS
use Illuminate\Support\Facades\Storage as OssStorage;

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

    // 渠道包建立推廣包
    public function createzip(Request $request, $channel_id, $apk_id)
    {
        // 1.取得資料
        $channel = Channel::find($channel_id);
        //dump($channel);
        $book = $channel->book;
        //dump($book);
        $chapter = $channel->chapter;
        //dump($chapter);
        $chapters = Bookchapter::active()->where('book_id', $chapter->book_id)->where('sort', '<=', $chapter->sort)->get();
        //dump($chapters);
        //dump($_SERVER['DOCUMENT_ROOT']);

        // 2.書籍內容
        $divTag = Storage::disk('public')->get('/channel/html/1/channel.html');
        $customContent = '';
        foreach ($chapters as $key=>$val) {
            $customContent .= $divTag;
            // 章節標題
            $customContent =  str_replace('[[[chapterTitle]]]', $val->name, $customContent);
            // OSS取內容
            $redisKey = sprintf('chapter_contentID%d', $val->id);
            $redisVal = Redis::get($redisKey);
            if(empty($redisVal)){
                // OSS檔案
                $path = str_replace('/Upload/', '', $val->oss_route);
                //$redisVal = Storage::get($path);//設定檔預設寫法
                $oss = OssStorage::disk('oss_txt');
                $redisVal = $oss->read($path);
                // 寫
                Redis::set($redisKey, $redisVal, 'EX', 86400);// 60 * 60 * 24 一天
            }
            // 章節內容
            $customContent =  str_replace('[[[chapterBody]]]', $redisVal, $customContent);
        }
        //dump($divTag);
        //dump($customContent);

        // 3.主體資料替換
        $html = Storage::disk('public')->get('/channel/html/1/html.html');
        $html =  str_replace('[[[bookTitle]]]', $book->name, $html);
        $html =  str_replace('[[[channelId]]]', $channel->id, $html);
        $html =  str_replace('[[[bookId]]]', $channel->book_id, $html);
        $html =  str_replace('[[[chapterID]]]', $channel->chapter_id, $html);
        $html =  str_replace('[[[chapterHtml]]]', $customContent, $html);
        //dump($html);

        // 4.存檔
        Storage::disk('public')->put('/channel/html/1/index.html', $html);

        // 5.壓縮
        $no = 1;
        $fileList = array(
            'css/bootstrap/4.5.2/bootstrap.min.css',
            'css/bootstrap/4.5.2/bootstrap.min.css.map',
            'img/html/img01.html',
            'img/users/01.jpg',
            'img/users/02.jpg',
            'img/users/03.jpg',
            'img/users/04.jpg',
            'img/users/05.jpg',
            'img/users/06.jpg',
            'img/users/07.jpg',
            'img/users/08.jpg',
            'img/users/09.jpg',
            'img/empty.jpg',
            'js/bootstrap/4.5.2/bootstrap.min.js',
            'js/bootstrap/4.5.2/bootstrap.min.js.map',
            'js/jquery/3.5.1/jquery-3.5.1.min.js',
            'js/jquery/3.5.1/jquery-3.5.1.min.map',
            'js/vue/vue-resource.min.js',
            'js/vue/vue.js',
            'favicon.ico',
            'index.html',
        );
        $fimlMame = sprintf('channel_%d_%s.zip', $channel->id, date('YmdHis'));//檔案名稱
        // dump($fimlMame);
        $path = sprintf(storage_path('app/public/channel/html/%d/'), $no);//目標壓縮目錄
        // dump($path);
        $pathZip = storage_path('app/public/channel/zip/');//zip存放目錄
        // dump($pathZip);

        // dump($pathZip . $fimlMame);

        // 壓縮引用(https://tw511.com/a/01/4748.html)
        $zip = new ZipArchive;
        // 開啟一個壓縮檔案
        // ZIPARCHIVE::OVERWRITE 總是建立一個新的檔案，如果指定的zip檔案存在，則會覆蓋掉。
        // ZIPARCHIVE::CREATE    如果指定的zip檔案不存在，則新建一個。
        // ZIPARCHIVE::EXCL      如果指定的zip檔案存在，則會報錯。
        // ZIPARCHIVE::CHECKCONS 對指定的zip執行其他一致性測試。
        $zip->open($pathZip . $fimlMame, ZipArchive::CREATE);
        // 加入壓縮檔案
        foreach ($fileList as $key=>$val) {
            $zip->addFile($path . $val, $val);
            // dump($path . $val);
        }
        // dump('壓縮檔案數量', $zip->numFiles);
        // dump('壓縮狀態', $zip->status );
        $zip->close();

        // 返回下載檔案
        return response()->download($pathZip . $fimlMame);
    }
}
