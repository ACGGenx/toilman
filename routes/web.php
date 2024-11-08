<?php

// Controllers
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageManagementController;
use App\Http\Controllers\Security\RolePermission;
use App\Http\Controllers\Security\RoleController;
use App\Http\Controllers\Security\PermissionController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Artisan;
// Packages
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\EnquiryController;
use App\Http\Controllers\SliderController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

require __DIR__ . '/auth.php';

Route::get('/storage', function () {
    Artisan::call('storage:link');
});

// Temporary route for design preview - REMOVE THIS IN PRODUCTION
Route::get('/product/{slug}', [ProductController::class, 'show'])
    ->name('product.show')
    ->where('slug', '[a-z0-9-]+');
    // ->middleware(['auth', 'prevent-back-history']);
 

//UI Pages Routs
Route::get('/', [HomeController::class, 'signin'])
    ->name('auth.signin')
    ->middleware('guest');

Route::group(['prefix' => 'auth'], function () {
    Route::get('signin', [HomeController::class, 'signin'])
        ->name('auth.signin')
        ->middleware('guest');
    Route::get('recoverpw', [HomeController::class, 'recoverpw'])
        ->name('auth.recoverpw')
        ->middleware('guest');
});

Route::group(['middleware' => ['auth', 'prevent-back-history']], function () {
    // Permission Module
    Route::get('/role-permission', [RolePermission::class, 'index'])->name('role.permission.list');
    Route::resource('permission', PermissionController::class);
    Route::resource('role', RoleController::class);

    // Dashboard Routes
    Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');

    // Users Module
    Route::resource('users', UserController::class);
    Route::post('/user/change-status', [UserController::class, 'changeStatus'])->name('user.change-status');
    Route::resource('categories', CategoryController::class);
    Route::post('/categories/change-status', [CategoryController::class, 'changeStatus'])->name('categories.change-status');
    Route::resource('products', ProductController::class);
    Route::post('/product/delete-image', [ProductController::class, 'deleteImage'])->name('product.delete-image');
    Route::post('/product/set-primary-image', [ProductController::class, 'setAsPrimary'])->name('product.set-primary-image');
    Route::post('/product/change-status', [ProductController::class, 'changeStatus'])->name('product.change-status');
    Route::get('/check-slug', [ProductController::class, 'checkSlug'])->name('check-slug');
    Route::get('/check-slug-cat', [CategoryController::class, 'checkSlug'])->name('check-slug-cat');
    Route::get('/enquiries', [EnquiryController::class, 'listEnquiries'])->name('enquiries.list');
    Route::post('/enquiries/download', [EnquiryController::class, 'downloadCSV'])->name('enquiries.download');
    Route::resource('pages', PageManagementController::class);
    Route::post('/pages/change-status', [PageManagementController::class, 'changeStatus'])->name('page.change-status');
    Route::resource('sliders', SliderController::class);
    Route::post('/sliders/{slider}/update-image-order', [SliderController::class, 'updateImageOrder'])->name('sliders.updateImageOrder');
    Route::post('/sliders/{slider}/remove-image', [SliderController::class, 'removeImage'])->name('sliders.removeImage');
    Route::post('/sliders/{slider}/upload-images', [SliderController::class, 'uploadImages'])->name('sliders.uploadImages');
});


Route::get('/enquiry', [EnquiryController::class, 'index'])->name('enquiries.index');
Route::get('/enquiry-bulk', [EnquiryController::class, 'showBulkEnquiryForm'])->name('enquiries.bulk');
Route::post('/enquiry/submit', [EnquiryController::class, 'submitEnquiry'])->name('enquiry.submit');
Route::post('/enquiry/submit-bulk', [EnquiryController::class, 'submitBulkEnquiry'])->name('enquiry.submit-bulk');
// Route::get('/product/{slug}', [ProductController::class, 'show'])->name('products.show');
Route::get('privacy-policy', [HomeController::class, 'privacypolicy'])->name('pages.privacy-policy');
Route::get('terms-of-use', [HomeController::class, 'termsofuse'])->name('pages.term-of-use');

// Category product listing routes
Route::get('/category/{slug?}', [CategoryController::class, 'showProducts'])->name('category.products');
Route::post('/category/filter', [CategoryController::class, 'filterProducts'])->name('category.filter');