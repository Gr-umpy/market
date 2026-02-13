<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Storage;
use Stripe\StripeClient;

class CheckoutController extends Controller
{
    public function createCheckoutSession(Request $request)
    {
        $stripe = new StripeClient(env('STRIPE_SECRET_KEY'));
        
        $sessionData = collect(session()->all())
            ->except(['_token', '_flash', '_previous'])
            ->filter(fn ($value, $key) => ! str_starts_with($key, 'login_web'));

        $productIds = [];
        $cartData = [];

        foreach ($sessionData as $key => $value) {
            $parts = explode(':', $key);
            $productId = $parts[0];
            $order = $parts[1];
            $productIds[] = $productId;
            $cartData[] = [
                'id' => $productId,
                'order' => $order,
                'quantity' => $value,
            ];
        }

        $products = Product::with('variants'/*, 'images'*/)->whereIn('id', array_unique($productIds))->get()->keyBy('id');

        $cartItems = collect();
        foreach ($cartData as $data) {
            $product = $products[$data['id']];
            $variant = $product->variants->where('order', $data['order'])->first();
            //$image = $product->images->where('order', 0)->first();
            $cartItems->push((object) [
                'product' => $product,
                'variant' => $variant,
                //'image' => $image,
                'quantity' => $data['quantity'],
            ]);
        }
        
        $lineItems = [];
        
        foreach ($cartItems as $cartItem) {
            $productData = [
                'name' => $cartItem->product->name . ' - ' . $cartItem->variant->name,

            ];
            /*
            if ($cartItem->image) {
                $imageUrl = url(Storage::url($cartItem->image->url));
                
                $productData['images'] = [$imageUrl];
            }
            */
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => $productData,
                    'unit_amount' => $cartItem->variant->price * 100,
                ],
                'quantity' => $cartItem->quantity,
            ];
        }
        
        $checkout_session = $stripe->checkout->sessions->create([
            'ui_mode' => 'embedded',
            'line_items' => $lineItems,
            'mode' => 'payment',
            'return_url' => url('/return?session_id={CHECKOUT_SESSION_ID}'),
        ]);
        
        return response()->json(['clientSecret' => $checkout_session->client_secret]);
    }

    public function checkoutStatus(Request $request)
    {
        $stripe = new StripeClient(env('STRIPE_SECRET_KEY'));
        $session = $stripe->checkout->sessions->retrieve($request->session_id);
        
        return response()->json([
            'status' => $session->status, 
            'customer_email' => $session->customer_details->email ?? null
        ]);
    }

}
