<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash; // パスワード暗号化に必須のクラス

class UserController extends Controller
{
    public function index()
    {
        // 最新順20件ずつのページネーション
        $users = User::latest()->paginate(20);
        return view('users.index', ['users' => $users]);
    }

    public function store(Request $request)
    {
        // 必須入力・形式チェック（バリデーション）
        $validated = $request->validate([
            'name'     => 'required|max:50',
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:8',
        ]);

        // 安全な User::create を使い、かつパスワードを Hash::make で暗号化
        User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        return redirect('/users');
    }
}
