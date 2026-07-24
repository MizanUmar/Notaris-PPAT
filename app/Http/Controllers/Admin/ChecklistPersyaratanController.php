<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ChecklistPersyaratan;

class ChecklistPersyaratanController extends Controller
{
    public function update(Request $request)
    {
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