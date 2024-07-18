<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserWebsitesResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{

    public function login(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:6',
        ]);
        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){
            Auth::user()->tokens()->delete();
                $token = Auth::user()->createToken('website-catelogue');
                $data=[
                    'token'=>$token->plainTextToken,
                    'user'=>Auth::user()
                ];
                return $this->sendResponse($data,'User logged in successfully',200);
        }else{
            return $this->sendError('Unauthorised.', ['error'=>'Unauthorised'], 401);
        }
    }

    public function favorite_websites(Request $request){
        $websites = Auth::user()->favorites;

        return $this->sendResponse(UserWebsitesResource::collection($websites) ,'Favorite websites successfully',200);
    }

    public function add_to_favorite(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'website' => 'required|numeric|exists:websites,id',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        $favorite = Auth::user()->favorites()->where('website_id', $request->website)->first();

        if($favorite){
            return $this->sendError('Already added to favorite website.', [], 400);
        }

        $favorite = Auth::user()->favorites()->create([
            'website_id' => $request->website,
        ]);

        return $this->sendResponse(new UserWebsitesResource($favorite), 'Favorite website added successfully',200);
    }

    public function remove_from_favorite(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'website' => 'required|numeric|exists:websites,id',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        $favorite = Auth::user()->favorites()->where('website_id', $request->website)->first();

        if(!$favorite){
            return $this->sendError('Already removed from favorite website.', [], 400);
        }

        $favorite->delete();

        return $this->sendResponse($favorite, 'Favorite website removed successfully',200);
    }

    public function logout()
    {
        Auth::logout();

        return $this->sendResponse([],'User logged out successfully',200);
    }
}
