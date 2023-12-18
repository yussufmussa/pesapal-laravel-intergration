<html>
<head>
    <title>Payment Details</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>
<body>
    <div class="container">
    <h1>Payment Details</h1>

<table border="1">
    <thead>
        <tr>
            <th>ID</th>
            <th>Payment Method</th>
            <th>Amount</th>
            <th>Created Date</th>
            <th>Status Description</th>
            <th>Description</th>
            <th>Message</th>
            <th>Merchant Reference</th>
            <th>Status Code</th>
            <th>Payment Status Code</th>
            <th>Currency</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>{{ $payment->id }}</td>
            <td>{{ $payment->payment_method }}</td>
            <td>{{ $payment->amount }}</td>
            <td>{{ $payment->created_date }}</td>
            <td>{{ $payment->payment_status_description }}</td>
            <td>{{ $payment->description }}</td>
            <td>{{ $payment->message }}</td>
            <td>{{ $payment->merchant_reference }}</td>
            <td>{{ $payment->status_code }}</td>
            <td>{{ $payment->payment_status_code }}</td>
            <td>{{ $payment->currency }}</td>
            <td>{{ $payment->status }}</td>
        </tr>
    </tbody>
</table>
    </div>
</body>
</html>
    
