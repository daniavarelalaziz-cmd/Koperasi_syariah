<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Laporan Angsuran - Koperasi Syariah</title>
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
        <h6 class="fw-bold text-uppercase mb-1 text-decoration-underline">LAPORAN PENERIMAAN ANGSURAN PEMBIAYAAN</h6>
        <small>Periode: Bulan {{ DateTime::createFromFormat('!m', (int)$bulan)->format('F') }} {{ $tahun }}</small>
    </div>

    <table class="table table-bordered table-sm">
        <thead class="text-center">
            <tr>
                <th>No</th>
                <th>No. Bukti</th>
                <th>Tanggal</th>
                <th>No. Pinjaman</th>
                <th>Nama Anggota</th>
                <th>Ke</th>
                <th class="text-end">Pokok (Rp)</th>
                <th class="text-end">Bagi Hasil 2% (Rp)</th>
                <th class="text-end">Total (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @php 
                $no = 1; 
                $totP = 0; $totB = 0; $totT = 0;
            @endphp
            @forelse($angsuran as $a)
                @php 
                    $totP += $a->bayar_pokok; 
                    $totB += $a->bayar_bagi_hasil; 
                    $totT += $a->total_bayar; 
                @endphp
                <tr>
                    <td class="text-center">{{ $no++ }}</td>
                    <td>{{ $a->no_bukti_angsuran }}</td>
                    <td>{{ $a->tgl_bayar->format('d/m/Y') }}</td>
                    <td>{{ $a->pinjaman->no_pinjaman }}</td>
                    <td>{{ $a->pinjaman->anggota->nama_lengkap }}</td>
                    <td class="text-center">{{ $a->angsuran_ke }}</td>
                    <td class="text-end">{{ number_format($a->bayar_pokok, 0, ',', '.') }}</td>
                    <td class="text-end">{{ number_format($a->bayar_bagi_hasil, 0, ',', '.') }}</td>
                    <td class="text-end">{{ number_format($a->total_bayar, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr><td colspan="9" class="text-center py-3">Tidak ada data pembayaran angsuran pada periode ini.</td></tr>
            @endforelse
        </tbody>
        <tfoot class="fw-bold">
            <tr>
                <td colspan="6" class="text-end">Total Penerimaan:</td>
                <td class="text-end">Rp {{ number_format($totP, 0, ',', '.') }}</td>
                <td class="text-end">Rp {{ number_format($totB, 0, ',', '.') }}</td>
                <td class="text-end">Rp {{ number_format($totT, 0, ',', '.') }}</td>
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

