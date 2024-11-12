
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice #{{ $invoice->invoice_number }}</title>
    <style>
        .invoice-box {
            max-width: 800px;
            margin: auto;
            padding: 30px;
            border: 1px solid #eee;
            box-shadow: 0 0 10px rgba(0, 0, 0, .15);
            font-size: 16px;
            line-height: 24px;
            font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
            color: #555;
        }
        
        .invoice-box table {
            width: 100%;
            line-height: inherit;
            text-align: left;
            border-collapse: collapse;
        }
        
        .invoice-box table td {
            padding: 5px;
            vertical-align: top;
        }
        
        .invoice-box table tr.heading td {
            background: #eee;
            border-bottom: 1px solid #ddd;
            font-weight: bold;
        }
        
        .invoice-box table tr.item td {
            border-bottom: 1px solid #eee;
        }
        
        .invoice-box table tr.total td {
            border-top: 2px solid #eee;
            font-weight: bold;
        }

        .text-right {
            text-align: right;
        }

        .company-info {
            margin-bottom: 30px;
        }

        .customer-info {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="invoice-box">
        <div class="company-info">
            <h2>Your Company Name</h2>
            <p>Address Line 1</p>
            <p>Address Line 2</p>
            <p>GSTIN: YOUR-GST-NUMBER</p>
        </div>

        <div class="customer-info">
            <strong>Bill To:</strong><br>
            {{ $invoice->customer->name }}<br>
            {{ $invoice->customer->address }}<br>
            GSTIN: {{ $invoice->customer->gst_number }}
        </div>

        <table>
            <tr>
                <td>Invoice Number: {{ $invoice->invoice_number }}</td>
<td class="text-right">Date: {{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d/m/Y') }}</td>
            </tr>
        </table>

        <table>
            <tr class="heading">
                <td>Item</td>
                <td>HSN</td>
                <td>Qty</td>
                <td>Price</td>
                <td>Amount</td>
                <td>GST %</td>
                <td>GST Amt</td>
                <td>Total</td>
            </tr>

            @foreach($invoice->items as $item)
            <tr class="item">
                <td>{{ $item->product->name }}</td>
                <td>{{ $item->product->hsn_code }}</td>
                <td>{{ $item->quantity }}</td>
                <td>₹{{ number_format($item->price, 2) }}</td>
                <td>₹{{ number_format($item->amount, 2) }}</td>
                <td>{{ $item->gst_rate }}%</td>
                <td>₹{{ number_format($item->gst_amount, 2) }}</td>
                <td>₹{{ number_format($item->amount + $item->gst_amount, 2) }}</td>
            </tr>
            @endforeach

            <tr class="total">
                <td colspan="6"></td>
                <td>Subtotal:</td>
                <td>₹{{ number_format($invoice->sub_total, 2) }}</td>
            </tr>
            <tr class="total">
                <td colspan="6"></td>
                <td>GST:</td>
                <td>₹{{ number_format($invoice->gst_amount, 2) }}</td>
            </tr>
            <tr class="total">
                <td colspan="6"></td>
                <td>Total:</td>
                <td>₹{{ number_format($invoice->total_amount, 2) }}</td>
            </tr>
        </table>

        <div style="margin-top: 50px">
            <p>Terms & Conditions:</p>
            <ol>
                <li>Payment is due within 30 days</li>
                <li>This is a computer generated invoice</li>
            </ol>
        </div>
    </div>
</body>
</html>