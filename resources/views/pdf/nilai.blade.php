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
                <div class="info-value">SMK Negeri 16 Jakarta</div>
            </div>
            <div class="info-row">
                <div class="info-label"><strong>Nama Peserta Didik</strong></div>
                <div class="info-separator">:</div>
                <div class="info-value">{{ $head->nama }}</div>
            </div>
            <div class="info-row">
                <div class="info-label"><strong>Nomor Induk/NISN</strong></div>
                <div class="info-separator">:</div>
                <div class="info-value">{{ $head->nisn }}</div>
            </div>
        </div>

        <div class="header-right">
            <div class="info-row">
                <div class="info-label"><strong>Tempat, Tanggal Lahir</strong></div>
                <div class="info-separator">:</div>
                <div class="info-value">
                    {{ $head->tempat_lahir }}, {{ \Carbon\Carbon::parse($head->tanggal_lahir)->format('d-m-Y') }}
                </div>
            </div>

            <div class="info-row">
                <div class="info-label"><strong>Kompetensi Keahlian</strong></div>
                <div class="info-separator">:</div>
                <div class="info-value">
                    @php
                        $kompetensi = [
                            'BR' => 'Bisnis Daring dan Pemasaran',
                            'MPLB' => 'Administrasi Perkantoran',
                            'AKL' => 'Akuntansi Lembaga',
                            'DKV' => 'Desain Komunikasi Visual'
                        ];
                        $kodeKelas = explode('-', $head->kode_kelas);
                        $kodeJurusan = end($kodeKelas);
                        $kompetensiKeahlian = $kompetensi[$kodeJurusan] ?? 'Kompetensi Tidak Diketahui';
                    @endphp
                    {{ $kompetensiKeahlian }}
                </div>
            </div>

            <div class="info-row">
                <div class="info-label"><strong>Tahun Pelajaran</strong></div>
                <div class="info-separator">:</div>
                <div class="info-value">{{ date('Y') }}</div>
            </div>
        </div>
    </div>

    <div class="judul">Rapor Peserta Didik</div>

    <div class="table-container">
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Mata Pelajaran</th>
                <th>Kelas</th>
                <th>Semester</th>
                <th>KKM</th>
                <th>Nilai</th>
            </tr>
        </thead>
        <tbody>
            @php
                $no = 1;
                $totalNilai = 0;
                $jumlahData = count($detail);

                $sortedDetail = $detail->sortBy([
                    fn($item) => $item->kelas,
                    fn($item) => $item->semester_id,
                ]);
            @endphp
            @foreach ($sortedDetail as $subject)
            <tr>
                <td>{{ $no++ }}</td>
                <td>{{ $subject->mata_pelajaran }}</td>
                <td>{{ $subject->kelas }}</td>
                <td>{{ $subject->semester_id }}</td>
                <td>{{ $subject->nilai_kkm }}</td>
                <td>{{ $subject->nilai_akhir }}</td>
                @php $totalNilai += $subject->nilai_akhir; @endphp
            </tr>
            @endforeach
            <!-- Baris untuk rata-rata nilai dengan garis bawah -->
            <tr style="border-top: 2px solid black;">
                <td colspan="5" style="text-align: right; font-weight: bold;">Rata-rata Nilai:</td>
                <td style="font-weight: bold;">
                    {{ $jumlahData > 0 ? number_format($totalNilai / $jumlahData, 2) : '0.00' }}
                </td>
            </tr>
        </tbody>
    </table>
</div>
<h3>Kepribadian dan Absensi</h3>
    <table class="absensi-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Kepribadian</th>
                <th>Nilai</th>
                <th>Jenis Absensi</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>1</td>
                <td>Sikap</td>
                <td>{{ $keterangan_sikap ?? '-' }}</td>
                <td>Sakit</td>
                <td>{{ $absensi->sum('total_sakit') ?? 0 }}%</td>
            </tr>
            <tr>
                <td>2</td>
                <td>Kedisiplinan</td>
                <td>{{ $keterangan_kedisiplinan ?? '-' }}</td>
                <td>Izin</td>
                <td>{{ $absensi->sum('total_izin') ?? 0 }}%</td>
            </tr>
            <tr>
                <td>3</td>
                <td>Kebersihan</td>
                <td>{{ $keterangan_kebersihan ?? '-' }}</td>
                <td>Tanpa Keterangan</td>
                <td>{{ $absensi->sum('total_alpa') ?? 0 }}%</td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        <div>
            <p></p>
            <p></p>
            <p></p>
        </div>
        <div>
            <p>Jakarta, {{ date('d M Y') }}</p>
            <p>Kepala Sekolah</p>
            <p>Maman Ruhiman, S.Pd, M.Pd</p>
        </div>
    </div>

    <button onclick="window.print()" class="no-print">Cetak / Simpan PDF</button>
</body>
</html>
