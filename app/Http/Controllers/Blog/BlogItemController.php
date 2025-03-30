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

class BlogItemController extends Controller
{
    protected string $Slug;
    private BlogItem $Items;
    private Categories $Categories;
    private BlogController $BlogHot;

    public function __construct()
    {

        parent::__construct(); // 確保繼承 Controller 的初始化邏輯
        $this->Slug = 'blog';
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
        $Media = $this->Media;
        $items = json_decode($this->Items->getItem($itemSlug), true);
        $BlogItems = $this->blogHot();//熱門文章
        $Categories = $this->Categories->getData_mlt($items['category_id']);

        $SEOData = $this->SEOdata($items);

        $AllNames = array_keys(get_defined_vars());

        return response()
            ->view("Blog.item", compact($AllNames));

    }

    /**
     * 熱門文章
     */
    public function blogHot()
    {
        return $this->BlogHot->cutData()['hot'];
    }

    public function SEOdata($items = null)
    {


        $Base = $this->Settings->getBase($this->Slug);
        $General = $this->Settings->getElseOrGeneral();
        $schema = $this->schema($items, "Article", $this->Slug, $General);

        return $SEOData = new SEOData(
            title: $items['seo_title'] ?? null, // 如果不存在，設置為 null
            description: $items['seo_description'] ?? null,
            image: $this->Settings->CheckProtocol(Storage::url($items['media']['path'])),
            url: request()->fullUrl(),
            schema: $schema,
            site_name: $General['brand_name'] ?? null,
            favicon: Storage::url($General['favicon']),
            robots: $Base['seo.robots'] ?? null,
            openGraphTitle: $items['seo_title'] ?? null
        );

    }

    public function schema($item, $type = '', $slug = '', $General = ''): SchemaCollection
    {
        // 初始化 SchemaCollection（假設這是一個已定義的類）
        $schemaCollection = new SchemaCollection();

        // 如果 $items 不是陣列，轉為陣列處理單一項目


        // 遍歷每個項目生成 Schema

        // 基礎 Schema 結構
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => $type, // 預設為 Article
            'headline' => $item['title'] ?? '未提供標題',
            'author' => [
                '@type' => 'Person',
                'name' => $General['brand_name'] . '小編'
            ],
            'datePublished' =>  $this->formatDate(max(strtotime($item['published_at']), date('Y-m-d'))), // 預設今天
            'dateModified' => $this->formatDate(max(strtotime($item['published_at']), strtotime($item['updated_at']))),
            'image' => Storage::url($item['media']['path']),
            'articleBody' => $item['content'] ?? '未提供內文',
            'description' => $item['seo_description'] ?? '', // 預設截取前160字
            'mainEntityOfPage' => [
                '@type' => 'WebPage',
                '@id' => url($this->Slug . "/" . ($item['categories']['slug'] ?? '') . "/item/" . $item['slug'])
            ]
        ];

        // 如果提供了 $General，加入 publisher 資訊
        if (!empty($General)) {
            $schema['publisher'] = [
                '@type' => 'Organization',
                'name' => $General['brand_name'].'小編',
                'logo' => [
                    '@type' => 'ImageObject',
                    "url" => !empty($General['favicon']) ? $this->Settings->CheckProtocol(Storage::url($General['favicon'])) : null,
                ]
            ];
        }

        // 將生成的 Schema 加入集合
        $schemaCollection->add($schema);

        return $schemaCollection;
    }
}
