<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laporan Penjualan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <style>
        table {
            border-collapse: collapse;
        }

        table,
        th,
        td {
            border: 1px solid black;
        }

        .text-right {
            text-align: right;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2 class="text-center text-bold mt-2">Laporan Penjualan Kebab Si Abah</h2>
        <h4 class="text-center">Tanggal {{ $starDate->format('d F Y') }} - {{ $endDate->format('d F Y') }}</h4>
        <table class="table table-bordered mt-4 border" border="2">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Transaksi</th>
                    <th>Cabang</th>
                    <th>User</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; ?>
                @foreach ($purchase as $purchase)
                    <tr>
                        <td>{{ $no++ }}</td>
                        <td>{{ $purchase->formatted_created_at }}</td>
                        <td>{{ $purchase->detailTransactionSale }}</td>
                        <td>{{ $purchase->cabang->name }}</td>
                        <td>{{ $purchase->user->name }}</td>
                        <td class="text-right">{{ $purchase->total_subtotal }}</td>
                    </tr>
                @endforeach
            </tbody>

        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous">
    </script>
</body>

</html>
