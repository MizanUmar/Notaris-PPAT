<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ChecklistPersyaratan;

class ChecklistPersyaratanController extends Controller
{
    public function update(Request $request)
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        if ($user->role === 'client') {
            $client = $user->client;
            if (!$client) {
                return response()->json(['success' => false, 'message' => 'Profil client tidak ditemukan.'], 403);
            }
            $permintaan = PermintaanLayanan::where('id', $request->permintaan_id)
                ->where('client_id', $client->id)
                ->first();
            if (!$permintaan) {
                return response()->json(['success' => false, 'message' => 'Akses ditolak.'], 403);
            }
        }

        ChecklistPersyaratan::updateOrCreate(
            [
                'permintaan_id' => $request->permintaan_id,
                'persyaratan_id' => $request->persyaratan_id,
            ],
            [
                'status' => $request->status
            ]
        );

        return response()->json([
            'success' => true
        ]);
    }
}