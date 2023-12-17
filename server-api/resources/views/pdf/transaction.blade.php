<!-- resources/views/pdf/transaction.blade.php -->

<html>
<head>
    <style>
    </style>
</head>
<body>
    <h1>Transaction Details</h1>

    <p>Transaction ID: {{ $transaction->id }}</p>
    <p>From: {{ $transaction->vcard }} to {{ $transaction->payment_reference }}</p>
    <p>Transaction Amount: {{ $transaction->value }}</p>
    <p>Old Balance: {{ $transaction->old_balance }}</p>
    <p>New Balance: {{ $transaction->new_balance }}</p>
    <p>Type of transaction: {{ $transaction->type == 'D' ? 'Debit' : 'Credit' }}</p>
    <p>Payment Type: {{ $transaction->payment_type }}</p>
    <p>Data de criação: {{ $transaction->created_at }}</p>

    @if ($transaction->category)
        <p><strong>Category:</strong> {{ $transaction->category->name }}</p>
    @endif

</body>
</html>
