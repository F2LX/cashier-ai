<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Groceries;
use App\Models\FaceData;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (auth()->user()) {
            return redirect('/reset');
        }
        return view('index');
    }

    public function pay()
    {
        return view('pay');
    }
    public function pin()
    {
        return view('pin');
    }

    public function invoice() 
    {
        
        // Mengambil cart dari session
    $cart = session()->get('cart', []);

    // Ambil semua product_id dari cart
    $productIds = array_keys($cart);

    // Query efisien menggunakan whereIn untuk mengambil product_name dan price
    $products = Groceries::whereIn('id', $productIds)
        ->get(['id', 'product_name', 'price'])
        ->map(function($product) use ($cart) {
            return [
                'id' => $product->id,
                'product_name' => $product->product_name,
                'quantity' => $cart[$product->id]['quantity'],
                'price' => $product->price
            ];
        });

    // Menampilkan ke view dengan data products
    return view('invoice', compact('products'));
    }

    public function reset(Request $request) {
        // Logout the user
    Auth::logout();

    // Invalidate the current session
    $request->session()->invalidate();

    // Regenerate session token to prevent session fixation attacks
    $request->session()->regenerateToken();

    // Redirect user to the login page or any other page
    return redirect('/')->with('success', 'You have been successfully logged out.');
    }

    public function validatepin(Request $request) {
        $fullPin = $request->pin1 . $request->pin2 . $request->pin3 . $request->pin4 . $request->pin5 . $request->pin6;
    
        // Mendapatkan user yang terautentikasi
        $user = auth()->user();

        // Memeriksa kecocokan PIN
        if ($user && Hash::check($fullPin, $user->password)) {
            return redirect('/invoice');
        } else {
            // Tindakan ketika PIN tidak cocok
            return redirect('/pin')->with('error','Incorrect pin, please try again!');
        }
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
        

        // Simpan data wajah
        if ($request->hasFile('img')) {
            $file = $request->file('img');
            $imageContent = file_get_contents($file->getRealPath());
            $response = Http::post('http://127.0.0.1:5000/is-face-valid', [
                'image' => "data:image/jpeg;base64,".base64_encode($imageContent),
            ]);
            // Get the response body
            $flaskResponse = $response->json();

            // Check if face is valid
            if (isset($flaskResponse['valid']) && $flaskResponse['valid'] === false) {
                return redirect()->back()->with('error', 'No face detected or matched');
            }
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = bcrypt($request->password);
            $user->save();
            $imageContent = file_get_contents($file->getRealPath());
            $encodedImage = "data:image/jpeg;base64,".base64_encode($imageContent);

            $face = new FaceData();
            $face->user_id = $user->id;
            $face->img = $encodedImage;
            $face->save();
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
        if ($fromFlask['match']===true && isset($fromFlask['match'])) 
        {
            $user = User::find($fromFlask['user_id']);
            if ($user) {
                Auth::login($user, false);
            }
            return redirect('/products')->with('success','You can now start scanning items.');
        } else {
            return redirect()->back()->with("error","No face detected!");
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
