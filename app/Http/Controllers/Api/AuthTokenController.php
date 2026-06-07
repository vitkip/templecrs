<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthTokenController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email'       => ['required', 'email'],
            'password'    => ['required', 'string'],
            'device_name' => ['sometimes', 'string', 'max:100'],
        ]);

        if (! Auth::attempt($request->only('email', 'password'))) {
            throw ValidationException::withMessages([
                'email' => ['ອີເມລ ຫຼື ລະຫັດຜ່ານບໍ່ຖືກຕ້ອງ.'],
            ]);
        }

        /** @var \App\Models\User $user */
        $user = $request->user();

        if (! $user->is_active) {
            Auth::logout();
            throw ValidationException::withMessages([
                'email' => ['ບັນຊີຂອງທ່ານຖືກລະງັບ.'],
            ]);
        }

        $deviceName = $request->input('device_name', $request->userAgent() ?? 'api-client');
        $token = $user->createToken($deviceName)->plainTextToken;

        return response()->json([
            'token'      => $token,
            'token_type' => 'Bearer',
            'user'       => [
                'id'    => $user->id,
                'name'  => $user->name,
                'email' => $user->email,
                'role'  => $user->role,
            ],
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'ອອກຈາກລະບົບສຳເລັດ.']);
    }

    public function me(Request $request): JsonResponse
    {
        $user = $request->user();

        return response()->json([
            'id'    => $user->id,
            'name'  => $user->name,
            'email' => $user->email,
            'role'  => $user->role,
        ]);
    }
}
