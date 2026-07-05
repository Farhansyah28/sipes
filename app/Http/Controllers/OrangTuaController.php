<?php

namespace App\Http\Controllers;

use App\Models\OrangTua;
use Illuminate\Http\Request;

class OrangTuaController extends Controller
{
    public function index()
    {
        $orang_tuas = OrangTua::paginate(10);
        return view('orang_tua.index', compact('orang_tuas'));
    }

    public function create()
    {
        return view('orang_tua.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_ayah' => 'nullable|string|max:255',
            'nama_ibu' => 'nullable|string|max:255',
            'no_hp_ayah' => 'nullable|string|max:20',
            'no_hp_ibu' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
        ]);

        OrangTua::create($validated);
        return redirect()->route('orang_tua.index')->with('success', 'Data Orang Tua berhasil ditambahkan.');
    }

    public function edit(OrangTua $orang_tua)
    {
        return view('orang_tua.edit', compact('orang_tua'));
    }

    public function update(Request $request, OrangTua $orang_tua)
    {
        $validated = $request->validate([
            'nama_ayah' => 'nullable|string|max:255',
            'nama_ibu' => 'nullable|string|max:255',
            'no_hp_ayah' => 'nullable|string|max:20',
            'no_hp_ibu' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
        ]);

        $orang_tua->update($validated);
        return redirect()->route('orang_tua.index')->with('success', 'Data Orang Tua berhasil diperbarui.');
    }

    public function destroy(OrangTua $orang_tua)
    {
        $orang_tua->delete();
        return redirect()->route('orang_tua.index')->with('success', 'Data Orang Tua berhasil dihapus.');
    }
}
