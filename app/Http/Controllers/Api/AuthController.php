<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\RateLimiter;

class AuthController extends Controller
{



    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = $user->createToken('API Token')->accessToken;

        return response()->json([
            'status' => true,
            'message' => 'User registered successfully',
            'token' => $token,
            'user' => $user
        ], 201);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        if (!Auth::attempt([
            'email' => $request->email,
            'password' => $request->password
        ])) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid email or password'
            ], 401);
        }

        $user = Auth::user();

         // Delete all old tokens
        $user->tokens()->delete();

        $token = $user->createToken('API Token')->accessToken;

        return response()->json([
            'status' => true,
            'message' => 'Login successful',
            'token' => $token,
            'user' => $user
        ]);
    }

    public function profile(Request $request)
    {
        return response()->json([
            'status' => true,
            'user' => $request->user()
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();

        return response()->json([
            'status' => true,
            'message' => 'Logout successful'
        ]);
    }

   public function sendOtp(Request $request)
{
    $request->validate([
        'phone' => 'required|digits:10'
    ]);

    $phone = $request->phone;

    $user = User::firstOrCreate(
        ['phone' => $phone],
        [
            'name' => 'User_' . uniqid(),
            'password' => null
        ]
    );

    // Prevent OTP resend within 60 seconds
    if ($user->otp_sent_at) {
    $secondsPassed = $user->otp_sent_at->diffInSeconds(now());

    if ($secondsPassed < 60) {
        $remainingSeconds = 60 - $secondsPassed;

        return response()->json([
            'status' => false,
            'message' => "Please wait {$remainingSeconds} seconds before requesting another OTP"
        ], 429);
    }
}

    $otp = random_int(100000, 999999);

    $user->update([
        'otp' => Hash::make($otp),
        'otp_expires_at' => now()->addMinutes(5),
        'otp_sent_at' => now(),
    ]);

    try {
        $response = Http::timeout(10)
            ->withHeaders([
                'authorization' => trim(config('services.fast2sms.api_key')),
                'accept' => 'application/json',
            ])
            ->post('https://www.fast2sms.com/dev/bulkV2', [
                'route' => 'dlt',
                'sender_id' => 'JKCHNR',
                'message' => '165676',
                'variables_values' => $otp,
                'flash' => 0,
                'numbers' => $phone,
            ]);

        if (
            !$response->successful() ||
            ($response->json()['return'] ?? false) != true
        ) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to send OTP'
            ], 500);
        }

        return response()->json([
            'status' => true,
            'message' => 'OTP sent successfully'
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'status' => false,
            'message' => 'SMS service unavailable',
            'error' => $e->getMessage()
        ], 500);
    }
}

public function verifyOtp(Request $request)
{
    $request->validate([
        'phone' => 'required|digits:10',
        'otp' => 'required|digits:6'
    ]);

    $user = User::where('phone', $request->phone)->first();

    if (!$user) {
        return response()->json([
            'status' => false,
            'message' => 'User not found'
        ], 404);
    }

    if (!$user->otp || !$user->otp_expires_at) {
        return response()->json([
            'status' => false,
            'message' => 'Please request a new OTP'
        ], 400);
    }

    // Check OTP expiry first
    if (now()->greaterThan($user->otp_expires_at)) {
        $user->update([
            'otp' => null,
            'otp_expires_at' => null,
            'otp_sent_at' => null,
            'otp_attempts' => 0,
        ]);

        return response()->json([
            'status' => false,
            'message' => 'OTP expired. Please request a new OTP'
        ], 401);
    }

    // Limit attempts
    if ($user->otp_attempts >= 5) {
        return response()->json([
            'status' => false,
            'message' => 'Too many failed attempts. Please request a new OTP.'
        ], 429);
    }

    // Verify OTP
    if (!Hash::check($request->otp, $user->otp)) {
        $user->increment('otp_attempts');

        $remainingAttempts = 5 - ($user->otp_attempts + 1);

        return response()->json([
            'status' => false,
            'message' => 'Invalid OTP',
            'remaining_attempts' => max($remainingAttempts, 0)
        ], 401);
    }

    // OTP verified successfully
    $user->update([
        'otp' => null,
        'otp_expires_at' => null,
        'otp_sent_at' => null,
        'otp_attempts' => 0,
    ]);

    // Optional: revoke old tokens so only one device stays logged in
    $user->tokens()->delete();

    $token = $user->createToken('android-app')->accessToken;

    return response()->json([
        'status' => true,
        'message' => 'Login successful',
        'token' => $token,
        'user' => [
            'id' => $user->id,
            'name' => $user->name,
            'phone' => $user->phone,
        ]
    ]);
}

public function checkuser(){
    return "User Check Succeed";
}

public function updateProfile(Request $request){
    return "UpdateProfile";

}

}