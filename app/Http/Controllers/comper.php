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
            // $mahasiswa = $mahasiswa = DB::table('mahasiswa')->get(); // Mengambil semua isi tabel
            // $mahasiswa = Mahasiswa::orderBy('Nim', 'desc')->paginate(3);
            // return view('mahasiswa.index', compact('mahasiswa'));
            // with('i', (request()->input('page', 1) - 1) * 5);

            $mahasiswa = Mahasiswa::with('kelas')->orderby('id','asc')->paginate(3);
            return view('mahasiswa.index', ['mahasiswa' => $mahasiswa]);
        }
    public function create()
        {
            $kelas = Kelas::all();
            return view('mahasiswa.create', ['kelas' => $kelas]);
        }
    public function store(Request $request)
        {
            //melakukan validasi data
            $request->validate([
            'Nim' => 'required|digits_between:8,10',
            'Nama' => 'required|string|max:25',
            'Kelas' => 'required',
            'Jurusan' => 'required',
            'Email' => ['required', 'email:dns', 'unique:mahasiswa'],
            'Alamat' => 'required',
            'Tanggal_Lahir' => 'required',
            ]);

            if ($request -> file('Foto_mahasiswa')){
                $foto = $request->file('Foto_mahasiswa')->store('images', 'public');
            }
            $mahasiswa = new Mahasiswa;
            $mahasiswa->Nim = $request->get('Nim');
            $mahasiswa->Nama = $request->get('Nama');
            $mahasiswa->Foto_mahasiswa = $foto;
            $mahasiswa->Jurusan = $request->get('Jurusan');
            $mahasiswa->Email = $request->get('Email');
            $mahasiswa->Alamat = $request->get('Alamat');
            $mahasiswa->Tanggal_Lahir = $request->get('Tanggal_Lahir');
            $mahasiswa->Kelas_id = $request->get('Kelas');

            
            $mahasiswa->save();

            return redirect()->route('mahasiswa.index')
                ->with('success', 'Mahasiswa Berhasil Ditambahkan');
        }
    public function show($Nim)
        {
            //menampilkan detail data dengan menemukan/berdasarkan Nim Mahasiswa
            $Mahasiswa = Mahasiswa::find($Nim);
            return view('mahasiswa.detail', compact('Mahasiswa'));
        }
    public function edit($Nim)
        {
        //menampilkan detail data dengan menemukan berdasarkan Nim Mahasiswa untuk diedit
            $Mahasiswa = DB::table('mahasiswa')->where('nim', $Nim)->first();;
            $Kelas = Kelas::all();
            return view('mahasiswa.edit', compact('Mahasiswa','Kelas'));
        }
    public function update(Request $request, $Nim)
        {
            //melakukan validasi data
            $request->validate([
            'Nim' => 'required|digits_between:8,12',
            'Nama' => 'required|string|max:25',
            'Kelas' => 'required',
            'Jurusan' => 'required',
            'Email' => ['required', 'email:dns'],
            'Alamat' => 'required',
            'Tanggal_Lahir' => 'required',
            ]);

            if ($request -> file('Foto_mahasiswa')){
                $foto = $request->file('Foto_mahasiswa')->store('images', 'public');
            }
            $mahasiswa = Mahasiswa::with('kelas')->where('nim', $Nim)->first();
            $mahasiswa->Nim = $request->get('Nim');
            $mahasiswa->Nama = $request->get('Nama');
            $mahasiswa->Foto_mahasiswa = $foto;
            $mahasiswa->Jurusan = $request->get('Jurusan');
            $mahasiswa->Email = $request->get('Email');
            $mahasiswa->Alamat = $request->get('Alamat');
            $mahasiswa->Tanggal_Lahir = $request->get('Tanggal_Lahir');
            
            $mahasiswa->save();

            $kelas = new Kelas;
            $kelas->id = $request->get('Kelas');

            $mahasiswa->kelas()->associate($kelas);
            $mahasiswa->save();
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
    public function nilai($id)
        {
            //menampilkan data dari relasi many to many
            $daftar = Mahasiswa_MataKuliah::with("matakuliah")->where("mahasiswa_id", $id)->get();
            $daftar->mahasiswa = Mahasiswa::with('kelas')->where('id', $id)->first();
            return view('mahasiswa.nilai', compact('daftar'));
        }

        public function cetak_pdf($id)
        {
            $daftar = Mahasiswa_MataKuliah::where("mahasiswa_id", $id)->get();
            $daftar->mahasiswa = Mahasiswa::with('kelas')->where('id', $id)->first();
            $pdf = PDF::loadview('mahasiswa.print', compact('daftar'));
            return $pdf->stream();
        }
};