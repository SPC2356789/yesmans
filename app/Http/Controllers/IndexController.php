<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\TripTime;
use App\Models\BlogItem;
use App\Models\Trip;
use App\Models\Media;
use App\Models\IndexCarousel;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Blog\BlogController;
use App\Http\Controllers\Itinerary\ItryController;

class IndexController extends Controller
{
    protected string $Slug;
    private IndexCarousel $Carousel;

    private BlogController $BlogHot;

    private string $jsPage;
    private TripTime $TripTime;
    private ItryController $Trip;

    public function __construct()
    {

        parent::__construct(); // 確保繼承 Controller 的初始化邏輯
        $this->Slug = 'index';
        $this->jsPage = 'home';
        $this->Carousel = new IndexCarousel();
        $this->BlogHot = new BlogController();
        $this->Trip = new ItryController();
        $this->TripTime = new TripTime();
    }

    /**
     * Display a listing of the resource.
     */


    public function index(Request $request): \Illuminate\Http\Response
    {

        $SEOData = $this->Settings->SEOdata($this->Slug);

        $Carousels = $this->Carousel->getData();
        $BlogItems = $this->blogHot();
        $Media = $this->Media;
        $Tags = $this->Trip->Tags()->keyBy('id')->toArray();

        $Slug = $this->Slug;
        $jsPage = $this->jsPage;
        $tripTab = $this->tripBoard()->take(5)->pluck('name', 'slug')->toArray(); //取所有分類限定前五個

        $tripData = [];
//最多也就五筆，所以根據$cate一個類別抓，然後$tripData組合起來
        foreach (array_keys($tripTab) as $cate) {
            $tripData[$cate] =$this->TripTime->getData($cate)->take(6)->keyBy('uuid');
        }

//        $tta = $this->convertCarouselUrls($tripData);
        $AllNames = array_keys(get_defined_vars());
        if (isset($_GET['t'])) {
            if ($_GET['t'] == 'a') {

                dd($tripData, $tripTab

//                    ->toArray()
                );
//                dd($tripTab
//
//                );

            }

        }
        return response()
            ->view($this->Slug, compact($AllNames));
    }

// ⭐ 這個方法會轉換 `carousel` 陣列裡的 ID 為 URL
    private function convertCarouselUrls()
    {
        $trips = Trip::all()->map(function ($trip) {
            if (!is_array($trip->carousel)) {
                return $trip;
            }

            // 取得對應的 Media 圖片
            $mediaPaths = Media::whereIn('id', $trip->carousel)
                ->pluck('path', 'id');

            // 轉換成 Storage URL
            $trip->carousel = collect($trip->carousel)->map(fn($id) => Storage::url($mediaPaths[$id] ?? null))->toArray();

            return $trip;
        });


        dd($trips);


    }

    /**
     * 熱門文章
     */
    public function blogHot()
    {
        return $this->BlogHot->cutData()['hot'];
    }

    /**
     * 熱門文章
     */
    public function tripBoard()
    {
        return $this->Trip->Category();
    }
}
