<?php

namespace App\Http\Controllers;

use App\Models\Santri;
use App\Models\User;
use App\Models\OrangTua;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class PortalAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('portal.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'no_hp' => 'required|string',
            'nis' => 'required|string',
        ]);

        // Cari Santri berdasarkan NIS
        $santri = Santri::where('nis', $request->nis)->first();

        if (!$santri || !$santri->orangTua) {
            return back()->withErrors(['error' => 'Data Santri atau Orang Tua tidak ditemukan. Periksa kembali NIS dan No. HP Anda.']);
        }

        $orangTua = $santri->orangTua;

        // Validasi kecocokan No HP (bisa No HP Ayah atau Ibu)
        if ($orangTua->no_hp_ayah !== $request->no_hp && $orangTua->no_hp_ibu !== $request->no_hp) {
            return back()->withErrors(['error' => 'Kombinasi NIS dan No. HP tidak cocok.']);
        }

        // Jika Orang Tua belum punya user_id, auto-provision
        if (!$orangTua->user_id) {
            $user = User::create([
                'name' => 'Orang Tua - ' . ($orangTua->nama_ayah ?? $orangTua->nama_ibu ?? 'Wali'),
                'email' => 'ortu_' . $request->nis . '_' . uniqid() . '@sipes.local',
                'password' => Hash::make($request->nis),
                'tenant_id' => $santri->tenant_id,
            ]);

            // Assign role
            Role::firstOrCreate(['name' => 'Orang Tua']);
            $user->assignRole('Orang Tua');

            // Update user_id
            $orangTua->update(['user_id' => $user->id]);
        } else {
            $user = $orangTua->user;
        }

        // Autentikasi User
        Auth::login($user);

        return redirect()->route('portal.dashboard')->with('success', 'Selamat datang di Portal Orang Tua SIPES.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('portal.login');
    }
}
