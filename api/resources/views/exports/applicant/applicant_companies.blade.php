<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
</head>
<body>
    <table class="table">
        <thead>
            <tr>
                <th colspan="13" valign="top"><b>Companies</b></th>
            </tr>
            <tr>
                <th width="5" align="left">ID</th>
                <th width="8">Created</th>
                <th width="13">Member Company</th>
                <th width="8">Modules</th>
                <th width="13">Applicant Name</th>
                <th width="11">Project URL</th>
                <th width="11">Project Email</th>
                <th width="11">Group Name</th>
                <th width="11">Owner Name</th>
                <th width="8">Risk Level</th>
                <th width="8">KYC Level</th>
                <th width="8">Status</th>
                <th width="10">URL</th>
            </tr>
        </thead>
        <tbody>
        @foreach($companies as $company)
            <tr>
                <td align="left" valign="top">{{ $company['company_id'] }}</td>
                <td valign="top">{{ $company['created_at'] }}</td>
                <td valign="top">{{ $company['member_company'] }}</td>
                <td valign="top">{{ $company['modules'] }}</td>
                <td valign="top">{{ $company['applicant_name'] }}</td>
                <td valign="top">{{ $company['project_url'] }}</td>
                <td valign="top">{{ $company['project_email'] }}</td>
                <td valign="top">{{ $company['group_name'] }}</td>
                <td valign="top">{{ $company['owner_name'] }}</td>
                <td valign="top">{{ $company['risk_level'] }}</td>
                <td valign="top">{{ $company['kyc_level'] }}</td>
                <td valign="top">{{ $company['status'] }}</td>
                <td valign="top">{{ $company['url'] }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</body>
</html>
