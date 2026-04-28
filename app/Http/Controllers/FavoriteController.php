<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request; // Ya está aquí
use Illuminate\View\View;

class FavoriteController extends Controller
{
    // Añadimos (Request $request) al método igual que en tu carrito
    public function index(Request $request): View
    {
        // Ahora usamos $request->user() en lugar de Auth::user()
        $favoriteItems = $request->user()
            ->favoriteLists()
            ->with('product.category')
            ->get();

        return view('favorites.index', compact('favoriteItems'));
    }

    // app/Http/Controllers/FavoriteController.php

    public function toggle(Request $request, $productId)
    {
        $user = $request->user();
        
        // Buscamos si ya existe el favorito
        $favorite = $user->favoriteLists()->where('product_id', $productId)->first();

        if ($favorite) {
            $favorite->delete();
            $mensaje = 'Eliminado de favoritos.';
        } else {
            $user->favoriteLists()->create([
                'product_id' => $productId
            ]);
            $mensaje = 'Añadido a favoritos.';
        }

        return back()->with('mensaje', $mensaje);
    }

}