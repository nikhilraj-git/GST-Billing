<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Customer;
use App\Models\Product;
use Illuminate\Http\Request;
use PDF;

class InvoiceController extends Controller
{
    public function index()
{
    $invoices = Invoice::with('customer')->latest()->get();
    return view('invoices.index', compact('invoices'));
}
    public function create()
    {
        $customers = Customer::all();
        $products = Product::all();
        return view('invoices.create', compact('customers', 'products'));
    }

    // public function store(Request $request)
    // {
    //     $validated = $request->validate([
    //         'customer_id' => 'required|exists:customers,id',
    //         'invoice_date' => 'required|date',
    //         'products' => 'required|array',
    //         'quantities' => 'required|array',
    //     ]);
    //      $invoiceNumber = 'INV-' . strtoupper(uniqid());
    //     $invoice = Invoice::create([
    //         'invoice_number' => $invoiceNumber, 
    //         'customer_id' => $validated['customer_id'],
    //         'invoice_date' => $validated['invoice_date'],
    //         'sub_total' => 0,
    //         'gst_amount' => 0,
    //         'total_amount' => 0,
    //         'gst_rate' => 18,
    //     ]);

    //     // Calculate totals and save invoice items
    //     $subTotal = 0;
    //     foreach($validated['products'] as $key => $product_id) {
    //         $product = Product::find($product_id);
    //         $quantity = $validated['quantities'][$key];
    //         $amount = $product->price * $quantity;
    //         $subTotal += $amount;
            
    //         $invoice->items()->create([
    //             'product_id' => $product_id,
    //             'quantity' => $quantity,
    //             'price' => $product->price,
    //             'amount' => $amount,
    //         ]);
    //     }

    //     $gstAmount = $subTotal * 0.18; // 18% GST
    //     $totalAmount = $subTotal + $gstAmount;

    //     $invoice->update([
    //         'sub_total' => $subTotal,
    //         'gst_amount' => $gstAmount,
    //         'total_amount' => $totalAmount,
    //     ]);

    //     return redirect()->route('invoices.show', $invoice->id);
    // }
    public function store(Request $request)
{
    $validated = $request->validate([
        'customer_id' => 'required|exists:customers,id',
        'invoice_date' => 'required|date',
        'products' => 'required|array',
        'quantities' => 'required|array',
    ]);

    $invoiceNumber = 'INV-' . strtoupper(uniqid());
    $invoice = Invoice::create([
        'invoice_number' => $invoiceNumber,
        'customer_id' => $validated['customer_id'],
        'invoice_date' => $validated['invoice_date'],
        'sub_total' => 0,
        'gst_amount' => 0,
        'total_amount' => 0,
    ]);

    // Calculate totals and save invoice items
    $subTotal = 0;
    foreach ($validated['products'] as $key => $product_id) {
        $product = Product::find($product_id);
        $quantity = $validated['quantities'][$key];
        $amount = $product->price * $quantity;
        $subTotal += $amount;

        // Calculate GST for the item
        $gstAmount = $amount * 0.18; // 18% GST
        
        // Save the item
        $invoice->items()->create([
            'product_id' => $product_id,
            'quantity' => $quantity,
            'price' => $product->price,
            'amount' => $amount,
            'gst_rate' => 18, // Assuming 18% GST rate
            'gst_amount' => $gstAmount, // Set GST amount for the item
        ]);
    }

    $gstAmountTotal = $subTotal * 0.18; // Total GST for the invoice
    $totalAmount = $subTotal + $gstAmountTotal;

    // Update the invoice with totals
    $invoice->update([
        'sub_total' => $subTotal,
        'gst_amount' => $gstAmountTotal,
        'total_amount' => $totalAmount,
    ]);

    return redirect()->route('invoices.show', $invoice->id);
}

public function show($id)
{
    // Retrieve the invoice along with its customer and items
    $invoice = Invoice::with(['customer', 'items.product'])->findOrFail($id);

    // Pass the invoice data to the view
    return view('invoices.show', compact('invoice'));
}

    public function generatePDF($id)
{
    $invoice = Invoice::with(['customer', 'items.product'])->findOrFail($id);
    $pdf = PDF::loadView('invoices.pdf', compact('invoice'));
    return $pdf->download('invoice-' . $invoice->invoice_number . '.pdf');
}

}