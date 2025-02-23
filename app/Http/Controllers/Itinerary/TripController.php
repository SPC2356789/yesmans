<?php

namespace App\Http\Controllers\Itinerary;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use function Laravel\Prompts\alert;
use App\Models\TripApply;
use App\Models\TripOrder;

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
    public function create(Request $request)
    {

        try {
            // 驗證驗證碼
            $rules = ['captcha' => 'required|captcha_api:' . request('key') . ',math'];
            $validator = validator()->make(request()->all(), $rules);

            if ($validator->fails()) {
                // 驗證碼錯誤，返回錯誤訊息
                return response()->json(['error' => 'NO PASS'], 500);
            }

            // 驗證其他資料
            $request->validate([
                'data' => 'required|array',
                'uuid' => 'required|string|max:100',
            ]);

            // 設定報名序號，時間戳 + 微秒 + 行程slug
            $order_number = (int)(microtime(true) * 1000) . $request->route('trip');
            $tripApplyId = [];
            $dataArray = $request['data'];
            $count=0;
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
                    'address' => $data['address'],
                    'PassPort' => $data['PassPort'],
                    'diet' => $data['diet'],
                    'experience' => $data['experience'],
                    'disease' => $data['disease'],
                    'LINE' => $data['LINE'],
                    'IG' => $data['IG'],
                    'emContactPh' => $data['emContactPh'],
                    'emContact' => $data['emContact'],
                ]);
                // 儲存每筆報名資料的 ID
                $tripApplyId[] = $tripApply->id;
                $count++; // 增加人數計數
            }

            // 儲存報名資料，包含報名的 ID 列表
            $tripOrder = TripOrder::create([
                'order_number' => $order_number,
                'trip_uuid' => $request['uuid'],
                'amount' => $request['amount'],
//                'applies' => json_encode($tripApplyId),  // 儲存報名 ID 的 JSON 格式
                'status' => 10,  // 報名的初始階段
            ]);
// 將 TripApply 關聯到 TripOrder
            $tripOrder->applies()->attach($tripApplyId);
            // 返回成功訊息
            return response()->json([
                'message' => '序號:' . $order_number . ' 報名成功<br> <br> 人數'.$count.'位',
            ]);

        } catch (\Exception $e) {
            // 錯誤處理，返回錯誤訊息
            return response()->json(['error' => $e->getMessage()], 500);
        }

    }

    /**
     * Store a newly created resource in storage.
     */
    public
    function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public
    function show(string $id)
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
