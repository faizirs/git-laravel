<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User; // Model User untuk berinteraksi dengan tabel users
use Illuminate\Support\Facades\Hash; // Facade Hash untuk mengenkripsi password

class RegisterController extends Controller
{
    /**
     * 
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('register');
    }

    /**
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // Mengambil data dari input request.
        $nama_lengkap = $request->input('nama_lengkap'); 
        $email        = $request->input('email');        
        $password     = $request->input('password');     

        // Menampilkan data dari input
        $user = User::create([
            'name'      => $nama_lengkap,                
            'email'     => $email,                       
            'password'  => Hash::make($password)        
        ]);

        // Mengecek apakah pengguna berhasil dibuat
        if($user) {
            return response()->json([
                'success' => true,
                'message' => 'Register Berhasil!' // jika pendaftaran berhasil
            ], 201);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Register Gagal!'  //  jika pendaftaran gagal
            ], 400); 
        }

    }
}
