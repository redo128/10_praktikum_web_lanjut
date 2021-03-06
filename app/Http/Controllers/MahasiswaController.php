<?php
namespace App\Http\Controllers;
use App\Models\Mahasiswa;
use App\Models\Kelas;
use App\Models\Mahasiswa_Matakuliah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDF;
class MahasiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
        {
            //fungsi eloquent menampilkan data menggunakan pagination
        //  $mahasiswa = $mahasiswa = DB::table('mahasiswa')->paginate(3); // Mengambil semua isi tabel
        //  $mahasiswa = Mahasiswa::orderBy('Nim', 'desc')->paginate(3);
        // return view('mahasiswa.index', compact('mahasiswa'));
        //  with('i', (request()->input('page', 1) - 1) * 5);
        $mahasiswa=Mahasiswa::with('kelas')->get();
        $paginate=Mahasiswa::orderby('id_mahasiswa','asc')->paginate(3);
        return view('mahasiswa.index',['mahasiswa'=>$mahasiswa,'paginate'=>$paginate]);
              //  with('i', (request()->input('page', 1) - 1) * 5);
        }
        public function nilai($id){

            $daftar = Mahasiswa_MataKuliah::with("matakuliah")->where("mahasiswa_id", $id)->get();
            $daftar->mahasiswa = Mahasiswa::with('kelas')->where('id_mahasiswa', $id)->first();
        //    dd($daftar);
            return view('mahasiswa.nilai', compact('daftar'));
        }
    public function create()
        {
            $Kelas=Kelas::all();//mendapatkan data dari tabel kelas
            return view('mahasiswa.create',['Kelas'=>$Kelas]);
        }
    public function store(Request $request)
        {
            //melakukan validasi data
            $request->validate([
            'Nim' => 'required',
            'Nama' => 'required',
            'Kelas' => 'required',
            'Jurusan' => 'required',
            'Email' => 'required',
            'Alamat' => 'required',
            'TanggalLahir' => 'required'
            ]);
          
        if ($request -> file('Foto_mahasiswa')){
            $foto = $request->file('Foto_mahasiswa')->store('images', 'public');
        }

        //fungsi eloquent untuk menambah data
        // Mahasiswa::create($request->all());

        $mahasiswa = new Mahasiswa();
        $mahasiswa->nim = $request->get("Nim");
        $mahasiswa->nama = $request->get("Nama");
        // $mahasiswa->kelas_id = $mahasiswa->kelas()->associate(Kelas::find($request->get("Kelas")));
        $mahasiswa->foto = $foto;
            $mahasiswa->Jurusan = $request->get('Jurusan');
            $mahasiswa->Email = $request->get('Email');
            $mahasiswa->Alamat = $request->get('Alamat');
            //$mahasiswa->save();
            //$kelas=new Kelas;
            //$kelas->id=$request->get('Kelas');
            $kelas=Kelas::find($request->get('Kelas'));
            $mahasiswa->Kelas()->associate($kelas);
            $mahasiswa->TanggalLahir = $request->get('TanggalLahir');
            $mahasiswa->foto=$foto;
            $mahasiswa->save();
            // //fungsi eloquent untuk menambah data
            // //  Mahasiswa::create($request->all());
            // $mahasiswa->save();
            //jika data berhasil ditambahkan, akan kembali ke halaman utama
            return redirect()->route('mahasiswa.index')
                ->with('success', 'Mahasiswa Berhasil Ditambahkan');
        }
    public function show($Nim)
        {
            //menampilkan detail data dengan menemukan/berdasarkan Nim Mahasiswa
            //code sebelum dibuat relasi-->$mahasiswa=mahasiswa::find($nim);
            //$Mahasiswa = Mahasiswa::find($Nim);
            $mahasiswa=Mahasiswa::with('kelas')->where('nim',$Nim)->first();
            //return view('mahasiswa.detail', compact('Mahasiswa'));
            return view('mahasiswa.detail', ['Mahasiswa'=>$mahasiswa]);
        }
    public function edit($Nim)
        {
        //menampilkan detail data dengan menemukan berdasarkan Nim Mahasiswa untuk diedit
            $Mahasiswa = DB::table('mahasiswa')->where('nim', $Nim)->first();;
            $kelas=Kelas::all();//mendapatkan data dari table kelas
            return view('mahasiswa.edit', compact('Mahasiswa','kelas'));
        }
    public function update(Request $request, $Nim)
        {
            //melakukan validasi data
            $request->validate([
                'Nim' => 'required',
                'Nama' => 'required', 
                'Foto' => 'required', 
                'Kelas' => 'required',
                'Jurusan' => 'required',
                'Email' => 'required',
                'Alamat' => 'required',
                'TanggalLahir' => 'required'
            ]);
            if ($request -> file('Foto')) {
                $image_name = $request -> file('Foto') -> store('images', 'public');
            }
            $mahasiswa = Mahasiswa::with('kelas')->where('nim',$Nim)->first();
            $mahasiswa->Nim = $request->get('Nim');
            $mahasiswa->Nama = $request->get('Nama');
            $mahasiswa->Foto = $image_name;
            $mahasiswa->Jurusan = $request->get('Jurusan');
            $mahasiswa->Email = $request->get('Email');
            $mahasiswa->Alamat = $request->get('Alamat');
            $mahasiswa->TanggalLahir = $request->get('TanggalLahir');
            $kelas=Kelas::find($request->get('Kelas'));
            $mahasiswa->Kelas()->associate($kelas);
            $mahasiswa->save();
            //fungsi eloquent untuk mengupdate data inputan kita
            // Mahasiswa::find($Nim)->update($request->all());
                //jika data berhasil diupdate, akan kembali ke halaman utama
                return redirect()->route('mahasiswa.index')
                    ->with('success', 'Mahasiswa Berhasil Diupdate');
        }
    public function destroy($Nim)
        {
            //fungsi eloquent untuk menghapus data
            Mahasiswa::find($Nim)->delete();
            return redirect()->route('mahasiswa.index')
                -> with('success', 'Mahasiswa Berhasil Dihapus');
        }
        public function cetak_pdf($id)
        {
            $daftar = Mahasiswa_MataKuliah::where("mahasiswa_id", $id)->get();
            $daftar->mahasiswa = Mahasiswa::with('kelas')->where('id_mahasiswa', $id)->first();
            $pdf = PDF::loadview('mahasiswa.print', compact('daftar'));
            return $pdf->stream();
        }
};
