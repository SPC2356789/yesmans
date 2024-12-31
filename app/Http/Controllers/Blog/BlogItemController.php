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

class BlogItemController extends Controller
{
    protected Setting $Settings;
    protected string $Slug;
    private BlogItem $Items;
    private Categories $Categories;
    private BlogController $BlogHot;

    public function __construct()
    {

        $this->Slug = 'blog';
        $this->Settings = new Setting();
        $this->Categories = new Categories();
        $this->Items = new BlogItem();
        $this->BlogHot = new BlogController();
    }

    /**
     * Display a listing of the resource.
     */


    public function index($key, $itemSlug)
    {
        $Slug = $this->Slug;

        $items = json_decode($this->Items->getItem($itemSlug), true);
        $BlogItems= $this->blogHot();//熱門文章
        $Categories = $this->Categories->getData_mlt($items['category_id']);

//        $Categories = array_merge(['all' => "所有文章"], $Categories);
//        return $items;

        $AllNames = array_keys(get_defined_vars());

        return response()
            ->view("Blog.item", compact($AllNames));

    }

    /**
     * 熱門文章
     */
    public function blogHot()
    {
        return   $this->BlogHot->cutData()['hot'];
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
