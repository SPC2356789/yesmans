<?php

namespace App\Http\Controllers\Itinerary;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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
        $selectedTripTime = $items->trip_times->pluck('date', 'uuid');
        $uuid_default = $tripTime_uuid ?? $selectedTripTime->keys()->first(); //如果找不到uuid就預設第一個
        $trip_times = $items->trip_times->keyBy('uuid')->get($uuid_default)->toArray();
//        dd($trip_times);
//        dd($selectedTripTime);
//        dd($selectedTripTime);

        // 檢查是否存在，但不影響原始查詢
//        if ($trip_times !== null) {
//            $checkQuery = clone $trip_times; // 複製查詢
//            if (!$checkQuery->exists()) {
//                abort(404, 'Not Found');
//            }
//        }
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
     * Show the form for creating a new resource.
     */
    public
    function create()
    {
        //
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
                'trip_time' => 'required|string',
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
