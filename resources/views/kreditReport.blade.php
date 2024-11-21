<!DOCTYPE html>
<html>

<head>
    <title>Laporan Kredit</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        table,
        th,
        td {
            border: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
            padding: 8px;
            text-align: center;
        }

        td {
            padding: 6px;
        }

        .summary {
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <h2 style="text-align: center;">Laporan Kredit</h2>
    <p style="text-align: center;">Tanggal Cetak: {{ date('d F Y') }}</p>

    <div class="summary">
        <table>
            <tr>
                <td>Jumlah Petani Belum Lunas</td>
                <td>{{ $jumlahPetaniBelumLunas }} Orang</td>
            </tr>
            <tr>
                <td>Total Kredit Belum Lunas</td>
                <td>Rp {{ number_format($totalKreditBelumLunas, 2, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Total Bunga Kredit</td>
                <td>Rp {{ number_format($totalKreditPlusBungaBelumLunas-$totalKreditBelumLunas, 2, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Total Kredit Dengan Bunga Belum Lunas</td>
                <td>Rp {{ number_format($totalKreditPlusBungaBelumLunas, 2, ',', '.') }}</td>
            </tr>
        </table>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Petani</th>
                <th>Tanggal</th>
                <th>Jumlah</th>
                <th>Hutang + Bunga</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($kredits as $kredit)
            <tr>
                <td style="text-align: center;">{{ $kredit->id }}</td>
                <td>{{ $kredit->petani->nama }}</td>
                <td style="text-align: center;">{{ $kredit->tanggal }}</td>
                <td style="text-align: right;">Rp {{ number_format($kredit->jumlah, 2, ',', '.') }}</td>
                <td style="text-align: right;">
                    Rp {{ number_format($kredit->hutang_plus_bunga, 2, ',', '.') }}
                    <br>
                    <small>({{ number_format($kredit->lama_bulan) }} Bulan, Bunga: Rp {{ number_format($kredit->bunga, 2, ',', '.') }})</small>
                </td>
                <td style="text-align: center;">{{ $kredit->status ? 'Lunas' : 'Belum Lunas' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>