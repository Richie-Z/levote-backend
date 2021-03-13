<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Division;

class AuthController extends Controller
{
     public function __construct()
    {
        $this->middleware('auth');
    }
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 401);
        }
        if (Auth::attempt(['username' => $request->username, 'password' => $request->password])) {
            $user = Auth::user();
            $token = $user->createToken('token')->accessToken;
            return response()->json([
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' =>  now()->addDays(env('PERSONAL_ACCESS_TOKEN_EXPIRY__DAYS'))
            ], 200);
        } else {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json(['message' => 'Successfully logged out'], 200);
    }
    public function me()
    {
        return response()->json(auth()->user());
    }
    public function reset(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'old_password' => 'required',
            'new_password' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 401);
        }
        if (!password_verify($request->old_password, Auth::user()->password)) {
            return response()->json(['message' => 'old password did not match'], 422);
        }
        $user = User::find(Auth::user()->id);
        $user->password = bcrypt($request->new_password);
        $user->save();
        $request->user()->token()->revoke();
        return response()->json(['message' => 'reset success, user logged out'], 200);
    }
    //dummy
    public function dummyuser()
    {
        for ($i = 1; $i < 4; $i++) {
            $info = 'hr_' . $i;
            $user = new User;
            $user->username = $info;
            $user->password = bcrypt($info);
            $user->role = 'user';
            $user->division_id = 5;
            $user->save();
        }
        return "success";
    }
    public function dummydiv()
    {
        $div = new Division;
        $div->name = 'HR';
        $div->save();
        return "success";
    }
}
