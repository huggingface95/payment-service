<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
</head>
<body>
    <table class="table">
        <thead>
            <tr>
                <th colspan="17" valign="top"><b>Outgoing Transfers</b></th>
            </tr>
            <tr>
                <th width="10" align="left">ID</th>
                <th width="13">Created at</th>
                <th width="13">Requested by</th>
                <th width="13">Recipient</th>
                <th width="13">Sender</th>
                <th width="10">Account ID</th>
                <th width="10">IBAN Account</th>
                <th width="20">Transfer Reason</th>
                <th width="20">Type</th>
                <th width="10">Urgency</th>
                <th width="10">Currency</th>
                <th width="10">Amount</th>
                <th width="10">Fee Amount</th>
                <th width="10">Fee Account</th>
                <th width="10">Provider Fee</th>
                <th width="10">Debit Amount</th>
                <th width="10" align="right">Status</th>
            </tr>
        </thead>
        <tbody>
        @foreach($transfers as $transfer)
            @if(isset($transfer['transaction_id']))
                <tr>
                    <td align="left" valign="top">{{ $transfer['transaction_id'] }}</td>
                    <td valign="top">{{ $transfer['date_time'] }}</td>
                    <td valign="top">{{ $transfer['requested'] }}</td>
                    <td valign="top">{{ $transfer['recipient'] }}</td>
                    <td valign="top">{{ $transfer['sender'] }}</td>
                    <td valign="top">{{ $transfer['account_id'] }}</td>
                    <td valign="top">{{ $transfer['account_number'] }}</td>
                    <td valign="top">{{ $transfer['reason'] }}</td>
                    <td valign="top">{{ $transfer['transaction_description'] }}</td>
                    <td valign="top">{{ $transfer['urgency'] }}</td>
                    <td valign="top">{{ $transfer['currency'] }}</td>
                    <td valign="top">{{ $transfer['debit'] }}</td>
                    <td valign="top">{{ $transfer['fee_amount'] }}</td>
                    <td valign="top">{{ $transfer['fee_account'] }}</td>
                    <td valign="top">{{ $transfer['fee_provider'] }}</td>
                    <td valign="top">{{ $transfer['credit_amount'] }}</td>
                    <td align="right" valign="top">{{ $transfer['status'] }}</td>
                </tr>
            @endif
        @endforeach
        </tbody>
    </table>
</body>
</html>
