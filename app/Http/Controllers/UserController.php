<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "username" => "required|string",
            "password" => "required|string"
        ]);

        if ($validator->fails()) {
            return response()->json(["status" => 0, "message" => $validator->errors()], 200);
        }

        if (filter_var($request->username, FILTER_VALIDATE_EMAIL)) {
            $user = User::where("email", $request->username)->where("password", $request->password)->get();

            if (count($user) > 0) {
                $token = $user->first()->createToken("Bearer");
                return response()->json(["status" => 1, "data" => ["token" => $token->plainTextToken, "token_type" => "Bearer"]], 200);
            }

            return response()->json(["status" => 0, "message" => "Login failed, username or password invalid"], 200);
        } else {
            $user = User::where("username", $request->username)->where("password", $request->password)->get();
            if (count($user) > 0) {
                $token = $user->first()->createToken("Bearer");
                return response()->json(["status" => 1, "data" => ["token" => $token->plainTextToken, "token_type" => "Bearer"]], 200);
            }

            return response()->json(["status" => 0, "message" => "Login failed, username or password invalid"], 200);
        }
    }

    public function account(Request $request)
    {
        return response()->json(["status" => 0, "data" => $request->user()], 200);
    }
}
