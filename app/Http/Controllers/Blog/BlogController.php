<?php

namespace App\Http\Controllers\Blog;

use App\Http\Controllers\Controller;
use App\Models\Categories;
use App\Models\BlogItem;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use JetBrains\PhpStorm\NoReturn;
use RalphJSmit\Laravel\SEO\SchemaCollection;
use RalphJSmit\Laravel\SEO\Support\SEOData;

class BlogController extends Controller
{
    protected $Settings;
    protected $Slug;

    public function __construct()
    {

        $this->Slug = 'blog';
        $this->Settings = new Setting();
        $this->Categories = new Categories();
        $this->Items = new BlogItem();
    }

    /**
     * Display a listing of the resource.
     */


    public function index(Request $request, $key = null,)
    {

        $term = $request->input('term') ?? null;
//        session('Term', '*');
        $searchTerm = $term;
        $keyUrl = ($key !== 'all' && $key !== null) ? $key : '*';
//        $SEOData = $this->Settings->SEOdata($this->Slug);
        $Slug = $this->Slug;
        $page = 4;
        $urlSlug = $key ?? 'all';
//        $page=redirect($request);
        $items = $this->Items->getData($keyUrl, $searchTerm ?? '')->paginate(4)->onEachSide(1);
//        if (request()->has('page') && request()->get('page') > $items->lastPage()) {
//            // 獲取路由中的 'key' 參數
//            $key = request()->route('key');
//
//            // 重定向到相同的路由，並將 'key' 和 'page' 參數一起傳遞
//            return redirect()->route($request->route()->getName(), ['key' => $key, 'page' => 1]);
//        }


        $Categories = $this->Categories->getData(1, 1, '*', 'slug', $searchTerm);
        $Categories = array_merge(['all' => "所有文章"], $Categories);
//        $SEOData = $this->SEOdata($this->Slug);
        $AllNames = array_keys(get_defined_vars());

        return response()
            ->view("Blog/" . $this->Slug, compact($AllNames));


    }

    public function search(Request $request, $k)
    {

        $key = $request->input('key') ?? $k;;
        $term = $request->input('term') ?? null;
//        $page = redirect($request);

        session(['Term' => $term]);//設置短會話

//        return $term;

        $keyUrl = ($key !== 'all' && $key !== null) ? $key : '*';
        $items = $this->Items->getData($keyUrl, $term ?? session('Term', '*'))->paginate(4)->onEachSide(1);;

        $current_page = $items->currentPage();
        $last_page = $items->lastPage();
//        return $items;
        $searchTerm = session('Term', '*');

        $AllNames = array_keys(get_defined_vars());
        return view('Blog.blog_items', compact($AllNames))->render();

    }



    public function redirect($request)
    {
        // 檢查是否是第一次進入（這裡使用 session 來記錄是否已訪問過）
        if (!session()->has('visited')) {
            // 如果是第一次訪問，將 page 設為其他值（例如第 2 頁）
            $page = 1;
            // 標記為已訪問過
            session(['visited' => true]);
        } else {
            $page = $request->input('page');
        }
        return $page;
    }

    public function SEOdata($where = 'index')
    {

//        dd(env('APP_DEV'));
        $Base = $this->getBase($where);
        $General = $this->getElseOrGeneral();

        $schemaCollection = new SchemaCollection();


        $array = json_decode($Base['seo.schema_markup'], true); // 第二个参数设为 true 以将其转换为关联数组
        $schemaCollection->add($array);

//        dd($array);
        return $SEOData = new SEOData(
            title: $Base['seo.title'] ?? null, // 如果不存在，設置為 null
            description: $Base['seo.description'] ?? null,
            image: !empty($Base['OG.image']) ? $this->CheckProtocol(Storage::url($Base['OG.image'])) : null,
            url: request()->fullUrl(),
            tags: !empty($Base['seo.tag']) ? $Base['seo.tag'] : null,
            schema: $schemaCollection,
            site_name: $General['brand_name'] ?? null,
            favicon: !empty($General['favicon']) ? $this->CheckProtocol(Storage::url($General['favicon'])) : null,
            robots: $Base['seo.robots'] ?? null,
            openGraphTitle: $Base['OG.title'] ?? null
        );

    }

    public function aaaa()
    {
        {
//            "@type": "ListItem",
//                "position": 1,
//                "url": "http://yesman.com/articles/mountain-hiking-tips",
//                "name": "登山技巧：如何開始你的冒險之旅",
//                "description": "一篇針對新手的登山入門指南，涵蓋基本裝備和安全提示。",
//                "datePublished": "2024-01-10",
//                "author": {
//            "@type": "Person",
//                    "name": "John Doe"
//                },
//                "image": "https://www.example.com/mountain-hiking.jpg"
//            },
//        {
//            "@type": "ListItem",
//                "position": 2,
//                "url": "http://yesman.com/articles/10-best-hiking-trails",
//                "name": "十大最佳登山路線推薦",
//                "description": "探索全球最值得挑戰的十大登山路線，從初學者到專家的選擇。",
//                "datePublished": "2024-02-15",
//                "author": {
//            "@type": "Person",
//                    "name": "Jane Smith"
//                },
//                "image": "https://www.example.com/best-hiking-trails.jpg"
//            }
        }

    }
}
