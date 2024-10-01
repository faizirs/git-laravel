<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class LoginController extends Controller
{
    /**
     * 
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('login'); 
    }

    /**
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function check_login(Request $request)
    {
        // Mengambil data dari input request.
        $email      = $request->input('email');      
        $password   = $request->input('password');   

        // Mencoba melakukan autentikasi dengan email dan password yang diberikan.
        if(Auth::guard('web')->attempt(['email' => $email, 'password' => $password])) {
            return response()->json([              // jika autentikasi berhasil.
                'success' => true
            ], 200); 
        } else {
            return response()->json([              // jika autentikasi gagal.
                'success' => false,
                'message' => 'Login Gagal!'        // Pesan error yang ditampilkan jika login gagal.
            ], 401); 
        }

    }
}
