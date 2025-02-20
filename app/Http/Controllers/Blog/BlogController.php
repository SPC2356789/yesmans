<?php

namespace App\Http\Controllers\Blog;

use App\Http\Controllers\Controller;
use App\Models\Categories;
use App\Models\BlogItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use JetBrains\PhpStorm\NoReturn;
use RalphJSmit\Laravel\SEO\SchemaCollection;
use RalphJSmit\Laravel\SEO\Support\SEOData;

class BlogController extends Controller
{
    private string $Slug;
    private Categories $Categories;
    private BlogItem $Items;
    private int $Page;
    private string $sidebarTitle;
    private string $secondSlug;
    /**
     * @var false
     */
    private bool $MediaMlt;
    private string $urlSlug;

    public function __construct()
    {

        parent::__construct(); // 確保繼承 Controller 的初始化邏輯
        $this->sidebarTitle = "文章分類";
        $this->Slug = 'blog';
        $this->secondSlug = 'item';
        $this->Categories = new Categories();
        $this->Items = new BlogItem();
        $this->Page = 12;//一頁幾個
        $this->urlSlug = 'all';//初始化網址
        $this->MediaMlt = false;//一頁幾個
    }

    /**
     * Display a listing of the resource.
     */


    public function index(Request $request): \Illuminate\Http\Response
    {
        $key = $request->route('key');
        $term = $request->input('term') ?? null;
        $urlSlug = $key ?? $this->urlSlug;

        // 取得共用資料
        $commonData = $this->getCommonData($urlSlug, $term);
        extract($commonData);

        $AllNames = array_merge(array_keys(get_defined_vars()), array_keys($commonData)); //結合所有
        return response()
            ->view("Blog/" . $this->Slug, compact(array_merge($AllNames)));
    }

    public function search(Request $request, $k): string
    {
        $key = $request->input('key') ?? $k;
        $term = $request->input('term') ?? null;
//        session(['blog_term' => $term]); // 儲存查詢條件 20250220取消session
        $urlSlug = $key ?? $this->urlSlug;

        // 取得共用資料
        $commonData = $this->getCommonData($urlSlug, $term);
        extract($commonData);
        $AllNames = array_merge(array_keys(get_defined_vars()), array_keys($commonData)); //結合所有
        return view('Layouts.item_card', compact($AllNames))->render();
    }

    private function getCommonData($urlSlug, $term): array
    {
        return [
            'cutData' => $this->cutData(),
            'hot' => $this->cutData()['hot'],
            'Media' => $this->Media,
            'MediaMlt' => $this->MediaMlt, // 此照片有無輪播
            'Slug' => $this->Slug,
            'secondSlug' => $this->secondSlug,
            'sidebarTitle' => $this->sidebarTitle, // 分類標題
            'BlogItems' => $this->cutData()['hot'], // 熱門文章
            'items' => $this->getItems($urlSlug, $term),
            'Categories' => $this->Category($urlSlug),
            'SEOData' => $this->SEOdata($this->getItems($urlSlug, $term)),
            'params' => ['term' => $term], // 換頁參數
            'searchTerm' => session('blog_term', '*'),
        ];
    }

    private function getItems($urlSlug, $term)
    {
        return $this->Items->getData($urlSlug, $term ?? session('blog_term'))
            ->paginate($this->Page)
            ->onEachSide(1);
    }

    /**
     * 查詢分類
     */
    public function Category($cat = null): array
    {
        return $this->Categories->getData(1, 1, '*', 'slug', $cat);
    }

    /**
     * 點擊率接收
     */
    public function store(Request $request): void
    {
        $post_id = $request->input('id') ?? null;
        $id = explode('_', $post_id);
        BlogItem::active($id);

    }


    /**
     * 把hot與seo分離
     */
    public function cutData(): array
    {
        $data['hot'] = $this->getHot($this->Settings->getBase($this->Slug)['hot']);

        return $data;

    }

    /**
     * 取得熱門文章
     */
    public function getHot($data)
    {

        $hot = [];//初始化
        foreach ($data as $hotV) {

            $hot[] = $hotV['blogItem'];

        }
        $hot = array_map('intval', $hot);

        return $this->Items->getHot($hot);

    }

    public function SEOdata($items = null)
    {
        $Base = $this->Settings->getBase($this->Slug);
        $General = $this->Settings->getElseOrGeneral();
        $schema = $this->schema($items, "Article", $this->Slug);

        return $SEOData = new SEOData(
            title: $Base['seo.title'] ?? null, // 如果不存在，設置為 null
            description: $Base['seo.description'] ?? null,
            image: !empty($Base['OG.image']) ? $this->Settings->CheckProtocol(Storage::url($Base['OG.image'])) : null,
            url: request()->fullUrl(),
            tags: !empty($Base['seo.tag']) ? $Base['seo.tag'] : null,
            schema: $schema,
            site_name: $General['brand_name'] ?? null,
            favicon: !empty($General['favicon']) ? $this->Settings->CheckProtocol(Storage::url($General['favicon'])) : null,
            robots: $Base['seo.robots'] ?? null,
            openGraphTitle: $Base['OG.title'] ?? null
        );

    }

}
