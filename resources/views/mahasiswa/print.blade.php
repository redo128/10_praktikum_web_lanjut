<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Kartu Hasil Studi</title>
</head>

<body>
    <div class="d-flex justify-content-center my-5">
        <h1 class="text-center">KARTU HASIL STUDI (KHS)</h1>
    </div>

    <br>
    <b>Nama:</b> {{$daftar->mahasiswa->nama}}<br>
    <b>NIM: </b>{{$daftar->mahasiswa->nim}}<br>
    <b>Kelas: </b> {{$daftar->mahasiswa->kelas->nama_kelas}}<br>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th scope="col">Mata Kuliah</th>
                <th scope="col">SKS</th>
                <th scope="col">Semester</th>
                <th scope="col">Nilai</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($daftar as $d)
            <tr scope="row">
                <td>
                    {{$d->matakuliah->nama_matkul}}
                </td>
                <td>
                    {{$d->matakuliah->sks}}
                </td>
                <td>
                    {{$d->matakuliah->semester}}
                </td>
                <td>
                    {{$d ->nilai}}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>