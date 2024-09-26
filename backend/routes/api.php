<?php

use App\Http\Controllers\Auth\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ChatGPTController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\ProductReceiptController;

Route::post('/v1/auth/login', [AuthController::class, 'login'])->middleware('api');
Route::post('/v1/auth/signup', [AuthController::class, 'signup'])->middleware('api');
Route::group(
    [

        'middleware' => [
            'api',
            'jwt'
        ],
        'prefix' => 'v1/auth'

    ],
    function ($router) {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::post('refresh', [AuthController::class, 'refresh']);
        Route::get('me', [AuthController::class, 'me']);
    }
);

//User Routes
Route::group(
    [

        'middleware' => [
            'api',
            'jwt',
            'check.permission:view_users'
        ],
        'prefix' => 'v1'
    ],
    function ($router) {
        //User Routes
        Route::get('users', [UserController::class, 'index']);
        Route::get('user/{id}', [UserController::class, 'show']);
        Route::post('user/add', [UserController::class, 'store']);
        Route::put('user/update/{id}', [UserController::class, 'update']);
        Route::delete('user/delete/{id}', [UserController::class, 'destroy']);
    }
);

//

Route::post('/chat', [ChatGPTController::class, 'chat']);

//Product Routes
Route::group(
    [
        'middleware' => [
            'api',
            'jwt',
        ],
        'prefix' => 'v1'
    ],
    function ($router) {
        //Product Routes
        Route::get('products', [ProductController::class, 'index'])->middleware('check.permission:view_products');
        Route::get('product/{id}', [ProductController::class, 'show'])->middleware('check.permission:view_products');
        Route::post('product/add', action: [ProductController::class, 'store'])->middleware('check.permission:create_products');
        Route::patch('product/update/{id}', action: [ProductController::class, 'update'])->middleware('check.permission:update_products');
        Route::delete('product/delete/{id}', action: [ProductController::class, 'destroy'])->middleware('check.permission:delete_products');
    }
);

//Material Routes
Route::group(
    [
        'middleware' => [
            'api',
            'jwt',
        ],
        'prefix' => 'v1'
    ],
    function ($router) {
        //Material Routes
        Route::get('materials', [MaterialController::class, 'index'])->middleware('check.permission:view_materials');
        Route::get('material/{id}', [MaterialController::class, 'show'])->middleware('check.permission:view_materials');
        Route::post('material/add', [MaterialController::class, 'store'])->middleware('check.permission:create_materials');
        Route::post('material/update/{id}', [MaterialController::class, 'update'])->middleware('check.permission:update_materials');
        Route::delete('material/delete/{id}', [MaterialController::class, 'destroy'])->middleware('check.permission:delete_materials');
    }
);

//Role Routes
Route::group(
    [
        'middleware' => [
            'api',
            'jwt',

        ],
        'prefix' => 'v1'
    ],
    function ($router) {
        //Role Routes
        Route::get('roles', [RoleController::class, 'index']);
        Route::get('role/{id}', [RoleController::class, 'show']);
        Route::post('role/add', [RoleController::class, 'store']);
        Route::put('role/update/{id}', [RoleController::class, 'update']);
        Route::delete('role/delete/{id}', [RoleController::class, 'destroy']);
    }
);


//Category Routes
Route::group(
    [
        'middleware' => [
            'api',
            'jwt',
        ],
        'prefix' => 'v1'
    ],
    function ($router) {
        //Category Routes
        Route::get('categories', [CategoryController::class, 'index']);
        Route::get('category/{id}', [CategoryController::class, 'show']);
        Route::post('category/add', [CategoryController::class, 'store']);
        Route::put('category/update/{id}', [CategoryController::class, 'update']);
        Route::delete('category/delete/{id}', [CategoryController::class, 'destroy']);
    }
);


//Product Receipt Routes

Route::group(
    [
        'middleware' => [
            'api',
            'jwt',
        ],
        'prefix' => 'v1'
    ],
    function ($router) {
        //Product Receipt Routes
        Route::get('product-receipts', [ProductReceiptController::class, 'index'])->middleware('check.permission:view_product_receipts');
        Route::get('product-receipt/{id}', [ProductReceiptController::class, 'show'])->middleware('check.permission:view_product_receipts');
        Route::post('product-receipt/add', [ProductReceiptController::class, 'store'])->middleware('check.permission:create_product_receipts');
        Route::put('product-receipt/update/{id}', [ProductReceiptController::class, 'update']);
        Route::delete('product-receipt/delete/{id}', [ProductReceiptController::class, 'destroy']);
    }
);
