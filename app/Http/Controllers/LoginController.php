<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
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
}
