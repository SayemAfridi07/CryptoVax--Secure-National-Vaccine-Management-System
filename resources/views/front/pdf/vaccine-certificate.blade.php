<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Vaccine Certificate - Vaxx</title>
    <style>
        #container {
            margin: 0 auto;
            margin-top: 50px;
        }

        .p-2 {
            padding: 5px;
        }

        .py {
            padding: 5px 0;
        }

        h2 {
            color: crimson;
        }

        .card {
            border: 1px solid seagreen;
            text-align: center;
            padding: 40px;
        }

        table {
            margin: 0 auto;
            width: 100%;
        }

        .logo {
            text-align: center;
            vertical-align: middle;
        }

        .logo img {
            display: inline-block;
        }

    </style>
</head>

<body>
    <div id="container">
        <div class="card">
            <h2>Covid-19 Vaccine Certificate</h2>
            <table border="1">
                <tr>
                    <th class="p-2">Name</th>
                    <td class="p-2">{{ $user->name }}</td>
                    <td colspan="3" rowspan="7" class="logo">
                        <img src="{{ public_path('img/logo.png') }}" alt="" style="2px solid red">
                    </td>
                </tr>
                <tr>
                    <th class="p-2">Email</th>
                    <td class="p-2">{{ $user->email }}</td>
                </tr>
                <tr>
                    <th class="p-2">Phone</th>
                    <td class="p-2">{{ $user->phone }}</td>
                </tr>
                <tr>
                    <th class="p-2">NID</th>
                    <td class="p-2">{{ $user->nid }}</td>
                </tr>
                <tr>
                    <th class="p-2">Date Of Birth</th>
                    <td class="p-2">{{ $user->dob }}</td>
                </tr>
                <tr>
                    <th class="p-2">Vaccine Center</th>
                    <td class="p-2">{{ $user->registration->center->name }}</td>
                </tr>
                <tr>
                    <th class="p-2">Registration Date</th>
                    <td class="p-2">{{ $user->registration->created_at->format('Y-M-d') }}</td>
                </tr>

                <tr>
                    <td colspan="6" class="text-center">
                        <h4>Vaccine Informations</h4>
                    </td>
                </tr>

                <tr>
                    <th>Dose Type</th>
                    <th>Date Scheduled</th>
                    <th>Date Taken</th>
                    <th>Given By</th>
                    <th>Vaccine Name</th>
                </tr>

                @foreach ($user->registration->doses as $dose)

                    @continue(!($dose->vaccine && $dose->taken_date))

                    <tr>
                        <th> {{ ucfirst($dose->dose_type ?? '') }} Dose </th>
                        <td> {{ $dose->scheduled_date ?? '' }} </td>
                        <td> {{ $dose->taken_date ?? '' }} </td>
                        <td> {{ $dose->givenBy?->name ?? '' }} </td>
                        <td> {{ $dose->vaccine?->name ?? '' }} </td>
                    </tr>
                    
                @endforeach

            </table>
        </div>
    </div>
</body>

</html>
