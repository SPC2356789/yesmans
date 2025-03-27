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
    protected string $urlSlug;
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
        $term = $request->input('term') ?? null;
        $tag = $request->input('tag') ? explode(',', $request->input('tag')) : null;
        $urlSlug = $key ?? $this->urlSlug;
        // 取得共用資料
        $commonData = $this->getCommonData($urlSlug, $term, $tag);
        extract($commonData);

        // 建立網址參數
        $params = ['term' => $term];
        if (!empty($tag)) {
            $params['tag'] = implode(',', (array)$tag);
        }

        if (request()->get('t') === 'a') {
            dd($cat, $term, $tagArray, $items);
        }


        $AllNames = array_merge(array_keys(get_defined_vars()), array_keys($commonData)); //結合所有
//       dd($Slug);
        return response()
            ->view("Itinerary/" . $Slug, compact($AllNames));
    }

    public function search(Request $request): string
    {
        $key = $request->input('key');
        $term = $request->input('term') ?? null;
        $tag = $request->input('tag') ?? null;
        $urlSlug = $key ?? $this->urlSlug;
        $params = ['term' => $term];
        if (!empty($tag)) {
            $params['tag'] = implode(',', (array)$tag);
        }
        // 取得共用資料
        $commonData = $this->getCommonData($urlSlug, $term, $tag);
        extract($commonData);
        $AllNames = array_merge(array_keys(get_defined_vars()), array_keys($commonData)); //結合所有
        return view('Layouts.item_card', compact($AllNames))->render();
    }

    private function getCommonData($key=null, $term=null, $tag=null): array
    {
        $tags = $this->Tags()->keyBy('id')->toArray();
        $tag_slug = $this->Tags()->keyBy('slug')->toArray();
        // 轉換 `tag` slug 成 `id`
        $tagArray = is_array($tag)
            ? array_values(array_filter(array_map(fn($t) => $tag_slug[$t]['id'] ?? null, $tag)))
            : ($tag ? [$tag] : []);

        $Categories_slug = $this->Category()->keyBy('slug')->toArray();
        $cat = $Categories_slug[$key]['id'] ?? null; // 把 slug 轉換成 id

        return [
            'months' => true,
            'sidebarTitle' => $this->sidebarTitle,
            'Slug' => $this->Slug,
            'secondSlug' => $this->secondSlug,
            'apply' => $this->apply,
            'Media' => $this->Media,
            'MediaMlt' => $this->MediaMlt,
            'Categories' => $this->Category($key)->pluck('name', 'slug'),
            'tags' => $tags,
            'tagArray' => $tagArray,
            'cat' => $cat,
            'items' => $this->getItems($cat, $term, $tagArray)->paginate($this->Page)->onEachSide(1),
        ];
    }


    public function getItems($cat, $term, $tagArray)
    {

        return $this->Trip->getData($cat, $term, $tagArray);
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
