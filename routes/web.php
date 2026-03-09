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
Route::get('/setting-agent-email/data', [SettingAgentEmailController::class, 'getData'])->name('setting.agent.email.data');

// Setup Channel Email Routes (9 Items)
Route::prefix('setup-channel-email')->name('setup-channel-email.')->group(function () {
    Route::get('/account-corporate', [SetupChannelEmailController::class, 'accountCorporate'])->name('account-corporate');
    Route::get('/account-corporate/data', [SetupChannelEmailController::class, 'getAccountCorporateData'])->name('account-corporate.getData');
    
    Route::get('/data-signature', [SetupChannelEmailController::class, 'dataSignature'])->name('data-signature');
    Route::get('/data-signature/data', [SetupChannelEmailController::class, 'getDataSignature'])->name('data-signature.getData');

    Route::get('/filter-jumlah-hari', [SetupChannelEmailController::class, 'filterJumlahHari'])->name('filter-jumlah-hari');
    Route::get('/filter-jumlah-hari/data', [SetupChannelEmailController::class, 'getFilterJumlahHari'])->name('filter-jumlah-hari.getData');
    
    Route::get('/incoming-email', [SetupChannelEmailController::class, 'incomingEmail'])->name('incoming-email');
    Route::get('/incoming-email/data', [SetupChannelEmailController::class, 'getIncomingEmailData'])->name('incoming-email.getData');
    
    Route::get('/jam-operasional', [SetupChannelEmailController::class, 'jamOperasional'])->name('jam-operasional');
    Route::get('/jam-operasional/data', [SetupChannelEmailController::class, 'getJamOperasional'])->name('jam-operasional.getData');
    
    Route::get('/setting-agent', [SetupChannelEmailController::class, 'settingAgent'])->name('setting-agent');
    Route::get('/setting-agent/data', [SetupChannelEmailController::class, 'getSettingAgentData'])->name('setting-agent.getData');
    
    Route::get('/setting-auto-reply', [SetupChannelEmailController::class, 'settingAutoReply'])->name('setting-auto-reply');
    Route::get('/setting-auto-reply/data', [SetupChannelEmailController::class, 'getSettingAutoReply'])->name('setting-auto-reply.getData');

    Route::get('/template-auto-reply', [SetupChannelEmailController::class, 'templateAutoReply'])->name('template-auto-reply');
    Route::get('/template-auto-reply/data', [SetupChannelEmailController::class, 'getTemplateAutoReply'])->name('template-auto-reply.getData');

    Route::get('/template-response', [SetupChannelEmailController::class, 'templateResponse'])->name('template-response');
    Route::get('/template-response/data', [SetupChannelEmailController::class, 'getTemplateResponse'])->name('template-response.getData');
});

// Setting Email System Routes (7 Items)
Route::prefix('setting-email-system')->name('setting-email-system.')->group(function () {
    Route::get('/accounts', [SetupChannelEmailController::class, 'emailAccounts'])->name('accounts');
    Route::get('/accounts/data', [SetupChannelEmailController::class, 'getEmailAccountsData'])->name('accounts.getData');

    Route::get('/signature', [SetupChannelEmailController::class, 'emailSignature'])->name('signature');
    Route::get('/signature/data', [SetupChannelEmailController::class, 'getEmailSignatureData'])->name('signature.getData');

    Route::get('/service', [SetupChannelEmailController::class, 'emailService'])->name('service');
    Route::get('/service/data', [SetupChannelEmailController::class, 'getEmailServiceData'])->name('service.getData');

    Route::get('/service-method', [SetupChannelEmailController::class, 'emailServiceMethod'])->name('service-method');
    Route::get('/service-method/data', [SetupChannelEmailController::class, 'getEmailServiceMethodData'])->name('service-method.getData');

    Route::get('/server-profile', [SetupChannelEmailController::class, 'serverProfile'])->name('server-profile');
    Route::get('/server-profile/data', [SetupChannelEmailController::class, 'getServerProfileData'])->name('server-profile.getData');

    Route::get('/server-protocol', [SetupChannelEmailController::class, 'serverProtocol'])->name('server-protocol');
    Route::get('/server-protocol/data', [SetupChannelEmailController::class, 'getServerProtocolData'])->name('server-protocol.getData');

    Route::get('/server-protocol-out', [SetupChannelEmailController::class, 'serverProtocolOut'])->name('server-protocol-out');
    Route::get('/server-protocol-out/data', [SetupChannelEmailController::class, 'getServerProtocolOutData'])->name('server-protocol-out.getData');
});

// Setting EPIC System Routes
Route::prefix('setting-epic-system')->name('setting-epic-system.')->group(function () {
    Route::get('/configuration', [SetupChannelEmailController::class, 'epicConfiguration'])->name('configuration');
    Route::get('/configuration/data', [SetupChannelEmailController::class, 'getEpicConfigurationData'])->name('configuration.getData');
});

Route::prefix('apps')->name('apps.')->group(function () {
    Route::get('/ticketing-department/data', [TicketingDepartmentController::class, 'getData'])->name('ticketing-department.getData');
    Route::get('/ticketing-department', [TicketingDepartmentController::class, 'index'])->name('ticketing-department');
    Route::get('/taskboard/data', [TaskboardController::class, 'getData'])->name('taskboard.getData');
    Route::get('/taskboard', [TaskboardController::class, 'index'])->name('taskboard');
    Route::get('/thread-system/data', [ThreadTransactionController::class, 'getData'])->name('thread-system.getData');
    Route::get('/thread-system', [ThreadTransactionController::class, 'index'])->name('thread-system');
    Route::post('/ticketing/store', [TicketingSystemController::class, 'store'])->name('ticketing.store');
    Route::get('/ticketing/ticket-history', [TicketingSystemController::class, 'getTicketHistoryData'])->name('ticketing.getTicketHistoryData');
    Route::get('/ticketing/customer-data', [TicketingSystemController::class, 'getCustomerData'])->name('ticketing.getCustomerData');
    Route::get('/ticketing', [TicketingSystemController::class, 'index'])->name('ticketing');
    Route::get('/history-ticketing/data', [HistoryTicketingController::class, 'getData'])->name('history-ticketing.getData');
    Route::get('/history-ticketing', [HistoryTicketingController::class, 'index'])->name('history-ticketing');
});

Route::prefix('master-customer')->name('master-customer.')->group(function () {
    Route::get('/data-table', [DataTableCustomerController::class, 'index'])->name('data-table');
    Route::get('/data-table/getData', [DataTableCustomerController::class, 'getData'])->name('data-table.getData');
    Route::get('/data-table/export', [DataTableCustomerController::class, 'export'])->name('data-table.export');
    Route::get('/data-customer', [DataCustomerController::class, 'index'])->name('data-customer');
    Route::get('/data-customer/getData', [DataCustomerController::class, 'getData'])->name('data-customer.getData');
});

Route::prefix('channel')->name('channel.')->group(function () {
    Route::prefix('email')->name('email.')->group(function () {
        Route::get('/inbox/inbox-data', [InboxEmailController::class, 'getInboxData'])->name('inbox.getInboxData');
        Route::get('/inbox/drafts-data', [InboxEmailController::class, 'getDraftsData'])->name('inbox.getDraftsData');
        Route::get('/inbox/spam-data', [InboxEmailController::class, 'getSpamData'])->name('inbox.getSpamData');
        Route::get('/inbox', [InboxEmailController::class, 'index'])->name('inbox');
        Route::post('/inbox/{id}/mark-read', [InboxEmailController::class, 'markAsRead'])->name('inbox.mark-read');
        Route::post('/inbox/{id}/spam', [InboxEmailController::class, 'moveToSpam'])->name('inbox.spam');
        Route::get('/history/data', [HistoryEmailController::class, 'getData'])->name('history.getData');
        Route::get('/history', [HistoryEmailController::class, 'index'])->name('history');
    });
});

Route::get('/journey', [JourneyController::class, 'index'])->name('journey.index');

Route::prefix('management-user')->name('management-user.')->group(function () {
    Route::get('/data-access-application', [DataAccessApplicationController::class, 'index'])->name('data-access-application');
    Route::get('/data-access-application/getData', [DataAccessApplicationController::class, 'getData'])->name('data-access-application.getData');
    Route::post('/data-access-application/store', [DataAccessApplicationController::class, 'store'])->name('data-access-application.store');
    Route::delete('/data-access-application/{id}', [DataAccessApplicationController::class, 'destroy'])->name('data-access-application.destroy');

    Route::get('/data-user-application', [DataUserApplicationController::class, 'index'])->name('data-user-application');
    Route::get('/data-user-application/getData', [DataUserApplicationController::class, 'getData'])->name('data-user-application.getData');
    Route::post('/data-user-application/store', [DataUserApplicationController::class, 'store'])->name('data-user-application.store');
    Route::put('/data-user-application/{id}', [DataUserApplicationController::class, 'update'])->name('data-user-application.update');
    Route::delete('/data-user-application/{id}', [DataUserApplicationController::class, 'destroy'])->name('data-user-application.destroy');

    Route::get('/level-user-application', [LevelUserApplicationController::class, 'index'])->name('level-user-application');
    Route::get('/level-user-application/counts', [LevelUserApplicationController::class, 'getCounts'])->name('level-user-application.counts');
    Route::get('/export-user-application', [ExportUserApplicationController::class, 'index'])->name('export.user.application');
    Route::get('/export-user-application/getData', [ExportUserApplicationController::class, 'getData'])->name('export.user.application.getData');
    Route::post('/export-user-application/export', [ExportUserApplicationController::class, 'export'])->name('export.user.application.download');
});

// Bantu Dagang Feature


// Menu Application Feature
Route::get('/menu-application/data', [MenuApplicationController::class, 'getData'])->name('menu.application.getData');
Route::get('/menu-application', [MenuApplicationController::class, 'index'])->name('menu.application');
Route::post('/menu-application/store', [MenuApplicationController::class, 'store'])->name('menu.application.store');
Route::get('/menu-application/{id}', [MenuApplicationController::class, 'show'])->name('menu.application.show');
Route::delete('/menu-application/{id}', [MenuApplicationController::class, 'destroy'])->name('menu.application.destroy');

// Sub Menu Application Feature
Route::get('/sub-menu-application/data', [SubMenuApplicationController::class, 'getData'])->name('sub.menu.application.getData');
Route::get('/sub-menu-application', [SubMenuApplicationController::class, 'index'])->name('sub.menu.application');
Route::post('/sub-menu-application/store', [SubMenuApplicationController::class, 'store'])->name('sub.menu.application.store');
Route::get('/sub-menu-application/{id}', [SubMenuApplicationController::class, 'show'])->name('sub.menu.application.show');
Route::put('/sub-menu-application/{id}', [SubMenuApplicationController::class, 'update'])->name('sub.menu.application.update');
Route::delete('/sub-menu-application/{id}', [SubMenuApplicationController::class, 'destroy'])->name('sub.menu.application.destroy');

// Detail Menu Application Feature
Route::get('/detail-menu-application/data', [\App\Http\Controllers\DetailMenuApplicationController::class, 'getData'])->name('detail.menu.application.getData');
Route::get('/detail-menu-application', [\App\Http\Controllers\DetailMenuApplicationController::class, 'index'])->name('detail.menu.application');
Route::post('/detail-menu-application/store', [\App\Http\Controllers\DetailMenuApplicationController::class, 'store'])->name('detail.menu.application.store');
Route::get('/detail-menu-application/{id}', [\App\Http\Controllers\DetailMenuApplicationController::class, 'show'])->name('detail.menu.application.show');
Route::put('/detail-menu-application/{id}', [\App\Http\Controllers\DetailMenuApplicationController::class, 'update'])->name('detail.menu.application.update');
Route::delete('/detail-menu-application/{id}', [\App\Http\Controllers\DetailMenuApplicationController::class, 'destroy'])->name('detail.menu.application.destroy');

// Ticket Notification System Feature
Route::get('/ticket-notification-system/data', [\App\Http\Controllers\TicketNotificationSystemController::class, 'getUserData'])->name('ticket.notification.system.getData');
Route::get('/ticket-notification-system', [\App\Http\Controllers\TicketNotificationSystemController::class, 'index'])->name('ticket.notification.system');
Route::post('/ticket-notification-system/user', [\App\Http\Controllers\TicketNotificationSystemController::class, 'storeUser'])->name('ticket.notification.system.user.store');
Route::post('/ticket-notification-system/setting', [\App\Http\Controllers\TicketNotificationSystemController::class, 'updateSetting'])->name('ticket.notification.system.setting.update');

// Setting Channel Agent
Route::get('/setting-channel-agent/data', [\App\Http\Controllers\SettingChannelAgentController::class, 'getData'])->name('setting.channel.agent.getData');
Route::resource('setting-channel-agent', \App\Http\Controllers\SettingChannelAgentController::class)->names('setting.channel.agent');

// Setting Agent Call
Route::get('/setting-agent-call/data', [SettingAgentCallController::class, 'getData'])->name('setting.agent.call.getData');
Route::get('/setting-agent-call', [SettingAgentCallController::class, 'index'])->name('setting.agent.call');

// Monitoring Login
Route::get('/monitoring-login/data', [MonitoringLoginController::class, 'getData'])->name('monitoring.login.getData');
Route::get('/monitoring-login', [MonitoringLoginController::class, 'index'])->name('monitoring.login.index');
Route::post('/setting-channel-agent/store', [\App\Http\Controllers\SettingChannelAgentController::class, 'store'])->name('setting.channel.agent.store');
Route::put('/setting-channel-agent/{id}', [\App\Http\Controllers\SettingChannelAgentController::class, 'update'])->name('setting.channel.agent.update');
Route::delete('/setting-channel-agent/{id}', [\App\Http\Controllers\SettingChannelAgentController::class, 'destroy'])->name('setting.channel.agent.destroy');

// Master Data Routes (+ AJAX getData endpoints)
Route::get('data-group-name/data', [\App\Http\Controllers\DataGroupNameController::class, 'getData'])->name('data-group-name.getData');
Route::resource('data-group-name', \App\Http\Controllers\DataGroupNameController::class);

Route::get('data-fulfillment-location/data', [\App\Http\Controllers\DataFulfillmentLocationController::class, 'getData'])->name('data-fulfillment-location.getData');
Route::resource('data-fulfillment-location', \App\Http\Controllers\DataFulfillmentLocationController::class);

Route::get('data-type/data', [\App\Http\Controllers\DataTypeController::class, 'getData'])->name('data-type.getData');
Route::resource('data-type', \App\Http\Controllers\DataTypeController::class);

Route::get('data-category/data', [\App\Http\Controllers\DataCategoryController::class, 'getData'])->name('data-category.getData');
Route::resource('data-category', \App\Http\Controllers\DataCategoryController::class);

Route::get('data-meta/data', [\App\Http\Controllers\DataMetaController::class, 'getData'])->name('data-meta.getData');
Route::resource('data-meta', \App\Http\Controllers\DataMetaController::class);

Route::get('data-sub-category/data', [\App\Http\Controllers\DataSubCategoryController::class, 'getData'])->name('data-sub-category.getData');
Route::resource('data-sub-category', \App\Http\Controllers\DataSubCategoryController::class);

Route::get('channel-ticket/data', [\App\Http\Controllers\ChannelTicketController::class, 'getData'])->name('channel-ticket.getData');
Route::resource('channel-ticket', \App\Http\Controllers\ChannelTicketController::class);

Route::get('department-escalation-unit/data', [\App\Http\Controllers\DepartmentEscalationUnitController::class, 'getData'])->name('department-escalation-unit.getData');
Route::resource('department-escalation-unit', \App\Http\Controllers\DepartmentEscalationUnitController::class);

Route::get('data-source/data', [\App\Http\Controllers\DataSourceController::class, 'getData'])->name('data-source.getData');
Route::resource('data-source', \App\Http\Controllers\DataSourceController::class);

Route::get('data-activity/data', [\App\Http\Controllers\DataActivityController::class, 'getData'])->name('data-activity.getData');
Route::resource('data-activity', \App\Http\Controllers\DataActivityController::class);

Route::get('data-aux-reason/data', [\App\Http\Controllers\DataAuxReasonController::class, 'getData'])->name('data-aux-reason.getData');
Route::resource('data-aux-reason', \App\Http\Controllers\DataAuxReasonController::class);

Route::get('data-status-ticket/data', [\App\Http\Controllers\DataStatusTicketController::class, 'getData'])->name('data-status-ticket.getData');
Route::resource('data-status-ticket', \App\Http\Controllers\DataStatusTicketController::class);

Route::get('data-group-agent/data', [\App\Http\Controllers\DataGroupAgentController::class, 'getData'])->name('data-group-agent.getData');
Route::resource('data-group-agent', \App\Http\Controllers\DataGroupAgentController::class);

Route::get('data-brand-category/data', [\App\Http\Controllers\DataBrandCategoryController::class, 'getData'])->name('data-brand-category.getData');
Route::resource('data-brand-category', \App\Http\Controllers\DataBrandCategoryController::class);

Route::get('data-fulfillment/data', [\App\Http\Controllers\DataFulfillmentController::class, 'getData'])->name('data-fulfillment.getData');
Route::resource('data-fulfillment', \App\Http\Controllers\DataFulfillmentController::class);

Route::get('data-holiday/data', [\App\Http\Controllers\DataHolidayController::class, 'getData'])->name('data-holiday.getData');
Route::resource('data-holiday', \App\Http\Controllers\DataHolidayController::class);

Route::get('data-brand-name/data', [\App\Http\Controllers\DataBrandNameController::class, 'getData'])->name('data-brand-name.getData');
Route::resource('data-brand-name', \App\Http\Controllers\DataBrandNameController::class);

Route::get('data-max-handle/data', [\App\Http\Controllers\DataMaxHandleController::class, 'getData'])->name('data-max-handle.getData');
Route::resource('data-max-handle', \App\Http\Controllers\DataMaxHandleController::class);

Route::get('data-site/data', [\App\Http\Controllers\DataSiteController::class, 'getData'])->name('data-site.getData');
Route::resource('data-site', \App\Http\Controllers\DataSiteController::class);

Route::prefix('recording')->name('recording.')->group(function () {
    Route::get('/data', [RecordingController::class, 'getData'])->name('getData');
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
