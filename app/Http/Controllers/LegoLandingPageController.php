<?php

namespace App\Http\Controllers;

use App\Models\LegoSet;
use Illuminate\Routing\Controller as BaseController;

class LegoLandingPageController extends BaseController
{
    public function index(): \Illuminate\Http\Response
    {
        $legoSets = LegoSet::all();
        return \Response::view('lego.landing_page', ['legoSets' => $legoSets]);
    }
}
