<?php


use App\Http\Livewire\Admin\AdminAddAttributeComponent;
use Illuminate\Support\Facades\Route;
use App\Http\Livewire\HomeComponent;
use App\Http\Livewire\ShopComponent;
use App\Http\Livewire\CartComponent;
use App\Http\Livewire\CheckoutComponent;
use App\Http\Livewire\DetailsComponent;
use App\Http\Livewire\CategoryComponent;
use App\Http\Livewire\WishlistComponent;
use App\Http\Livewire\ThankyouComponent;
use App\Http\Livewire\Admin\AdminAddCategoryComponent;
use App\Http\Livewire\Admin\AdminHomeSliderComponent;
use App\Http\Livewire\Admin\AdminAddHomeSliderComponent;
use App\Http\Livewire\Admin\AdminEditHomeSliderComponent;
use App\Http\Livewire\Admin\AdminSaleComponent;
use App\Http\Livewire\Admin\AdminEditCategoryComponent;
use App\Http\Livewire\Admin\AdminCategoryComponent;
use App\Http\Livewire\Admin\AdminProductComponent;
use App\Http\Livewire\Admin\AdminAddProductComponent;
use App\Http\Livewire\Admin\AdminEditProductComponent;
use App\Http\Livewire\SearchComponent;
use App\Http\Livewire\Admin\AdminCouponsComponent;
use App\Http\Livewire\Admin\AdminAddCouponComponent;
use App\Http\Livewire\Admin\AdminAttributesComponent;
use App\Http\Livewire\Admin\AdminContactComponent;
use App\Http\Livewire\Admin\AdminEditCouponComponent;

use App\Http\Livewire\Admin\AdminDashboardComponent;
use App\Http\Livewire\Admin\AdminEditAttributeComponent;
use App\Http\Livewire\Admin\AdminHomeCategoryComponent;
use App\Http\Livewire\Admin\AdminOrderComponent;
use App\Http\Livewire\Admin\AdminOrderDetailsComponent;
use App\Http\Livewire\Admin\AdminSettingComponent;
use App\Http\Livewire\Coba;
use App\Http\Livewire\ContactComponent;
use App\Http\Livewire\ProvinceComponent;
use App\Http\Livewire\ShippingComponent;
use App\Http\Livewire\User\UserChangePasswordComponent;
use App\Http\Livewire\User\UserDashboardComponent;
use App\Http\Livewire\User\UserEditProfileComponent;
use App\Http\Livewire\User\UserOrderDetailsComponent;
use App\Http\Livewire\User\UserOrdersComponent;
use App\Http\Livewire\User\UserProfileComponent;
use App\Http\Livewire\User\UserReviewComponent;

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
Route::get('/baru', Coba::class);
Route::get('/', HomeComponent::class);
Route::get('/shop', ShopComponent::class);
Route::get('/cart', CartComponent::class)->name('product.cart');
Route::get('/checkout', CheckoutComponent::class)->name('checkout');
Route::get('/product/{slug}', DetailsComponent::class)->name('product.details');
Route::get('/product-category/{category_slug}/{scategory_slug?}', CategoryComponent::class)->name('product.category');
Route::get('/search', SearchComponent::class)->name('product.search');
Route::get('/wishlist', WishlistComponent::class)->name('product.wishlist');
Route::get('/thank-you', ThankyouComponent::class)->name('thankyou'); 
Route::get('/contact-us', ContactComponent::class)->name('contact'); 
Route::get('/shipping', ShippingComponent::class)->name('shipping'); 
Route::get('/province/{id}/cities', ProvinceComponent::class);
Route::post('/post', ShippingComponent::class)->name('submmit.ongkir'); 




Route::get('/user/dashboard',UserDashboardComponent::class)->middleware(['auth'])->name('user.dashboard');
Route::get('/user/orders',UserOrdersComponent::class)->middleware(['auth'])->name('user.orders');
Route::get('/user/orders/{order_id}',UserOrderDetailsComponent::class)->middleware(['auth'])->name('user.orderdetails');
Route::get('/user/review/{order_item_id}',UserReviewComponent::class)->middleware(['auth'])->name('user.review');
Route::get('/user/change-password',UserChangePasswordComponent::class)->middleware(['auth'])->name('user.changepassword');
Route::get('/user/profile', UserProfileComponent::class)->name('user.profile');
Route::get('/user/profile/edit', UserEditProfileComponent::class)->name('user.editprofile');


Route::get('/admin/dashboard',AdminDashboardComponent::class)->middleware(['auth'])->name('admin.dashboard');
Route::get('/admin/categories',AdminCategoryComponent::class)->middleware(['auth'])->name('admin.categories');
Route::get('/admin/category/add',AdminAddCategoryComponent::class)->middleware(['auth'])->name('admin.addcategory');
Route::get('/admin/category/edit/{category_slug}/{scategory_slug?}',AdminEditCategoryComponent::class)->middleware(['auth'])->name('admin.editcategory');
Route::get('/admin/product',AdminProductComponent::class)->middleware(['auth'])->name('admin.product');
Route::get('/admin/product/add',AdminAddProductComponent::class)->middleware(['auth'])->name('admin.addproduct');
Route::get('/admin/product/edit/{product_slug}',AdminEditProductComponent::class)->middleware(['auth'])->name('admin.editproduct');


Route::get('/admin/slider',AdminHomeSliderComponent::class)->name('admin.homeslider');
Route::get('/admin/slider/add', AdminAddHomeSliderComponent::class)->name('admin.addhomeslider');
Route::get('/admin/slider/edit/{slide_id}', AdminEditHomeSliderComponent::class)->name('admin.edithomeslider');

Route::get('/admin/home-categories',AdminHomeCategoryComponent::class)->name('admin.homecategories');
Route::get('/admin/sale',AdminSaleComponent::class)->name('admin.sale');

Route::get('/admin/coupons', AdminCouponsComponent::class)->name('admin.coupons');
Route::get('/admin/coupon/add', AdminAddCouponComponent::class)->name('admin.addcoupon');
Route::get('/admin/coupon/edit/{coupon_id}', AdminEditCouponComponent::class)->name('admin.editcoupon');

Route::get('/admin/orders', AdminOrderComponent::class)->name('admin.orders');
Route::get('/admin/orders/{order_id}', AdminOrderDetailsComponent::class)->name('admin.orderdetails');
Route::get('/admin/contact-us', AdminContactComponent::class)->name('admin.contact');
Route::get('/admin/settings', AdminSettingComponent::class)->name('admin.settings');
Route::get('/admin/attributes', AdminAttributesComponent::class)->name('admin.attributes');
Route::get('/admin/attribute/add', AdminAddAttributeComponent::class)->name('admin.add_attribute');
Route::get('/admin/attribute/edit/{attribute_id}', AdminEditAttributeComponent::class)->name('admin.edit_attribute');


require __DIR__.'/auth.php';
