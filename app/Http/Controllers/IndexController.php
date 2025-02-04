<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\TripTime;
use App\Models\BlogItem;
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
        $Tags = $this->Trip->Tags();

        $Slug = $this->Slug;
        $jsPage = $this->jsPage;
        $tripTab = $this->tripBoard();
//        $tripData=$this->TripTime->getData('recent')->take(6);
        $tripData = [];

        foreach (array_keys($tripTab) as $tab) {
            $tripData[$tab] = $this->TripTime->getData($tab)->take(6)->get()->keyBy('uuid')->toArray();
        }
        $itinerary = [
            [
                'id' => 'all-tab',
                'tab' => '近期活動',
                'data' => [
                    ["uuid" => "1", "time" => '11/04~11/06', 'name' => "桃山", "img" => storage::url('poc1.jpg')],
                    ["uuid" => "2", "time" => '11/07~11/09', 'name' => "塔塔加", "img" => storage::url('poc2.jpg')],
                    ["uuid" => "3", "time" => '11/10~11/12', 'name' => "玉山", "img" => storage::url('poc3.jpg')],
                    ["uuid" => "4", "time" => '11/13~11/15', 'name' => "雪山", "img" => storage::url('poc4.jpg')],
                    ["uuid" => "5", "time" => '11/16~11/18', 'name' => "合歡山", "img" => storage::url('poc5.jpg')],
                    ["uuid" => "6", "time" => '11/19~11/21', 'name' => "阿里山", "img" => storage::url('poc1.jpg')]
                ]
            ],
            [
                'id' => 'soon-tab',
                'tab' => '即將成團',
                'data' => [
                    ["time" => '12/01~12/03', 'name' => "太平山"],
                    ["time" => '12/05~12/07', 'name' => "南湖大山"],
                    ["time" => '12/10~12/12', 'name' => "大霸尖山"],
                    ["time" => '12/15~12/17', 'name' => "雪山"],
                    ["time" => '12/15~12/17', 'name' => "雪山"],
                    ["time" => '12/20~12/22', 'name' => "大鬼湖"]
                ]
            ],
            [
                'id' => 'high',
                'tab' => '高山百岳',
                'data' => [
                    ["time" => '12/25~12/27', 'name' => "玉山"],
                    ["time" => '12/28~12/30', 'name' => "合歡山"],
                    ["time" => '01/05~01/07', 'name' => "雪山"],
                    ["time" => '12/15~12/17', 'name' => "雪山"],
                    ["time" => '01/10~01/12', 'name' => "中央山脈"],
                    ["time" => '01/15~01/17', 'name' => "大霸尖山"]
                ]
            ],
            [
                'id' => 'low',
                'tab' => '簡單郊山',
                'data' => [
                    ["time" => '11/22~11/24', 'name' => "鶯歌山"],
                    ["time" => '11/25~11/27', 'name' => "台北陽明山"],
                    ["time" => '12/01~12/03', 'name' => "象山"],
                    ["time" => '12/05~12/07', 'name' => "大溪山"],
                    ["time" => '12/15~12/17', 'name' => "雪山"],
                    ["time" => '12/10~12/12', 'name' => "九份山"]
                ]
            ],
            [
                'id' => 'more',
                'tab' => '更多',
                'data' => [
                    ["time" => '12/10~12/12', 'name' => "大霸尖山"],
                    ["time" => '01/10~01/12', 'name' => "南湖大山"],
                    ["time" => '01/15~01/17', 'name' => "玉山"],
                    ["time" => '12/15~12/17', 'name' => "雪山"],
                    ["time" => '01/20~01/22', 'name' => "雪山"],
                    ["time" => '02/01~02/03', 'name' => "合歡山"]
                ]
            ]
        ];


        $AllNames = array_keys(get_defined_vars());
        if (isset($_GET['t'])) {
            if ($_GET['t'] == 'a') {

                dd($tripData

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
