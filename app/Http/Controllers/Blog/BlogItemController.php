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


    public function index($key, $itemSlug)
    {
        $Slug = $this->Slug;
        $items = json_decode($this->Items->getItem($key, $itemSlug), true);

//        return $items;
        $AllNames = array_keys(get_defined_vars());

        return response()
            ->view("Blog.item", compact($AllNames));
    }
}
