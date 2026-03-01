<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskboardController;
use App\Http\Controllers\TicketingSystemController;
use App\Http\Controllers\ThreadTransactionController;
use App\Http\Controllers\InboxEmailController;
use App\Http\Controllers\JourneyController;
use App\Http\Controllers\TicketingDepartmentController;
use App\Http\Controllers\HistoryTicketingController;
use App\Http\Controllers\DataTableCustomerController;
use App\Http\Controllers\DataCustomerController;
use App\Http\Controllers\HistoryEmailController;
use App\Http\Controllers\DashboardEmailController;
use App\Http\Controllers\MonitoringEmailResponseController;
use App\Http\Controllers\DataAccessApplicationController;
use App\Http\Controllers\DataUserApplicationController;
use App\Http\Controllers\LevelUserApplicationController;
use App\Http\Controllers\ExportUserApplicationController;
use App\Http\Controllers\BantuDagangController;
use App\Http\Controllers\MenuApplicationController;
use App\Http\Controllers\SubMenuApplicationController;
use App\Http\Controllers\SetupChannelEmailController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('apps.taskboard');
});

Route::get('/home', function () {
    return redirect()->route('apps.taskboard');
})->name('home');

Route::get('/dashboard-email', [DashboardEmailController::class, 'index'])->name('dashboard.email');
Route::post('/dashboard-email/data', [DashboardEmailController::class, 'getData'])->name('dashboard.email.data');

Route::get('/monitoring-email-response', [MonitoringEmailResponseController::class, 'index'])->name('monitoring.email.response');
Route::post('/monitoring-email-response/data', [MonitoringEmailResponseController::class, 'getData'])->name('monitoring.email.response.data');

// Setup Channel Email Routes (9 Items)
Route::prefix('setup-channel-email')->name('setup-channel-email.')->group(function () {
    Route::get('/account-corporate', [SetupChannelEmailController::class, 'accountCorporate'])->name('account-corporate');
    Route::get('/data-signature', [SetupChannelEmailController::class, 'dataSignature'])->name('data-signature');
    Route::get('/filter-jumlah-hari', [SetupChannelEmailController::class, 'filterJumlahHari'])->name('filter-jumlah-hari');
    Route::get('/incoming-email', [SetupChannelEmailController::class, 'incomingEmail'])->name('incoming-email');
    Route::get('/jam-operasional', [SetupChannelEmailController::class, 'jamOperasional'])->name('jam-operasional');
    Route::get('/setting-agent', [SetupChannelEmailController::class, 'settingAgent'])->name('setting-agent');
    Route::get('/setting-auto-reply', [SetupChannelEmailController::class, 'settingAutoReply'])->name('setting-auto-reply');
    Route::get('/template-auto-reply', [SetupChannelEmailController::class, 'templateAutoReply'])->name('template-auto-reply');
    Route::get('/template-response', [SetupChannelEmailController::class, 'templateResponse'])->name('template-response');
});

// Setting Email System Routes (7 Items)
Route::prefix('setting-email-system')->name('setting-email-system.')->group(function () {
    Route::get('/accounts', [SetupChannelEmailController::class, 'emailAccounts'])->name('accounts');
    Route::get('/signature', [SetupChannelEmailController::class, 'emailSignature'])->name('signature');
    Route::get('/service', [SetupChannelEmailController::class, 'emailService'])->name('service');
    Route::get('/service-method', [SetupChannelEmailController::class, 'emailServiceMethod'])->name('service-method');
    Route::get('/server-profile', [SetupChannelEmailController::class, 'serverProfile'])->name('server-profile');
    Route::get('/server-protocol', [SetupChannelEmailController::class, 'serverProtocol'])->name('server-protocol');
    Route::get('/server-protocol-out', [SetupChannelEmailController::class, 'serverProtocolOut'])->name('server-protocol-out');
});

// Setting EPIC System Routes
Route::prefix('setting-epic-system')->name('setting-epic-system.')->group(function () {
    Route::get('/configuration', [SetupChannelEmailController::class, 'epicConfiguration'])->name('configuration');
});

Route::prefix('apps')->name('apps.')->group(function () {
    Route::get('/ticketing-department', [TicketingDepartmentController::class, 'index'])->name('ticketing-department');
    Route::get('/taskboard', [TaskboardController::class, 'index'])->name('taskboard');
    Route::get('/thread-system', [ThreadTransactionController::class, 'index'])->name('thread-system');
    Route::get('/ticketing', [TicketingSystemController::class, 'index'])->name('ticketing');
    Route::get('/history-ticketing', [HistoryTicketingController::class, 'index'])->name('history-ticketing');
});

Route::prefix('master-customer')->name('master-customer.')->group(function () {
    Route::get('/data-table', [DataTableCustomerController::class, 'index'])->name('data-table');
    Route::get('/data-customer', [DataCustomerController::class, 'index'])->name('data-customer');
});

Route::prefix('channel')->name('channel.')->group(function () {
    Route::prefix('email')->name('email.')->group(function () {
        Route::get('/inbox', [InboxEmailController::class, 'index'])->name('inbox');
        Route::post('/inbox/{id}/mark-read', [InboxEmailController::class, 'markAsRead'])->name('inbox.mark-read');
        Route::post('/inbox/{id}/spam', [InboxEmailController::class, 'moveToSpam'])->name('inbox.spam');
        Route::get('/history', [HistoryEmailController::class, 'index'])->name('history');
    });
});

Route::get('/journey', [JourneyController::class, 'index'])->name('journey.index');

Route::prefix('management-user')->name('management-user.')->group(function () {
    Route::get('/data-access-application', [DataAccessApplicationController::class, 'index'])->name('data-access-application');
    Route::get('/data-user-application', [DataUserApplicationController::class, 'index'])->name('data-user-application');
    Route::get('/level-user-application', [LevelUserApplicationController::class, 'index'])->name('level-user-application');
    Route::get('/level-user-application/counts', [LevelUserApplicationController::class, 'getCounts'])->name('level-user-application.counts');
    Route::get('/export-user-application', [ExportUserApplicationController::class, 'index'])->name('export.user.application');
    Route::post('/export-user-application/export', [ExportUserApplicationController::class, 'export'])->name('export.user.application.download');
});

// Bantu Dagang Feature


// Menu Application Feature
Route::get('/menu-application', [MenuApplicationController::class, 'index'])->name('menu.application');
Route::post('/menu-application/store', [MenuApplicationController::class, 'store'])->name('menu.application.store');
Route::get('/menu-application/{id}', [MenuApplicationController::class, 'show'])->name('menu.application.show');
Route::delete('/menu-application/{id}', [MenuApplicationController::class, 'destroy'])->name('menu.application.destroy');

// Sub Menu Application Feature
Route::get('/sub-menu-application', [SubMenuApplicationController::class, 'index'])->name('sub.menu.application');
Route::post('/sub-menu-application/store', [SubMenuApplicationController::class, 'store'])->name('sub.menu.application.store');
Route::get('/sub-menu-application/{id}', [SubMenuApplicationController::class, 'show'])->name('sub.menu.application.show');
Route::put('/sub-menu-application/{id}', [SubMenuApplicationController::class, 'update'])->name('sub.menu.application.update');
Route::delete('/sub-menu-application/{id}', [SubMenuApplicationController::class, 'destroy'])->name('sub.menu.application.destroy');

// Detail Menu Application Feature
Route::get('/detail-menu-application', [\App\Http\Controllers\DetailMenuApplicationController::class, 'index'])->name('detail.menu.application');
Route::post('/detail-menu-application/store', [\App\Http\Controllers\DetailMenuApplicationController::class, 'store'])->name('detail.menu.application.store');
Route::get('/detail-menu-application/{id}', [\App\Http\Controllers\DetailMenuApplicationController::class, 'show'])->name('detail.menu.application.show');
Route::put('/detail-menu-application/{id}', [\App\Http\Controllers\DetailMenuApplicationController::class, 'update'])->name('detail.menu.application.update');
Route::delete('/detail-menu-application/{id}', [\App\Http\Controllers\DetailMenuApplicationController::class, 'destroy'])->name('detail.menu.application.destroy');

// Ticket Notification System Feature
Route::get('/ticket-notification-system', [\App\Http\Controllers\TicketNotificationSystemController::class, 'index'])->name('ticket.notification.system');
Route::post('/ticket-notification-system/user', [\App\Http\Controllers\TicketNotificationSystemController::class, 'storeUser'])->name('ticket.notification.system.user.store');
Route::post('/ticket-notification-system/setting', [\App\Http\Controllers\TicketNotificationSystemController::class, 'updateSetting'])->name('ticket.notification.system.setting.update');

// Setting Channel Agent Feature
Route::get('/setting-channel-agent', [\App\Http\Controllers\SettingChannelAgentController::class, 'index'])->name('setting.channel.agent.index');
Route::post('/setting-channel-agent/store', [\App\Http\Controllers\SettingChannelAgentController::class, 'store'])->name('setting.channel.agent.store');
Route::put('/setting-channel-agent/{id}', [\App\Http\Controllers\SettingChannelAgentController::class, 'update'])->name('setting.channel.agent.update');
Route::delete('/setting-channel-agent/{id}', [\App\Http\Controllers\SettingChannelAgentController::class, 'destroy'])->name('setting.channel.agent.destroy');
