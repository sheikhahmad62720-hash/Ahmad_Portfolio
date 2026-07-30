<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\Auth\AuthController;
use App\Http\Controllers\API\Public\PortfolioController;
use App\Http\Controllers\API\Public\BlogController;
use App\Http\Controllers\API\Public\ContactController;
use App\Http\Controllers\API\Public\NewsletterController;
use App\Http\Controllers\API\Admin\DashboardController;
use App\Http\Controllers\API\Admin\ProjectController as AdminProjectController;
use App\Http\Controllers\API\Admin\BlogController as AdminBlogController;
use App\Http\Controllers\API\Admin\SkillController;
use App\Http\Controllers\API\Admin\MessageController;
use App\Http\Controllers\API\Admin\ServiceController;
use App\Http\Controllers\API\Admin\TestimonialController;
use App\Http\Controllers\API\Admin\CertificateController;
use App\Http\Controllers\API\Admin\MediaController;
use App\Http\Controllers\API\Admin\AnalyticsController;
use App\Http\Controllers\API\Admin\SettingController;
use App\Http\Controllers\API\Admin\UploadController;
use App\Http\Controllers\API\Admin\ExperienceController;
use App\Http\Controllers\API\Admin\EducationController;

// Public API Routes (v1)
Route::prefix('v1')->group(function () {

    // Authentication
    Route::post('/auth/login', [AuthController::class, 'login']);
    Route::post('/auth/register', [AuthController::class, 'register']);

    // Portfolio - Public
    Route::prefix('portfolio')->group(function () {
        Route::get('/', [PortfolioController::class, 'index']);
        Route::get('/projects', [PortfolioController::class, 'projects']);
        Route::get('/projects/{slug}', [PortfolioController::class, 'projectDetail']);
        Route::get('/skills', [PortfolioController::class, 'skills']);
        Route::get('/services', [PortfolioController::class, 'services']);
        Route::get('/experience', [PortfolioController::class, 'experience']);
        Route::get('/education', [PortfolioController::class, 'education']);
        Route::get('/certificates', [PortfolioController::class, 'certificates']);
        Route::get('/testimonials', [PortfolioController::class, 'testimonials']);
    });

    // Blog - Public
    Route::prefix('blogs')->group(function () {
        Route::get('/', [BlogController::class, 'index']);
        Route::get('/featured', [BlogController::class, 'featured']);
        Route::get('/recent', [BlogController::class, 'recent']);
        Route::get('/categories', [BlogController::class, 'categories']);
        Route::get('/tags', [BlogController::class, 'tags']);
        Route::get('/{slug}', [BlogController::class, 'show']);
    });

    // Contact Form
    Route::post('/contact', [ContactController::class, 'submit']);

    // Newsletter
    Route::post('/newsletter/subscribe', [NewsletterController::class, 'subscribe']);
    Route::post('/newsletter/unsubscribe', [NewsletterController::class, 'unsubscribe']);

    // Protected Routes (Authentication Required)
    Route::middleware('auth:sanctum')->group(function () {
        // Auth User Routes
        Route::post('/auth/logout', [AuthController::class, 'logout']);
        Route::post('/auth/refresh', [AuthController::class, 'refresh']);
        Route::get('/auth/user', [AuthController::class, 'user']);

        // Admin Routes (Admin Role Required)
        Route::middleware(['admin'])->prefix('admin')->group(function () {

            // File Upload
            Route::post('/upload', [UploadController::class, 'upload']);

            // Dashboard
            Route::get('/dashboard', [DashboardController::class, 'index']);

            // Projects Management
            Route::prefix('projects')->group(function () {
                Route::get('/', [AdminProjectController::class, 'index']);
                Route::post('/', [AdminProjectController::class, 'store']);
                Route::get('/{id}', [AdminProjectController::class, 'show']);
                Route::put('/{id}', [AdminProjectController::class, 'update']);
                Route::delete('/{id}', [AdminProjectController::class, 'destroy']);
                Route::post('/bulk-delete', [AdminProjectController::class, 'bulkDelete']);
                Route::post('/{id}/restore', [AdminProjectController::class, 'restore']);
            });

            // Blogs Management
            Route::prefix('blogs')->group(function () {
                Route::get('/', [AdminBlogController::class, 'index']);
                Route::post('/', [AdminBlogController::class, 'store']);
                Route::get('/{id}', [AdminBlogController::class, 'show']);
                Route::put('/{id}', [AdminBlogController::class, 'update']);
                Route::delete('/{id}', [AdminBlogController::class, 'destroy']);
                Route::post('/bulk-delete', [AdminBlogController::class, 'bulkDelete']);
            });

            // Skills Management
            Route::prefix('skills')->group(function () {
                Route::get('/', [SkillController::class, 'index']);
                Route::post('/', [SkillController::class, 'store']);
                Route::get('/{id}', [SkillController::class, 'show']);
                Route::put('/{id}', [SkillController::class, 'update']);
                Route::delete('/{id}', [SkillController::class, 'destroy']);
                Route::post('/reorder', [SkillController::class, 'reorder']);
            });

            // Messages Management
            Route::prefix('messages')->group(function () {
                Route::get('/', [MessageController::class, 'index']);
                Route::get('/{id}', [MessageController::class, 'show']);
                Route::put('/{id}', [MessageController::class, 'update']);
                Route::patch('/{id}', [MessageController::class, 'update']);
                Route::post('/{id}/reply', [MessageController::class, 'reply']);
                Route::delete('/{id}', [MessageController::class, 'destroy']);
                Route::post('/bulk-delete', [MessageController::class, 'bulkDelete']);
            });

            // Services Management
            Route::prefix('services')->group(function () {
                Route::get('/', [ServiceController::class, 'index']);
                Route::post('/', [ServiceController::class, 'store']);
                Route::put('/{id}', [ServiceController::class, 'update']);
                Route::delete('/{id}', [ServiceController::class, 'destroy']);
            });

            // Testimonials Management
            Route::prefix('testimonials')->group(function () {
                Route::get('/', [TestimonialController::class, 'index']);
                Route::post('/', [TestimonialController::class, 'store']);
                Route::get('/{id}', [TestimonialController::class, 'show']);
                Route::put('/{id}', [TestimonialController::class, 'update']);
                Route::delete('/{id}', [TestimonialController::class, 'destroy']);
            });

            // Certificates Management
            Route::prefix('certificates')->group(function () {
                Route::get('/', [CertificateController::class, 'index']);
                Route::post('/', [CertificateController::class, 'store']);
                Route::put('/{id}', [CertificateController::class, 'update']);
                Route::delete('/{id}', [CertificateController::class, 'destroy']);
            });

            // Media Management
            Route::prefix('media')->group(function () {
                Route::get('/', [MediaController::class, 'index']);
                Route::post('/', [MediaController::class, 'store']);
                Route::put('/{id}', [MediaController::class, 'update']);
                Route::delete('/{id}', [MediaController::class, 'destroy']);
                Route::get('/folders', [MediaController::class, 'folders']);
                Route::post('/folders', [MediaController::class, 'createFolder']);
            });

            // Analytics
            Route::prefix('analytics')->group(function () {
                Route::get('/visitors', [AnalyticsController::class, 'visitors']);
                Route::get('/page-views', [AnalyticsController::class, 'pageViews']);
                Route::get('/geography', [AnalyticsController::class, 'geography']);
                Route::get('/devices', [AnalyticsController::class, 'devices']);
                Route::get('/browsers', [AnalyticsController::class, 'browsers']);
                Route::get('/content', [AnalyticsController::class, 'content']);
            });

            // Experience Management
            Route::prefix('experience')->group(function () {
                Route::get('/', [ExperienceController::class, 'index']);
                Route::post('/', [ExperienceController::class, 'store']);
                Route::get('/{id}', [ExperienceController::class, 'show']);
                Route::put('/{id}', [ExperienceController::class, 'update']);
                Route::delete('/{id}', [ExperienceController::class, 'destroy']);
            });

            // Education Management
            Route::prefix('education')->group(function () {
                Route::get('/', [EducationController::class, 'index']);
                Route::post('/', [EducationController::class, 'store']);
                Route::get('/{id}', [EducationController::class, 'show']);
                Route::put('/{id}', [EducationController::class, 'update']);
                Route::delete('/{id}', [EducationController::class, 'destroy']);
            });

            // Settings Management
            Route::prefix('settings')->group(function () {
                Route::get('/', [SettingController::class, 'index']);
                Route::put('/', [SettingController::class, 'update']);
                Route::get('/{key}', [SettingController::class, 'show']);
            });
        });
    });
});
