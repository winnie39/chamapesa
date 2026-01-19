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

    <h2>USERS WITH MOST TRANSACTIONS</h2>

    <table>

        @foreach ($data['mostTransactions'] as $transaction)
            <tr>
                <th>{{ $transaction['name'] }} </th>
                <th>{{ $transaction['phone_number'] }} </th>
                <th> {{ number_format($transaction['sumOfTransactions']) }} </th>
            </tr>
        @endforeach

    </table>

</body>

</html>
