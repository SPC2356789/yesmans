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

    public function __construct()
    {

        parent::__construct(); // 確保繼承 Controller 的初始化邏輯
        $this->sidebarTitle = "文章分類";
        $this->Slug = 'blog';
        $this->secondSlug = 'item';
        $this->Categories = new Categories();
        $this->Items = new BlogItem();
        $this->Page = 12;//一頁幾個
        $this->MediaMlt = false;//一頁幾個
    }

    /**
     * Display a listing of the resource.
     */


    public function index(Request $request,): \Illuminate\Http\Response
    {
        $key = $request->route('key');
        $cutData = $this->cutData();
        $hot = $cutData['hot'];
        $Media = $this->Media;
        $MediaMlt = $this->MediaMlt;//此照片有無輪播
        $term = $request->input('term') ?? null;
//        session('Term', '*');
        $searchTerm = $term;
        $Slug = $this->Slug;
        $secondSlug = $this->secondSlug;
        $sidebarTitle = $this->sidebarTitle;//分類的標
        $BlogItems = $this->cutData()['hot'];//熱門文章
        $urlSlug = $key ?? 'all';
        $keyUrl = ($key !== 'all' && $key !== null) ? $key : '*';
        $items = $this->Items->getData($keyUrl, $searchTerm ?? '')->paginate($this->Page)->onEachSide(1);//取資料
        $Categories = $this->Category();//取分類
        $SEOData = $this->SEOdata($items);
//        dd($this->Slug, $SEOData);
        $AllNames = array_keys(get_defined_vars());

        return response()
            ->view("Blog/" . $this->Slug, compact($AllNames));


    }

    public function Category(): array
    {
        $Categories = $this->Categories->getData(1, 1, '*', 'slug');
        return array_merge(['all' => "所有文章"], $Categories);
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
        $items = $this->Items->getData($keyUrl, $term ?? session('term'))->paginate($this->Page)->onEachSide(1);;
        $Slug = $this->Slug;
        $current_page = $items->currentPage();
        $last_page = $items->lastPage();
//        return $items;
        $searchTerm = session('term', '*');

        $AllNames = array_keys(get_defined_vars());
        return view('Layouts.item_card', compact($AllNames))->render();

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
