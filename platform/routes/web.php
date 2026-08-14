<?php

use App\Http\Controllers\BankSandboxController;
use App\Http\Controllers\InvitationController;
use App\Http\Controllers\PlatformController;
use Illuminate\Support\Facades\Route;

Route::inertia('/', 'Welcome')->name('home');
Route::get('/invitations/{token}', [InvitationController::class, 'show'])->name('invitations.show');
Route::post('/invitations/{token}', [InvitationController::class, 'accept'])->name('invitations.accept');

Route::middleware(['auth', 'verified', 'organization', 'sensitive.2fa', 'audit'])->group(function () {
    Route::get('dashboard', [PlatformController::class, 'dashboard'])->middleware('organization.permission:dashboard.view')->name('dashboard');
    Route::get('companies', [PlatformController::class, 'companies'])->middleware('organization.permission:companies.view')->name('companies.index');
    Route::post('companies', [PlatformController::class, 'storeCompany'])->middleware('organization.permission:companies.manage')->name('companies.store');
    Route::get('members', [InvitationController::class, 'index'])->middleware('organization.permission:members.manage')->name('members.index');
    Route::post('members/invitations', [InvitationController::class, 'store'])->middleware('organization.permission:members.manage')->name('members.invitations.store');
    Route::patch('members/{user}/role', [InvitationController::class, 'updateRole'])->middleware('organization.permission:members.manage')->name('members.role.update');
    Route::delete('members/{user}', [InvitationController::class, 'destroy'])->middleware('organization.permission:members.manage')->name('members.destroy');
    Route::delete('members/invitations/{invitation}', [InvitationController::class, 'destroyInvitation'])->middleware('organization.permission:members.manage')->name('members.invitations.destroy');
    Route::get('bank-connections', [PlatformController::class, 'bankConnections'])->middleware('organization.permission:bank-connections.view')->name('bank-connections.index');
    Route::post('bank-connections', [PlatformController::class, 'storeBankConnection'])->middleware('organization.permission:bank-connections.manage')->name('bank-connections.store');
    Route::patch('bank-connections/{bankConnection}', [PlatformController::class, 'updateBankConnection'])->middleware('organization.permission:bank-connections.manage')->name('bank-connections.update');
    Route::post('bank-connections/{bankConnection}/test', [PlatformController::class, 'testBankConnection'])->middleware('organization.permission:bank-tests.run')->name('bank-connections.test');
    Route::post('bank-connections/{bankConnection}/sync', [PlatformController::class, 'sync'])->middleware('organization.permission:bank-sync.run')->name('bank-connections.sync');
    Route::get('sandbox', [BankSandboxController::class, 'index'])->middleware('organization.permission:bank-connections.view')->name('sandbox.index');
    Route::post('sandbox/runs', [BankSandboxController::class, 'store'])->middleware('organization.permission:bank-tests.run')->name('sandbox.runs.store');
    Route::get('bank-accounts', [PlatformController::class, 'accounts'])->middleware('organization.permission:financial.view')->name('bank-accounts.index');
    Route::get('bank-transactions', [PlatformController::class, 'transactions'])->middleware('organization.permission:financial.view')->name('bank-transactions.index');
    Route::get('reconciliations', [PlatformController::class, 'reconciliations'])->middleware('organization.permission:financial.view')->name('reconciliations.index');
    Route::post('reconciliations/{reconciliation}/decisions', [PlatformController::class, 'decide'])->middleware('organization.permission:reconciliation.approve')->name('reconciliations.decide');
    Route::get('pix', fn (PlatformController $controller) => $controller->receivables('pix'))->middleware('organization.permission:financial.view')->name('pix.index');
    Route::post('pix', [PlatformController::class, 'storePix'])->middleware('organization.permission:financial.operate')->name('pix.store');
    Route::post('pix/{receivable}/refund', [PlatformController::class, 'refundPix'])->middleware('organization.permission:financial.operate')->name('pix.refund');
    Route::get('boletos', fn (PlatformController $controller) => $controller->receivables('boleto'))->middleware('organization.permission:financial.view')->name('boletos.index');
    Route::post('boletos', [PlatformController::class, 'storeBoleto'])->middleware('organization.permission:financial.operate')->name('boletos.store');
    Route::patch('boletos/{receivable}', [PlatformController::class, 'updateBoleto'])->middleware('organization.permission:financial.operate')->name('boletos.update');
    Route::post('boletos/{receivable}/cancel', [PlatformController::class, 'cancelBoleto'])->middleware('organization.permission:financial.operate')->name('boletos.cancel');
    Route::post('receivables/{receivable}/refresh', [PlatformController::class, 'refreshReceivable'])->middleware('organization.permission:financial.operate')->name('receivables.refresh');
    Route::get('erp-integrations', [PlatformController::class, 'erp'])->middleware('organization.permission:erp-integrations.view')->name('erp.index');
    Route::post('erp-integrations', [PlatformController::class, 'storeErpConnection'])->middleware('organization.permission:erp-integrations.manage')->name('erp.store');
    Route::get('webhook-deliveries', [PlatformController::class, 'webhooks'])->middleware('organization.permission:webhooks.view')->name('webhooks.index');
    Route::get('audit', [PlatformController::class, 'audit'])->middleware('organization.permission:audit.view')->name('audit.index');
    Route::get('operations', [PlatformController::class, 'operations'])->middleware('organization.permission:operations.view')->name('operations.index');
});

require __DIR__.'/settings.php';
