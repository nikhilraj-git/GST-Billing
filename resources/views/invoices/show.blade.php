@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Invoice #{{ $invoice->invoice_number }}</h5>
                    <div>
                        <!-- Add the PDF download button here -->
                        <a href="{{ route('invoices.pdf', $invoice->id) }}" class="btn btn-primary me-2">
                            <i class="bi bi-download"></i> Download PDF
                        </a>
                        <a href="{{ route('invoices.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Back to Invoices
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Invoice Details Section -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6>Customer Details</h6>
                            <p>
                                <strong>{{ $invoice->customer->name }}</strong><br>
                                {{ $invoice->customer->address }}<br>
                                Email: {{ $invoice->customer->email }}<br>
                                Phone: {{ $invoice->customer->phone }}<br>
                                GSTIN: {{ $invoice->customer->gst_number }}
                            </p>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <h6>Invoice Details</h6>
                            <p>
Invoice Date: {{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d/m/Y') }}<br>
                                Status: <span class="badge bg-{{ $invoice->status === 'paid' ? 'success' : 'warning' }}">
                                    {{ ucfirst($invoice->status) }}
                                </span>
                            </p>
                        </div>
                    </div>

                    <!-- Items Table -->
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Item</th>
                                    <th>HSN</th>
                                    <th class="text-center">Qty</th>
                                    <th class="text-end">Price</th>
                                    <th class="text-end">Amount</th>
                                    <th class="text-end">GST %</th>
                                    <th class="text-end">GST Amt</th>
                                    <th class="text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($invoice->items as $item)
                                <tr>
                                    <td>{{ $item->product->name }}</td>
                                    <td>{{ $item->product->hsn_code }}</td>
                                    <td class="text-center">{{ $item->quantity }}</td>
                                    <td class="text-end">₹{{ number_format($item->price, 2) }}</td>
                                    <td class="text-end">₹{{ number_format($item->amount, 2) }}</td>
                                    <td class="text-end">{{ $item->gst_rate }}%</td>
                                    <td class="text-end">₹{{ number_format($item->gst_amount, 2) }}</td>
                                    <td class="text-end">₹{{ number_format($item->amount + $item->gst_amount, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="6"></td>
                                    <td class="text-end"><strong>Subtotal:</strong></td>
                                    <td class="text-end">₹{{ number_format($invoice->sub_total, 2) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="6"></td>
                                    <td class="text-end"><strong>GST:</strong></td>
                                    <td class="text-end">₹{{ number_format($invoice->gst_amount, 2) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="6"></td>
                                    <td class="text-end"><strong>Total:</strong></td>
                                    <td class="text-end"><strong>₹{{ number_format($invoice->total_amount, 2) }}</strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    @if($invoice->notes)
                    <div class="mt-4">
                        <h6>Notes:</h6>
                        <p class="mb-0">{{ $invoice->notes }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
