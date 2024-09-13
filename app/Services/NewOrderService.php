<?php

namespace App\Services;

use App\Mail\OrderConfirmation;
use App\Mail\VendorNotification;
use App\Models\Order;
use App\Models\Product;
use App\Models\Vendor;
use App\Traits\ApiResponseTrait;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class NewOrderService{
 use ApiResponseTrait;

 public function placeOrder($request, $user){
  DB::beginTransaction();
  try {
   $data = $request->validated();

   $order = $this->createOrder($data, $user->id);
   
   $itemsByVendor = $this->organizeItemsByVendor($data['items']);
   
   $total = $this->processVendorPayments($itemsByVendor, $data['payment_method_id'], $order);
   $order->update(['total_amount' => $total]);

   // Email to the client
   Mail::to($user->email)->send(new OrderConfirmation($order));

   // Send email to vendors
   foreach ($itemsByVendor as $vendorId => $items) {
    $vendor = Vendor::find($vendorId);
    if ($vendor) {
     Mail::to($vendor->shop_email)->send(new VendorNotification($vendor, $items));
    }
   }
   
   DB::commit();
   return [ 'success' => true, 'order' => $order, 'phone_number' => $request->phone_number ?? null];
  } catch (\Exception $e) {
   DB::rollBack();
   return [ 'success' => false, 'message' => $e->getMessage(),];
  }
 }

 private function createOrder(array $data, string $user_id): Order{
  $order_data = [
   "user_id" => $user_id,
   "payment_method_id" => $data['payment_method_id'],
   "shipping_address" => $data['shipping_address'] ?? null,
   "order_date" => Carbon::now(),
   'total_amount' => 0 // Initialize total amount
  ];
  $order = Order::create($order_data);
  return $order;
 }

 private function organizeItemsByVendor(array $items): array{
  $itemsByVendor = [];
  foreach ($items as $item) {
   $product = Product::find($item['product_id']);
   if (!$product) {
    throw new \Exception(__('messages.product_not_found'.' '.$item['product_id']));
   }

   $vendorId = $product->vendor_id;
   if (!isset($itemsByVendor[$vendorId])) {
    $itemsByVendor[$vendorId] = [];
   }

   $itemsByVendor[$vendorId][] = [
    'product_id' => $product->id,
    'product_name' => (app()->getLocale() === 'ar') ? ($product->name_ar ?? $product->name_en) : $product->name_en ,
    'quantity' => $item['quantity'],
    'price' => $product->price,
   ];
  }
  return $itemsByVendor;
 }

 private function processVendorPayments(array $itemsByVendor, string $paymentMethodId, Order $order): float{
  $total = 0;
  foreach ($itemsByVendor as $vendorId => $items) {
   $vendor = Vendor::find($vendorId);
   
   if (!$vendor || !$vendor->paymentMethods->contains($paymentMethodId)) {
     throw new \Exception("No payment method specified for vendor ID $vendorId.");
    }

   $total += $this->createOrderItems( $items, $order);
   $this->updateProductStock($items);
  }
  return $total;
 }

 private function createOrderItems(array $items, Order $order){
  $item_total = 0;
  foreach ($items as $item) {
   $item_data = [
    'quantity' => $item['quantity'],
    'price' => $item['price']
   ];
   $order->items()->attach($item['product_id'], $item_data);
   $item_total += $item['quantity'] * $item['price'];
  }
  return $item_total;
 }

 private function updateProductStock(array $items){
  foreach ($items as $item) {
   $product = Product::find($item['product_id']);
   $product->decrement('stock', $item['quantity']);

   if($product->stock <= 0){
    $product->update(['status' => 'out of stock']);
   }
  }
 }

}