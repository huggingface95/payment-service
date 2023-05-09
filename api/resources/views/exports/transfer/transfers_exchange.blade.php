<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
</head>
<body>
    <table class="table">
        <thead>
            <tr>
                <th colspan="15" valign="top"><b>Exchange Transactions</b></th>
            </tr>
            <tr>
                <th width="10" align="left">Transaction ID</th>
                <th width="13">Date / Time</th>
                <th width="13">Requested by</th>
                <th width="13">Client</th>
                <th width="13">Debited Account</th>
                <th width="10">Credited Account</th>
                <th width="20">Quotes Provider</th>
                <th width="20">Exchange Rate</th>
                <th width="10">Margin Comm. Rate</th>
                <th width="10">Debited Amount</th>
                <th width="10">Quotes Provider Fee</th>
                <th width="10">Final Converted Amount</th>
                <th width="10">Credited Amount</th>
                <th width="10" align="right">Status</th>
            </tr>
        </thead>
        <tbody>
        @foreach($transfers as $transfer)
            @if(isset($transfer['transaction_description']))
                @continue
            @endif
            <tr>
                <td align="left" valign="top">{{ $transfer['transaction_id'] }}</td>
                <td valign="top">{{ $transfer['date_time'] }}</td>
                <td valign="top">{{ $transfer['requested'] }}</td>
                <td valign="top">{{ $transfer['client'] }}</td>
                <td valign="top">{{ $transfer['debited_account'] }}</td>
                <td valign="top">{{ $transfer['credited_account'] }}</td>
                <td valign="top">{{ $transfer['quotes_provider'] }}</td>
                <td valign="top">{{ $transfer['exchange_rate'] }}</td>
                <td valign="top">{{ $transfer['margin_commission'] }}</td>
                <td valign="top">{{ $transfer['debited_amount'] }}</td>
                <td valign="top">{{ $transfer['quotes_provider_fee'] }}</td>
                <td valign="top">{{ $transfer['final_amount'] }}</td>
                <td valign="top">{{ $transfer['credited_amount'] }}</td>
                <td align="right" valign="top">{{ $transfer['status'] }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</body>
</html>
