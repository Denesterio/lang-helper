<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SaveUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function getUser(): UserResource
    {
        return new UserResource(Auth::user() ?? new User());
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    public function auth(Request $request): JsonResponse
    {
        $credentials = $request->validate([
            'name' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            return response()->json(['user' => Auth::user()]);
        }

        throw new \Exception('Не удалось авторизоваться');
    }

    public function logout(Request $request): JsonResponse
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return response()->json(['user' => new User()]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SaveUserRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();
    
            $user = User::create([
                'name' => $data['name'],
                'password' => Hash::make($data['password']),
            ]);
    
            if (Auth::attempt(['name' => $user->name, 'password' => $data['password']], $data['remember'])) {
                $request->session()->regenerate();
    
                return response()->json(['user' => Auth::user()]);
            } else {
                throw new \Exception('Не удалось авторизоваться');
            }
        } catch (\Throwable $e) {
            throw new \Exception('Ошибка регистрации');
        }
    }
}
