<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Invoice</title>
    <style>
        /* Reset and base styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
            color: #333;
            line-height: 1.6;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        /* Form styles */
        .invoice-form {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: 600;
        }

        input[type="text"],
        input[type="date"],
        input[type="number"],
        select {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }

        .products-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        .products-table th,
        .products-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            text-decoration: none;
        }

        .btn:hover {
            background-color: #45a049;
        }

        .btn-add {
            background-color: #2196F3;
        }

        .btn-add:hover {
            background-color: #1976D2;
        }

        /* Summary section */
        .summary {
            background: #f9f9f9;
            padding: 20px;
            border-radius: 4px;
            margin-top: 20px;
        }

        .summary-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }

        .total {
            font-size: 18px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Create New Invoice</h1>
        
        <form class="invoice-form" method="POST" action="{{ route('invoices.store') }}">
            @csrf
            
            <div class="form-group">
                <label for="customer_id">Customer</label>
                <select name="customer_id" id="customer_id" required>
                    <option value="">Select Customer</option>
                    @foreach($customers as $customer)
                        <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="invoice_date">Invoice Date</label>
                <input type="date" name="invoice_date" id="invoice_date" required>
            </div>

            <table class="products-table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Amount</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody id="products-container">
                    <tr>
                        <td>
                            <select name="products[]" class="product-select" required>
                                <option value="">Select Product</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}" data-price="{{ $product->price }}">
                                        {{ $product->name }}
                                    </option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <input type="number" name="quantities[]" class="quantity-input" min="1" value="1" required>
                        </td>
                        <td class="price">₹0.00</td>
                        <td class="amount">₹0.00</td>
                        <td>
                            <button type="button" class="btn btn-add" onclick="addProduct()">+</button>
                        </td>
                    </tr>
                </tbody>
            </table>

            <div class="summary">
                <div class="summary-item">
                    <span>Subtotal:</span>
                    <span id="subtotal">₹0.00</span>
                </div>
                <div class="summary-item">
                    <span>GST (18%):</span>
                    <span id="gst">₹0.00</span>
                </div>
                <div class="summary-item total">
                    <span>Total:</span>
                    <span id="total">₹0.00</span>
                </div>
            </div>

            <button type="submit" class="btn">Generate Invoice</button>
        </form>
    </div>

    <script>
        function addProduct() {
            const container = document.getElementById('products-container');
            const newRow = container.firstElementChild.cloneNode(true);
            
            // Reset values
            newRow.querySelector('.product-select').value = '';
            newRow.querySelector('.quantity-input').value = 1;
            newRow.querySelector('.price').textContent = '₹0.00';
            newRow.querySelector('.amount').textContent = '₹0.00';
            
            container.appendChild(newRow);
            calculateTotals();
        }

        document.addEventListener('change', function(e) {
            if (e.target.classList.contains('product-select') || 
                e.target.classList.contains('quantity-input')) {
                calculateTotals();
            }
        });

        function calculateTotals() {
            let subtotal = 0;
            
            document.querySelectorAll('#products-container tr').forEach(row => {
                const select = row.querySelector('.product-select');
                const quantity = row.querySelector('.quantity-input').value;
                
                if (select.value) {
                    const price = select.options[select.selectedIndex].dataset.price;
                    const amount = price * quantity;
                    
                    row.querySelector('.price').textContent = `₹${parseFloat(price).toFixed(2)}`;
                    row.querySelector('.amount').textContent = `₹${amount.toFixed(2)}`;
                    
                    subtotal += amount;
                }
            });

            const gst = subtotal * 0.18;
            const total = subtotal + gst;

            document.getElementById('subtotal').textContent = `₹${subtotal.toFixed(2)}`;
            document.getElementById('gst').textContent = `₹${gst.toFixed(2)}`;
            document.getElementById('total').textContent = `₹${total.toFixed(2)}`;
        }
    </script>
</body>
</html>