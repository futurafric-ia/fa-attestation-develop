<!doctype html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <link rel="stylesheet" href="{{ public_path('static/css/bootstrap.min.css') }}"/>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{ $title ?? 'Liste' }}</title>

    <style>
        .header {
            margin-top: 1.25rem;
            text-transform: uppercase;
            text-align: center;
            padding-top: 0.25rem;
            padding-bottom: 0.25rem;
            background-color: #818c98;
            color: white;
        }
    </style>
</head>

<body>

<div>
    <img style="max-width: 250px; height: 45px" src="{{ public_path('static/images/logo.png') }}" alt="logo.png">
</div>

<div class="header">
    <h2>{{ $title ?? 'Liste' }}</h2>
</div>

<div class="mt-5">
    @foreach($items->chunk(20) as $chunk)
        <div style="page-break-after: always; page-break-inside: avoid;">
            <div class="table-responsive p-2">
                <table class="table table-bordered">
                    <thead style="background-color : #818c98; color: #fff; font-weight: bold;">
                    <tr>
                        <th>#</th>
                        @foreach ($columns as $title => $column)
                            <th>{{ $title }}</th>
                        @endforeach
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($chunk as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            @foreach (array_values($columns) as $column)
                                @if (is_callable($column))
                                    <td>{{ $column($item) }}</td>
                                @else
                                    <td>{{ data_get($item, $column) }}</td>
                                @endif
                            @endforeach
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endforeach
</div>

</body>
</html>
