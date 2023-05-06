<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
</head>
<body>
    <table class="table">
        <thead>
            <tr>
                <th colspan="7" valign="top"><b>Transfer outgoings</b></th>
            </tr>
            <tr>
                <th width="10" align="left">Date / Time</th>
                <th width="13">Transaction ID</th>
                <th width="13">Account ID</th>
                <th width="36">Transaction Description</th>
                <th width="10">Currency</th>
                <th width="10">Debit</th>
                <th width="10" align="right">Status</th>
            </tr>
        </thead>
        <tbody>
        @foreach($transfers as $transfer)
            <tr>
                <td align="left" valign="top">{{ $transfer['date_time'] }}</td>
                <td valign="top">{{ $transfer['transaction_id'] }}</td>
                <td valign="top">{{ $transfer['account_id'] }}</td>
                <td valign="top">{{ $transfer['transaction_description'] }}</td>
                <td valign="top">{{ $transfer['currency'] }}</td>
                <td valign="top">{{ $transfer['debit'] }}</td>
                <td align="right" valign="top">{{ $transfer['status'] }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</body>
</html>
