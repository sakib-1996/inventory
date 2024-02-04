<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Models\InvoiceProduct;
use Illuminate\Support\Facades\DB;

class InvoicesController extends Controller
{
    function invoiceCreate(Request $request)
    {
        DB::beginTransaction();

        try {
            // Validation (example, adjust as needed)
            $request->validate([
                // ... (existing validation rules)
            ]);

            $user_id = $request->header('id');
            $customer_id = $request->input('customer_id');

            // Create the invoice
            $invoice = Invoice::create([
                'total' => $request->input('total'),
                'discount' => $request->input('discount'),
                'vat' => $request->input('vat'),
                'payable' => $request->input('payable'),
                'user_id' => $user_id,
                'customer_id' => $customer_id,
            ]);

            $invoiceID = $invoice->id;

            $products = $request->input('products');

            foreach ($products as $eachProduct) {
                $product = Product::find($eachProduct['product_id']);

                if (!$product) {
                    DB::rollBack();
                    return response()->json(['success' => false, 'message' => 'Product not found'], 404);
                }

                // Check if there is enough stock
                if ($product->qty < $eachProduct['qty']) {
                    DB::rollBack();
                    return response()->json(['success' => false, 'message' => 'Insufficient stock for product ' . $product->id], 400);
                }

                // Deduct stock quantity
                $product->qty -= $eachProduct['qty'];
                $product->save();

                // Create invoice product
                InvoiceProduct::create([
                    'invoice_id' => $invoiceID,
                    'user_id' => $user_id,
                    'product_id' => $eachProduct['product_id'],
                    'qty' => $eachProduct['qty'],
                    'sale_price' => $eachProduct['sale_price'],
                ]);
            }

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Invoice created successfully']);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Failed to create invoice', 'error' => $e->getMessage()], 500);
        }
    }

    function invoiceSelect(Request $request)
    {
        $user_id = $request->header('id');
        return Invoice::where('user_id', $user_id)->with('customer')->get();
    }

    function InvoiceDetails(Request $request)
    {
        $user_id = $request->header('id');

        // Assuming 'id' is the primary key of the customers table
        $customerDetails = Customer::where('id', $request->input('cus_id'))->first();

        // Assuming 'id' is the primary key of the invoice table
        $invoiceTotal = Invoice::where('id', $request->input('inv_id'))->first();

        // Assuming 'invoice_id' is the foreign key in the InvoiceProduct table
        $invoiceProduct = InvoiceProduct::where('invoice_id', $request->input('inv_id'))
            ->with('product')
            ->get();

        return array(
            'customer' => $customerDetails,
            'invoice' => $invoiceTotal,
            'product' => $invoiceProduct,
        );
    }


    function invoiceDelete(Request $request)
    {
        DB::beginTransaction();
        try {
            $user_id = $request->header('id');
            InvoiceProduct::where('invoice_id', $request->input('inv_id'))
                ->where('user_id', $user_id)
                ->delete();
            Invoice::where('id', $request->input('inv_id'))->delete();
            DB::commit();
            return 1;
        } catch (Exception $e) {
            DB::rollBack();
            return 0;
        }
    }
}
