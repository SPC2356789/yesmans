<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\IndexCarousel;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class IndexController extends Controller
{
    protected $Settings;
    protected $Slug;

    public function __construct()
    {

        $this->Slug = 'index';
        $this->Settings = new Setting();
        $this->Carousel = new IndexCarousel();
    }

    /**
     * Display a listing of the resource.
     */


    public function index(Request $request)
    {

        $SEOData = $this->Settings->SEOdata($this->Slug);

        $Carousels = $this->Carousel->getData();

        $Slug = $this->Slug;

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

        return response()
            ->view($this->Slug, compact($AllNames))

        ;
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
