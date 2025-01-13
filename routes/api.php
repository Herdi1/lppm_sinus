<?php

use App\Http\Controllers\ActivityDetailController;
use App\Http\Controllers\ActivityPeriodController;
use App\Models\Research;
use Illuminate\Http\Request;
use App\Models\ProgressReport;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RoleController;
use Spatie\Permission\Models\Permission;
use App\Http\Controllers\ScopeController;
use App\Http\Controllers\SchemeController;
use App\Http\Controllers\StatusController;
use App\Http\Controllers\LogbookController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\ResearchController;
use App\Http\Controllers\SubstanceController;
use App\Http\Controllers\OutputTypeController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\BudgetGroupController;
use App\Http\Controllers\TktCategoryController;
use App\Http\Controllers\TktIndicatorController;
use App\Http\Controllers\ResearchFocusController;
use App\Http\Controllers\ResearchThemeController;
use App\Http\Controllers\ResearchTopicController;
use App\Http\Controllers\OutputCategoryController;
use App\Http\Controllers\ResearchReviewController;
use App\Http\Controllers\BudgetComponentController;
use App\Http\Controllers\ComunityServiceComponentController;
use App\Http\Controllers\ComunityServiceController;
use App\Http\Controllers\FocusRIRNController;
use App\Http\Controllers\FocusThematicController;
use App\Http\Controllers\MediaOutputCategoryController;
use App\Http\Controllers\MediaOutputTypeController;
use App\Http\Controllers\PartnerOutputCategoryController;
use App\Http\Controllers\PartnerOutputTypeController;
use App\Http\Controllers\PublicationOutputCategoryController;
use App\Http\Controllers\PublicationOutputTypeController;
use App\Http\Controllers\ProgressReportController;
use App\Http\Controllers\ResearchComponentController;
use App\Http\Controllers\ResearchFinalReportController;
use App\Http\Controllers\ResearchMonevController;
use App\Http\Controllers\ResearchPriorityController;
use App\Http\Controllers\ScienceCluster1Controller;
use App\Http\Controllers\ScienceCluster2Controller;
use App\Http\Controllers\ScienceCluster3Controller;
use App\Http\Controllers\ServiceFinalReportController;
use App\Http\Controllers\ServiceLogbookController;
use App\Http\Controllers\ServiceMonevController;
use App\Http\Controllers\ServiceProgressReportController;
use App\Http\Controllers\ServiceReviewController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\ApiKeyMiddleware;
use App\Models\ActivityDetail;

Route::get('/', function () {
    return 'API';
});

Route::middleware([ApiKeyMiddleware::class])->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    //auth route
    Route::post('/login', [AuthController::class, 'login']);
    Route::middleware('auth:api')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/me', [AuthController::class, 'me']);
        Route::post('/update-password', [AuthController::class, 'changePassword']);
        Route::post('/update-profile', [AuthController::class, 'updateProfile']);
    });
    //route users
    Route::get('/users', [UserController::class, 'getAllUsers']);
    Route::get('/users/roles/{id}', [UserController::class, 'getUserByRole']);
    Route::post('/users/create', [UserController::class, 'createUser']);
    Route::post('/users/reset-password/{id}', [UserController::class, 'resetPassword']);
    Route::post('/users/give-permission-to-role', [UserController::class, 'assignPermissionToRole']);
    Route::post('/users/give-role-to-user', [UserController::class, 'assignRoleToUser']);
    Route::post('/users/remove-role-from-user', [UserController::class, 'removeRoleFromUser']);

    //route usulan penelitian
    Route::apiResource('research', ResearchController::class);
    // Route::prefix('/research')->
    Route::post('/research/{id}/status-update', [ResearchController::class, 'updateStatus']);
    Route::post('/research/{id}/reviewers', [ResearchController::class, 'addReviewers']);
    Route::get('/reviewer/{id}/research', [ResearchController::class, 'getResearchByReviewer']);
    Route::get('/research/download/{researchId}', [ResearchController::class, 'researchProposal']);
    Route::put('/research/set-deadline/{id}', [ResearchController::class, 'setReportDeadline']);
    Route::post('/research/set-approval-funds/{id}', [ResearchController::class, 'setApprovalFunds']);

    //route usulan pengabdian masyarakat
    Route::apiResource('comunity-service', ComunityServiceController::class);
    Route::post('/comunity-service/{id}/status-update', [ComunityServiceController::class, 'updateStatus']);
    Route::get('/comunity-service/download/{communityService}', [ComunityServiceController::class, 'comunityServiceProposal']);
    Route::get('/comunity-service/{id}/status-history', [StatusController::class, 'getStatusHistory']);
    Route::post('/comunity-service/{id}/reviewers', [ComunityServiceController::class, 'addReviewers']);
    Route::get('/user/{id}/comunity-service', [ComunityServiceController::class, 'getServiceByUser']);
    Route::get('/status/{id}/comunity-service', [ComunityServiceController::class, 'getServiceByStatus']);
    Route::get('/reviewer/{id}/comunity-service', [ComunityServiceController::class, 'getServiceByReviewer']);

    //route review usulan pengabdian
    Route::middleware('auth:api')->apiResource('comunity-service-reviews', ServiceReviewController::class);
    Route::get('comunity-service-reviews/{serviceId}/comunity-services', [ServiceReviewController::class, 'getReviewByServiceId']);

    //route review usulan penelitian
    Route::middleware('auth:api')->apiResource(
        'research-reviews',
        ResearchReviewController::class
    );
    Route::get(
        'research-reviews/{researchId}/research',
        [ResearchReviewController::class, 'getReviewByResearchId']
    );

    //route catatan harian
    Route::apiResource('/logbook-service', ServiceLogbookController::class);
    Route::apiResource('/logbook', LogbookController::class);
    Route::get('logbook/{request}', [LogbookController::class, 'getLogbookByResearch']);

    //route laporan kemajuan
    Route::apiResource('progress-report', ProgressReportController::class);
    Route::apiResource('service-progress-report', ServiceProgressReportController::class);

    //route laporan kemajuan
    Route::apiResource('service-final-report', ServiceFinalReportController::class);

    //route monev research
    Route::apiResource('monev-research', ResearchMonevController::class);
    Route::get('/monev-research/{id}', [ResearchMonevController::class, 'getMonevReviewByResearchId']);

    //route laporan akhir
    Route::apiResource('research-final-report', ResearchFinalReportController::class);

    //route monev community service
    Route::apiResource('monev-comunity-service', ServiceMonevController::class);
    Route::get('/monev-comunity-service/{id}', [ServiceMonevController::class, 'getMonevReviewByServiceId']);

    //route download dokumen
    Route::get('download/document/{path}', [DocumentController::class, 'downloadDocument'])
        ->where('path', '.*');

    //periode kegiatan
    Route::apiResource('periods', ActivityPeriodController::class);
    Route::get('/reminder', [ActivityPeriodController::class, 'deadlineReminder']);

    //activity
    Route::get('/activity/{type}', [ActivityDetailController::class, 'getDetail']);
    Route::get('/activities', [ActivityDetailController::class, 'getAllActivity']);
    Route::post('/activity/add', [ActivityDetailController::class, 'addActivity']);
    Route::put('/activity/update/{activityDetail}', [ActivityDetailController::class, 'updateActivityDetail']);

    Route::get('/budget-component', [BudgetComponentController::class, 'getBudgetComponent']);
    Route::get('/budget-group', [BudgetGroupController::class, 'getBudgetGroup']);
    Route::get('/output-category/{scheme}', [OutputCategoryController::class, 'getOutputCategory']);
    Route::get('/output-type', [OutputTypeController::class, 'getOutputType']);

    Route::get('/category', [ResearchComponentController::class, 'getResearchCategory']);
    Route::get('/research-focus', [ResearchComponentController::class, 'getResearchFocus']);
    Route::get('/research-theme/{focus}', [ResearchComponentController::class, 'getResearchTheme']);
    Route::get('/research-topic/{theme}', [ResearchComponentController::class, 'getResearchTopic']);
    Route::get('/research-scheme/{target}', [ResearchComponentController::class, 'getResearchScheme']);
    Route::get('/cluster1', [ResearchComponentController::class, 'getCluster1']);
    Route::get('/cluster2', [ResearchComponentController::class, 'getCluster2']);
    Route::get('/cluster3', [ResearchComponentController::class, 'getCluster3']);
    Route::get('/research-priority', [ResearchComponentController::class, 'getResearchPriority']);
    Route::get('/scope', [ResearchComponentController::class, 'getScope']);
    Route::get('/substance', [ResearchComponentController::class, 'getSubstance']);
    Route::get('/status', [StatusController::class, 'getStatus']);
    Route::get('/tkt-category', [TktCategoryController::class, 'getTktCategory']);
    Route::get('/tkt-indicator/{category_id}/{level}', [TktIndicatorController::class, 'getTktIndicator']);

    Route::get('/research/{id}/status-history', [StatusController::class, 'getStatusHistory']);

    Route::get('/service-category', [ComunityServiceComponentController::class, 'getServiceCategory']);
    Route::get('/service-scheme', [ComunityServiceComponentController::class, 'getServiceScheme']);
    Route::get('/service-scope', [ComunityServiceComponentController::class, 'getServiceScope']);
    Route::get('/service-focus-temathic', [ComunityServiceComponentController::class, 'getFocusTemathic']);
    Route::get('/service-focus-rirn', [ComunityServiceComponentController::class, 'getFocusRirn']);
    Route::get('/service-cluster1', [ComunityServiceComponentController::class, 'getCluster1']);
    Route::get('/service-cluster2/{cluster1}', [ComunityServiceComponentController::class, 'getCluster2']);
    Route::get('/service-cluster3/{cluster2}', [ComunityServiceComponentController::class, 'getCluster3']);
    Route::get('/partner-output-category', [ComunityServiceComponentController::class, 'getPartnerOutputCategories']);
    Route::get('/partner-output-type', [ComunityServiceComponentController::class, 'getPartnerOutputType']);
    Route::get('/publication-output-category', [ComunityServiceComponentController::class, 'getPublicationOutputCategories']);
    Route::get('/publication-output-type/{category}', [ComunityServiceComponentController::class, 'getPublicationOutputType']);
    Route::get('/media-output-category', [ComunityServiceComponentController::class, 'getMediaOutputCategories']);
    Route::get('/media-output-type/{category}', [ComunityServiceComponentController::class, 'getMediaOutputType']);
    Route::get('/video-output-category', [ComunityServiceComponentController::class, 'getVideoOutputCategories']);
    Route::get('/video-output-type', [ComunityServiceComponentController::class, 'getVideoOutputType']);
    Route::get('/partner-group', [ComunityServiceComponentController::class, 'getPartnerGroup']);
    Route::get('/partner-type', [ComunityServiceComponentController::class, 'getPartnerType']);
    Route::get('/supporting-file', [ComunityServiceComponentController::class, 'getSupportingFileType']);

    
});

Route::middleware('auth:api')->group(function () {});
