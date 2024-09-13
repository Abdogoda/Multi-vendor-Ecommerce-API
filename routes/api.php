<?php

use App\Http\Controllers\API\V1\Admin\AdminController;
use App\Http\Controllers\API\V1\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\API\V1\Admin\PaymentMethodController as AdminPaymentMethodController;
use App\Http\Controllers\API\V1\Admin\TagController as AdminTagController;
use App\Http\Controllers\API\V1\Auth\ChangePasswordController;
use App\Http\Controllers\API\V1\Auth\ForgotPasswordController;
use App\Http\Controllers\API\V1\Auth\GoogleController;
use App\Http\Controllers\API\V1\Auth\LoginController;
use App\Http\Controllers\API\V1\Auth\LogoutController;
use App\Http\Controllers\API\V1\Auth\ProfileController;
use App\Http\Controllers\API\V1\Auth\RegisterController;
use App\Http\Controllers\API\V1\Auth\ResetPasswordController;
use App\Http\Controllers\API\V1\Auth\SendOTPController;
use App\Http\Controllers\API\V1\Auth\TokenController;
use App\Http\Controllers\API\V1\Auth\UserStatusController;
use App\Http\Controllers\API\V1\Auth\VerifyOTPController;
use App\Http\Controllers\API\V1\CategoryController;
use App\Http\Controllers\API\V1\Customer\OrderController as CustomerOrderController;
use App\Http\Controllers\API\V1\Customer\PaymentController as CustomerPaymentController;
use App\Http\Controllers\API\V1\CustomerController;
use App\Http\Controllers\API\V1\PaymentMethodController;
use App\Http\Controllers\API\V1\ProductController;
use App\Http\Controllers\API\V1\TagController;
use App\Http\Controllers\API\V1\Vendor\CategoryController as VendorCategoryController;
use App\Http\Controllers\API\V1\Vendor\OrderController as VendorOrderController;
use App\Http\Controllers\API\V1\Vendor\PaymentMethodController as VendorPaymentMethodController;
use App\Http\Controllers\API\V1\Vendor\ProductController as VendorProductController;
use App\Http\Controllers\API\V1\Vendor\TagController as VendorTagController;
use App\Http\Controllers\API\V1\VendorController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// ==================== AUTH ROUTES =================
Route::prefix('auth')->group(function(){
  // ==================== Route accessible only by guests
  Route::middleware('guest')->group(function(){
    Route::post('login', LoginController::class);
    Route::post('register/customer', [RegisterController::class, 'customerRegister']);
    Route::post('register/vendor', [RegisterController::class, 'vendorRegister']);
    Route::post('password/forgot', ForgotPasswordController::class);
    Route::post('password/reset', ResetPasswordController::class);
    Route::post('otp/send', SendOTPController::class);
    Route::post('otp/verify', VerifyOTPController::class);
    
    Route::get('google/redirect', [GoogleController::class, 'loginOrRegisterWithGoogle']);
    Route::get('google/callback', [GoogleController::class, 'handleGoogleCallback']);
  });
  
  // ==================== Route accessible by any user
  Route::middleware('auth:api')->group(function(){
    // === PROFILE ROUTES
    Route::post('refresh-token', TokenController::class);
    Route::post('logout', LogoutController::class);
    
    Route::controller(ProfileController::class)->prefix('profile')->group(function(){
      Route::get('/', 'profile');
      Route::post('/', 'updateProfile');
      Route::middleware('role:vendor')->patch('/vendor', 'updateVendorProfile');
    });
    Route::post('password/change', ChangePasswordController::class);

    // change Status by admin
    Route::middleware('role:admin')->post('/users/{user}/activate-account', UserStatusController::class);
  });

});


// ==================== Customer Routes
Route::prefix('customers')->controller(CustomerController::class)->group(function(){
  Route::get('/', 'index');
  Route::get('/{customer}', 'show');
});

// ==================== Customer Order Routes
Route::middleware(['api', 'role:customer'])->prefix('customer')->name('customer.')->group(function(){
  Route::get('/orders/{order}/checkout', [CustomerPaymentController::class, 'checkout'])->name('payment.checkout');
  Route::get('/orders/payment/callback', [CustomerPaymentController::class, 'callback']);
  Route::apiResource('orders', CustomerOrderController::class);
});

// ==================== Vendor Routes
Route::prefix('vendors')->controller(VendorController::class)->group(function(){
  Route::get('/', 'index');
  Route::get('/{vendor}', 'show');
  
  Route::get('/{vendor}/products', 'showProducts');
  Route::get('/{vendor}/payment_methods', 'showPaymentMethods');
});

// ==================== Payment Methods Routes
Route::prefix('payment_methods')->group(function(){
  Route::controller(PaymentMethodController::class)->group(function(){
    Route::get('/', 'index');
    Route::get('/{paymentMethod}', 'show');
  });

  Route::middleware(['api', 'role:admin'])->controller(AdminPaymentMethodController::class)->group(function(){
    Route::post('/', 'store');
    Route::patch('/{paymentMethod}', 'update');
    Route::delete('/{paymentMethod}', 'destroy');
  });
});

// ==================== Vendor Payment Methods Routes
Route::middleware(['api', 'role:vendor'])->prefix('vendor-profile')->group(function(){
  Route::apiResource('payment_methods', VendorPaymentMethodController::class);
  Route::apiResource('orders', VendorOrderController::class);
});

// ==================== Tag Routes
Route::prefix('tags')->group(function(){
  Route::controller(TagController::class)->group(function(){
    Route::get('/', 'index');
    Route::get('/{tag}', 'show');
  });

  Route::middleware(['api', 'role:admin,vendor'])->post('/', [VendorTagController::class, 'store']);

  Route::middleware(['api', 'role:admin'])->controller(AdminTagController::class)->group(function () {
    Route::patch('/{tag}', 'update');
    Route::delete('/{tag}' , 'destroy');
  });
});

// ==================== Category Routes
Route::prefix('categories')->group(function(){
  Route::controller(CategoryController::class)->group(function(){
    Route::get('/', 'index');
    Route::get('/{category}', 'show');
  });

  Route::middleware(['api', 'role:vendor,admin'])->post('/',  [VendorCategoryController::class, 'store']);
  
  Route::middleware(['api', 'role:admin'])->controller(AdminCategoryController::class)->group(function(){
    Route::patch('/{category}', 'update');
    Route::delete('/{category}', 'destroy');
  });
});

// ==================== Product Routes
Route::prefix('products')->group(function(){
  Route::controller(ProductController::class)->group(function(){
    Route::get('/', 'index');
    Route::get('/{product}', 'show');
  });
  
  Route::middleware(['api', 'role:vendor'])->controller(VendorProductController::class)->group(function(){
    Route::post('/', 'store');
    Route::patch('/{product}', 'update');
    Route::delete('/{product}', 'destroy');
    Route::post('/{product}/images/add', 'addProductImages');
    Route::post('/{product}/images/delete', 'deleteProductImages');
    Route::post('/{product}/tags/update', 'updateProductTags');
  });
});

// ==================== Admin Routes
Route::middleware(['api', 'role:admin'])->prefix('admins')->controller(AdminController::class)->group(function(){
  Route::get('/', 'index');
  Route::get('/{admin}', 'show');
  Route::post('/', 'store');
});