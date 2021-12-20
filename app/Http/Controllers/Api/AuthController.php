<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        if($request->has('image') && !empty($request['image'])){
            $file = base64_decode($request['image']);
            $imageName = uniqid() . '.png';
            $filePath = 'images/' . $imageName;
            
            if (Storage::disk('public')->put($filePath, $file)){
                $request['image'] = Storage::disk('public')->url($filePath);
            } else {
                return response(['message' => 'Gagal upload gambar'], 409);
            }
        }
        
        $registrationData = $request->all();
        $validate = Validator::make($registrationData, [
            'name' => 'required|max:60',
            'email' => 'required|email:rfc,dns|unique:users',
            'password' => 'required',
        ]);

        if($validate->fails())
            return response(['message' => $validate->errors()], 400);
        
        $registrationData['password'] = bcrypt($request->password);
        $user = User::create($registrationData);
        return response([
            'message' => 'Register Success',
            'user' => $user
        ], 200);
    }

    public function login(Request $request)
    {
        $loginData = $request->all();
        $validate = Validator::make($loginData, [
            'email' => 'required|email:rfc,dns',
            'password' => 'required'
        ]);

        if($validate->fails())
            return response(['message' => $validate->errors()], 400);

        if(!Auth::attempt($loginData))
            return response(['message' => 'Invalid Credentials'], 401);
        
        $user = Auth::user();
        $token = $user->createToken('Authentication Token')->accessToken;

        return response([
            'message' => 'Login Success',
            'user' => $user,
            'token_type' => 'Bearer',
            'access_token' => $token
        ]);
    }

    public function logout()
    {
        $user = Auth::user();
        if (!is_null($user)) {
            $token = Auth::user()->token();
            $token->revoke();
            return response([ 
                'message' => 'Logout Success',
            ], 200);
        } 
        
        return response([ 
            'message' => 'Logout Failed',
        ], 401);
    }
}

