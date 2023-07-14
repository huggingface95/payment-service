<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
</head>
<body>
    <table class="table">
        <thead>
            <tr>
                <th colspan="10" valign="top"><b>Transfers</b></th>
            </tr>
            <tr>
                <th width="10" align="left">ID</th>
                <th width="13">Created at</th>
                <th width="20">From</th>
                <th width="20">To</th>
                <th width="22">Details</th>
                <th width="10">Type transfer</th>
                <th width="10">Transfer Amount</th>
                <th width="10">Fee Amount</th>
                <th width="10">Total Debit/Credit Amount</th>
                <th width="10" align="right">Status</th>
            </tr>
        </thead>
        <tbody>
        @foreach($transfers as $transfer)
                <tr>
                    <td align="left" valign="top">{{ $transfer['transfer_id'] }}</td>
                    <td valign="top">{{ $transfer['created_at'] }}</td>
                    <td valign="top">{{ $transfer['from_account'] }}</td>
                    <td valign="top">{{ $transfer['to_account'] }}</td>
                    <td valign="top">{{ $transfer['reason'] }}</td>
                    <td valign="top">{{ $transfer['transfer_type'] }}</td>
                    <td valign="top">{{ $transfer['amount'] }}</td>
                    <td valign="top">{{ $transfer['fee_amount'] }}</td>
                    <td valign="top">{{ $transfer['amount'] + $transfer['fee_amount'] }}</td>
                    <td align="right" valign="top">{{ $transfer['payment_status_id'] }}</td>
                </tr>
        @endforeach
        </tbody>
    </table>
</body>
</html>
