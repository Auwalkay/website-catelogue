<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{

    public function index(){
        $categories = Category::all();

        return $this->sendResponse($categories,'category list',200);
    }

    public function create(Request $request){
        $validator = Validator::make($request->all(),[
            'name'=>'required|unique:categories,name',
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.',$validator->errors());
        }

        $cateogry = Category::create([
            'name'=>$request->name,
        ]);

        return $this->sendResponse($cateogry,'category created',200);
    }


}
