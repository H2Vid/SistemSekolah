<!DOCTYPE html>
<html>
<head>
    <title>Rapor Peserta Didik</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
        }

        @media print {
            @page {
                size: A4;
                margin: 20mm 15mm 20mm 15mm;
            }

            .kop-surat {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
            }

            .no-print {
                display: none;
            }
        }

        .kop-surat img {
            width: 100%;
        }

        .header-section {
            margin-top: 70px;
            margin-bottom: 10px;
            display: flex;
            justify-content: space-between;
            line-height: 1.8;
            font-size: 14px;
        }

        .header-left, .header-right {
            display: flex;
            flex-direction: column;
        }

        .header-left {
            width: 50%;
        }

        .header-right {
            width: 45%;
            margin-left: 20px;
        }

        .info-row {
            display: flex;
            margin-bottom: 5px;
        }

        .info-label {
            flex: 0 0 50%;
            text-align: left;
        }

        .info-separator {
            flex: 0 0 10px;
            text-align: center;
        }

        .info-value {
            flex: 1;
            text-align: left;
        }

        .judul {
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            margin: 20px 0;
        }

        .table-container {
            border: 2px solid black;
            padding: 10px;
            margin: 20px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }

        table th, table td {
            border: 1px solid black;
            padding: 6px;
            text-align: left;
        }

        .footer {
            margin-top: 20px;
            display: flex;
            justify-content: space-between;
        }

        .footer div {
            text-align: center;
        }

        .footer p {
            margin: 40px 0 0;
        }

        .rata-rata-section {
            margin-top: 10px;
            font-weight: bold;
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="kop-surat">
        <img src="{{ asset('kop-surat.jpg') }}" alt="Kop Surat">
    </div>

    <div class="header-section">
        <div class="header-left">
            <div class="info-row">
                <div class="info-label"><strong>Nama Sekolah</strong></div>
                <div class="info-separator">:</div>
                <div class="info-value">SMK Negeri 106 Jakarta</div>
            </div>
            <div class="info-row">
                <div class="info-label"><strong>Nama Guru</strong></div>
                <div class="info-separator">:</div>
                <div class="info-value">{{ $guru->nama }}</div>
            </div>
            <div class="info-row">
                <div class="info-label"><strong>NIP</strong></div>
                <div class="info-separator">:</div>
                <div class="info-value">{{ $guru->nip }}</div>
            </div>
        </div>

        <div class="header-right">
            <div class="info-row">
                <div class="info-label"><strong>Tempat, Tanggal Lahir</strong></div>
                <div class="info-separator">:</div>
                <div class="info-value">{{ $guru->tempat_lahir }}, {{ $guru->tanggal_lahir }}</div>
            </div>

            <div class="info-row">
                <div class="info-label"><strong>Kompetensi Keahlian</strong></div>
                <div class="info-separator">:</div>
                <div class="info-value">{{ $guru->kompetensi_keahlian }}</div>
            </div>

            <div class="info-row">
                <div class="info-label"><strong>Tahun Pelajaran</strong></div>
                <div class="info-separator">:</div>
                <div class="info-value">2024/2025</div>
            </div>
        </div>
    </div>

    <div class="judul">Rapor Peserta Didik</div>

    <div class="table-container">
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Mapel</th>
                <th>NISN</th>
                <th>NAMA</th>
                <th>PH1</th>
                <th>PH2</th>
                <th>UTS</th>
                <th>UAS</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($nilai as $index => $n)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $n->kode_mapel }}</td>
                <td>{{ $n->nisn }}</td>
                <td>{{ $n->nama }}</td>
                <td>{{ $n->ph1 }}</td>
                <td>{{ $n->ph2 }}</td>
                <td>{{ $n->uts }}</td>
                <td>{{ $n->uas }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

    <button onclick="window.print()" class="no-print">Cetak / Simpan PDF</button>
</body>
</html>
