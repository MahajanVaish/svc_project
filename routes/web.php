<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AssessmentController;
use App\Http\Controllers\Admin\ChargesController;
use App\Http\Controllers\Admin\BranchController;
use App\Http\Controllers\Admin\DietPlanController;
use App\Http\Controllers\Admin\HydraController;
use App\Http\Controllers\Admin\InquiryDietChartController;
use App\Http\Controllers\Admin\JoinedInquiryController;
use App\Http\Controllers\Admin\OnlineAbroadInquiryController;
use App\Http\Controllers\Admin\LHRController;
use App\Http\Controllers\Admin\ManageProgramController;
use App\Http\Controllers\Admin\NutritionController;
use App\Http\Controllers\Admin\PendingInquiryController;
use App\Http\Controllers\Admin\ProgressController;
use App\Http\Controllers\Admin\RecipeController;
use App\Http\Controllers\Admin\AppointmentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\InquiryController;
use App\Http\Controllers\patients\SVCController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\Doctor\ZoomMeetingController;
use App\Http\Controllers\Doctor\DoctorController;
use App\Models\Followups;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/login', function () {
    if (Auth::check()) {
        $user = Auth::user();
        return $user->hasRole('Superadmin') ? redirect()->route('admin.dashboard') : redirect()->route('diet.chart');
    }
    return redirect('/show-login');
});
Route::get('/', function () {
    if (Auth::check()) {
        $user = Auth::user();
        return $user->hasRole('Superadmin') ? redirect()->route('admin.dashboard') : redirect()->route('diet.chart');
    }
    return redirect('/show-login');
});

// ============ CACHE CLEAR ROUTE (No terminal needed) ============
Route::get('/clear-all-cache', function () {
    $results = [];

    // 1. Clear application cache
    try {
        Illuminate\Support\Facades\Artisan::call('cache:clear');
        $results[] = '✅ Application cache cleared';
    } catch (\Exception $e) {
        $results[] = '⚠️ Cache clear: ' . $e->getMessage();
    }

    // 2. Clear config cache
    try {
        Illuminate\Support\Facades\Artisan::call('config:clear');
        $results[] = '✅ Config cache cleared';
    } catch (\Exception $e) {
        $results[] = '⚠️ Config clear: ' . $e->getMessage();
    }

    // 3. Clear route cache
    try {
        Illuminate\Support\Facades\Artisan::call('route:clear');
        $results[] = '✅ Route cache cleared';
    } catch (\Exception $e) {
        $results[] = '⚠️ Route clear: ' . $e->getMessage();
    }

    // 4. Clear view cache
    try {
        Illuminate\Support\Facades\Artisan::call('view:clear');
        $results[] = '✅ View cache cleared';
    } catch (\Exception $e) {
        $results[] = '⚠️ View clear: ' . $e->getMessage();
    }

    // 5. Clear OPcache
    if (function_exists('opcache_reset')) {
        opcache_reset();
        $results[] = '✅ OPcache cleared';
    }

    return '<html><head><title>Cache Cleared</title></head><body style="font-family:Arial;padding:40px;background:#1a1a2e;color:#e0e0e0;">'
        . '<h1 style="color:#00d97e;">🧹 Cache Clear Results</h1>'
        . '<div style="background:#16213e;padding:20px;border-radius:10px;margin:20px 0;">'
        . implode('<br>', $results)
        . '</div>'
        . '<p style="margin-top:20px;"><a href="/show-login" style="padding:10px 20px;background:#086838;color:white;text-decoration:none;border-radius:5px;">← Go to Admin Panel</a></p>'
        . '</body></html>';
});
// ============ END CACHE CLEAR ============


// Registration
Route::get('/show-register', [AuthController::class, 'showRegister'])->name('show-register');
Route::post('/register', [AuthController::class, 'register'])->name('register');

// Login
Route::get('/show-login', [AuthController::class, 'showLogin'])->name('show-login');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::get('/zoom-join/{id}', [ZoomMeetingController::class, 'joinMeeting'])->name('zoom.join');


Route::middleware(['auth'])->group(function () {
    // Dashboard - Accessible to all authenticated users
    Route::get('/dashboard', [SVCController::class, 'index'])->name('dashboard');

    // Invoice - Accessible to all authenticated users
    Route::get('/add-invoice', [InvoiceController::class, 'addInvoice'])->name('add.invoice');
    Route::post('/store-invoice', [InvoiceController::class, 'storeInvoice'])->name('store.invoice');
    Route::get('/view-invoice/{id}', [InvoiceController::class, 'viewInvoice'])->name('view.invoice');
    Route::get('/download-invoice/{id}', [InvoiceController::class, 'downloadInvoice'])->name('download.invoice');
    Route::post('/invoice/delete/{id}', [InvoiceController::class, 'deleteInvoice'])->name('delete.invoice');
    Route::post('/invoice/add-payment', [InvoiceController::class, 'addPayment'])->name('invoice.add.payment');

    // Finance / Transactions
    Route::get('/patient-transactions', [App\Http\Controllers\Admin\PatientTransactionController::class, 'index'])->name('transactions.index');
    Route::get('/patient-ledger/{patient_id}/{branch_id}', [App\Http\Controllers\Admin\PatientTransactionController::class, 'ledger'])->name('transactions.ledger');
    Route::post('/patient-transactions/delete', [App\Http\Controllers\Admin\PatientTransactionController::class, 'deletePatientTransactions'])->name('transaction.delete');
    Route::get('/get-patient-programs/{id}', [App\Http\Controllers\InvoiceController::class, 'getPatientPrograms'])->name('get.patient.programs');
    // Route::get('/invoice/get-patients', [App\Http\Controllers\InvoiceController::class, 'getPatientsByBranch'])->name('invoice.get.patients');
     Route::post('/invoice/get-patients', [App\Http\Controllers\InvoiceController::class, 'getPatientsByBranch'])->name('invoice.get.patients');


    // Calendar
    Route::get('/follow-up-calendar', [CalendarController::class, 'index'])->name('followup.calendar');
    Route::get('/patient-details/{id}', [CalendarController::class, 'getPatientDetails'])->name('patient.details');
    Route::get('/inquiry-details/{id}', [CalendarController::class, 'getInquiryDetails'])->name('inquiry.details');

    Route::get('/patient-analytics', [PatientController::class, 'analytics'])->name('patient.analytics');
    Route::post('/patient-analytics/data', [PatientController::class, 'analyticsData'])->name('patient.analytics.data');

    // Patient filtering
    Route::post('/get-filtered-patients', [PatientController::class, 'getFilteredPatients'])->name('get.filtered.patients');
    Route::post('/get-total-patients', [PatientController::class, 'getTotalPatients'])->name('get.total.patients');
    Route::post('/get-patient-list', [PatientController::class, 'getPatientList'])->name('get.patient.list');

    // Profile/Settings
    Route::get('/profile', [AuthController::class, 'showProfile'])->name('profile');
    Route::post('/profile/update', [AuthController::class, 'updateProfile'])->name('profile.update');

    // Doctor
    Route::get('/doctor/my-patients', [DoctorController::class, 'myPatients'])->name('doctor.my-patients');
    Route::get('/doctor/meeting-history', [DoctorController::class, 'meetingHistory'])->name('doctor.meeting-history');
    Route::post('/doctor/meetings/{id}/update', [DoctorController::class, 'updateMeetingSchedule'])->name('doctor.meeting.update');
    Route::post('/doctor/meetings/{id}/delete', [DoctorController::class, 'deleteMeeting'])->name('doctor.meeting.delete');

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Appointment Management
    Route::get('/admin/appointments', [AppointmentController::class, 'index'])->name('appointment.index');
    Route::post('/admin/appointment/store', [AppointmentController::class, 'store'])->name('appointment.store');
    Route::put('/admin/appointment/{id}', [AppointmentController::class, 'update'])->name('appointment.update');
    Route::delete('/admin/appointment/{id}', [AppointmentController::class, 'destroy'])->name('appointment.destroy');
});


Route::middleware(['auth'])->group(function () {
    // SVC Patient Management
    Route::get('/svc-patient', [SVCController::class, 'searchSvcPatient'])->name('svc-patient');
    Route::get('/indoor-patients', [SVCController::class, 'indoorPatients'])->name('indoor.patients');
    Route::get('/add-svc-inquiry', [SVCController::class, 'addInquiry'])->name('svc.add.inquiry');
    Route::post('/store-svc-inquiry', [SVCController::class, 'store'])->name('store.svc.inquiry');
    Route::get('/get-suggestions', [SVCController::class, 'getSuggestions'])->name('get.suggestions');
    Route::get('/get-medicine-suggestions', [SVCController::class, 'getMedicineSuggestions'])->name('get.medicine.suggestions');
    Route::post('/save-medical-condition', [SVCController::class, 'saveMedicalCondition'])->name('save.medical.condition');
    Route::get('/edit-svc-inquiry/{id}', [SVCController::class, 'editSvcInquiry'])->name('edit.svc.inquiry');
    Route::put('/update-svc-inquiry/{id}', [SVCController::class, 'updateSvcInquiry'])->name('update.svc.inquiry');
    Route::delete('/delete-svc-inquiry/{id}', [SVCController::class, 'deleteSvcInquiry'])->name('delete-inquiry');
    Route::get('/svc-profile/{id}', [SVCController::class, 'viewSvcProfile'])->name('svc.profile');
    Route::post('/svc-profile/{id}/update-image', [SVCController::class, 'updateProfileImage'])->name('svc.profile.update-image');
    Route::post('/svc-profile/{id}/indoor-treatment', [SVCController::class, 'saveProfileIndoorTreatment'])->name('svc.profile.indoor-treatment');
    Route::get('/export-svc-patients', [SVCController::class, 'exportSvcPatients'])->name('export.svc.patients');
    Route::post('/svc-profile/{id}/add-payment', [SVCController::class, 'addIpdPayment'])->name('svc.profile.add-payment');
    Route::post('/svc-profile/{id}/update-charges', [SVCController::class, 'updateCharges'])->name('svc.profile.update-charges');
    Route::get('/ipd-profile/{id}', [SVCController::class, 'viewIpdProfile'])->name('ipd.profile');
    // Monthly Assessment - Accessible to all branch users
    Route::get('/monthly-assessment', [AssessmentController::class, 'monthlyAssessment'])->name('monthly.assessment');
    // Route::post('/monthly-assessment/get-patients', [AssessmentController::class, 'getPatientsByBranch'])->name('monthly.assessment.getPatients');
    Route::post('/monthly-assessment/get-patients', [AssessmentController::class, 'getPatientsByBranch'])->name('monthly.assessment.getPatients');

    Route::post('/monthly-assessment/store', [AssessmentController::class, 'storeAssessment'])->name('monthly.assessment.store');
    Route::get('/monthly-assessment/history', [AssessmentController::class, 'getAssessmentHistory'])->name('monthly.assessment.history');
    Route::get('/monthly-assessment/{id}/details', [AssessmentController::class, 'getAssessmentDetails'])->name('monthly.assessment.details');

    // Follow-up routes
    Route::get('/add-follow-up/{patient_id}', [SVCController::class, 'addFollowUp'])->name('add.follow.up');
    Route::post('/store-followup/{patient_id}', [SVCController::class, 'storeFollowUp'])->name('store.follow.up');
    Route::get('/edit-follow-up/{patient_id}/{followup_id}', [SVCController::class, 'editFollowUp'])->name('edit.follow.up');
    Route::put('/update-follow-up/{patient_id}/{followup_id}', [SVCController::class, 'updateFollowUp'])->name('update.follow.up');
    Route::delete('/delete-follow-up/{id}', [SVCController::class, 'deleteFollowUp'])->name('delete.follow.up');
    Route::get('/patient/{patient_id}/followup-history', [SVCController::class, 'getFollowupHistory'])->name('followup.history');
    Route::get('/followup/{followup_id}/full-details', [SVCController::class, 'getFullFollowupDetails']);
    Route::get('/followup/{followup_id}/details', [SVCController::class, 'getFollowupDetails']);
    Route::post('/followup/{id}/create-zoom-meeting', [ZoomMeetingController::class, 'createMeeting'])->name('followup.create-zoom-meeting');
    Route::post('/zoom-meeting/create/{id}', [ZoomMeetingController::class, 'createMeeting'])->name('zoom.meeting.create');

    // Followup times API
    Route::get('/api/patient/{patient_id}/followup-times', function ($patient_id, Request $request) {
        $date = $request->query('date');
        $followups = Followups::with('metas')
            ->where('patient_id', $patient_id)
            ->whereDate('followup_date', $date)
            ->get();
        $times = $followups->map(function ($followup) {
            $timeMeta = $followup->metas->firstWhere('meta_key', 'followups_time');
            return [
                'time' => $timeMeta ? $timeMeta->meta_value : '00:00:00',
                'formatted' => $timeMeta ? \Carbon\Carbon::parse($timeMeta->meta_value)->format('h:i A') : '00:00 AM'
            ];
        })->unique('time')->sortBy('time')->values();
        return response()->json(['times' => $times]);
    });

    Route::get('/follow-up-patients', [InquiryController::class, 'followup'])->name('followup.patients.appointment');
    Route::post('/update-followup-date', [InquiryController::class, 'updateFollowupDate'])->name('update.followup.date');
    Route::get('/add-inquiry', [InquiryController::class, 'create'])->name('add-inquiry-patient');
    Route::post('/add-inquiry', [InquiryController::class, 'store'])->name('store-inquiry-patient');
    Route::get('/pending-inquiries', [InquiryController::class, 'pending'])->name('pending.inquiries');
    Route::get('/joined-patients', [InquiryController::class, 'joined'])->name('joined.patients');
    Route::get('/inquiry/{id}/edit', [InquiryController::class, 'edit'])->name('edit.inquiry');
    Route::put('/inquiry/{id}', [InquiryController::class, 'update'])->name('update.inquiry');
    Route::delete('/inquiry/{id}', [InquiryController::class, 'destroy'])->name('inquiry.destroy');

    // Diet Plan Routes - Accessible to all branch users
    Route::get('/diet-plan', [DietPlanController::class, 'create'])->name('diet.plan');
    Route::post('/diet-plan/get-patients', [DietPlanController::class, 'getPatientsByBranch'])->name('diet.getPatientsByBranch');
    Route::get('/diet-plan/get-recipes', [DietPlanController::class, 'getRecipes'])->name('diet.getRecipes');
    Route::get('/diet-plan/get-name-suggestions', [DietPlanController::class, 'getDietNameSuggestions'])->name('diet.getDietNameSuggestions');
    Route::post('/diet-plan/store', [DietPlanController::class, 'store'])->name('diet.plan.store');
    Route::get('/diet-plan/{id}/edit', [DietPlanController::class, 'edit'])->name('diet.plan.edit');
    Route::post('/diet-plan/update', [DietPlanController::class, 'update'])->name('diet.plan.update');
    Route::post('/diet-plan/delete', [DietPlanController::class, 'destroy'])->name('diet.plan.delete');
    Route::get('/diet-plan/print/{id}', [DietPlanController::class, 'print'])->name('diet.plan.print');

    // Progress Report Routes - Accesseible to all branch users
    Route::get('/progress-reports', [ProgressController::class, 'index'])->name('progress-reports');
    Route::post('progress_report.store', [ProgressController::class, 'store'])->name('progress_report.store');
    Route::get('/progress/patients/{branchId}', [ProgressController::class, 'getPatientsByBranch']);
    Route::get('/progress/patient-prefill/{id}', [ProgressController::class, 'getPatientPrefill'])->name('progress.patient.prefill');
    Route::post('/progress-report/add', [InquiryDietChartController::class, 'addProgressReport'])->name('progress.report.add');
    Route::get('/progress-report/{id}/details', [InquiryDietChartController::class, 'getProgressReportDetails'])->name('progress.report.details');
    Route::post('/progress-report/update', [InquiryDietChartController::class, 'updateProgressReport'])->name('progress.report.update');
    Route::post('/progress-report/delete', [InquiryDietChartController::class, 'deleteProgressReport'])->name('progress.report.delete');

    // Diet Chart Routes - Accessible to all branch users
    Route::get('/admin/diet-chart', [InquiryDietChartController::class, 'dietChart'])->name('diet.chart');
    Route::get('/admin/diet-chart/search', [InquiryDietChartController::class, 'dietChartSearch'])->name('diet.chart.search');
    Route::get('/admin/add-inquiry', [InquiryDietChartController::class, 'create'])->name('add.inquiry');
    Route::post('/admin/add-inquiry', [InquiryDietChartController::class, 'store'])->name('store.inquiry');
    Route::get('/admin/inquiry/{id}/edit', [InquiryDietChartController::class, 'edit'])->name('inquiry.edit');
    Route::post('/admin/inquiry/{id}/update', [InquiryDietChartController::class, 'update'])->name('inquiry.update');
    Route::get('/admin/get-patients-by-branch', [InquiryDietChartController::class, 'getPatientsByBranch'])->name('get.patients.by.branch');
    Route::get('/export-inquiries', [InquiryDietChartController::class, 'export'])->name('export.inquiries');
    Route::delete('/admin/delete-inquiry/{id}', [InquiryDietChartController::class, 'destroy'])->name('delete.inquiry');
    Route::get('/patient-profile/{id}', [InquiryDietChartController::class, 'patientProfile'])->name('patient.profile');
    Route::post('/patient-profile/{id}/update-image', [InquiryDietChartController::class, 'updateProfileImage'])->name('patient.profile.update-image');
    Route::get('/admin/diet-join-patient/{id}', [InquiryDietChartController::class, 'dietJoinPatient'])->name('diet.join.patient');
    Route::get('/admin/edit-diet-chart/{id}', [InquiryDietChartController::class, 'editDietJoinPatient'])->name('edit.diet.chart');
    Route::post('/admin/save-diet-chart', [InquiryDietChartController::class, 'saveDietChart'])->name('save.diet.chart');
    Route::put('/admin/update-diet-chart/{id}', [InquiryDietChartController::class, 'updateDietChart'])->name('update.diet.chart');
    Route::post('/admin/delete-diet-history/{id}', [InquiryDietChartController::class, 'deleteDietHistory'])->name('delete.diet.history');
    Route::post('/admin/update-diet-history-meta', [InquiryDietChartController::class, 'updateDietHistoryMeta'])->name('update.diet.history.meta');
    Route::post('/admin/patient/reverse-to-followup/{id}', [InquiryDietChartController::class, 'reverseToFollowup'])->name('patient.reverse-to-followup');
    Route::post('/payment-program/update', [InquiryDietChartController::class, 'updatePaymentProgram'])->name('update.payment.program');
    Route::post('/payment-program/delete', [InquiryDietChartController::class, 'deletePaymentProgram'])->name('delete.payment.program');

    // Pending Inquiry Routes - Accessible to all branch users
    Route::get('/admin/pending-inquiry', [PendingInquiryController::class, 'pendingInquiry'])->name('pending.inquiry');
    Route::get('/export-pending-inquiries', [PendingInquiryController::class, 'exportPendingInquiries'])->name('export.pending.inquiries');

    // Joined Inquiry Routes - Accessible to all branch users
    Route::get('/admin/joined-inquiry', [JoinedInquiryController::class, 'joinedInquiry'])->name('joined.inquiry');
    Route::get('/export-joined-inquiries', [JoinedInquiryController::class, 'exportJoinedInquiries'])->name('export.joined.inquiries');

    // Online/Abroad Inquiry Routes
    Route::get('/admin/online-abroad-inquiry', [OnlineAbroadInquiryController::class, 'onlineInquiry'])->name('online.abroad.inquiry');
    Route::get('/export-online-abroad-inquiries', [OnlineAbroadInquiryController::class, 'exportOnlineInquiries'])->name('export.online.abroad.inquiries');

    // Monthly Assessment Routes - Accessible to all branch users
    Route::post('/monthly-assessment/update', [AssessmentController::class, 'updateAssessment'])->name('monthly.assessment.update');
    Route::post('/monthly-assessment/delete', [AssessmentController::class, 'deleteAssessment'])->name('monthly.assessment.delete');

    // Call Log Routes
    Route::get('/admin/call-log/create/{id}', [App\Http\Controllers\Admin\CallLogController::class, 'create'])->name('call.log.create');
    Route::post('/admin/call-log/store', [App\Http\Controllers\Admin\CallLogController::class, 'store'])->name('call.log.store');
    Route::get('/admin/call-log/history/{patient_id}', [App\Http\Controllers\Admin\CallLogController::class, 'history'])->name('call.log.history');
});


Route::middleware(['auth', 'role:Superadmin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/payment-summary', [AdminController::class, 'paymentSummary'])->name('admin.payment.summary');

    // Charges Management
    Route::get('/svc/charges', [ChargesController::class, 'SVCcharges'])->name('svc.charges');
    Route::post('/svc/charges', [ChargesController::class, 'store'])->name('svc.charges.store');
    Route::put('/svc/charges/{id}', [ChargesController::class, 'update'])->name('svc.charges.update');
    Route::delete('/svc/charges/{id}', [ChargesController::class, 'destroy'])->name('svc.charges.destroy');

    // Branch Management
    Route::get('/create/branches', [BranchController::class, 'createBranch'])->name('create.branch');
    Route::post('/create/branches', [BranchController::class, 'storeBranch'])->name('branch.store');
    Route::put('/branches/{id}', [BranchController::class, 'updateBranch'])->name('branch.update');
    Route::delete('/branches/{id}', [BranchController::class, 'deleteBranch'])->name('branch.delete');

    // Nutrition Management
    Route::get('/nutrition-info', [NutritionController::class, 'index'])->name('nutrition-info');
    Route::post('/nutrition', [NutritionController::class, 'store'])->name('nutrition.store');
    Route::get('/nutrition/data', [NutritionController::class, 'getNutritionData'])->name('nutrition.data');
    Route::post('/nutrition/upload-csv', [NutritionController::class, 'uploadCsv'])->name('nutrition.upload.csv');
    Route::get('/nutrition/download-sample', [NutritionController::class, 'downloadSampleCsv'])->name('nutrition.download.sample');
    Route::get('/nutrition/{id}/edit', [NutritionController::class, 'edit'])->name('nutrition.edit');
    Route::put('/nutrition/{id}', [NutritionController::class, 'update'])->name('nutrition.update');
    Route::delete('/nutrition/{id}', [NutritionController::class, 'destroy'])->name('nutrition.destroy');

    // Recipe Management
    Route::get('/recipes', [RecipeController::class, 'index'])->name('recipes.index');
    Route::post('/recipes', [RecipeController::class, 'store'])->name('recipes.store');
    Route::get('/recipes/{id}/edit', [RecipeController::class, 'edit'])->name('recipes.edit');
    Route::put('/recipes/{id}', [RecipeController::class, 'update'])->name('recipes.update');
    Route::delete('/recipes/{id}', [RecipeController::class, 'destroy'])->name('recipes.destroy');

    // User Management
    Route::get('/svc/users', [AdminController::class, 'SVC'])->name('admin.svc.users');
    Route::post('/users/store', [AdminController::class, 'storeUser'])->name('admin.users.store');

    // Program Management
    Route::get('/manage-programs', [ManageProgramController::class, 'index'])->name('admin.manage-programs');
    Route::post('/manage-programs/store', [ManageProgramController::class, 'store'])->name('admin.manage-programs.store');
    Route::put('/manage-programs/update/{id}', [ManageProgramController::class, 'update'])->name('admin.manage-programs.update');
    Route::delete('/manage-programs/delete/{id}', [ManageProgramController::class, 'destroy'])->name('admin.manage-programs.delete');
});


Route::middleware(['auth'])->prefix('lhr')->name('lhr.')->group(function () {
    Route::get('/pending', [LHRController::class, 'pending'])->name('pending');
    Route::get('/joined', [LHRController::class, 'joined'])->name('joined');
    Route::get('/add-inquiry', [LHRController::class, 'addInquiry'])->name('add.inquiry');
    Route::post('/add-inquiry/store', [LHRController::class, 'storeInquiry'])->name('add.inquiry.store');
    Route::get('/edit/{id}', [LHRController::class, 'edit'])->name('edit');
    Route::post('/update/{id}', [LHRController::class, 'update'])->name('update');
    Route::post('/delete/{id}', [LHRController::class, 'destroy'])->name('destroy');
    Route::post('/move-to-joined/{id}', [LHRController::class, 'moveToJoined'])->name('move.to.joined');
    Route::post('/move-to-pending/{id}', [LHRController::class, 'moveToPending'])->name('move.to.pending');
    Route::post('/change-status/{id}', [LHRController::class, 'changeStatus'])->name('change.status');
    Route::post('/bulk-status-update', [LHRController::class, 'bulkStatusUpdate'])->name('bulk.status.update');
    Route::get('/export/pending', [LHRController::class, 'exportPending'])->name('export.pending');
    Route::get('/export/all-pending', [LHRController::class, 'exportAllPending'])->name('export.all.pending');
    Route::get('/export/joined', [LHRController::class, 'exportJoined'])->name('export.joined');
    Route::get('/export/all-joined', [LHRController::class, 'exportAllJoined'])->name('export.all.joined');
    Route::get('/patient-profile/{id}', [LHRController::class, 'showPatientProfile'])->name('patient.profile');
    Route::post('/patient-profile/{id}/update-image', [LHRController::class, 'updateProfileImage'])->name('patient.profile.update-image');
    Route::get('/{id}/followup', [LHRController::class, 'followup'])->name('followup');
    Route::post('/{id}/followup', [LHRController::class, 'storeFollowup'])->name('followup.store');
    Route::get('/get-used-sessions', [LHRController::class, 'getUsedSessions'])->name('get.used.sessions');
    Route::get('/get-latest-settings', [LHRController::class, 'getLatestSettings'])->name('get.latest.settings');
    Route::post('/remove-inquiry-area', [LHRController::class, 'removeInquiryArea'])->name('remove.inquiry.area');
});


Route::middleware(['auth'])->prefix('hydra')->name('hydra.')->group(function () {
    Route::get('/pending', [HydraController::class, 'pending'])->name('pending');
    Route::get('/joined', [HydraController::class, 'joined'])->name('joined');
    Route::get('/add-inquiry', [HydraController::class, 'addInquiry'])->name('add.inquiry');
    Route::post('/add-inquiry/store', [HydraController::class, 'storeInquiry'])->name('add.inquiry.store');
    Route::get('/edit/{id}', [HydraController::class, 'edit'])->name('edit');
    Route::post('/update/{id}', [HydraController::class, 'update'])->name('update');
    Route::post('/move-to-joined/{id}', [HydraController::class, 'moveToJoined'])->name('move.to.joined');
    Route::post('/move-to-pending/{id}', [HydraController::class, 'moveToPending'])->name('move.to.pending');
    Route::post('/delete/{id}', [HydraController::class, 'destroy'])->name('destroy');
    Route::get('/export/pending', [HydraController::class, 'exportPending'])->name('export.pending');
    Route::get('/export/all-pending', [HydraController::class, 'exportAllPending'])->name('export.all.pending');
    Route::get('/export/joined', [HydraController::class, 'exportJoined'])->name('export.joined');
    Route::get('/export/all-joined', [HydraController::class, 'exportAllJoined'])->name('export.all.joined');
    Route::get('/patient-profile/{id}', [HydraController::class, 'showPatientProfile'])->name('patient.profile');
    Route::post('/patient-profile/{id}/update-image', [HydraController::class, 'updateProfileImage'])->name('patient.profile.update-image');
    Route::get('/{id}/follow-up/create', [HydraController::class, 'createFollowUp'])->name('followup.create');
    Route::post('/{id}/follow-up/store', [HydraController::class, 'storeFollowUp'])->name('followup.store');
});

Route::get('/check-roles', function () {
    if (Auth::check()) {
        $user = Auth::user();
        return response()->json([
            'authenticated' => true,
            'user_id' => $user->id,
            'user_name' => $user->name,
            'user_email' => $user->email,
            'roles' => $user->getRoleNames()->toArray(),
            'has_superadmin' => $user->hasRole('superadmin'),
            'has_svc' => $user->hasRole('SVC'),
            'has_lhr' => $user->hasRole('LHR'),
            'has_hydra' => $user->hasRole('BD HYDRA'),
        ]);
    }
    return response()->json(['authenticated' => false]);
});

Route::get('/demoPage', [AdminController::class, 'demoPage'])->name('demo.page');
Route::get('/go-ledger', function() {
    $invoice = \App\Models\Invoice::where('invoice_no', 'INV-ST-00001')->first();
    if ($invoice) {
        $patient = \App\Models\AccInquiry::find($invoice->patient_id);
        if ($patient) {
            return redirect()->route('transactions.ledger', ['patient_id' => $patient->patient_id, 'branch_id' => $invoice->branch_id]);
        }
    }
    return "Invoice or patient not found";
});
// View invoice routes (accessible without auth for invoice sharing)
Route::get('/view-invoice/{id}', [InvoiceController::class, 'viewInvoice'])->name('view.invoice');
Route::get('/download-invoice/{id}', [InvoiceController::class, 'downloadInvoice'])->name('download.invoice');

Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.forgot');
Route::post('/forgot-password/verify-email', [AuthController::class, 'verifyEmail'])->name('password.verify-email');
Route::post('/forgot-password/update', [AuthController::class, 'updateForgotPassword'])->name('password.update');

Route::get('/run-migration-now', function () {
    echo "<pre>=== Starting DB Migration via Route ===\n";
    try {
        // 1. Add columns to acc_inquirys
        \Illuminate\Support\Facades\Schema::table('acc_inquirys', function (\Illuminate\Database\Schema\Blueprint $table) {
            if (!\Illuminate\Support\Facades\Schema::hasColumn('acc_inquirys', 'diet')) {
                $table->text('diet')->nullable();
                echo "Added column 'diet' to acc_inquirys\n";
            }
            if (!\Illuminate\Support\Facades\Schema::hasColumn('acc_inquirys', 'exercise')) {
                $table->text('exercise')->nullable();
                echo "Added column 'exercise' to acc_inquirys\n";
            }
            if (!\Illuminate\Support\Facades\Schema::hasColumn('acc_inquirys', 'sleep')) {
                $table->text('sleep')->nullable();
                echo "Added column 'sleep' to acc_inquirys\n";
            }
            if (!\Illuminate\Support\Facades\Schema::hasColumn('acc_inquirys', 'water')) {
                $table->text('water')->nullable();
                echo "Added column 'water' to acc_inquirys\n";
            }
            if (!\Illuminate\Support\Facades\Schema::hasColumn('acc_inquirys', 'joined_program_ids')) {
                $table->text('joined_program_ids')->nullable();
                echo "Added column 'joined_program_ids' to acc_inquirys\n";
            }
            if (!\Illuminate\Support\Facades\Schema::hasColumn('acc_inquirys', 'programs_array')) {
                $table->text('programs_array')->nullable();
                echo "Added column 'programs_array' to acc_inquirys\n";
            }
            if (!\Illuminate\Support\Facades\Schema::hasColumn('acc_inquirys', 'cash_payment')) {
                $table->text('cash_payment')->nullable();
                echo "Added column 'cash_payment' to acc_inquirys\n";
            }
            if (!\Illuminate\Support\Facades\Schema::hasColumn('acc_inquirys', 'gpay_payment')) {
                $table->text('gpay_payment')->nullable();
                echo "Added column 'gpay_payment' to acc_inquirys\n";
            }
            if (!\Illuminate\Support\Facades\Schema::hasColumn('acc_inquirys', 'cheque_payment')) {
                $table->text('cheque_payment')->nullable();
                echo "Added column 'cheque_payment' to acc_inquirys\n";
            }
            if (!\Illuminate\Support\Facades\Schema::hasColumn('acc_inquirys', 'due_payment')) {
                $table->text('due_payment')->nullable();
                echo "Added column 'due_payment' to acc_inquirys\n";
            }
        });

        // 2. Migrate legacy data from opts + opt_meta to acc_inquirys
        $inquiries = \Illuminate\Support\Facades\DB::table('acc_inquirys')->get();
        $migratedCount = 0;
        foreach ($inquiries as $inquiry) {
            $opt = \Illuminate\Support\Facades\DB::table('opts')
                ->where('patient_id', $inquiry->patient_id)
                ->where(function ($q) {
                    $q->whereNull('delete_status')
                      ->orWhere('delete_status', '')
                      ->orWhere('delete_status', '0');
                })
                ->orderByDesc('id')
                ->first();

            if (!$opt && !empty($inquiry->id)) {
                $opt = \Illuminate\Support\Facades\DB::table('opts')
                    ->where('patient_id', (string)$inquiry->id)
                    ->where(function ($q) {
                        $q->whereNull('delete_status')
                          ->orWhere('delete_status', '')
                          ->orWhere('delete_status', '0');
                    })
                    ->orderByDesc('id')
                    ->first();
            }

            if ($opt) {
                $metas = \Illuminate\Support\Facades\DB::table('opt_meta')
                    ->where('opt_id', $opt->id)
                    ->get()
                    ->pluck('meta_value', 'meta_key')
                    ->toArray();

                $updateData = [];
                $fields = ['diet', 'exercise', 'sleep', 'water', 'joined_program_ids', 'programs_array', 'cash_payment', 'gpay_payment', 'cheque_payment', 'due_payment', 'inquiry_foc'];
                
                foreach ($fields as $field) {
                    if (isset($metas[$field]) && $metas[$field] !== '') {
                        $updateData[$field] = $metas[$field];
                    }
                }

                if (!empty($updateData)) {
                    \Illuminate\Support\Facades\DB::table('acc_inquirys')->where('id', $inquiry->id)->update($updateData);
                    $migratedCount++;
                }
            }
        }
        echo "=== Migration Complete ===\n";
        echo "Successfully migrated data for {$migratedCount} patients!\n";
    } catch (\Exception $e) {
        echo "ERROR: " . $e->getMessage() . "\n";
    }
});