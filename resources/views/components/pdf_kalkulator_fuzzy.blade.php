<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>Kalkulator Fuzzy</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            line-height: 1.5;
            margin: 0;
            padding: 0;
        }

        h3 {
            margin: 2px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th,
        td {
            border: 1px solid black;
            padding: 5px;
        }

        th {
            background-color: #f2f2f2;
            text-align: left;
        }

        td.right-align {
            text-align: right;
        }
    </style>
</head>

<body>
    <div align="center">
        <h3>Kalkulator Fuzzy</h3>
    </div>
    <hr style="height:1px;background-color:black;">
    <br>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Bayi</th>
                <th>Jenis Kelamin</th>
                <th>Usia</th>
                <th>Berat Badan</th>
                <th>Tinggi Badan</th>
                <th>Hasil Fuzzy Sugeno</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($records as $record)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $record->nama_bayi }}</td>
                    <td>{{ $record->jenis_kelamin }}</td>
                    <td class="right-align">{{ $record->usia }} bulan</td>
                    <td class="right-align">{{ number_format($record->berat_badan, 2) }} kg</td>
                    <td class="right-align">{{ $record->tinggi_badan }} cm</td>
                    <td>{{ $record->indexFuzzy->nama }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h3>Ringkasan</h3>
    <table>
        <thead>
            <tr>
                <th>Hasil Fuzzy Sugeno</th>
                <th>Laki-Laki</th>
                <th>Perempuan</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @php
                $summary = $records->groupBy('indexFuzzy.nama')->map(function ($group) {
                    return [
                        'Laki-Laki' => $group->where('jenis_kelamin', 'Laki-Laki')->count(),
                        'Perempuan' => $group->where('jenis_kelamin', 'Perempuan')->count(),
                    ];
                });
            @endphp
            @foreach ($summary as $indexFuzzy => $counts)
                <tr>
                    <td>{{ $indexFuzzy }}</td>
                    <td class="right-align">{{ $counts['Laki-Laki'] }}</td>
                    <td class="right-align">{{ $counts['Perempuan'] }}</td>
                    <td class="right-align">{{ $counts['Laki-Laki'] + $counts['Perempuan'] }}</td>
                </tr>
            @endforeach
            <tr>
                <td><strong>Total</strong></td>
                <td class="right-align"><strong>{{ $summary->sum('Laki-Laki') }}</strong></td>
                <td class="right-align"><strong>{{ $summary->sum('Perempuan') }}</strong></td>
                <td class="right-align"><strong>{{ $summary->sum('Laki-Laki') + $summary->sum('Perempuan') }}</strong>
                </td>
            </tr>
        </tbody>
    </table>
</body>

</html>
