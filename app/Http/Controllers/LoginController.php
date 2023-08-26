<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserRole;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule as ValidationRule;

class LoginController extends Controller
{
    public function login() {
        return view('login'); 
    }
    public function postLogin(Request $res){
        if (Auth::attempt(['email' => $res->email, 'password' => $res->password])) {
            if (auth()->user()->roles->role_id == 1) {
                return redirect()->route('adminIndex');
            }
            return redirect()->route('index');
        }
        else {
            return back()->withErrors(['error' => 'Tên đăng nhập hoặc mật khẩu không đúng!!!']);
        };
    }
    public function logout(){
        Auth::logout();
        return redirect()->route('index');
    }

    public function postSignup(Request $res) {
        $input = $res->all();

        request()->validate([
            'email' => 'required|email|unique:users,email',
            'name' => 'required|min:8',
            'password' => 'required|min:6'
           ]);

        $input['password'] = Hash::make($input['password']);
        $user = User::create($input);

        UserRole::create([
            'user_id' => $user->id,
            'role_id' => 2
        ]);

        return back()->with('success', 'Đăng kí thành công!');
    }

    public function signup() {
        return view('signup');
    }
}
