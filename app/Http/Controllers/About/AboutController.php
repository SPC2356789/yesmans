<?php

namespace App\Http\Controllers\About;

use App\Http\Controllers\Controller;
use App\Models\AboutMember;
use App\Models\Setting;
use Illuminate\Http\Request;

class AboutController extends Controller
{
    protected $Settings;
    protected $Slug;

    public function __construct()
    {

        $this->Slug = 'about';
        $this->Settings = new Setting();
    }

    /**
     * Display a listing of the resource.
     */


    public function index()
    {

        $SEOData = $this->Settings->SEOdata($this->Slug);

//dd($SEOData);
        $Slug = $this->Slug;
        $story = $this->Settings->getElseOrGeneral($this->Slug);
//        dd($story);
        $AllNames = array_keys(get_defined_vars());
        return response()
            ->view("About/" . $this->Slug, compact($AllNames));
    }

}
