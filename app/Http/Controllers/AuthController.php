<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\FaceData;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('index');
    }

    public function pay()
    {
        return view('pay');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function product()
    {
        return view('products');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //  // Validasi input
        //  $validated = $request->validate([
        //     'name' => 'required|string|max:255',
        //     'email' => 'required|string|email|max:255|unique:users',
        //     'password' => 'required|string|min:8|confirmed',
        //     'img' => 'required|file|mimes:jpeg,png',
        // ]);

        // Buat pengguna baru
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->save();

        // Simpan data wajah
        if ($request->hasFile('img')) {
            $file = $request->file('img');

            $imageContent = file_get_contents($file->getRealPath());
            $encodedImage = "data:image/jpeg;base64,".base64_encode($imageContent);

            $face = new FaceData();
            $face->user_id = $user->id;
            $face->img = $encodedImage;
            $face->save();
        } else {
            dd($request);
        }

        return redirect()->back()->with('success', 'Registration successful.');
    }
    public function login(Request $request)
    {

        $file = $request->file('img');
        $imageContent = file_get_contents($file->getRealPath());
        $response = Http::post('http://127.0.0.1:5000/verify-faces', [
            'image1' => "data:image/jpeg;base64,".base64_encode($imageContent),
        ]);
    
        // Tangani respons dari Flask
        $fromFlask = $response->json();
        dd($fromFlask);
        if ($fromFlask['match']===true && isset($fromFlask['match'])) {
           
            Auth::login($fromFlask['user_id']);
        } else {
            return redirect()->back()->with("Error","Muka tidak terdeteksi.");
        }
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
