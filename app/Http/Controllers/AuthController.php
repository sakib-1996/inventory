<?php

namespace App\Http\Controllers;


use App\Models\User;
use App\Helper\JWTToken;
use Illuminate\Http\Request;
use App\Helper\ResponseHelper;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function UserLogin(Request $request)
    {
        $UserEmail = $request->input("email");
        $password = $request->input('password');
        $user = User::where('email', $UserEmail)->first();

        if ($user && Hash::check($password, $user->password)) {
            $token = JWTToken::CreateToken($UserEmail, $user->id, $user->role);

            return ResponseHelper::Out('success', '', 200)->cookie('token', $token, 60 * 24 * 30);
        } else {
            return ResponseHelper::Out('fail', null, 401);
        }
    }

    function UserLogout()
    {
        cookie('token', '', -1);
    }

    public function VarifyNewUser(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|string',
                'otp' => 'required|string',
            ]);

            $email = $request->input('email');
            $otp = $request->input('otp');

            $user = User::where('email', $email)->where('otp', $otp)->first();

            if ($user) {
                User::where('email', '=', $email)->update(['otp' => '0']);
                $token = JWTToken::CreateToken($email, $user->id, $user->role);
                return ResponseHelper::Out('success', '', 200)->cookie('token', $token, 60 * 24 * 30);
            } else {
                return ResponseHelper::Out('fail', 'Invalid OTP or user not found', 404);
            }
        } catch (Exception $exception) {
            return ResponseHelper::Out('fail', ['error' => $exception->getMessage()], 422);
        }
    }

    public function CreatePasswordNewUser(Request $request)
    {
        try {
            $request->validate([
                'password' => 'required|string|min:3',
                'password_repeat' => 'required|string|same:password',
            ]);

            $userEmail = $request->header('email');
            $password = $request->input('password');

            if (!$userEmail) {
                return redirect()->route('login');
            }

            $hashedPassword = Hash::make($password);

            User::where('email', $userEmail)->update([
                'password' => $hashedPassword,
            ]);

            return ResponseHelper::Out('success', ['message' => 'Password updated successfully'], 200);
        } catch (Exception $exception) {
            return ResponseHelper::Out('fail', ['error' => $exception->getMessage()], 422);
        }
    }
}
