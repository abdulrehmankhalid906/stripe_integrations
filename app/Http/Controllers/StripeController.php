<?php

namespace App\Http\Controllers;

use Exception;
use Stripe\Stripe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Stripe\Checkout\Session;

class StripeController extends Controller
{
    public function checkoutStripeSession()
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));

        $userPromotionCode = 'WINTER000';

        $promotionCodes = [

            //Coupon - I (Different Codes - Flat) 
            'LITHUANIA2025' => 'promo_1Q4HJF05bYP6qNcuIIK2FIWp',
            'SUMMER2024' => 'promo_1Q4H0I05bYP6qNcuWd4Fr8V9',

            //Coupon - 2 (Percentage)
            'WINTER000' => 'promo_1Q4IPU05bYP6qNcuQcPW5pJC'
        ];

        $discounts = [];

        if ($userPromotionCode && isset($promotionCodes[$userPromotionCode])) {

            // dd($promotionCodes[$userPromotionCode]);
            $discounts = [
                [
                    'promotion_code' => $promotionCodes[$userPromotionCode],
                ],
            ];
        }

        try {
            $checkout_session = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => env('CASHIER_CURRENCY'),
                        'product_data' => [
                            'name' => 'Tier II',
                        ],
                        'unit_amount' => 300 * 100, 
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => route('checkout.success'),
                'cancel_url' => route('checkout.cancel'),
                'discounts' => !empty($discounts) ? $discounts : null,
            ]);

            return redirect($checkout_session->url);

        } catch (Exception $error) {
            Log::error('Stripe error: ' . $error->getMessage());

            return redirect()->back()->with('error', 'Something went wrong: ' . $error->getMessage());
        }
    }

    public function success()
    {
        return redirect()->route('user.dashboard')->with('success', 'You have successfully purchased the package');
    }

    public function cancel()
    {
        return redirect()->route('user.dashboard')->with('error', 'You have cancelled the purchase');
    }
}
