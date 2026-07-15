<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\InternshipController;
use App\Http\Controllers\RentalController;
use App\Http\Controllers\UserController;

use App\Http\Controllers\PublicInternshipController;
use App\Http\Controllers\PublicRentalController;
use App\Http\Controllers\PublicDashboardController;

use App\Http\Controllers\InternshipScrapeController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\FavoritesController;

/*
|--------------------------------------------------------------------------
| AUTH ROUTES (Login/Register pages)
|--------------------------------------------------------------------------
*/
Auth::routes();

// Google OAuth Authentication Routes
Route::get('/auth/google', [\App\Http\Controllers\Auth\GoogleController::class, 'redirectToGoogle'])
    ->name('auth.google');
Route::get('/auth/google/callback', [\App\Http\Controllers\Auth\GoogleController::class, 'handleGoogleCallback'])
    ->name('auth.google.callback');

// Explicit Email Verification Routes
Route::get('/email/verify', [\App\Http\Controllers\Auth\VerificationController::class, 'show'])
    ->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', [\App\Http\Controllers\Auth\VerificationController::class, 'verify'])
    ->name('verification.verify');

Route::post('/email/resend-unauth', [\App\Http\Controllers\Auth\VerificationController::class, 'resendUnauth'])
    ->name('verification.resend.unauth');

// Role Selection and Custom Login Routes
Route::get('/login/role', [\App\Http\Controllers\Auth\LoginController::class, 'showRoleSelection'])
    ->name('login.role');

// Override default login GET view to redirect to role selection
Route::get('/login', function () {
    return redirect()->route('login.role');
})->name('login');

Route::get('/student/login', [\App\Http\Controllers\Auth\LoginController::class, 'showStudentLoginForm'])
    ->name('student.login');

Route::get('/company/login', [\App\Http\Controllers\Auth\LoginController::class, 'showCompanyLoginForm'])
    ->name('company.login');

Route::get('/admin/login', [\App\Http\Controllers\Auth\LoginController::class, 'showAdminLoginForm'])
    ->name('admin.login');


/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES (Visitor / User Side)
|--------------------------------------------------------------------------
*/
Route::get('/', [PublicDashboardController::class, 'index'])
    ->name('public.dashboard');

// Public internships
Route::get('/browse-internships', [PublicInternshipController::class, 'index'])
    ->name('public.internships.index');

Route::get('/browse-internships/{internship}', [PublicInternshipController::class, 'show'])
    ->name('public.internships.show');

// Public rentals (accommodation)
Route::get('/accommodation', [PublicRentalController::class, 'index'])
    ->name('public.rentals.index');

Route::get('/accommodation/{rental}', [PublicRentalController::class, 'show'])
    ->name('public.rentals.show');

/*
|--------------------------------------------------------------------------
| REGISTERED USER ROUTES (User Side)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'student', 'verified.student'])->get('/profile', [UserProfileController::class, 'index'])
    ->name('user.profile');
Route::middleware(['auth', 'student', 'verified.student'])->put('/profile', [UserProfileController::class, 'update'])
    ->name('user.profile.update');

Route::middleware(['auth', 'student', 'verified.student'])->group(function () {

    Route::get('/favorites', [FavoritesController::class, 'index'])
        ->name('favorites.index');

    // Review and Rating routes
    Route::post('/browse-internships/{internship}/reviews', [\App\Http\Controllers\InternshipReviewController::class, 'store'])
        ->name('public.internships.reviews.store');
    Route::post('/accommodation/{rental}/reviews', [\App\Http\Controllers\AccommodationReviewController::class, 'store'])
        ->name('public.rentals.reviews.store');

    // internship fav
    Route::post('/favorites/internships/{internship}', [FavoritesController::class, 'addInternship'])
        ->name('favorites.internships.add');

    Route::delete('/favorites/internships/{internship}', [FavoritesController::class, 'removeInternship'])
        ->name('favorites.internships.remove');

    // rental fav
    Route::post('/favorites/rentals/{rental}', [FavoritesController::class, 'addRental'])
        ->name('favorites.rentals.add');

    Route::delete('/favorites/rentals/{rental}', [FavoritesController::class, 'removeRental'])
        ->name('favorites.rentals.remove');

    // Notification preferences
    Route::get('/preferences', [\App\Http\Controllers\UserPreferenceController::class, 'edit'])
        ->name('preferences.edit');
    
    Route::post('/preferences', [\App\Http\Controllers\UserPreferenceController::class, 'update'])
        ->name('preferences.update');

    // Onboarding routes
    Route::get('/onboarding', [\App\Http\Controllers\OnboardingController::class, 'welcome'])
        ->name('onboarding.welcome');
    
    Route::get('/onboarding/step1', [\App\Http\Controllers\OnboardingController::class, 'step1'])
        ->name('onboarding.step1');
    
    // Step 2 - both GET (for back button) and POST (for form submission)
    Route::get('/onboarding/step2', [\App\Http\Controllers\OnboardingController::class, 'step2'])
        ->name('onboarding.step2');
    Route::post('/onboarding/step2', [\App\Http\Controllers\OnboardingController::class, 'step2']);
    
    Route::post('/onboarding/complete', [\App\Http\Controllers\OnboardingController::class, 'complete'])
        ->name('onboarding.complete');
    
    Route::post('/onboarding/skip', [\App\Http\Controllers\OnboardingController::class, 'skip'])
        ->name('onboarding.skip');
});

/*
|--------------------------------------------------------------------------
| HOME REDIRECT (so /home works for both roles)
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', function () {
    $user = auth()->user();

    if ($user && ($user->role ?? 'user') === 'admin') {
        return redirect()->route('admin.dashboard');
    }

    if ($user && ($user->role ?? 'user') === 'company') {
        return redirect()->route('company.dashboard');
    }

    return redirect()->route('public.dashboard');
})->name('home');

// Guest Company Registration Routes
Route::get('/company/register', [\App\Http\Controllers\Auth\CompanyRegisterController::class, 'showRegistrationForm'])->name('company.register');
Route::post('/company/register', [\App\Http\Controllers\Auth\CompanyRegisterController::class, 'register']);

// Company Protected Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/company/dashboard', [\App\Http\Controllers\CompanyDashboardController::class, 'index'])->name('company.dashboard');
    
    Route::middleware(['verified.company'])->group(function () {
        Route::get('/company/internships/create', [\App\Http\Controllers\CompanyInternshipController::class, 'create'])->name('company.internships.create');
        Route::post('/company/internships', [\App\Http\Controllers\CompanyInternshipController::class, 'store'])->name('company.internships.store');
        Route::get('/company/internships/{internship}/edit', [\App\Http\Controllers\CompanyInternshipController::class, 'edit'])->name('company.internships.edit');
        Route::put('/company/internships/{internship}', [\App\Http\Controllers\CompanyInternshipController::class, 'update'])->name('company.internships.update');
        Route::delete('/company/internships/{internship}', [\App\Http\Controllers\CompanyInternshipController::class, 'destroy'])->name('company.internships.destroy');
    });
});

/*
|--------------------------------------------------------------------------
| ADMIN ROUTES (Admin Side)
|--------------------------------------------------------------------------
| Only logged-in ADMIN can access these.
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin'])->group(function () {

    Route::get('/admin', [AdminDashboardController::class, 'index'])
        ->name('admin.dashboard');

    Route::post('/admin/send-recommendations', [AdminDashboardController::class, 'sendRecommendations'])
        ->name('admin.send-recommendations');

    Route::delete('/internships/bulk-delete', [InternshipController::class, 'bulkDestroy'])->name('internships.bulk-delete');
    Route::resource('internships', InternshipController::class);

    Route::delete('/rentals/bulk-delete', [RentalController::class, 'bulkDestroy'])->name('rentals.bulk-delete');
    Route::resource('rentals', RentalController::class);

    Route::delete('/users/bulk-delete', [UserController::class, 'bulkDestroy'])->name('users.bulk-delete');
    Route::resource('users', UserController::class);

    Route::post('/internships/scrape', [\App\Http\Controllers\InternshipScrapeController::class, 'scrape'])
    ->name('internships.scrape');

    Route::get('/mock-internships', function () {
        return view('mock.internships');
    })->name('mock.internships');

    Route::post('/rentals/scrape', [RentalController::class, 'scrapeReal'])
        ->name('rentals.scrape');

    // Company verification management
    Route::get('/admin/verifications', [\App\Http\Controllers\Admin\CompanyVerificationController::class, 'index'])->name('admin.verifications.index');
    Route::post('/admin/verifications/{profile}/approve', [\App\Http\Controllers\Admin\CompanyVerificationController::class, 'approve'])->name('admin.verifications.approve');
    Route::post('/admin/verifications/{profile}/reject', [\App\Http\Controllers\Admin\CompanyVerificationController::class, 'reject'])->name('admin.verifications.reject');

    // Company internships monitoring
    Route::get('/admin/company-internships', [\App\Http\Controllers\Admin\AdminCompanyInternshipController::class, 'index'])->name('admin.company-internships.index');
    Route::delete('/admin/company-internships/bulk-delete', [\App\Http\Controllers\Admin\AdminCompanyInternshipController::class, 'bulkDestroy'])->name('admin.company-internships.bulk-delete');
    Route::post('/admin/company-internships/{internship}/suspend', [\App\Http\Controllers\Admin\AdminCompanyInternshipController::class, 'toggleSuspend'])->name('admin.company-internships.suspend');
    Route::delete('/admin/company-internships/{internship}', [\App\Http\Controllers\Admin\AdminCompanyInternshipController::class, 'destroy'])->name('admin.company-internships.destroy');
});

// SMTP Mail Connection Test Route
Route::get('/test-email', function () {
    try {
        \Illuminate\Support\Facades\Mail::raw('This is a test email from InternStay confirming your SMTP setup is working correctly!', function ($message) {
            $message->to(env('MAIL_USERNAME', 'n.alishamaisarah.mn@gmail.com'))
                    ->subject('InternStay SMTP Setup Test');
        });
        return '<div style="font-family: sans-serif; padding: 20px; color: #155724; background: #d4edda; border: 1px solid #c3e6cb; border-radius: 8px;">'
             . '<strong>SUCCESS:</strong> Test email sent successfully to <strong>' . htmlspecialchars(env('MAIL_USERNAME')) . '</strong>! Check your inbox/spam folder.'
             . '</div>';
    } catch (\Exception $e) {
        return '<div style="font-family: sans-serif; padding: 20px; color: #721c24; background: #f8d7da; border: 1px solid #f5c6cb; border-radius: 8px;">'
             . '<strong>SMTP ERROR:</strong> ' . htmlspecialchars($e->getMessage())
             . '<br><br><strong>Troubleshooting:</strong>'
             . '<ul>'
             . '<li>Check that your internet connection is active.</li>'
             . '<li>Ensure your Gmail App Password is correct in your <code>.env</code> file (spaces removed).</li>'
             . '<li>Verify that your Gmail account has 2-Step Verification enabled.</li>'
             . '</ul>'
             . '</div>';
    }
});
