<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Address;
use App\Models\UserProfile;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $profile = $user->profile ?? new UserProfile();
        $addresses = Address::where('user_id', $user->id)->get();
        $orders = [];

        return view('profile.index', compact('user', 'profile', 'addresses', 'orders'));
    }

    public function updateInfo(Request $request)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . Auth::id(),
        ]);

        Auth::user()->update([
            'name'  => $request->name,
            'email' => $request->email,
        ]);

        return back()->with('mensaje', 'Datos actualizados correctamente');
    }

    public function updatePassword(Request $request)
    {
        $validator = validator($request->all(), [
            'current_password' => 'required',
            'password'         => 'required|min:8|confirmed|different:current_password',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput()
                ->with('active_tab', 'password');
        }

        if (!Hash::check($request->current_password, Auth::user()->password)) {
            return back()
                ->withErrors(['current_password' => 'La contraseña actual no es correcta'])
                ->with('active_tab', 'password');
        }

        Auth::user()->update([
            'password' => Hash::make($request->password),
        ]);

        return back()
            ->with('mensaje', 'Contraseña actualizada correctamente')
            ->with('active_tab', 'password');
    }

    // Direcciones
    public function storeAddress(Request $request)
    {
        $request->validate([
            'street'      => 'required|string',
            'city'        => 'required|string',
            'postal_code' => 'required|string',
            'country'     => 'required|string',
            'state'       => 'nullable|string',
        ]);

        if ($request->is_default) {
            Address::where('user_id', Auth::id())->update(['is_default' => false]);
        }

        Address::create([
            'user_id'     => Auth::id(),
            'street'      => $request->street,
            'city'        => $request->city,
            'state'       => $request->state,
            'postal_code' => $request->postal_code,
            'country'     => $request->country,
            'is_default'  => $request->boolean('is_default'),
        ]);

        return back()->with('mensaje', 'Dirección añadida correctamente');
    }

    public function destroyAddress($id)
    {
        $address = Address::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $address->delete();
        return back()->with('mensaje', 'Dirección eliminada correctamente');
    }

    // Métodos de pago simulados
    public function storePaymentMethod(Request $request)
    {
        $request->validate([
            'card_holder' => 'required|string',
            'card_number' => 'required|digits:16',
            'expiry'      => 'required|string',
            'card_type'   => 'required|string',
        ]);

        $methods = session('payment_methods', []);
        $methods[] = [
            'id'          => uniqid(),
            'card_holder' => $request->card_holder,
            'last4'       => substr($request->card_number, -4),
            'expiry'      => $request->expiry,
            'card_type'   => $request->card_type,
        ];
        session(['payment_methods' => $methods]);

        return back()->with('mensaje', 'Método de pago añadido');
    }

    public function destroyPaymentMethod($id)
    {
        $methods = session('payment_methods', []);
        $methods = array_filter($methods, fn($m) => $m['id'] !== $id);
        session(['payment_methods' => array_values($methods)]);
        return back()->with('mensaje', 'Método de pago eliminado');
    }
}