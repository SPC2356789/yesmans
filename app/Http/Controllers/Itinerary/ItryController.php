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
    /**
     * @var true
     */
    private bool $MediaMlt;


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
        $this->urlSlug = 'recent';//初始化網址
        $this->MediaMlt = true;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): \Illuminate\Http\Response
    {
        $key = $request->route('key');
        $months = true;//打開月份
        $sidebarTitle = $this->sidebarTitle;//分類的標
        $term = $request->input('term') ?? null;
        $Slug = $this->Slug;
        $secondSlug = $this->secondSlug;
        $apply = $this->apply;
        $Media = $this->Media;
        $MediaMlt = $this->MediaMlt;//此照片有無輪播
        $urlSlug = $key ?? $this->urlSlug;
        $Categories = $this->Category($urlSlug)->pluck('name', 'slug');//取分類
        $Categories_slug = $this->Category()->keyBy('slug')->toArray();
        $cat = $Categories_slug[$urlSlug]['id'];//把slug翻譯成id

//        $Categories_Array = is_array($Categories_id) ? array_values(array_filter(array_map(fn($t) => $Categories[$t]['id'] ?? null, $urlSlug))) : [];
        $tags = $this->Tags()->keyBy('id')->toArray();
        $items = $this->getItems($cat)->paginate($this->Page)->onEachSide(1);
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


    public function search(Request $request,): string
    {
        $Media = $this->Media;
        $div = 'button';//因共用結構所以要寫
        $MediaMlt = $this->MediaMlt;//此照片有無輪播
        $secondSlug = $this->secondSlug;
        $key = $request->input('key');;
        $term = $request->input('term') ?? null;
        $tag = $request->input('tag') ?? null;
        $month = $request->input('month') ?? null;
        $tags = $this->Tags()->keyBy('id')->toArray();
        $Categories_slug = $this->Category()->keyBy('slug')->toArray();
        $cat = $Categories_slug[$key]['id'];//把slug翻譯成id
        $tag_slug = $this->Tags()->keyBy('slug')->toArray();
        // 检查 $tag_slug 是否为空，若为空则返回空数组
        $tagArray = is_array($tag) ? array_values(array_filter(array_map(fn($t) => $tag_slug[$t]['id'] ?? null, $tag))) : [];

        session(['trip_term' => $term, 'tag' => $tagArray, 'month' => $month]);//儲存查詢
//        dd($tagArray);
        $items = $this->Trip->getData($cat, $term ?? session('trip_term'), session('tag') ?? '')->paginate($this->Page)->onEachSide(1);;
//        dd($items->toArray());
        $Slug = $this->Slug;
        $current_page = $items->currentPage();
        $last_page = $items->lastPage();
//        return $items;
        $searchTerm = session('trip_term', '*');

        $AllNames = array_keys(get_defined_vars());
        return view('Layouts.item_card', compact($AllNames))->render();

    }

    public function getItems($cate)
    {

        return $this->Trip->getData($cate, $searchTerm ?? '');
    }

    public function Category($chkUrl = null)
    {


        return $this->Categories->getData(2, 1, '*', 'id', $chkUrl);
//        return array_merge(['recent' => "近期活動", 'upcoming' => '即將成團'], $Categories);

    }

    public function Tags()
    {
        return $this->Categories->getData(2, 2, '*',);
    }


}
