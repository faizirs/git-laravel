<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\Product; // Memanggil Model Product
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Storage; // Memanggil Facade Storage untuk menangani penyimpanan file
use Auth; // Memanngil Facade Auth untuk mengelola autentikasi pengguna

class ProductController extends Controller
{
    /**
     * Konstruktor untuk ProductController.
     * Menetapkan middleware 'auth' untuk memastikan pengguna harus login untuk mengakses controller ini.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * 
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $products = Product::latest()->paginate(10); // Mengambil produk terbaru dengan paginasi 10 item per halaman

        // Mengembalikan view 'product' dengan data produk
        return view('product', compact('products'));
    }

    /**
     * 
     * @return View
     */
    public function create(): View
    {
        return view('products.create'); // Mengembalikan view 'products.create' yang berisi form untuk membuat produk baru
    }

    /**
     * 
     * @param  Request $request
     * @return RedirectResponse
     */

    // Menyimpan produk baru ke database.
    public function store(Request $request): RedirectResponse
    {
        // Validasi form
        $request->validate([
            'image'         => 'required|image|mimes:jpeg,jpg,png|max:2048', 
            'title'         => 'required|min:5', 
            'description'   => 'required|min:10', 
            'price'         => 'required|numeric', 
            'stock'         => 'required|numeric'  
        ]);

        // Mengunggah gambar ke folder public/products
        $image = $request->file('image');
        $image->move(public_path('products'), $image->getClientOriginalName());

        // Menyimpan data produk ke database
        Product::create([
            'image'         => $image->getClientOriginalName(),  // Menyimpan nama file gambar
            'title'         => $request->title,
            'description'   => $request->description,
            'price'         => $request->price,
            'stock'         => $request->stock
        ]);

        // Redirect ke halaman index dengan pesan sukses
        return redirect()->route('product.index')->with(['success' => 'Produk berhasil ditambahkan!']);
    }

    /**
     * 
     * @param  string $id
     * @return View
     */
    public function show(string $id): View
    {
        $product = Product::findOrFail($id); // Mengambil produk berdasarkan ID, atau gagal jika tidak ditemukan

        // kembali ke halaman 'products.show' dengan data produk
        return view('products.show', compact('product'));
    }

    /**
     * 
     * @param  string $id
     * @return View
     */
    public function edit(string $id): View
    {
        $product = Product::findOrFail($id); // Mengambil produk berdasarkan ID, atau gagal jika tidak ditemukan

        // kembali ke halaman 'products.edit' dengan data produk
        return view('products.edit', compact('product'));
    }

    /**
     * 
     * @param  Request $request
     * @param  string $id
     * @return RedirectResponse
     */

     // mengupdate data produk berdasarkan ID.
    public function update(Request $request, $id): RedirectResponse
    {
        // Validasi form
        $request->validate([
            'image'         => 'image|mimes:jpeg,jpg,png|max:2048', 
            'title'         => 'required|min:5', 
            'description'   => 'required|min:10', 
            'price'         => 'required|numeric', 
            'stock'         => 'required|numeric'  
        ]);

        $product = Product::findOrFail($id); // Mengambil produk berdasarkan ID, atau gagal jika tidak ditemukan

        // Mengecek jika gambar baru diunggah
        if ($request->hasFile('image')) {
            // Mengunggah gambar baru
            $image = $request->file('image');
            $image->move(public_path('products'), $image->getClientOriginalName());
        
            // Menghapus gambar lama jika ada
            if ($product->image) {
                Storage::delete(public_path('products') . '/' . $product->image);
            }
        
            // Memperbarui produk dengan gambar baru
            $product->update([
                'image'         => $image->getClientOriginalName(),
                'title'         => $request->title,
                'description'   => $request->description,
                'price'         => $request->price,
                'stock'         => $request->stock
            ]);
        } else {
            // Memperbarui produk tanpa mengganti gambar
            $product->update([
                'title'         => $request->title,
                'description'   => $request->description,
                'price'         => $request->price,
                'stock'         => $request->stock
            ]);
        }

        // Redirect ke halaman index dengan pesan sukses
        return redirect()->route('product.index')->with(['success' => 'Data Berhasil Diubah!']);
    }

    /**
     * 
     * @param  string $id
     * @return RedirectResponse
     */

     // menghapus produk berdasarkan ID yang diambil.
    public function destroy($id): RedirectResponse
    {
        $product = Product::findOrFail($id); // Mengambil produk berdasarkan ID, atau gagal jika tidak ditemukan

        // menghapus gambar produk dari penyimpanan
        Storage::delete('public/products/'. $product->image);

        // menghapus produk dari database
        $product->delete();

        // Redirect ke halaman index dengan pesan sukses
        return redirect()->route('product.index')->with(['success' => 'Data Berhasil Dihapus!']);
    }

    /**
     * Menglogout pengguna dan mengalihkan ke halaman login.
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function logout(Request $request) {
        Auth::logout(); // Melakukan logout pengguna
        return redirect(route('login')); // Mengalihkan ke halaman login
    }
}
