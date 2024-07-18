<?php

namespace App\Http\Controllers;

use App\Http\Resources\WebsiteResource;
use App\Models\Website;
use Illuminate\Http\Request;

class WebsiteController extends Controller
{

    public function index(Request $request){
        $websites = Website::query();
        if ($request->has('category')){
            $websites = $websites->where('category_id', $request->category);
        }else{
            $websites = $websites;
        }
        $websites=$websites->orderBy('name', 'asc')->get();

        return $this->sendResponse(WebsiteResource::collection( $websites), 'Website List',200);
    }
}
