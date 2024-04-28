<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;  // Include Log facade
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function showPaymentForm(Request $request)
{
    $orderId = $request->query('order_id');
    $paymentMethod = $request->query('payment_method');
    
    // Retrieve order details using raw SQL and proper column name
    $order = DB::select("SELECT * FROM orders WHERE order_id = ?", [$orderId])[0] ?? null;

    // Log for debugging
    Log::info('Showing payment form for method:', ['orderId' => $orderId, 'paymentMethod' => $paymentMethod, 'order' => $order]);

    return view('payment-details', [
        'paymentMethod' => $paymentMethod,
        'order' => $order  // Make sure to handle this in your view
    ]);
}



public function processPayment(Request $request)
{
    $orderId = $request->input('order_id');
    $cardNumber = $request->input('card_number'); // Assuming you need this for transaction ID generation

    // Generate a unique transaction ID
    $paymentTransactionId = 'TXN-' . strtoupper(dechex(time())) . '-' . substr($cardNumber, -4);

    DB::beginTransaction();
    try {
        // As your payments table does not seem to have a 'transaction_id', let's update the 'status' and processed_at date.
        $updatePaymentSQL = "UPDATE payments SET status = 'completed', processed_at = ? WHERE order_id = ?";
        DB::update($updatePaymentSQL, [now(), $orderId]);

        // Update the orders table to include the transaction ID
        $updateOrderSQL = "UPDATE orders SET payment_transaction_id = ? WHERE order_id = ?";
        DB::update($updateOrderSQL, [$paymentTransactionId, $orderId]);

        DB::commit();
        Log::info('Payment processed successfully.', ['order_id' => $orderId, 'transaction_id' => $paymentTransactionId]);
        return redirect()->route('my-profile')->with('success', 'Payment processed successfully!');
    } catch (\Exception $e) {
        DB::rollback();
        Log::error('Error processing payment', ['error' => $e->getMessage(), 'order_id' => $orderId]);
        return back()->with('error', 'Error processing payment: ' . $e->getMessage());
    }
}


}
