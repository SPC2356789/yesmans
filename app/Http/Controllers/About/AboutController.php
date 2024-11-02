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
        $this->Members = new AboutMember();
    }

    /**
     * Display a listing of the resource.
     */


    public function index()
    {

        $SEOData = $this->Settings->SEOdata($this->Slug);
        $Slug = $this->Slug;
        $members = $this->Members->getData();

        $stories = $this->Settings->getElseOrGeneral($this->Slug);
//        dd($members);
        $AllNames = array_keys(get_defined_vars());
        return response()
            ->view("About/" . $this->Slug, compact($AllNames));
    }

}
