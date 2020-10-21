<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

// Model資料庫
use App\Model\Books\BookInfo;
use App\Model\Books\BookChapter;
use App\Model\Books\BookType;
use App\Model\Users\User;
use App\Model\Rankings\Ranking;
use App\Model\Orders\Amount;
use App\Model\Orders\Order;
use App\Model\Orders\Payment;
use App\Model\Orders\PointLog;
use App\Model\Promotes\ChannelApk;
use App\Model\Analysis\AnalysisUser;

use DB;

// JWT
use \Firebase\JWT\JWT;

use Illuminate\Http\File;
// 阿里雲OSS
use Illuminate\Support\Facades\Storage;

// Redis;
use Redis;

use Hash;

use Config;

class TestsController extends Controller
{
    // 測試頁
    public function index($no=0)
    {
        dump('測試頁');
        switch ($no){
            case 1:
                echo date('Y-m-d H:i:s');
                var_dump(storage_path('app/public' . env('FILE_ADMIN_PATH', '/admin')));
                break;
            case 2:
                phpinfo();
                break;
            case 3:
                dump('環境設定檔ENV');
                $a = env('DB_CONNECTION', '預設');
                dump($a);
                $b = env('DB_HOST_READ', '預設');
                dump($b);
                $c = env('DB_HOST_WRITE', '預設');
                dump($c);
                $d = Config::get('database.default');
                dump($d);
                $e = Config::get('admin.database.connection');
                dump($e);
                $f = Config::get('jwt.secret');
                dump($f);
                break;
            case 4:
                $m_max = date('m');
                $d_start = 30;
                $d_max = 30;
                $h_max = 23;
                for ($m = 9 ; $m <= $m_max ; $m++) {
                    //echo $m;
                    if ($m == 10) {
                        $d_max = date('d');
                    }
                    for ($d = 1 ; $d <= $d_max ; $d++) {
                        //echo $d;
                        for ($h = 0 ; $h <= $h_max ; $h++) {
                            //echo $h;
                            //echo sprintf('php artisan analysis:userhour --start_datetime=2020%02d%02d%02d0000', $m, $d, $h), "<br/>\n";
                            echo sprintf('php artisan analysis:channelhour --start_datetime=2020%02d%02d%02d0000', $m, $d, $h), "<br/>\n";
                        }
                    }
                }
                break;
            case 5:
                dump('字串開頭檢查');
                //$file_path = '/oss/abcs/php';
                //$file_path = 'http://www.googole.com/oss/abcs/php';
                $file_path = 'https://www.googole.com/oss/abcs/php';
                if(starts_with($file_path, 'http://') || starts_with($file_path, 'https://')){
                    dump($file_path, 'http開頭');
                }else{
                    dump($file_path, '不是');
                }
                break;
            default:
                return response()->json(['測試外投資料庫'], 200, [], JSON_PRETTY_PRINT);
        }
    }

    // 測試外投資料庫
    public function wtdb($no=0)
    {
        dump('外投資料測試頁');
        switch ($no){
            case 1:
                // 書籍
                $id = 70884;
                $data = BookInfo::find($id);
                var_dump($data);
                var_dump($data->chapter);
                var_dump($data->type);
                break;
            case 2:
                // 書籍章節
                $args = [];
                //$id = 664146;
                $id = 1280855;
                //$args['id'] = 1280855;
                $data = BookChapter::find($id);
                dump($data);
                dump($data->book);
                break;
                $query = BookChapter::where('is_offline', 1)->orderBy('sort', 'ASC');
                $root = $query->findOrFail($args['id']);
                //book/71007/725570.txt
                ///Upload/book/71007/72989.txt
                $path = str_replace('/Upload/', '', $root->route);
                $results = Storage::get($path);
                $root->content = $results;
                var_dump($root);
                break;
            case 3:
                // 書籍分類
                $id = 1;
                $data = BookType::find($id);
                var_dump($data);
                break;
            case 4:
                // 章節的上下章節
                $tid = 74037;
                $sort = 4;
                $data = BookChapter::select('*')->where('tid', $tid)->where('sort', '<', $sort)->orderBy('sort', 'DESC')->first();
                var_dump($data['id']);
                $data = BookChapter::select('*')->where('tid', $tid)->where('sort', '>', $sort)->orderBy('sort', 'ASC')->first();
                var_dump($data->id);
                break;
            case 5:
                $key = "example_key";
                $payload = array(
                    "iss" => "http://example.org",
                    "aud" => "http://example.com",
                    "iat" => 1356999524,
                    "nbf" => 1357000000
                );
                
                /**
                 * IMPORTANT:
                 * You must specify supported algorithms for your application. See
                 * https://tools.ietf.org/html/draft-ietf-jose-json-web-algorithms-40
                 * for a list of spec-compliant algorithms.
                 */
                $jwt = JWT::encode($payload, $key);
                $decoded = JWT::decode($jwt, $key, array('HS256'));
                
                print_r($decoded);
                
                /*
                 NOTE: This will now be an object instead of an associative array. To get
                 an associative array, you will need to cast it as such:
                */
                
                $decoded_array = (array) $decoded;
                
                /**
                 * You can add a leeway to account for when there is a clock skew times between
                 * the signing and verifying servers. It is recommended that this leeway should
                 * not be bigger than a few minutes.
                 *
                 * Source: http://self-issued.info/docs/draft-ietf-oauth-json-web-token.html#nbfDef
                 */
                JWT::$leeway = 60; // $leeway in seconds
                $decoded = JWT::decode($jwt, $key, array('HS256'));
                break;
            case 6:
                $args = ['phone'=>'1380000001', 'password'=>'123456'];
                print_r($args);
                //$args['password'] = bcrypt($args['password']);
                print_r($args);
                $query = User::where('status', 1)->orderBy('id', 'ASC');
                $query->where('phone', $args['phone']);
                $data = $query->first();
                print_r($data);
                $ch = Hash::check($args['password'], $data->password);
                var_dump($ch);
                break;
            case 7:
                $args = [];
                do{
                    $args['account'] = sprintf('AUTO%s', str_random(12));
                    //$args['account'] = 'auto000000000001';
                    var_dump($args);
                    $queryUser = User::where('account', $args['account'])->first();
                    var_dump( empty($queryUser) );
                } while ( !empty($queryUser) );
                // 註冊
                $args['password'] = str_random(6);
                $user = User::create($args);
                var_dump($user);
                break;
            case 8:
                $args = [];
                $args['token'] = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZCI6MSwiYWNjb3VudCI6ImF1dG8wMDAwMDAwMDAwMDEiLCJuaWNrX25hbWUiOm51bGwsInBob25lIjoiMTM4MDAwMDAwMDEiLCJzZXgiOjAsInZpcCI6MCwidmlwX2F0IjpudWxsfQ.YBS_gOiI5rVCcgnymoC15FTZUU7DzuVx1uCI8rDqO4k';
                if (isset($args['token'])) {
                    $key = "example_key";
                    $userJwt = JWT::decode($args['token'], $key, array('HS256'));
                    var_dump($userJwt);
                    //$userJwt->id = 99;
                    $user = User::find($userJwt->id);
                    //var_dump($user);
                    if ( !empty($user) ) {
                        echo 'O';
                        $user->points -= 10;
                        $user->save();
                    } else {
                        echo 'X';
                    }
                }
                break;
            case 9:
                $txt = '<p>我记得那一晚恰巧是yīn历的十五，月亮很圆，柔和的月光把屋子里照得亮亮的。由于睡前贪嘴吃了几块巧克力，我很久都没有睡着，最后索xìng睁大眼睛，</p><p>呆呆的望着月婆婆，想着心事。这时我感到有些口渴，便起身轻手轻脚的去厨房喝水。</p><p>经过父母的房间，发现似乎还有灯光，并且不时还有一些奇怪的声音传出。</p><p>这时侯已是半夜１２点多了，“爸妈在淦什麽呢？这麽晚还不休息。”我心里直泛嘀咕。其实这种状况已不是我第一次遇到了，</p><p>每次我都很想偷偷的看看爸妈到底在做什麽，可我每次都忍住了，因为觉得这样做不好。</p><p>这时屋内又传来爸妈嘻笑声，尽管听得不太清，但不知为什麽我的脸却变红了。我隐隐约约觉得爸妈一定在做一件非常好玩的事，而且是他们俩才能做的，</p><p>所以才会瞒着我，每次都在我睡觉以后才做。我的好奇心越来越强烈，今晚我实在忍不住想看看。</p><p>“只看一眼，看完就回去睡觉，爸妈不会发现的。”我不断的说服着自己。</p><p>我开始蹑手蹑脚的向房门移去，生怕弄出半点声响。我终于来到门前，伸出颤抖的手推开一道细小的门缝，</p><p>这时我几乎都能听到心脏在“咚咚”的剧烈跳动着。我定定神，大着胆子向屋里望去，眼神立刻便凝固住了┅┅</p><p>只见在柔和昏黄的灯光下，爸妈都是光着身子躺在床上。妈妈靠在爸爸的怀里，正用手玩弄着爸爸的ròu棒。</p><p>爸爸的ròu棒非常的粗大，有七、八寸长，紫红色的guītóu足有鸡蛋大校这是我第一次看到男人的yángjù，老实说，我当时并不知道那是什麽。</p><p>妈妈继续玩着，像是在玩一件非常有趣的玩具，并不时的低下头去，把ròu棒含在嘴里用力的吸吮，很快爸爸的ròu棒就变得又硬又粗了，而且油光发亮的。</p><p>这时妈妈的yín态毕露，柔腻的央求着：“大勇，求你了，再玩一会吧，人家还没过瘾呢</p><p>“阿珍，时侯不早了，休息吧。明天早上你还要起来给女儿做饭呢</p><p>爸爸吸着烟，一边还把玩着妈妈丰盈的双rǔ。</p><p>“不，我要嘛┅┅大勇，是不是我对你没有吸引力了？你整天在外面风流快活，让我独守冷床。</p><p>回来家还这麽敷衍人家，你是不是想bī我到外面去找男人，跟你戴顶绿帽子１妈妈有些生气了。</p><p>“好了，阿珍，别生气了。我跟那些女人只是逢场做戏罢了，你才是我最宝贵的，你在我心中的地位她们又怎麽能比呢，我又怎会冷落你呢，</p><p>想天天爱你疼你还来不及呢！我只是看你今天忙了一天的家务，太辛苦了。</p><p>不过爱妻既然还有兴致，老公自然要全力奉陪了。”爸爸把妈妈搂在怀里，不住的宽慰爱抚。</p><p>“好，我的心肝，小dàngfù，你还想怎样玩？”爸爸摩挲着妈妈白皙圆润的大腿，调笑着。</p><p>妈妈这才转怒为喜，用手捶着爸爸的xiōng口，说道：“老公，你好坏，这样说人家。</p><p>好，那我就是yín娃dàngfù。好大勇，我现在好难受，小好yǎng，我要你的大ròu棒来止yǎng。”</p><p>爸爸看到妈妈如此的饥渴，也不忍心再捉弄了，便取过一个枕头，垫在妈妈的pìgǔ底下，分开双腿，露出妈妈的小。妈妈的yīn户很饱满，耻毛浓密乌黑，</p><p>此时已被yín水泡的shīlùlù的。只见爸爸跪在妈妈面前，对准小，一挺腰，便把大ròu棒连根chā入了妈妈的小。</p><p>此时妈妈显得满足极了，长长的shēnyín着，又xìngfèn又感激的望着爸爸。爸爸停了一会才把ròu棒慢慢的抽了出来，</p><p>但很快的再缓缓的chā进去，并且让ròu棒在小内转动着，这又引得妈妈连声的娇哼。</p><p>而此时正在门外偷看的我，已经被这香艳刺激的一幕惊呆了。我有些不知所措，只觉得粉脸好烫，有些喘不过气来。真是羞死人了！我想赶快离开这，</p><p>可是双脚像是被钉住了一般，无法动弹。我当时又羞又怕，不知该如何是好。</p>';
                $txt = str_limit($txt, 50, $end = '...更多內容請充值');
                echo $txt;
                break;
            case 10:
                $user = User::active()->get();
                print_r($user);
                break;
            case 11:
                $user = User::find(8);
                $order = Order::find(15);
                $user->points = 30;
                $order->point_old = 30;
                $order->transaction_at = '5069-11-11 11:20:15';
                try {
                    // 存檔
                    $status = DB::transaction(function() use ($user, $order){
                        $userStatus = $user->save();
                        $orderStatus = $order->save();
                    });
                    var_dump($status);
                } catch (Exception $e) {
                    echo 'Caught exception: ',  $e->getMessage(),'<br>';
                }
                break;
            case 12:
                $logSave = [
                    'user_id' => 8,
                    'book_id' => 71007,
                    'chapter_id' => 725581,
                ];
                $pointlog = PointLog::where($logSave)->first();
                //var_dump($pointlog);
                if(!empty($pointlog)){
                    echo 'A';
                }else{
                    echo 'B';
                }
                break;
            case 13:
                $id = 1;
                // dump(PointLog);
                $data = PointLog::find($id);
                dump($data->book);
                dump($data->chapter);
                break;
            case 14:
                $id = 74037;
                $book_o = BookInfo::find($id);
                dump($book_o);
                //單欄位累計
                //BookInfo::where('id', $id)->increment('click_w', 1);
                //多欄位累計
                BookInfo::where('id', $id)->update(array(
                    'click_w' => DB::raw('click_w + 1'),
                    'click_m' => DB::raw('click_m + 1'),
                    'click_s'  => DB::raw('click_s + 1'),
                 ));
                $book_n = BookInfo::find($id);
                dump($book_n);
                break;
            case 15:
                $query = AnalysisUser::select(DB::raw('sum(wap_user_reg) as sum_wur, sum(app_user_reg) as sum_aur, sum(wap_user_login) as sum_wul, sum(app_user_login) as sum_aul, sum(wap_order_all) as sum_woa, sum(app_order_all) as sum_aoa, sum(wap_order_success) as sum_wos, sum(app_order_success) as sum_aos, sum(wap_recharge) as sum_wr, sum(app_recharge) as sum_ar'))->first();
                dump($query);
                break;
            case 16:
                // 多對多關聯查詢
                dump('關聯資料庫查詢條件');
                // 多對多
                // with() - 預載關聯
                // $query1 = Bookinfo::with('types')->active();
                // has() - 宣告關聯model而已
                $query1 = Bookinfo::has('types')->active();
                // whereHas() - 針對關聯model去下條件
                // $query1 = Bookinfo::whereHas('types', function ($query) {
                //     $query->where('t_booktype.id', 1);
                // });
                // dump($query1->toSql());
                dump($query1->skip(10)->take(5)->get());
                // 一對一
                // $query2 = Bookinfo::with('chapter')->active();
                // $query2 = Bookinfo::has('chapter')->active();
                $query2 = Bookinfo::whereHas('chapter', function ($query) {
                    $query->where('status', 1);
                });
                dump($query2->toSql());
                dump($query2->skip(10)->take(5)->get());
                break;
            case 17:
                // 多對多關聯查詢
                dump('關聯資料轉id,id,id');
                $query = Ranking::with('books')->active()->get();
                //dump($query);
                if (!$query->isEmpty()) {
                    $i = 205;
                    foreach ($query as $val) {
                        $books = $val->books;
                        //dump('books', $books);
                        // 補資料
                        if(count($books) == 0){
                            dump('沒有', $val->id);
                            $in = explode(',', $val->book_id);
                            dump($in);
                            $now_date = date('Y-m-d h:i:s');
                            $saveData = array();
                            foreach ($in as $bookid) {
                                $i++;
                                $saveData[] = ['id' => $i, 'ranking_id' => $val->id, 'bookinfo_id' => $bookid, 'created_at' => $now_date, 'updated_at' => $now_date];
                            }
                            dump($saveData);
                            //DB::table('t_ranking_bookinfo')->insert($saveData);
                        }
                        //dump('books implode', $books->implode('id', ','));
                    }
                }
                break;
            case 18:
                dump();
                $id = $form->model()->id;
                $book = Bookinfo::find($id);
    
                break;
            default:
                var_dump('測試外投資料庫');
        }
    }

    // 測試query分段
    public function model($no=0)
    {
        dump('Model測試頁');
        switch ($no){
            case 1:
                $query = BookInfo::where('is_offline', 1);
                //var_dump($query);
                $query->where('id', 70884);
                //var_dump($query->first());
                //var_dump($query->get());
                var_dump($query->paginate(10, ['*'], 'page', 1));
                // var_dump($query->where('id', 70884)->count());
                var_dump('model-測試資料庫 - 1');
                break;
            case 2:
                //$args = ['id'=>1];
                $args = ['phone'=>"1380000001"];
                $query = User::where('status', 1)->orderBy('id', 'ASC');
                // 指定多筆查詢
                if (isset($args['id'])) {
                    $query->where('id', $args['id']);
                }elseif (isset($args['phone'])) {
                    $query->where('phone', $args['phone']);
                }
                $data = $query->get();
                print_r($data);
                break;
            case 3:
                $id = 1;
                //$query = Amount::find($id);
                //print_r($query);
                //print_r($query->payment);
                //$query = Order::find($id);
                //$query = Payment::find($id);
                //print_r($query);
                //print_r($query->amount);
                //$query = PointLog::find($id);
                //print_r($query);
                break;
            case 4:
                // QL API支付測試用
                $args = array();
                $args['token'] = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZCI6MSwiYWNjb3VudCI6ImF1dG8wMDAwMDAwMDAwMDEiLCJuaWNrX25hbWUiOm51bGwsInBob25lIjoiMTM4MDAwMDAwMDEiLCJzZXgiOjAsInZpcCI6MCwidmlwX2F0IjpudWxsfQ.hga1vzG8rEuxeTjnj-xPauHX07FB7L2nB7i-UNJ8dSE';
                $args['amount_id'] = 1;
                $args['payment_id'] = 1;
                $args['app'] = 1;
                $args['channel_id'] = 0;
                $args['link_id'] = 0;

                $dataSave = [];
                // 檢查一組可用訂單代碼
                do{
                    $orderTag = env('ORDER_TAG', 'WT0');
                    $dataSave['order_sn'] = sprintf('%s%08d%s', $orderTag, date('Ymd'), str_random(11));
                    $queryPk = Order::where('order_sn', $dataSave['order_sn'])->first();
                } while ( !empty($queryPk) );// true會繼續跑回圈,有值就在跑一圈跑到沒值
                // JWT反解user_info
                $key = Config::get('jwt.secret');
                $userJwt = JWT::decode($args['token'], $key, array('HS256'));
                // print_r($userJwt);
                if(empty($userJwt)){
                    // 失敗處理
                    return null;
                }
        
                $dataSave['user_id'] = $userJwt->id;
                // 支付商資料
                $amount = Amount::active()->find($args['amount_id']);
                // print_r($amount);
                if(empty($amount)){
                    // 失敗處理
                    return null;
                }
                $dataSave['price'] = $amount->price;
                $dataSave['points'] = $amount->points;
                $dataSave['vip'] = $amount->vip;
                $dataSave['vip_day'] = $amount->vip_day;
                // 支付金額資料
                $payment = $amount->payment->find($args['payment_id']);
                dump($payment);
                if(empty($payment)){
                    // 失敗處理
                    return null;
                }
                // 白癡浮動機制腦袋裝屎
                if ($payment->float == 1) {
                    $min = ($payment->min < 1)? 1: $payment->min;
                    $max = ($payment->max > 30)? 30: $payment->max;
                    $del = rand($min, $max);
                    $dataSave['price'] = $amount->price - ( $del / 100);
                }
                $dataSave['payment_id'] = $payment->id;
                $dataSave['callbackUrl'] = $payment->domain_call;
                $dataSave['sdk'] = $payment->sdk;
                $dataSave['config'] = json_encode($payment->config);// model有處理過所以要跟這處理
                //print_r($dataSave);
                //APP預設1
                $dataSave['app'] = $args['app'];
                //渠道ID&推廣ID
                $dataSave['channel_id'] = $args['channel_id'];
                $dataSave['link_id'] = $args['link_id'];
                $order = Order::create($dataSave);

                dump($order);
                //return $order;
                break;
            case 5:
                $data = BookInfo::find(46719);
                //dump($data);
                dump($data->types);
                //$data = BookType::find(1);
                //dump($data);
                //dump($data->books);
                break;
            case 6:
                $query = ChannelApk::active()->orderBy('id', 'DESC');
                $query->where('channel_id', '1');
                $val = $query->first();
                dump($val);
                break;
            default:
                var_dump('model-測試資料庫');
        }
    }

    // 阿里雲OSS
    public function oss($no=0)
    {
        dump('oss測試頁');
        switch ($no){
            case 1:
                dump('OSS讀取');
                $oss = Storage::disk('oss_txt');
                dump($oss);
                $results = $oss->read('book/70884/381662.txt');
                dump($results);
                break;
            case 2:
                dump('OSS上傳');
                $a = Config::get('filesystems.disks.oss_img.bucket');
                //$a = env('FILE_ADMIN_PATH', '/admin');
                dump($a);
                $oss_bucken = env('OSS_IMG_BUCKET');
                dump($oss_bucken);
                $oss_endpoint = env('OSS_IMG_ENDPOINT');
                dump($oss_endpoint);
                exit();
                $upload_path = storage_path('app/public' . env('FILE_ADMIN_PATH', '/admin'));
                dump($upload_path);
                $file_path = 'oss/cover/7573d33c8ea8e8477945431f079e7e9c.jpg';
                dump($file_path);
                $oss = Storage::disk('oss_img');
                dump($oss);
                //$content = File::get($upload_path . '/' . $file_path);
                //$txt = $oss->put($file_path, $content);
                $txt = $oss->putFile($file_path, new File($upload_path . '/' . $file_path));
                dump($txt);
                break;
            case 3:
                dump('OSS上傳');
                $oss = Storage::disk('oss_img');
                //put

                break;
            default:
                return response()->json(['測試外投資料庫'], 200, [], JSON_PRETTY_PRINT);
        }
    }

    // redis連線操作
    public function redi($no=0)
    {
        dump('Redi測試頁');
        switch ($no){
            case 1:
                //
                var_dump(['title'=>'redis測試頁']);
                // GET laravel_database_will_test
                $query = sprintf('QL_book_%s', '74037');
                // $res = Redis::set($query, json_encode(['name'=>'測試用','des'=>'test']));
                // var_dump($res);
                
                $val = Redis::get($query);
                var_dump($val);

                // $val = Redis::command('lrange', ['name', 5, 10]);
                // var_dump($val);

                $val = Redis::setex('library', 60, 'predis'); // 存储 key 为 library， 值为 predis 的记录, 有效时长为 10 秒
                var_dump($val);
                break;
            case 2:
                // 資料庫頁簽方式查詢使用REDIS紀錄
                $args = [
                    'page' => 1,
                    'limit' => 999,
                ];
                $query = Ranking::active();
                // Redis
                $redisKey = sprintf('rank_list%d_%d', $args['page'], $args['limit']);
                if ($redisVal = Redis::get($redisKey)) {
                    dump('Redis資料');
                    dump(unserialize($redisVal));
                }
                $redisVal = $query->paginate($args['limit'], ['*'], 'page', $args['page']);
                $redisVal = serialize($redisVal);
                dump('資料庫分頁資料');
                dump($redisVal);
                //dump(serialize($redisVal));
                // 寫
                Redis::set($redisKey, $redisVal, 'EX', 3600);
                // Redis::set('user:1:notified', 1, 'EX', 10);
                // Redis::expire($redisKey, 60);
                break;
            case 3:
                //更新壽命刪除資料
                dump('REDIS壽命');
                $id = 1;
                $redisKey = sprintf('payment_ID%d', $id);
                dump($redisKey);
                $s = Redis::expire($redisKey, 0);
                dump($s);
                break;
            case 4:
                dump('REDIS - 用KEY篩選全部有關的KEY');
                $id = 1;
                $redisKey = sprintf('payment_ID%d', $id);
                dump($redisKey);
                $s = Redis::expire($redisKey, 0);
                dump($s);
                break;
            default:
                return response()->json(['測試外投資料庫'], 200, [], JSON_PRETTY_PRINT);
        }
    }

    public function files($no=0)
    {
        dump('檔案測試頁');
        switch ($no){
            case 1:
                dump('複製檔案');
                //dump(Storage::move('/usr/share/nginx/website/wt_api/storage/app/public/admin/files/q_V1.1.apk', '/mnt/apk/client/1/q_V1.1.apk'));
                //dump(Storage::move('admin/files/q_V1.1.apk', '/mnt/apk/client/1/q_V1.1.apk'));
                //只能處理storage/app/public下的檔案
                dump(exec('mkdir /mnt/apk/client/1'));
                dump(exec('cp /usr/share/nginx/website/wt_api/storage/app/public/admin/files/q_198.apk /mnt/apk/client/1/q_V1.1.apk'));
                break;
            case 2:
                break;
            case 3:
                break;
            default:
                return response()->json(['測試外投資料庫'], 200, [], JSON_PRETTY_PRINT);
        }
    }
}
