<?php

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Services\API\ProductService;
use App\Services\API\CategoryService;
use App\Services\API\SupplierService;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\UserController;
use App\Services\API\SubcategoryService;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\SubcategoryController;
use App\Http\Controllers\ProductsByCategoryController;
use App\Http\Controllers\ProductsBySubcategoryController;
use App\Http\Controllers\SubcategoriesByCategoryController;

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

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::group(['middleware' => ['auth', 'verified', 'role:admin']], function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    Route::resources([
        'categories' => CategoryController::class,
        'units' => UnitController::class,
        'products' => ProductController::class,
        'suppliers' => SupplierController::class,
        'customers' => CustomerController::class,
        'users' => UserController::class,
        'purchases' => PurchaseController::class,
        'subcategories-by-category' => SubcategoriesByCategoryController::class,
        'products-by-category' => ProductsByCategoryController::class,
        'products-by-subcategory' => ProductsBySubcategoryController::class,
        'subcategories' => SubcategoryController::class,
    ]);

    // API routes
    Route::get('/products-list/{category_id}', function (string $category_id) {
        return ProductService::getProducts($category_id);
    })->name('products-list');

    Route::get('/search-suppliers', function (Request $request) {
        return SupplierService::searchSuppliers($request->search);
    })->name('search-suppliers');

    Route::get('/search-products', function (Request $request) {
        return ProductService::searchSuppliers($request->search);
    })->name('search-products');

    Route::get('/search-category', function (Request $request) {
        return CategoryService::searchCategory($request->search);
    })->name('search-category');

    Route::get('/search-subcategory', function (Request $request) {
        return SubcategoryService::searchSubcategory($request->category_id, $request->search);
    })->name('search-subcategory');
});

require __DIR__.'/auth.php';
