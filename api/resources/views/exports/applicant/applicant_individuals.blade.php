<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
</head>
<body>
    <table class="table">
        <thead>
            <tr>
                <th colspan="12" valign="top"><b>Applicant Individuals</b></th>
            </tr>
            <tr>
                <th width="5" align="left">ID</th>
                <th width="8">Created</th>
                <th width="16">Member Company</th>
                <th width="10">Modules</th>
                <th width="16">Applicant Name</th>
                <th width="13">Project URL</th>
                <th width="13">Project Email</th>
                <th width="13">Group Name</th>
                <th width="8">Risk Level</th>
                <th width="8">KYC Level</th>
                <th width="8">Status</th>
                <th width="8">URL</th>
            </tr>
        </thead>
        <tbody>
        @foreach($applicants as $applicant)
            <tr>
                <td align="left" valign="top">{{ $applicant['applicant_id'] }}</td>
                <td valign="top">{{ $applicant['created_at'] }}</td>
                <td valign="top">{{ $applicant['member_company'] }}</td>
                <td valign="top">{{ $applicant['modules'] }}</td>
                <td valign="top">{{ $applicant['applicant_name'] }}</td>
                <td valign="top">{{ $applicant['project_url'] }}</td>
                <td valign="top">{{ $applicant['project_email'] }}</td>
                <td valign="top">{{ $applicant['group_name'] }}</td>
                <td valign="top">{{ $applicant['risk_level'] }}</td>
                <td valign="top">{{ $applicant['kyc_level'] }}</td>
                <td valign="top">{{ $applicant['status'] }}</td>
                <td valign="top">{{ $applicant['url'] }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</body>
</html>
