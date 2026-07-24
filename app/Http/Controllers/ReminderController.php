<?php

namespace App\Http\Controllers;

use App\Models\Reminder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReminderController extends Controller
{

    public function index()
    {

        return Reminder::where('user_id',Auth::id())

            ->get()

            ->map(function($r){

                return [

                    'id'=>$r->id,

                    'title'=>$r->judul,

                    'start'=>$r->tanggal->format('Y-m-d'),

                    'color'=>$r->selesai ? '#16a34a' : '#2563eb'

                ];

            });

    }

    public function store(Request $request)
    {

        $request->validate([

            'judul'=>'required',

            'tanggal'=>'required|date',

            'catatan'=>'nullable'

        ]);

        Reminder::create([

            'user_id'=>Auth::id(),

            'judul'=>$request->judul,

            'catatan'=>$request->catatan,

            'tanggal'=>$request->tanggal

        ]);

        return back()->with('success','Reminder berhasil ditambahkan');

    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'judul' => 'required',
            'tanggal' => 'required|date',
            'catatan' => 'nullable',
        ]);

        $reminder = Reminder::findOrFail($id);

        $reminder->update([
            'judul'    => $request->judul,
            'catatan'  => $request->catatan,
            'tanggal'  => $request->tanggal,
            'selesai'  => $request->has('selesai'),
        ]);

        return redirect()->back()->with('success', 'Reminder berhasil diperbarui');
    }

    public function destroy($id)
    {

        Reminder::destroy($id);

        return back();

    }

    public function getByDate($tanggal)
    {
        return Reminder::where('user_id', auth()->id())
            ->whereDate('tanggal', $tanggal)
            ->first();
    }

}