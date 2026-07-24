<?php

namespace App\Http\Controllers;

use App\Models\Layanan;
use App\Models\PersyaratanDokumen;
use App\Models\InformasiLayanan;
use Illuminate\Http\Request;

class LayananController extends Controller
{
    public function index()
    {
        $layanan = Layanan::with(['persyaratan', 'informasi'])->orderBy('nama_layanan', 'asc')->get();
        return view('admin.layanan.index', compact('layanan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_layanan' => 'required|string|max:100',
            'deskripsi' => 'required|string',
            'estimasi_waktu' => 'required|string|max:100',
            'status_aktif' => 'required|boolean',
        ]);

        Layanan::create($request->all());

        return redirect()->route('admin.layanan.index')->with('success', 'Layanan berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $layanan = Layanan::findOrFail($id);

        $request->validate([
            'nama_layanan' => 'required|string|max:100',
            'deskripsi' => 'required|string',
            'estimasi_waktu' => 'required|string|max:100',
            'status_aktif' => 'required|boolean',
        ]);

        $layanan->update($request->all());

        return redirect()->route('admin.layanan.index')->with('success', 'Layanan berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $layanan = Layanan::findOrFail($id);
        $layanan->delete();

        return redirect()->route('admin.layanan.index')->with('success', 'Layanan berhasil dihapus!');
    }

    // Persyaratan Dokumen CRUD per Layanan
    public function storePersyaratan(Request $request, $layananId)
    {
        $request->validate([
            'nama_dokumen' => 'required|string|max:100',
            'keterangan' => 'nullable|string',
        ]);

        PersyaratanDokumen::create([
            'layanan_id' => $layananId,
            'nama_dokumen' => $request->nama_dokumen,
            'keterangan' => $request->keterangan,
        ]);

        return back()->with('success', 'Persyaratan dokumen berhasil ditambahkan!');
    }

    public function updatePersyaratan(Request $request, $id)
    {
        $persyaratan = PersyaratanDokumen::findOrFail($id);
        
        $request->validate([
            'nama_dokumen' => 'required|string|max:100',
            'keterangan' => 'nullable|string',
        ]);

        $persyaratan->update($request->only(['nama_dokumen', 'keterangan']));

        return back()->with('success', 'Persyaratan dokumen berhasil diperbarui!');
    }

    public function destroyPersyaratan($id)
    {
        $persyaratan = PersyaratanDokumen::findOrFail($id);
        $persyaratan->delete();

        return back()->with('success', 'Persyaratan dokumen berhasil dihapus!');
    }

    // Informasi Layanan CRUD per Layanan
    public function storeInformasi(Request $request, $layananId)
    {
        $request->validate([
            'judul' => 'required|string|max:150',
            'isi_informasi' => 'required|string',
            'tanggal' => 'required|date',
        ]);

        InformasiLayanan::create([
            'layanan_id' => $layananId,
            'judul' => $request->judul,
            'isi_informasi' => $request->isi_informasi,
            'tanggal' => $request->tanggal,
        ]);

        return back()->with('success', 'Informasi layanan berhasil ditambahkan!');
    }

    public function updateInformasi(Request $request, $id)
    {
        $informasi = InformasiLayanan::findOrFail($id);

        $request->validate([
            'judul' => 'required|string|max:150',
            'isi_informasi' => 'required|string',
            'tanggal' => 'required|date',
        ]);

        $informasi->update($request->only(['judul', 'isi_informasi', 'tanggal']));

        return back()->with('success', 'Informasi layanan berhasil diperbarui!');
    }

    public function destroyInformasi($id)
    {
        $informasi = InformasiLayanan::findOrFail($id);
        $informasi->delete();

        return back()->with('success', 'Informasi layanan berhasil dihapus!');
    }
}
