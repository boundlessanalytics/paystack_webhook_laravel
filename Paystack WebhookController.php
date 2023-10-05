<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    public function handleWebhook(Request $request)
    {
        //Get Payload
        $payload = $request->getContent();
        // $payload = $request->all();

        // Verify the Paystack webhook signature
        $paystackSecret = config('services.paystack.secret_key');
        $paystackHeader = $request->header('x-paystack-signature');

        //
        if ($this->isValidPaystackWebhook($payload, $paystackHeader, $paystackSecret)) {
            // Handle the webhook event based on the event type
            $eventData = $request->json('data');
            $eventType = $request->json('event');

            if ($eventType === 'charge.success') {
                // Handle successful payment event
                // Example: Update order status or send confirmation email
            } elseif ($eventType === 'charge.failure') {
                // Handle failed payment event
                // Example: Notify user about payment failure
            }

            Log::info('message', ['success' => 'Webhook received']);

            // return response()->json(['message' => 'Webhook received']);
        } else {
            Log::info('message', ['error' => 'Invalid webhook signature']);
        }
    }

    private function isValidPaystackWebhook($payload, $signature, $secret)
    {
        $computedSignature = hash_hmac('sha512', $payload, $secret);
        return $computedSignature === $signature;
        // return hash_equals($hash, $signature);
    }
}