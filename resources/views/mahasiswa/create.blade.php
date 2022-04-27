@extends('mahasiswa.layout')
@section('content')

<div class="container mt-5">

    <div class="row justify-content-center align-items-center">
        <div class="card" style="width: 24rem;">
        <div class="card-header">
            Tambah Mahasiswa
        </div>
            <div class="card-body">
                @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>Whoops!</strong> There were some problems with your input.<br><br>
                    <ul>
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
                <form method="post" action="{{ route('mahasiswa.store') }}" id="myForm" enctype="multipart/form-data">
                @csrf
                    <div class="form-group">
                        <label for="Nim">Nim</label>
                        <input type="text" name="Nim" class="form-control" id="Nim" aria-describedby="Nim">
                    </div>
                    <div class="form-group">
                        <label for="Nama">Nama</label>
                        <input type="text" name="Nama" class="form-control" id="Nama" aria-describedby="Nama">
                    </div>
                    <div class="form-group">
                        <label for="Foto_mahasiswa">Foto Mahasiswa</label>
                        <input type="file" name="Foto_mahasiswa" accept = 'image/jpeg , image/jpg, image/gif, image/png' class="form-control-file" id="Foto_mahasiswa">
                    </div>
                    <div class="form-group">
                        <label for="Kelas">Kelas</label>
                        <select name="Kelas" class="form-control">
                            @foreach ($Kelas as $kls)
                            <option value="{{ $kls ->id }}">{{$kls->nama_kelas}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="Jurusan">Jurusan</label>
                        <input type="text" name="Jurusan" class="form-control" id="Jurusan" aria-describedby="Jurusan">
                    </div>
                    <div class="form-group">
                        <label for="Email">Email</label>
                        <input type="Email" name="Email" class="form-control" id="Email" aria-describedby="Email">
                    </div>
                    <div class="form-group">
                        <label for="Alamat">Alamat</label>
                        <input type="text" name="Alamat" class="form-control" id="Alamat" aria-describedby="Alamat">
                    </div>
                    <div class="form-group">
 <label for="TanggalLahir">Tanggal Lahir</label>
 <input type="TanggalLahir" name="TanggalLahir" class="form-control" id="TanggalLahir" ariadescribedby="TanggalLahir" >
 </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection