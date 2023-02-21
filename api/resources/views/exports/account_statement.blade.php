<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
</head>
<body>
    <table>
        <tbody>
            <tr>
                <td colspan="3" valign="top">Transactions in {{ $account_currency }} - {{ $account_number }}</td>
                <td colspan="3">
                    Openning balance (on {{ $opening_balance_date }}): {{ $opening_balance }} {{ $account_currency }}<br>
                    Debit Turnover: {{ $debit_turnover }} {{ $account_currency }}<br>
                    Credit Turnover: {{ $credit_turnover }} {{ $account_currency }}<br>
                    Closing balance (on {{ $closing_balance_date }}): {{ $closing_balance }} {{ $account_currency }}<br>
                </td>
            </tr>
        </tbody>
    </table>
    <table class="table">
        <thead>
            <tr>
                <th width="8" align="left">ID</th>
                <th width="10">Created at</th>
                <th width="27">Sender/Recipient</th>
                <th width="35">Account</th>
                <th width="12">Amount</th>
                <th width="10">Status</th>
            </tr>
        </thead>
        <tbody>
        @foreach($transactions as $transaction)
            <tr>
                <td align="left" valign="top">{{ $transaction['transaction_id'] }}</td>
                <td valign="top">{{ $transaction['created_at'] }}</td>
                <td valign="top">{{ Illuminate\Support\Str::transliterate($transaction['sender_recipient']) }}<br>{{ Illuminate\Support\Str::transliterate($transaction['reason']) }}</td>
                <td valign="top">{{ $transaction['account_number'] }} ({{ Illuminate\Support\Str::transliterate($transaction['account_client']) }})</td>
                <td valign="top">{{ $transaction['amount'] }} {{ $account_currency }}<br>Balance: {{ $transaction['account_balance'] }}</td>
                <td valign="top">{{ $transaction['status'] }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</body>
</html>