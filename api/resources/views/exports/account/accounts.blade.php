<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
</head>
<body>
    <table class="table">
        <thead>
            <tr>
                <th colspan="14" valign="top"><b>Accounts</b></th>
            </tr>
            <tr>
                <th width="10" align="left">ID</th>
                <th width="13">Member Company</th>
                <th width="12">Client Name</th>
                <th width="10">Owner name</th>
                <th width="10">IBAN Provider</th>
                <th width="10">IBAN Account</th>
                <th width="7">Currency</th>
                <th width="6">Primary</th>
                <th width="8">Cur. Balance</th>
                <th width="8">Reserv. Balance</th>
                <th width="8">Availab. Balance</th>
                <th width="6">Total transactions</th>
                <th width="6">Total Panding</th>
                <th width="6">Last transaction</th>
            </tr>
        </thead>
        <tbody>
        @foreach($accounts as $account)
            <tr>
                <td align="left" valign="top">{{ $account['account_id'] }}</td>
                <td valign="top">{{ $account['member_company'] }}</td>
                <td valign="top">{{ $account['client_name'] }}</td>
                <td valign="top">{{ $account['owner_name'] }}</td>
                <td valign="top">{{ $account['iban_provider'] }}</td>
                <td valign="top">{{ $account['iban_account'] }}</td>
                <td valign="top">{{ $account['currency'] }}</td>
                <td valign="top">{{ $account['is_primary'] }}</td>
                <td valign="top">{{ $account['current_balance'] }}</td>
                <td valign="top">{{ $account['reserved_balance'] }}</td>
                <td valign="top">{{ $account['available_balance'] }}</td>
                <td valign="top">{{ $account['total_transactions'] }}</td>
                <td valign="top">{{ $account['total_panding'] }}</td>
                <td valign="top">{{ $account['last_transaction'] }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</body>
</html>