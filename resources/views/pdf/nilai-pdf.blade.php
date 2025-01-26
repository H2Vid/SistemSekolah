<!DOCTYPE html>
<html>
<head>
    <title>Data Nilai Guru</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
    </style>
</head>
<body>
    <h2>Data Nilai Guru</h2>
    <table>
        <thead>
            <tr>
                <th>Semester</th>
                <th>Mata Pelajaran</th>
                <th>Kelas</th>
                <th>Nama Siswa</th>
                <th>PH1</th>
                <th>PH2</th>
                <th>UTS</th>
                <th>UAS</th>
                <th>Keterampilan</th>
                <th>Sikap</th>
                <th>Kebersihan</th>
                <th>Kedisiplinan</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $row)
                <tr>
                    <td>{{ $row->nama_semester }}</td>
                    <td>{{ $row->mapel }}</td>
                    <td>{{ $row->kelas }}</td>
                    <td>{{ $row->nama }}</td>
                    <td>{{ $row->ph1 }}</td>
                    <td>{{ $row->ph2 }}</td>
                    <td>{{ $row->uts }}</td>
                    <td>{{ $row->uas }}</td>
                    <td>{{ $row->nilai_keterampilan }}</td>
                    <td>{{ $row->sikap }}</td>
                    <td>{{ $row->kebersihan }}</td>
                    <td>{{ $row->kedisiplinan }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
