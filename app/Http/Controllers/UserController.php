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
                return response()->json(["status" => 1, "data" => ["user" => $user->first(), "token" => $token->plainTextToken, "token_type" => "Bearer"]], 200);
            }

            return response()->json(["status" => 0, "message" => "Login failed, username or password invalid"], 200);
        } else {
            $user = User::where("username", $request->username)->where("password", $request->password)->get();
            if (count($user) > 0) {
                $token = $user->first()->createToken("Bearer");
                return response()->json(["status" => 1, "data" => ["user" => $user->first(), "token" => $token->plainTextToken, "token_type" => "Bearer"]], 200);
            }

            return response()->json(["status" => 0, "message" => "Login failed, username or password invalid"], 200);
        }
    }

    public function signup(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "name" => "required|string",
            "username" => "required|string",
            "email" => "required|email",
            "password" => "required|string",
        ]);

        if ($validator->fails()) {
            return response()->json(["status" => 0, "message" => $validator->errors()], 200);
        }

        if (filter_var($request->username, FILTER_VALIDATE_EMAIL)) {
            $user_c = User::where("email", $request->username)->get();

            if (count($user_c) > 0) {
                return response()->json(["status" => 0, "message" => "Email already exists"], 200);
            }
        } else {
            $user_c = User::where("username", $request->username)->get();

            if (count($user_c) > 0) {
                return response()->json(["status" => 0, "message" => "Email already exists"], 200);
            }
        }

        $user = new User;
        $user->name = $request->name;
        $user->username = $request->username;
        $user->role = "user";
        $user->images = null;
        $user->email = $request->email;
        $user->password = $request->password;
        $user->save();

        $token = $user->first()->createToken("Bearer");

        return response()->json(["status" => 1, "message" => "Successfully", "data" => ["user" => $user, "token" => $token->plainTextToken, "token_type" => "Bearer"]], 200);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json(["status" => 1, "data" => "Successfully"], 200);
    }

    public function account(Request $request)
    {
        return response()->json(["status" => 1, "data" => $request->user()], 200);
    }
}
