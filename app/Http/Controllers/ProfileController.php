<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use App\Mail\OTPMail;
use Illuminate\Http\Request;
use App\Helper\ResponseHelper;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    public function UsrList(Request $request)
    {
        $role = $request->header('role');
        if ($role !== "admin") {
            return ResponseHelper::Out('fail', 'authorized', 403);
        }
        $user = User::all();
        return ResponseHelper::Out('success',  $user, 200);
    }


    public function CreateProfile(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'firstName' => 'required|string|min:2',
                'lastName' => 'required|string|min:2',
            ]);
            if ($validator->fails()) {
                return ResponseHelper::Out('fail', ['errors' => $validator->errors()], 422);
            }
            $email = $request->header('email');
            $firstName = $request->input('firstName');
            $lastName = $request->input('lastName');

            User::where('email', $email)->update([
                'firstName' => $firstName,
                'lastName' => $lastName,
            ]);

            $user = User::where('email', $email)->first();
            return ResponseHelper::Out('success',  $user, 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'fail',
                'message' => 'Something Went Wrong',
            ], 500);
        }
    }

    public function ReadProfile(Request $request): JsonResponse
    {
        $user_id = $request->header('id');
        $data = User::where('id', $user_id)->first();
        return ResponseHelper::Out('success', $data, 200);
    }

    public function createUser(Request $request)
    {
        try {
            $role = $request->header('role');
            if ($role !== "admin") {
                return ResponseHelper::Out('fail', ['role' => 'You are not authorized for this page'], 403);
            }

            $request->validate([
                'email' => 'required|string|unique:users',
                'mobile' => 'required|string|unique:users',
                'role' => 'required|string',
                'firstName' => 'required|string|min:2',
                'lastName' => 'required|string|min:2',
            ]);

            // Generate OTP and create user data
            $otp = rand(1000, 9999);
            $userData = [
                'email' => $request->input('email'),
                'mobile' => $request->input('mobile'),
                'role' => $request->input('role'),
                'otp' => $otp,
                'firstName' => $request->input('firstName'),
                'lastName' => $request->input('lastName'),
            ];

            // Send OTP via email
            Mail::to($userData['email'])->send(new OTPMail($otp));

            // Create user
            $user = User::create($userData);

            // Return success response
            return ResponseHelper::Out('success', ['user' => $user], 201);
        } catch (Exception $exception) {
            // Return fail response with error message
            return ResponseHelper::Out('fail', ['error' => $exception->getMessage()], 422);
        }
    }
}
