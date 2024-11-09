<?php

use App\Http\Controllers\Auth\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ChatGPTController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ColorController;
use App\Http\Controllers\InventoryReportController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\MaterialExportController;
use App\Http\Controllers\MaterialReceiptController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductExportController;
use App\Http\Controllers\ProductReceiptController;
use App\Http\Controllers\ProposeController;
use App\Http\Controllers\ShelfController;
use App\Http\Controllers\WarehouseController;

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
        Route::patch('user/update/{id}', [UserController::class, 'update']);
        Route::delete('user/delete/{id}', [UserController::class, 'destroy']);
    }
);

//Color Routes

Route::group(
    [
        'middleware' => [
            'api',
            'jwt',
        ],
        'prefix' => 'v1'
    ],
    function ($router) {
        //Color Routes
        Route::get('colors', [ColorController::class, 'index'])->middleware('check.permission:view_colors');
        Route::get('color/{id}', [ColorController::class, 'show'])->middleware('check.permission:view_colors');
        Route::post('color/add', [ColorController::class, 'store'])->middleware('check.permission:create_colors');
        Route::patch('color/update/{id}', [ColorController::class, 'update'])->middleware('check.permission:update_colors');
        Route::delete('color/delete/{id}', [ColorController::class, 'destroy'])->middleware('check.permission:delete_colors');
    }
);

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
        Route::patch('material/update/{id}', [MaterialController::class, 'update'])->middleware('check.permission:update_materials');
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
        Route::get('roles', [RoleController::class, 'index'])->middleware('check.permission:view_roles');
        Route::get('role/{id}', [RoleController::class, 'show'])->middleware('check.permission:view_roles');
        Route::post('role/add', [RoleController::class, 'store'])->middleware('check.permission:create_roles');
        Route::patch('role/update/{id}', [RoleController::class, 'update'])->middleware('check.permission:update_roles');
        Route::delete('role/delete/{id}', [RoleController::class, 'destroy'])->middleware('check.permission:delete_roles');
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
        Route::get('categories', [CategoryController::class, 'index'])->middleware('check.permission:view_categories');
        Route::get('categories/parent', [CategoryController::class, 'parentCategory'])->middleware('check.permission:view_categories');
        Route::get('categories/product', [CategoryController::class, 'productCategory'])->middleware('check.permission:view_categories');
        Route::get('categories/material', [CategoryController::class, 'materialCategory'])->middleware('check.permission:view_categories');
        Route::get('category/{id}', [CategoryController::class, 'show'])->middleware('check.permission:view_categories');
        Route::post('category/add', [CategoryController::class, 'store'])->middleware('check.permission:create_categories');
        Route::patch('category/update/{id}', [CategoryController::class, 'update'])->middleware('check.permission:update_categories');
        Route::delete('category/delete/{id}', [CategoryController::class, 'destroy'])->middleware('check.permission:delete_categories');
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
    }
);

//Material Receipt Routes
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
        Route::get('material-receipts', [MaterialReceiptController::class, 'index'])->middleware('check.permission:view_material_receipts');
        Route::get('material-receipt/{id}', action: [MaterialReceiptController::class, 'show'])->middleware('check.permission:view_material_receipts');
        Route::post('material-receipt/add', action: [MaterialReceiptController::class, 'store'])->middleware('check.permission:create_material_receipts');
    }

    // ->middleware('check.permission:view_material_receipts')
    // ->middleware('check.permission:create_material_receipts')
);


//Product Export Routes

Route::group(
    [
        'middleware' => [
            'api',
            'jwt',
        ],
        'prefix' => 'v1'
    ],
    function ($router) {
        //Product Export Routes
        Route::get('product-exports', [ProductExportController::class, 'index'])->middleware('check.permission:view_product_exports');
        Route::get('product-export/{id}', [ProductExportController::class, 'show'])->middleware('check.permission:view_product_exports');
        Route::post('product-export/add', [ProductExportController::class, 'store'])->middleware('check.permission:create_product_exports');
    }
);

//Material Export Routes

Route::group(
    [
        'middleware' => [
            'api',
            'jwt',
        ],
        'prefix' => 'v1'
    ],
    function ($router) {
        //Material Export Routes
        Route::get('material-exports', [MaterialExportController::class, 'index'])->middleware('check.permission:view_material_exports');
        Route::get('material-export/{id}', [MaterialExportController::class, 'show'])->middleware('check.permission:view_material_exports');
        Route::post('material-export/add', [MaterialExportController::class, 'store'])->middleware('check.permission:create_material_exports');
    }
);
//Warehouse Routes

Route::group(
    [
        'middleware' => [
            'api',
            'jwt',
        ],
        'prefix' => 'v1'
    ],
    function ($router) {
        Route::get('warehouses', [WarehouseController::class, 'index'])->middleware('check.permission:view_warehouses');
        Route::get('warehouses/product', [WarehouseController::class, 'productWarehouses'])->middleware('check.permission:view_warehouses');
        Route::get('warehouses/material', [WarehouseController::class, 'materialWarehouses'])->middleware('check.permission:view_warehouses');
        Route::get('warehouse/{id}', [WarehouseController::class, 'show'])->middleware('check.permission:view_warehouses');
        Route::post('warehouse/add', [WarehouseController::class, 'store'])->middleware('check.permission:create_warehouse');
        Route::patch('warehouse/update/{id}', [WarehouseController::class, 'update'])->middleware('check.permission:update_warehouses');
        Route::delete('warehouse/delete/{id}', [WarehouseController::class, 'destroy'])->middleware('check.permission:delete_warehouses');
        Route::get('warehouse-items/{id}', [WarehouseController::class, 'showProductOrMaterialByWarehouse'])->middleware('check.permission:view_warehouse');
    }
);

//Shelf Routes

Route::group(
    [
        'middleware' => [
            'api',
            'jwt',
        ],
        'prefix' => 'v1'
    ],
    function ($router) {
        //Shelf Routes
        Route::get('shelves', [ShelfController::class, 'index'])->middleware('check.permission:view_shelves');
        Route::get('shelf/{id}', [ShelfController::class, 'show'])->middleware('check.permission:view_shelves');
        Route::post('shelf/add', [ShelfController::class, 'store'])->middleware('check.permission:create_shelves');
        Route::patch('shelf/update/{id}', [ShelfController::class, 'update'])->middleware('check.permission:update_shelves');
        Route::delete('shelf/delete/{id}', [ShelfController::class, 'destroy'])->middleware('check.permission:delete_shelves');
        Route::get('/shelves/filter', [ShelfController::class, 'filterShelves'])->middleware('check.permission:view_shelves');
        Route::get('/shelves/items/{warehouseId}', [ShelfController::class, 'getShelfItemsByWarehouseId'])->middleware('check.permission:view_shelves');
    }
);


//Propose Routes

Route::group(
    [
        'middleware' => [
            'api',
            'jwt',
        ],
        'prefix' => 'v1'
    ],
    function ($router) {
        //Propose Routes
        Route::get('proposes', [ProposeController::class, 'index'])->middleware('check.permission:view_proposes');
        Route::get('propose/{id}', [ProposeController::class, 'show'])->middleware('check.permission:view_proposes');
        Route::post('propose/add', [ProposeController::class, 'store'])->middleware('check.permission:create_proposes');
        Route::patch('propose/update/{id}', [ProposeController::class, 'update'])->middleware('check.permission:update_proposes');
        Route::delete('propose/delete/{id}', [ProposeController::class, 'destroy'])->middleware('check.permission:delete_proposes');

        Route::patch('propose/send/{id}', [ProposeController::class, 'sendPropose'])->middleware('check.permission:send_propose');
        Route::patch('propose/accept/{id}', [ProposeController::class, 'acceptPropose'])->middleware('check.permission:accept_propose');
        Route::patch('propose/reject/{id}', [ProposeController::class, 'rejectPropose'])->middleware('check.permission:reject_propose');
    }
);


//Order Routes

Route::group(
    [
        'middleware' => [
            'api',
            'jwt',
        ],
        'prefix' => 'v1'
    ],
    function ($router) {
        //Order Routes
        Route::get('orders', [OrderController::class, 'index'])->middleware('check.permission:view_orders');
        Route::get('order/{id}', [OrderController::class, 'show'])->middleware('check.permission:view_orders');
        Route::post('order/add', [OrderController::class, 'store'])->middleware('check.permission:create_orders');
        Route::patch('order/update/{id}', [OrderController::class, 'update'])->middleware('check.permission:update_orders');
        Route::delete('order/delete/{id}', [OrderController::class, 'destroy'])->middleware('check.permission:delete_orders');

        Route::patch('order/confirm/{id}', action: [OrderController::class, 'confirmOrder'])->middleware('check.permission:update_orders');
        Route::patch('order/complete/{id}', [OrderController::class, 'completeOrder'])->middleware('check.permission:update_orders');
        Route::patch('order/cancel/{id}', [OrderController::class, 'cancelOrder'])->middleware('check.permission:update_orders');
    }
);


//Inventory Report Routes

Route::group(
    [
        'middleware' => [
            'api',
            'jwt',
        ],
        'prefix' => 'v1'
    ],
    function ($router) {
        //Inventory Report Routes
        Route::get('inventory-reports', [InventoryReportController::class, 'index'])->middleware('check.permission:view_inventory_reports');
        Route::get('inventory-report/{id}', [InventoryReportController::class, 'show'])->middleware('check.permission:view_inventory_reports');
        Route::post('inventory-report/add', [InventoryReportController::class, 'store'])->middleware('check.permission:create_inventory_reports');
        Route::patch('inventory-report/update/{id}', [InventoryReportController::class, 'update'])->middleware('check.permission:update_inventory_reports');
        Route::delete('inventory-report/delete/{id}', [InventoryReportController::class, 'destroy'])->middleware('check.permission:delete_inventory_reports');

        Route::patch('inventory-report/send/{id}', [InventoryReportController::class, 'sendInventoryReport'])->middleware('check.permission:send_inventory_report');
        Route::patch('inventory-report/confirm/{id}', [InventoryReportController::class, 'confirmInventoryReport'])->middleware('check.permission:confirm_inventory_report');
    }
);
