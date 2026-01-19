<!DOCTYPE html>
<html>

<head>
    <style>
        table {
            font-family: arial, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }

        td,
        th {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }

        tr:nth-child(even) {
            background-color: #dddddd;
        }
    </style>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>

<body>

    <h2>USERS WITH MOST DOWNLINES</h2>

    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Phone number</th>
                <th>Referrals count</th>
            </tr>
        </thead>
        <tbody>

            @foreach ($data['users'] as $user)
                <tr>
                    <td>{{ $user['name'] }} </td>
                    <td>{{ $user['phone_number'] }} </td>
                    <td> {{ number_format($user['downlines_count']) }} </td>
                </tr>
            @endforeach
        </tbody>

    </table>

</body>

</html>
