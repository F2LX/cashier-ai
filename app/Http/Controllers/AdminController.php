<?php

namespace App\Http\Controllers;

use App\Models\Groceries;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.index');
    }

    public function addindex()
    {
        return view('admin.addproduct');
    }

    public function manage()
    {
        $items=Groceries::all();
        return view('admin.manage',compact('items'));
    }

    public function delete($id)
    {
        // Misalnya kamu ingin menghapus produk berdasarkan ID
        $product = Groceries::find($id);
    
        if ($product) {
            $product->delete();
            return redirect()->back()->with('success', 'Produk berhasil dihapus.');
        } else {
            return redirect()->back()->with('error', 'Produk tidak ditemukan.');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function showProducts()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
       
        // Handle file upload
        if ($request->hasFile('thumbnail')) {
            // Simpan file ke dalam folder 'thumbnails' di storage
            $path = $request->file('thumbnail')->store('thumbnails', 'public');
        } else {
            $path = null;
        }

        // Simpan data produk ke dalam database
        $product = new Groceries;
        $product->product_name = $request->input('product_name');
        $product->price = $request->input('price');
        $product->class = $request->input('class');
        $product->thumbnail = $path; // Simpan path thumbnail
        $product->save();

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
