<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use RalphJSmit\Laravel\SEO\Support\SEOData;

class IndexController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $SEOData = new SEOData(
            title: env('APP_NAME'),
        description: 'Lorem Ipsum',
            locale: app()->getLocale()

        );
//        $Carousels=$this->Carousels();
        $Carousels="0";
//        $Facts=$this->Facts();
        $Facts="0";

        return response()
            ->view('index', compact('SEOData','Carousels','Facts'))//            ->header('X-Robots-Tag', 'index,follow')
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
