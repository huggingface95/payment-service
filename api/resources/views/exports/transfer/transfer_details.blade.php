<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
</head>
<body>
    <table class="table">
        <thead>
            <tr>
                <th colspan="4" valign="top"><strong>Transfer details</strong></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td align="left" width="20" valign="top">Date / Time</td>
                <td align="left" width="30" valign="top">{{ $created_date }}</td>
                <td align="left" width="20" valign="top">Execution date</td>
                <td align="left" width="30" valign="top">{{ $execution_date }}</td>
            </tr>
            <tr>
                <td align="left" width="20" valign="top">Client (Name and ID)</td>
                <td align="left" width="30" valign="top">{{ $account_client_name }} (ID {{ $account_id }})</td>
                <td align="left" width="20" valign="top">Transfer amount</td>
                <td align="left" width="30" valign="top">{{ $amount }} {{ $currency }}</td>
            </tr>
            <tr>
                <td align="left" width="20" valign="top">Operation type</td>
                <td align="left" width="30" valign="top">{{ $operation_type }}</td>
                <td align="left" width="20" valign="top">Payment provider fee</td>
                <td align="left" width="30" valign="top">{{ $payment_provider_fee }} {{ $currency }}</td>
            </tr>
            <tr>
                <td align="left" width="20" valign="top">Transfer ID</td>
                <td align="left" width="30" valign="top">{{ $transfer_id }}</td>
                <td align="left" width="20" valign="top">Fee amount</td>
                <td align="left" width="30" valign="top">{{ $fee_amount }} {{ $currency }}</td>
            </tr>
            <tr>
                <td align="left" width="20" valign="top">Debit from account (ID / IBAN)</td>
                <td align="left" width="30" valign="top">{{ $account_id }} / {{ $iban }}</td>
                <td align="left" width="20" valign="top">Final amount debited</td>
                <td align="left" width="30" valign="top">{{ $final_amount_debited }} {{ $currency }}</td>
            </tr>
            <tr>
                <td align="left" width="20" valign="top">Status</td>
                <td align="left" width="30" valign="top">{{ $status }}</td>
                <td align="left" width="20" valign="top">Fee account</td>
                <td align="left" width="30" valign="top">ID {{ $fee_account }}</td>
            </tr>
            <tr>
                <td align="left" width="20" valign="top">Urgency</td>
                <td align="left" width="30" valign="top">{{ $urgency }}</td>
                <td align="left" width="20" valign="top">Transfer reason</td>
                <td align="left" width="30" valign="top">{{ $transfer_reason }}</td>
            </tr>
        </tbody>
    </table>
</body>
</html>
