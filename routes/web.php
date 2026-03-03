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
use App\Http\Controllers\SettingChannelAgentController;
use App\Http\Controllers\SettingAgentCallController;
use App\Http\Controllers\SettingAgentEmailController;
use App\Http\Controllers\MonitoringLoginController;
use App\Http\Controllers\RecordingController;
use App\Http\Controllers\ReportController;

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

// Setting Channel Email
Route::get('/dashboard-email', [\App\Http\Controllers\DashboardEmailController::class, 'index'])->name('dashboard.email');
Route::post('/dashboard-email/data', [DashboardEmailController::class, 'getData'])->name('dashboard.email.data');
Route::get('/monitoring-email-response', [\App\Http\Controllers\MonitoringEmailResponseController::class, 'index'])->name('monitoring.email.response');
Route::post('/monitoring-email-response/data', [MonitoringEmailResponseController::class, 'getData'])->name('monitoring.email.response.data');
Route::get('/setting-agent-email', [SettingAgentEmailController::class, 'index'])->name('setting.agent.email');

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

// Setting Channel Agent
Route::get('/setting-channel-agent', [\App\Http\Controllers\SettingChannelAgentController::class, 'index'])->name('setting.channel.agent.index');

// Setting Agent Call
Route::get('/setting-agent-call', [SettingAgentCallController::class, 'index'])->name('setting.agent.call');

// Monitoring Login
Route::get('/monitoring-login', [MonitoringLoginController::class, 'index'])->name('monitoring.login.index');
Route::post('/setting-channel-agent/store', [\App\Http\Controllers\SettingChannelAgentController::class, 'store'])->name('setting.channel.agent.store');
Route::put('/setting-channel-agent/{id}', [\App\Http\Controllers\SettingChannelAgentController::class, 'update'])->name('setting.channel.agent.update');
Route::delete('/setting-channel-agent/{id}', [\App\Http\Controllers\SettingChannelAgentController::class, 'destroy'])->name('setting.channel.agent.destroy');

// Master Data Routes
Route::resource('data-group-name', \App\Http\Controllers\DataGroupNameController::class);
Route::resource('data-fulfillment-location', \App\Http\Controllers\DataFulfillmentLocationController::class);
Route::resource('data-type', \App\Http\Controllers\DataTypeController::class);
Route::resource('data-category', \App\Http\Controllers\DataCategoryController::class);
Route::resource('data-meta', \App\Http\Controllers\DataMetaController::class);
Route::resource('data-sub-category', \App\Http\Controllers\DataSubCategoryController::class);
Route::resource('channel-ticket', \App\Http\Controllers\ChannelTicketController::class);
Route::resource('department-escalation-unit', \App\Http\Controllers\DepartmentEscalationUnitController::class);
Route::resource('data-source', \App\Http\Controllers\DataSourceController::class);
Route::resource('data-activity', \App\Http\Controllers\DataActivityController::class);
Route::resource('data-aux-reason', \App\Http\Controllers\DataAuxReasonController::class);
Route::resource('data-status-ticket', \App\Http\Controllers\DataStatusTicketController::class);
Route::resource('data-group-agent', \App\Http\Controllers\DataGroupAgentController::class);
Route::resource('data-brand-category', \App\Http\Controllers\DataBrandCategoryController::class);
Route::resource('data-fulfillment', \App\Http\Controllers\DataFulfillmentController::class);
Route::resource('data-holiday', \App\Http\Controllers\DataHolidayController::class);
Route::resource('data-brand-name', \App\Http\Controllers\DataBrandNameController::class);
Route::resource('data-max-handle', \App\Http\Controllers\DataMaxHandleController::class);
Route::resource('data-site', \App\Http\Controllers\DataSiteController::class);

Route::prefix('recording')->name('recording.')->group(function () {
    Route::get('/', [RecordingController::class, 'index'])->name('index');
});

Route::prefix('report')->name('report.')->group(function () {
    Route::get('/statistic-call', [ReportController::class, 'statisticCall'])->name('statistic-call');
    Route::get('/assign-email', [ReportController::class, 'assignEmail'])->name('assign-email');
    Route::get('/sl-nespresso', [ReportController::class, 'slNespresso'])->name('sl-nespresso');
    Route::get('/sl-kanmo', [ReportController::class, 'slKanmo'])->name('sl-kanmo');
    Route::get('/base-on-sla', [ReportController::class, 'baseOnSLA'])->name('base-on-sla');
    Route::get('/base-on-transaction', [ReportController::class, 'baseOnTransaction'])->name('base-on-transaction');
    Route::get('/base-on-staff', [ReportController::class, 'baseOnStaff'])->name('base-on-staff');
    Route::get('/thread-transaction', [ReportController::class, 'threadTransaction'])->name('thread-transaction');
    Route::get('/interaction-ticket', [ReportController::class, 'interactionTicket'])->name('interaction-ticket');
    Route::get('/agent-aux', [ReportController::class, 'agentAux'])->name('agent-aux');
    Route::get('/channel-email', [ReportController::class, 'channelEmail'])->name('channel-email');
    Route::get('/login-activity', [ReportController::class, 'loginActivity'])->name('login-activity');
});
