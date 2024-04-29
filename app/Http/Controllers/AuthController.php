<?php

namespace App\Http\Controllers;

use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function register()
    {
        return view('auth.register');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function login()
    {
        return view('auth.login');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function register_proses(Request $request)
    {
        $request->validate([
            //username harus diisi, berupa string, minimal 3 karakter dan bernilai unik di table m_user kolom username
            'username' => 'required|string|min:3|unique:m_user,username',
            'nama' => 'required|string|max:100',
            'password' => 'required|min:5',
            'level_id' => 'required|integer',
            'foto' => 'nullable|image',
        ]);

        $user = UserModel::all();
        
        $fileName = time().$request->file('foto')->getClientOriginalName();
        $path = $request->file('foto')->storeAs('images', $fileName, 'public');

        UserModel::create([
            'username'=> $request -> username,
            'nama'=> $request -> nama,
            'password' => bcrypt($request -> password),
            'activate' => 0,
            'foto' => $path,
            'level_id' => $request -> level_id,
        ]);
        
        return back()->with('success', 'Register Successfully');
    }

    /**
     * Display the specified resource.
     */
    public function login_proses(Request $request)
    {
        $auth = [
            'username' => $request->username,
            'password' => $request->password,
        ];

        if(Auth::attempt($auth)) {
            if (auth()->user()->activate == 0) {
                return back()->with('activate', 'Akun belum di aktivasi');
            }
            return redirect('beranda')->with('success', 'Login Berhasil');
        }

        return back()->with('error', 'Username or Password is wrong');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        return redirect('login');
    }
}
