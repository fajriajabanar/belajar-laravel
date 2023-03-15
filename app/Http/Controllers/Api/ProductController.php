<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $product = Product::select('id','namaproduk','deskripsi','harga','gambar') ->get();
            return response()->json([
                'data' => $product
            ], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validasi = Validator::make($request->all(), [
            'namaproduk' => 'required',
            'deskripsi' => 'required',
            'harga' => 'required|integer',
            'gambar' => 'required|image|mimes:jpg,png|max:4096'
        ]);
        // Respon kalau validasi gagal
        if($validasi->fails()) {
         return response()->json($validasi->errors(), 422);
        }

        // simpan gambar ke storage
        $gambar = $request->file('gambar')->store('public/gambar');
        // simpan gambar ke database
        $data = Product::create([
            'namaproduk' => $request->namaproduk,
            'deskripsi' => $request->deskripsi,
            'harga' => $request->harga,
            'gambar' => $gambar
        ]);

        Log::info('user sedang menambahkan  data produk');

        return response()->json([
            "status" => "berhasil ditambahkan",
            "data" => $data
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = Product::select('id','namaproduk','deskripsi','harga','gambar') ->get();
        return response()->json([
            'data' => $product
            ->where('id', $id)
            ->first()
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validasi = Validator::make($request->all(), [
            'namaproduk' => 'required',
            'deskripsi' => 'required',
            'harga' => 'required|integer'
        ]);
        // Respon kalau validasi gagal
        if($validasi->fails()) {
         return response()->json($validasi->errors(), 422);
        }

        Product::where('id', $id)->update($request->all());

        Log::info('user sedang megedit  data produk');

        return response()->json([
            'status' => 'Update Sukses'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Product::where('id', $id)->first();
        // Menghapus gambar dari storage
        if ($product->gambar){
            Storage::delete($product->gambar);
        }
        // menghapus gambar dari database
        $product->delete();

        Log::info('user telah mengapus produk');

        return response()->json([
            'status' => 'Berhasil Dihapus'
        ],200);
    }
}
