<?php

namespace App\Http\Controllers\Itinerary;

use App\Http\Controllers\Controller;

use App\Models\Categories;
use App\Models\TripTime;
use App\Models\Trip;
use Illuminate\Http\Request;
use JetBrains\PhpStorm\NoReturn;

class ItryController extends Controller
{
    protected Categories $Categories;
    protected string $Slug;
    private string $sidebarTitle;
    protected TripTime $TripTime;
    protected Trip $Trip;
    private int $Page;
    protected string $secondSlug;
    /**
     * @var true
     */
    private bool $apply;
    /**
     * @var string
     */
    private string $urlSlug;


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
        $this->urlSlug = 'recent';//開啟報名
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): \Illuminate\Http\Response
    {
        $key = $request->route('key');

        $Categories = $this->Category();//取分類
        $sidebarTitle = $this->sidebarTitle;//分類的標
        $term = $request->input('term') ?? null;
        $Slug = $this->Slug;
        $secondSlug = $this->secondSlug;
        $apply = $this->apply;
        $Media = $this->Media;

        $tags = $this->Tags();

        $months = true;//打開月份
        $MediaMlt = true;//此照片有無輪播
        $urlSlug = is_null($key) ? $this->urlSlug : $key;
        $items = $this->getItems($urlSlug)->paginate($this->Page)->onEachSide(1);
        if (isset($_GET['t'])) {
            if ($_GET['t'] == 'a') {
                dd($items);
            }

        }
        $AllNames = array_keys(get_defined_vars());
        return response()
            ->view("Itinerary/" . $Slug, compact($AllNames));
    }

    private function test($cate,)
    {
        $items = $this->Trip->getData($cate, $searchTerm ?? '');

        return $items;
    }


    public function search(Request $request, $k): string
    {
        $Media = $this->Media;
        $MediaMlt = $this->MediaMlt;//此照片有無輪播
        $secondSlug = $this->secondSlug;
        $key = $request->input('key') ?? $k;;
        $term = $request->input('term') ?? null;
        session(['term' => $term]);//儲存查詢
        $keyUrl = ($key !== 'all' && $key !== null) ? $key : '*';
        $items = $this->Trip->getData($keyUrl, $term ?? session('term'))->paginate($this->Page)->onEachSide(1);;
        $Slug = $this->Slug;
        $current_page = $items->currentPage();
        $last_page = $items->lastPage();
//        return $items;
        $searchTerm = session('term', '*');

        $AllNames = array_keys(get_defined_vars());
        return view('Layouts.item_card', compact($AllNames))->render();

    }

    public function getItems($cate)
    {
        return $this->Trip->getData($cate, $searchTerm ?? '')
            ;
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
