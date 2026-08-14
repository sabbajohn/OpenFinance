<?php

use App\Http\Controllers\InvitationController;
use App\Http\Controllers\PlatformController;
use Illuminate\Support\Facades\Route;

Route::inertia('/', 'Welcome')->name('home');
Route::get('/invitations/{token}', [InvitationController::class, 'show'])->name('invitations.show');
Route::post('/invitations/{token}', [InvitationController::class, 'accept'])->name('invitations.accept');

Route::middleware(['auth', 'verified', 'organization', 'sensitive.2fa', 'audit'])->group(function () {
    Route::get('dashboard', [PlatformController::class, 'dashboard'])->name('dashboard');
    Route::get('companies', [PlatformController::class, 'companies'])->name('companies.index');
    Route::post('companies', [PlatformController::class, 'storeCompany'])->name('companies.store');
    Route::get('members', [InvitationController::class, 'index'])->name('members.index');
    Route::post('members/invitations', [InvitationController::class, 'store'])->name('members.invitations.store');
    Route::get('bank-connections', [PlatformController::class, 'bankConnections'])->name('bank-connections.index');
    Route::post('bank-connections', [PlatformController::class, 'storeBankConnection'])->name('bank-connections.store');
    Route::patch('bank-connections/{bankConnection}', [PlatformController::class, 'updateBankConnection'])->name('bank-connections.update');
    Route::post('bank-connections/{bankConnection}/test', [PlatformController::class, 'testBankConnection'])->name('bank-connections.test');
    Route::post('bank-connections/{bankConnection}/sync', [PlatformController::class, 'sync'])->name('bank-connections.sync');
    Route::get('bank-accounts', [PlatformController::class, 'accounts'])->name('bank-accounts.index');
    Route::get('bank-transactions', [PlatformController::class, 'transactions'])->name('bank-transactions.index');
    Route::get('reconciliations', [PlatformController::class, 'reconciliations'])->name('reconciliations.index');
    Route::post('reconciliations/{reconciliation}/decisions', [PlatformController::class, 'decide'])->name('reconciliations.decide');
    Route::get('pix', fn (PlatformController $controller) => $controller->receivables('pix'))->name('pix.index');
    Route::post('pix', [PlatformController::class, 'storePix'])->name('pix.store');
    Route::post('pix/{receivable}/refund', [PlatformController::class, 'refundPix'])->name('pix.refund');
    Route::get('boletos', fn (PlatformController $controller) => $controller->receivables('boleto'))->name('boletos.index');
    Route::post('boletos', [PlatformController::class, 'storeBoleto'])->name('boletos.store');
    Route::patch('boletos/{receivable}', [PlatformController::class, 'updateBoleto'])->name('boletos.update');
    Route::post('boletos/{receivable}/cancel', [PlatformController::class, 'cancelBoleto'])->name('boletos.cancel');
    Route::post('receivables/{receivable}/refresh', [PlatformController::class, 'refreshReceivable'])->name('receivables.refresh');
    Route::get('erp-integrations', [PlatformController::class, 'erp'])->name('erp.index');
    Route::post('erp-integrations', [PlatformController::class, 'storeErpConnection'])->name('erp.store');
    Route::get('webhook-deliveries', [PlatformController::class, 'webhooks'])->name('webhooks.index');
    Route::get('audit', [PlatformController::class, 'audit'])->name('audit.index');
    Route::get('operations', [PlatformController::class, 'operations'])->name('operations.index');
});

require __DIR__.'/settings.php';
