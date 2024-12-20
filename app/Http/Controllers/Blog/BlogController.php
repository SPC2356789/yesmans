<?php

namespace App\Http\Controllers\Blog;

use App\Http\Controllers\Controller;
use App\Models\Categories;
use App\Models\BlogItem;
use App\Models\Setting;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    protected $Settings;
    protected $Slug;

    public function __construct()
    {

        $this->Slug = 'Blog';
        $this->Settings = new Setting();
        $this->Categories = new Categories();
        $this->Items = new BlogItem();
    }

    /**
     * Display a listing of the resource.
     */


    public function index($key)
    {

//        $SEOData = $this->Settings->SEOdata($this->Slug);
        $Slug = $this->Slug;
        $urlSlug = $key;

        $Categories = $this->Categories->getData(1, 1, '*', 'slug');
        $items = $this->Items->getData($key);
//
//        dd($Items);
        $AllNames = array_keys(get_defined_vars());
        return response()
            ->view("Blog/" . $this->Slug, compact($AllNames));
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
