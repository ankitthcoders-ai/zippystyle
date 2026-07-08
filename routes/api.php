<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthenticationController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ShopController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\PurchaseController;
use App\Http\Controllers\Api\UserAddressController;
use App\Http\Controllers\Api\PaymentMethodController;
use App\Http\Controllers\Api\UserController;

Route::post('/login', [AuthenticationController::class, 'login']);
Route::post('/register', [AuthenticationController::class, 'store']);

Route::middleware('auth:api')->group(function () {
  Route::get('/user', [AuthenticationController::class, 'get']);
  Route::get('/products', [ProductController::class, 'products']);
  Route::get('/my/products', [ProductController::class, 'myProducts']);
  Route::get('/shops', [ShopController::class, 'shops']);
  Route::get('/my/shops', [ShopController::class, 'myShops']);
  Route::get('/categories', [CategoryController::class, 'categories']);
  Route::get('/shop/{id}/products', [ShopController::class, 'products']);
  Route::get('/{id}/products', [ProductController::class, 'productsDetails']);
  Route::get('/sub/categories', [CategoryController::class, 'subCategories']);

  Route::post('/product/create', [ProductController::class, 'createProduct']);
  Route::post('/product/update/{id}', [ProductController::class, 'updateProduct']);
  Route::put('/product/update/{id}', [ProductController::class, 'updateProduct']);
  Route::delete('/product/delete/{id}', [ProductController::class, 'deleteProduct']);

  Route::post('/shop/create', [ShopController::class, 'createShop']);
  Route::post('/shop/update/{id}', [ShopController::class, 'updateShop']);
  Route::put('/shop/update/{id}', [ShopController::class, 'updateShop']);
  Route::delete('/shop/delete/{id}', [ShopController::class, 'deleteShop']);

  Route::post('/user/create', [UserController::class, 'createUser']);
  Route::post('/user/update/{id}', [AuthenticationController::class, 'update']);
  Route::put('/user/update/{id}', [AuthenticationController::class, 'update']);
  Route::delete('/user/delete/{id}', [AuthenticationController::class, 'destroy']);

  Route::get('/payment-methods', [PaymentMethodController::class, 'index']);

  Route::get('/addresses', [UserAddressController::class, 'index']);
  Route::get('/address/{id}', [UserAddressController::class, 'show']);
  Route::post('/address/create', [UserAddressController::class, 'store']);
  Route::post('/address/update/{id}', [UserAddressController::class, 'update']);
  Route::put('/address/update/{id}', [UserAddressController::class, 'update']);
  Route::delete('/address/delete/{id}', [UserAddressController::class, 'destroy']);

  Route::get('/purchases', [PurchaseController::class, 'index']);
  Route::get('/merchant/orders', [PurchaseController::class, 'merchantOrders']);
  Route::post('/purchase/create', [PurchaseController::class, 'store']);
});
