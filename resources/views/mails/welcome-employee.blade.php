<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Welcome to Our Company</title>
</head>

<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">

    <h1>Welcome to Our Company!</h1>

    <p>Hello {{ $employee->name }},</p>

    <p>
        Welcome to our company! We&#039; re happy to have you join our team.
    </p>

    <p>
        Your employee account has been created successfully.
    </p>

    <p>
        <strong>Employee Details</strong>
    </p>

    <ul>
        <li>
            Name: {{ $employee->name }}
        </li>

        <li>
            Email: {{ $employee->email }}
        </li>

        <li>
            Joining Date: {{ $employee->joining_date?->format('d M Y') ?? 'Not specified' }}
        </li>
    </ul>

    <p>
        If you have any questions, please contact your HR department.
    </p>

    <p>
        Best regards,<br>
        HR Team
    </p>

</body>

</html>
