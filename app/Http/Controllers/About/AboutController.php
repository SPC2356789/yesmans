<?php

namespace App\Http\Controllers\About;

use App\Http\Controllers\Controller;
use App\Models\AboutMember;
use Illuminate\Http\Request;

class AboutController extends Controller
{
    private AboutMember $Members;
    private string $Slug;

    public function __construct()
    {

        parent::__construct(); // 確保繼承 Controller 的初始化邏輯
        $this->Slug = 'about';
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
