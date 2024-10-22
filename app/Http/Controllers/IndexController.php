<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use RalphJSmit\Laravel\SEO\Support\SEOData;
use App\Models\Setting;
use App\Models\IndexCarousel;
use Illuminate\Support\Facades\Http;

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

        $Carousels = $this->Carousel->Carousels();

        $Slug = $this->Slug;
//        $request=$request;
        $AllNames = array_keys(get_defined_vars());
//dd($SEOData);
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
