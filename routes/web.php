<?php

use Illuminate\Support\Facades\Route;
use Modules\Schooling\Http\Controllers\SchoolingController;
use Modules\Schooling\Livewire\GraduationList;
use Modules\Schooling\Livewire\GraduationStudent;
use Modules\Schooling\Livewire\PpdbList;
use Modules\Schooling\Livewire\PpdbApplicant;
use Modules\Schooling\Livewire\PpdbParentData;
use Modules\Schooling\Livewire\PpdbDocument;

use Modules\Schooling\Livewire\Ppdb;
use Modules\Schooling\Livewire\PpdbData;

Route::get('/kelulusan/cek/{year}', [SchoolingController::class, 'graduationForm'])->name('graduation.form');
Route::post('/kelulusan/hasil', [SchoolingController::class, 'graduationCheck'])->name('graduation.check');
Route::post('/kelulusan/pdf', [SchoolingController::class, 'graduationPdf'])->name('graduation.pdf');

Route::get('/ppdb/registrasi', [SchoolingController::class, 'registrasi'])->name('ppdb.registrasi');
Route::post('/ppdb/registrasi/success', [SchoolingController::class, 'saveregister'])->name('ppdb.store');
Route::get('/ppdb/{id}', [SchoolingController::class, 'success'])->name('ppdb.success');

Route::get('/ppdb/online/{slug}', Ppdb::class)->name('ppdb.wizard');

Route::get('/ppdb/registration/{id}/pdf', [SchoolingController::class, 'pdf'])->name('ppdb.registration.pdf');

// Route::get('/graduation/2025', [SchoolingController::class, 'graduationstatus'])->name('graduation');

// Route::middleware(['auth', 'verified'])->group(function () {
//     Route::resource('schoolings', SchoolingController::class)->names('schooling');
// });

Route::middleware(['auth', 'verified'])->group(function () {
    // Route::resource('corporations', CorporationController::class)->names('corporation');

    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('ppdb', PpdbList::class)->name('ppdb.index');
        Route::get('ppdb/{year}', PpdbData::class)->name('ppdb.student');
        Route::get('graduation', GraduationList::class)->name('graduation.index');
        Route::get('graduation/{year}', GraduationStudent::class)->name('graduation.student');

        // Route::get('announ/create', AnnouncementCreate::class)->name('announcement.create');

        // Route::get('reports', ReportIndex::class)->name('report.index');
        // Route::get('reports/create', ReportCreate::class)->name('report.create');
        // Route::get('stocks', StockIndex::class)->name('stock.index');

    });
});
