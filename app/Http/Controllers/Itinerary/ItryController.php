<?php

namespace App\Http\Controllers\Itinerary;

use App\Http\Controllers\Controller;

use App\Models\Categories;
use App\Models\TripTime;
use App\Models\Trip;
use Illuminate\Http\Request;

class ItryController extends Controller
{
    private Categories $Categories;
    protected string $Slug;
    private string $sidebarTitle;
    private TripTime $TripTime;
    private Trip $Trip;
    private int $Page;
    private string $secondSlug;
    /**
     * @var true
     */
    private bool $apply;


    public function __construct()
    {
        parent::__construct(); // 確保繼承 Controller 的初始化邏輯
        $this->Slug = 'itinerary';
        $this->secondSlug = 'trip';
        $this->TripTime = new TripTime();
        $this->Trip = new Trip();
        $this->Categories = new Categories();
        $this->sidebarTitle = "行程分類";
        $this->Page = 12;//一頁幾個
        $this->apply = true;//開啟報名
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, $key = null,): \Illuminate\Http\Response
    {
        $Categories = $this->Category();//取分類
        $sidebarTitle = $this->sidebarTitle;//分類的標
        $term = $request->input('term') ?? null;
        $Slug = $this->Slug;
        $secondSlug = $this->secondSlug;
        $apply = $this->apply;
        $Media = $this->Media;

        $tags = $this->Tags();
//        dd($tags);
        $months = true;//打開月份
        $MediaMlt = true;//此照片有無輪播
        $urlSlug = $key ?? 'recent';//設定即將成團為首項
//        $this->test($key);
        $items = $this->Trip->getData($urlSlug, $searchTerm ?? '')
            ->paginate($this->Page)->onEachSide(1);
        if (isset($_GET['t'])) {
            if ($_GET['t'] == 'a') {
                dd($items);
            }

        }
//        dd($items->toarray(),$tags);

        $AllNames = array_keys(get_defined_vars());
        return response()
            ->view("Itinerary/" . $Slug, compact($AllNames));


    }

    private function test($cate,)
    {
        $items = $this->Trip->getData($cate, $searchTerm ?? '');

        return $items;
    }

    public function Category(): array
    {
        $Categories = $this->Categories->getData(2, 1, '*', 'slug');
        return array_merge(['recent' => "近期活動", 'upcoming' => '即將成團'], $Categories);
    }

    public function Tags(): array
    {
        return $this->Categories->getData(2, 2, '*', 'slug',);
    }


}
