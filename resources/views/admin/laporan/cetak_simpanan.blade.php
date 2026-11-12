<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Laporan Simpanan - Koperasi Syariah</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <style>
        body { font-family: 'Times New Roman', Times, serif; font-size: 13px; color: #000; }
        .kop-header { border-bottom: 3px double #000; margin-bottom: 20px; padding-bottom: 10px; }
        @media print {
            .no-print { display: none !important; }
        }
    </style>
</head>
<body class="p-4" onload="window.print()">

    <div class="no-print mb-3 text-end">
        <button onclick="window.print()" class="btn btn-sm btn-primary">Cetak Sekarang</button>
        <button onclick="window.close()" class="btn btn-sm btn-secondary">Tutup</button>
    </div>

    <div class="text-center kop-header">
        <h4 class="fw-bold mb-1">KOPERASI SYARIAH PUNGGUR INTI SEJAHTERA</h4>
        <div class="small">Badan Hukum No: 002117/BH/M.KUKM.2/IX/2016</div>
        <div class="small">Alamat: Desa Sidomulyo, Kecamatan Punggur, Kabupaten Lampung Tengah</div>
    </div>

    <div class="text-center mb-3">
        <h6 class="fw-bold text-uppercase mb-1 text-decoration-underline">LAPORAN MUTASI SIMPANAN KOPERASI</h6>
        <small>Periode: Bulan {{ DateTime::createFromFormat('!m', (int)$bulan)->format('F') }} {{ $tahun }}</small>
    </div>

    <table class="table table-bordered table-sm">
        <thead class="text-center">
            <tr>
                <th>No</th>
                <th>No. Trx</th>
                <th>Tanggal</th>
                <th>Nama Anggota</th>
                <th>Jenis</th>
                <th>Tipe</th>
                <th class="text-end">Jumlah (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @forelse($transaksi as $t)
                <tr>
                    <td class="text-center">{{ $no++ }}</td>
                    <td>{{ $t->no_transaksi }}</td>
                    <td>{{ $t->tgl_transaksi->format('d/m/Y') }}</td>
                    <td>{{ $t->anggota->nama_lengkap }}</td>
                    <td>{{ $t->jenisSimpanan->nama_simpanan }}</td>
                    <td class="text-center">{{ strtoupper($t->jenis_transaksi) }}</td>
                    <td class="text-end">{{ number_format($t->jumlah, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr><td colspan="7" class="text-center py-3">Tidak ada transaksi pada periode ini.</td></tr>
            @endforelse
        </tbody>
        <tfoot class="fw-bold">
            <tr>
                <td colspan="6" class="text-end">Total Setoran Kas Masuk:</td>
                <td class="text-end">Rp {{ number_format($totalSetor, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td colspan="6" class="text-end">Total Penarikan Kas Keluar:</td>
                <td class="text-end">Rp {{ number_format($totalTarik, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td colspan="6" class="text-end">Surplus Kas Bersih:</td>
                <td class="text-end">Rp {{ number_format($totalSetor - $totalTarik, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="row mt-5 pt-3">
        <div class="col-7"></div>
        <div class="col-5 text-center">
            <div>Punggur, {{ date('d F Y') }}</div>
            <div class="mb-5">Ketua Koperasi Syariah</div>
            <div class="fw-bold text-decoration-underline">Arifanto, S.P., M.M.</div>
            <small>Pimpinan Koperasi</small>
        </div>
    </div>

</body>
</html>

