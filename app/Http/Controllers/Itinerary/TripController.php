<?php

namespace App\Http\Controllers\Itinerary;

use App\Filament\Clusters\Order;
use App\Helper\ShortCrypt;
use App\Http\Controllers\Controller;
use App\Models\TripTime;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use function Laravel\Prompts\alert;
use App\Models\TripApply;
use App\Models\TripOrder;
use Illuminate\Support\Facades\Crypt;

class TripController extends ItryController
{
    private string $jsPage;

    public function __construct()
    {
        parent::__construct();
        $this->jsPage = 'trip';
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): \Illuminate\Http\Response
    {


        $bankInfo = $this->Settings->getBase($this->Slug, '_bank');

        $jsPage = $this->jsPage;
        $Slug = $this->Slug;
        $secondSlug = $this->secondSlug;
        $Media = $this->Media;
        $trip = $request->route('trip');

        $tripTime_uuid = request()->query('trip_time') ?? (empty(session('trip_time')) ? null : session('trip_time')); // 取得 'trip_time' 參數

        $items = $this->Trip->getTrip($trip, $tripTime_uuid);

        session(['trip_time' => $tripTime_uuid ?? '']);
        $selectedTripTime = $items->trip_times->pluck('dateAll', 'uuid');
        $uuid_default = $tripTime_uuid ?? $selectedTripTime->keys()->first(); //如果找不到uuid就預設第一個

        $trip_times = $items->trip_times->keyBy('uuid')->get($uuid_default)->toArray();


        $AllNames = array_keys(get_defined_vars());
        if (isset($_GET['t'])) {
            if ($_GET['t'] == 'a') {
                dd($AllNames
//                    ->get()
//                    ->first()  // 只取出第一项
//                    ->toArray()
                );
            }

        }
        return response()
            ->view("Itinerary/itinerary_item", compact($AllNames));
    }

    /**
     * 建立訂單
     */
    public function create(Request $request): \Illuminate\Http\JsonResponse
    {

        // 驗證其他資料
        $request->validate([
            'data' => 'required|array',
            'uuid' => 'required|string|max:100',
//            'paid_amount' => 'required|numeric',
//            'account_last_five' => 'required|string|max:5',

        ], [
            'data.required' => '資料欄位是必填的。',
            'uuid.required' => 'UUID有誤。',
            'uuid.max' => 'UUID有誤。',
//            'paid_amount.required' => '付款金額必填。',
//            'account_last_five.required' => '帳號末五碼必填。',
        ]);

        try {
            // 驗證驗證碼
            $rules = ['captcha' => 'required|captcha_api:' . request('key') . ',math'];
            $validator = validator()->make(request()->all(), $rules);


            if ($validator->fails()) {
                return response()->json(['error' => '驗證碼錯誤'], 500);
            }


            // 設定報名序號，時間戳 + 微秒 +_+行程slug
            $order_number = (int)(microtime(true) * 1000) . '_' . $request->route('trip');
            $tripApplyId = [];
            $dataArray = $request['data'];
            $count = 0;
            // 儲存報名資料
            foreach ($dataArray as $data) {

                // 使用模型創建報名資料
                $tripApply = TripApply::create([
                    'name' => $data['name'],
                    'order_number' => $order_number,
                    'gender' => $data['gender'],
                    'birthday' => $data['birthday'],
                    'email' => $data['email'],
                    'phone' => $data['phone'],
                    'country' => $data['country'],
                    'id_card' => $data['id_card'],
                    'PassPort' => $data['PassPort'],
                    'address' => $data['address'],
                    'diet' => $data['diet'],
                    'experience' => $data['experience'],
                    'disease' => $data['disease'] ? implode(',', $data['disease']) : '無',
                    'LINE' => $data['LINE'],
                    'IG' => $data['IG'],
                    'emContact' => $data['emContact'],
                    'emContactPh' => $data['emContactPh'],
                ]);
                // 儲存每筆報名資料的 ID
                $tripApplyId[] = $tripApply->id;
                $count++; // 增加人數計數
            }

            // 儲存報名資料，包含報名的 ID 列表
            $tripOrder = TripOrder::create([
                'order_number' => $order_number,
                'account_last_five' => $request['account_last_five'],
                'paid_amount' => $request['paid_amount'],
                'amount' => $request['amount'],
//                'applies' => json_encode($tripApplyId),  // 儲存報名 ID 的 JSON 格式
                'status' => 10,  // 報名的初始階段
            ]);
// 將 TripApply 關聯到 TripOrder
            $tripOrder->applies()->attach($tripApplyId);
            $tripOrder->times()->attach($request['uuid']);
//uuid尋找時間與團名
            $TripTimes = TripTime::with('Trip')->where('uuid', $request['uuid'])->first();;
            $TripTime = TripTime::selectRaw(TripTime::getDateLogic())->where('uuid', $request['uuid'])->first();
            $trip = "{$TripTimes->Trip->title}-{$TripTimes->Trip->subtitle}({$TripTime->dateAll})";
// 假設 $order_number 和 $trip 已定義
            $lineLink = "<a href='https://line.me/R/oaMessage/" . env('Line_ID') . "/?訂單序號: {$order_number}，{$trip}' class='inline-block bg-green-500 p-2 rounded-md hover:bg-green-600 transition-colors'>";
            $lineLink .= "<img src='https://scdn.line-apps.com/n/line_add_friends/btn/zh-Hant.png' class='w-32' alt='加入好友' border='0'></a>";


            // 返回成功訊息
            return response()->json([
                'message' => '序號: ' . $order_number . '<br><br>人數: ' . $count . '位<br>' . $lineLink
            ]);

        } catch (\Exception $e) {
            // 錯誤處理，返回錯誤訊息
            return response()->json(['error' => $e->getMessage()], 500);
        }

    }

    public function gatOrder(Request $request): \Illuminate\Http\JsonResponse
    {
        // 驗證驗證碼
        $rules = ['captcha' => 'required|captcha_api:' . request('key') . ',math'];
        $validator = validator()->make(request()->all(), $rules);
        if ($validator->fails()) {
            // 驗證碼錯誤，返回錯誤訊息
            return response()->json('驗證失敗', 500);
        }
        $id_card = $request->input('id_card');
        $phone = $request->input('phone');
        $email = $request->input('email');

// 驗證所有欄位必須輸入
        if (!$id_card || !$phone || !$email) {
            return response()->json([
                'status' => 'error',
                'message' => '請提供身分證號碼、電話號碼和電子郵件，所有欄位皆為必填'
            ], 400);
        }
// 使用 Laravel 的驗證器檢查格式
        $request->validate([
            'id_card' => 'required|string|size:10',
            'phone' => 'required|string|regex:/^09\d{8}$/',
            'email' => 'required|email',
        ]);

// 生成雜湊值
        $emailHash = hash('sha256', $email);
        $phoneHash = hash('sha256', $phone);
        $id_cardHash = hash('sha256', $id_card);

// 使用雜湊值查詢
        $applies = TripApply::where('email_hash', $emailHash)
            ->where('phone_hash', $phoneHash)
            ->where('id_card_hash', $id_cardHash) // 修正大小寫一致性
            ->get();

        $matchedApplies = [];

        $applies->each(function ($apply) use ($email, $phone, $id_card, &$matchedApplies) {
            if ($apply->email === $email && $apply->phone === $phone && $apply->id_card === $id_card) {
                $order = $apply->orders->first(); // 只取第一筆訂單
                if ($order) {
                    $times = $order->times()->whereDate('date_start', '>=', Carbon::today())->first();
                    if ($times) { // 檢查是否有符合日期條件的 TripTime
                        $date = $order->times()->whereDate('date_start', '>=', Carbon::today())
                            ->selectRaw(TripTime::getDateLogic())
                            ->first();
                        $trip = $times->trip;
                        $matchedApplies[] = [
                            'name' => "{$apply->name}",
                            'title' => "{$trip->title} ~ {$trip->subtitle}",
                            'times' => $date ? $date->dateAll : '無日期',
                            'orders' => "訂單編號: {$order->order_number}",
                            'status' => "狀態: " . config("order_statuses.{$order->status}.text", '未知') . config("order_statuses.{$order->status}.note", '')
                        ];
                    }
                }
            }
        });

        if (!empty($matchedApplies)) {
            return response()->json([
                $matchedApplies
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => '查無訂單'
            ], 404);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public
    function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        try {
            // 驗證收到的資料
            $request->validate([
                'trip_time' => 'required|string|max:100',
            ]);

            // 更新 session 中的 trip_time 值
            session(['trip_time' => $request->trip_time]);

            // 回傳成功的 JSON 回應
            return response()->json(['message' => session('trip_time')]);
        } catch (\Exception $e) {
            // 錯誤處理
            return response()->json(['error' => $e->getMessage()], 500);
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public
    function destroy(string $id)
    {
        //
    }
}
