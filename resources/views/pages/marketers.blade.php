<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>

<body class=" flex flex-col px-2 py-3">
    <div class="overflow-x-auto">
        <table class="table table-xs">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone number</th>

                    @foreach ($data['levels'] as $level)
                        <th>Level {{ $level['level'] }} </th>
                    @endforeach

                </tr>
            </thead>
            <tbody>
                @foreach ($data['marketers'] as $referrals)
                    <tr>
                        <th> {{ $referrals['user']['name'] }} </th>
                        <td> {{ $referrals['user']['email'] }}</td>
                        <td> {{ $referrals['user']['phone_number'] }}</td>

                        @php
                            $totalAmounts = 0;
                        @endphp

                        @foreach ($referrals['refs'] as $key => $levels)
                            <td>
                                <ol>
                                    @foreach ($levels as $levelUsers)
                                        <li>
                                            {{ $levelUsers['user']['name'] }} - {{ $levelUsers['transactions'] }}
                                        </li>
                                    @endforeach

                                    @php
                                        $totalAmounts += $referrals['sum'][$key];
                                    @endphp

                                    <li class=" text-yellow-600">Total: {{ $referrals['sum'][$key] }} </li>
                                </ol>
                            </td>
                        @endforeach

                        <td>
                            <ol>

                                <li class=" font-bold text-lg text-yellow-600">Total: {{ number_format($totalAmounts) }}
                                </li>

                            </ol>
                        </td>

                    </tr>
                @endforeach

            </tbody>
            <tfoot>

            </tfoot>
        </table>
    </div>
</body>

</html>
