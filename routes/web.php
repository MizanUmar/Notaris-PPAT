<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\LayananController;
use App\Http\Controllers\PermintaanLayananController;
use App\Http\Controllers\AktaController;
use App\Http\Controllers\SuratController;
use App\Http\Controllers\BukuTamuController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\ClientProfileController;
use App\Http\Controllers\ReminderController;
use App\Http\Controllers\Admin\ChecklistPersyaratanController;
use Illuminate\Support\Facades\Route;

// ==============================
// PUBLIC
// ==============================

Route::get('/', [LandingController::class, 'index'])->name('landing');

Route::get('/buku-tamu/scan', [BukuTamuController::class, 'showCheckInForm'])
    ->name('buku-tamu.checkin');

Route::post('/buku-tamu/store', [BukuTamuController::class, 'storeCheckIn'])
    ->name('buku-tamu.store');

// ==============================
// AUTH GUEST
// ==============================

Route::middleware('guest')->group(function () {

    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');

    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
});

Route::post(
    '/admin/checklist-persyaratan/update',
    [ChecklistPersyaratanController::class, 'update']
)->name('admin.checklist.update');

Route::get('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');


// ======================================================================
// ADMIN
// ======================================================================

Route::middleware(['auth', 'role:admin,notaris'])
    ->prefix('admin')
    ->group(function () {

        // ===========================================================
        // Dashboard
        // ===========================================================

        Route::get('/', [AdminDashboardController::class, 'index'])
            ->name('admin.dashboard');

        // ===========================================================
        // Reminder
        // ===========================================================

        Route::get('/reminder/events', [ReminderController::class, 'index'])->name('reminder.events');
        Route::post('/reminder', [ReminderController::class, 'store'])->name('reminder.store');
        Route::put('/reminder/{id}', [ReminderController::class, 'update'])->name('reminder.update');
        Route::delete('/reminder/{id}', [ReminderController::class, 'destroy'])->name('reminder.destroy');
        Route::get('/reminder/by-date/{tanggal}', [ReminderController::class, 'getByDate'])->name('reminder.byDate');

        // ===========================================================
        // Client
        // ===========================================================

        Route::prefix('clients')->group(function () {

            Route::get('/', [ClientController::class, 'index'])->name('admin.clients.index');
            Route::post('/store', [ClientController::class, 'store'])->name('admin.clients.store');
            Route::get('/show/{id}', [ClientController::class, 'show'])->name('admin.clients.show');
            Route::post('/update/{id}', [ClientController::class, 'update'])->name('admin.clients.update');
            Route::post('/delete/{id}', [ClientController::class, 'destroy'])->name('admin.clients.destroy');
        });

        // ===========================================================
        // Layanan
        // ===========================================================

        Route::prefix('layanan')->group(function () {

            Route::get('/', [LayananController::class, 'index'])->name('admin.layanan.index');

            Route::post('/store', [LayananController::class, 'store'])->name('admin.layanan.store');
            Route::post('/update/{id}', [LayananController::class, 'update'])->name('admin.layanan.update');
            Route::post('/delete/{id}', [LayananController::class, 'destroy'])->name('admin.layanan.destroy');

            Route::post('/{layananId}/persyaratan/store', [LayananController::class, 'storePersyaratan'])->name('admin.layanan.persyaratan.store');
            Route::post('/persyaratan/update/{id}', [LayananController::class, 'updatePersyaratan'])->name('admin.layanan.persyaratan.update');
            Route::post('/persyaratan/delete/{id}', [LayananController::class, 'destroyPersyaratan'])->name('admin.layanan.persyaratan.destroy');

            Route::post('/{layananId}/informasi/store', [LayananController::class, 'storeInformasi'])->name('admin.layanan.informasi.store');
            Route::post('/informasi/update/{id}', [LayananController::class, 'updateInformasi'])->name('admin.layanan.informasi.update');
            Route::post('/informasi/delete/{id}', [LayananController::class, 'destroyInformasi'])->name('admin.layanan.informasi.destroy');
        });

        // ===========================================================
        // Permintaan Layanan
        // ===========================================================

        Route::prefix('permintaan')->group(function () {

            Route::get('/', [PermintaanLayananController::class, 'adminIndex'])->name('admin.permintaan.index');

            Route::get('/show/{id}', [PermintaanLayananController::class, 'adminShow'])->name('admin.permintaan.show');

            Route::post('/update-status/{id}', [PermintaanLayananController::class, 'adminUpdateStatus'])->name('admin.permintaan.update-status');

            Route::post('/delete/{id}', [PermintaanLayananController::class, 'adminDestroy'])->name('admin.permintaan.destroy');
        });

        // ===========================================================
        // PERMINTAAN AKTA
        // ===========================================================

        Route::get(
            '/permintaan-akta',
            [AktaController::class, 'permintaanAkta']
        )
            ->name('admin.permintaan-akta');

        // ===========================================================
        // AKTA
        // ===========================================================

        Route::prefix('akta')->group(function () {

            Route::get(
                '/',
                [AktaController::class, 'index']
            )
                ->name('admin.akta.index');

            Route::get(
                '/create/{permintaan}',
                [AktaController::class, 'create']
            )
                ->name('admin.akta.create');

            Route::post(
                '/store',
                [AktaController::class, 'store']
            )
                ->name('admin.akta.store');

            Route::post(
                '/update/{id}',
                [AktaController::class, 'update']
            )
                ->name('admin.akta.update');

            Route::post(
                '/delete/{id}',
                [AktaController::class, 'destroy']
            )
                ->name('admin.akta.destroy');

            Route::get(
                '/preview/{id}',
                [AktaController::class, 'preview']
            )
                ->name('admin.akta.preview');

            // routes/web.php — di dekat rute akta lainnya
            Route::get('/admin/akta/edit/{id}', [AktaController::class, 'edit'])->name('admin.akta.edit');
        });

        Route::get(
            '/permintaan-surat',
            [SuratController::class, 'permintaanSurat']
        )
            ->name('admin.permintaan-surat');

        // ===========================================================
        // Surat
        // ===========================================================

        Route::prefix('surat')->group(function () {

            Route::get('/', [SuratController::class, 'index'])->name('admin.surat.index');
            Route::get('/create/{permintaan}', [SuratController::class, 'create'])->name('admin.surat.create');
            Route::post('/store', [SuratController::class, 'store'])->name('admin.surat.store');
            Route::post('/update/{id}', [SuratController::class, 'update'])->name('admin.surat.update');
            Route::post('/delete/{id}', [SuratController::class, 'destroy'])->name('admin.surat.destroy');
            Route::get('/preview/{id}', [SuratController::class, 'preview'])->name('admin.surat.preview');
            // routes/web.php
            Route::get('/admin/surat/edit/{id}', [SuratController::class, 'edit'])->name('admin.surat.edit');
        });

        // ===========================================================
        // Buku Tamu
        // ===========================================================

        Route::prefix('buku-tamu')->group(function () {

            Route::get('/', [BukuTamuController::class, 'adminIndex'])->name('admin.buku-tamu.index');

            Route::get('/qr', [BukuTamuController::class, 'adminShowQr'])->name('admin.buku-tamu.qr');

            Route::post('/delete/{id}', [BukuTamuController::class, 'adminDestroy'])->name('admin.buku-tamu.destroy');
        });

        // ===========================================================
        // Profil Kantor
        // ===========================================================

        Route::prefix('profil')->group(function () {

            Route::get('/edit', [LandingController::class, 'editProfil'])->name('admin.profil.edit');

            Route::post('/update', [LandingController::class, 'updateProfil'])->name('admin.profil.update');
        });
    });

// ======================================================================
// CLIENT
// ======================================================================

Route::middleware(['auth', 'role:client'])
    ->prefix('client')
    ->group(function () {

        Route::get(
            '/',
            [PermintaanLayananController::class, 'clientDashboard']
        )
            ->name('client.dashboard');

        Route::prefix('permintaan')->group(function () {

            Route::get('/', [PermintaanLayananController::class, 'clientIndex'])->name('client.permintaan.index');

            Route::get('/create', [PermintaanLayananController::class, 'clientCreate'])->name('client.permintaan.create');

            Route::post('/store', [PermintaanLayananController::class, 'clientStore'])->name('client.permintaan.store');

            Route::get('/show/{id}', [PermintaanLayananController::class, 'clientShow'])->name('client.permintaan.show');

            Route::post('/{id}/upload-dokumen', [PermintaanLayananController::class, 'clientUploadDokumen'])->name('client.permintaan.upload-dokumen');
        });

        Route::post(
            '/dokumen/delete/{id}',
            [PermintaanLayananController::class, 'clientDeleteDokumen']
        )
            ->name('client.dokumen.delete');

        Route::prefix('biodata')->group(function () {

            Route::get('/edit', [ClientProfileController::class, 'edit'])->name('client.biodata.edit');

            Route::post('/update', [ClientProfileController::class, 'update'])->name('client.biodata.update');
        });

        Route::get(
            '/persyaratan',
            [PermintaanLayananController::class, 'clientPersyaratan']
        )
            ->name('client.persyaratan.index');

        Route::prefix('akta')->group(function () {
            Route::get('/', [AktaController::class, 'clientIndex'])->name('client.akta.index');
            Route::get('/preview/{id}', [AktaController::class, 'clientPreview'])->name('client.akta.preview');
        });

        Route::prefix('surat')->group(function () {
            Route::get('/', [SuratController::class, 'clientIndex'])->name('client.surat.index');
            Route::get('/preview/{id}', [SuratController::class, 'clientPreview'])->name('client.surat.preview');
        });
    });
    