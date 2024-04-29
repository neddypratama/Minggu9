<?php

namespace App\Http\Controllers;

use App\DataTables\KategoriDataTable;
use App\Http\Requests\StorePostRequest;
use App\Models\KategoriModel;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class KategoriController extends Controller
{
    public function index(){
        $breadcrumb = (object)[
            'title'=>'Daftar Kategori',
            'list' => ['Home', 'Kategori']  
        ];

        $page = (object)[
            'title' => 'Daftar kategori yang terdaftar dalam sistem'
        ];

        $activeMenu = 'kategori'; //set saat menu aktif
        $kategori = KategoriModel::all();

        return view('kategori.index', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'kategori' => $kategori,
            'activeMenu' => $activeMenu]);
    }

    // Ambil data kategori dalam bentuk json untuk datatables 
    public function list(Request $request) 
    { 
        $kategoris = KategoriModel::select('kategori_id', 'kategori_kode', 'kategori_nama'); 
 
        return DataTables::of($kategoris) 
        ->addIndexColumn() // menambahkan kolom index / no urut (default nama kolom: DT_RowIndex) 
        ->addColumn('aksi', function ($kategori) {  // menambahkan kolom aksi 
            $btn  = '<a href="'.url('/kategori/' . $kategori->kategori_id).'" class="btn btn-info btn-sm">Detail</a> '; 
            $btn .= '<a href="'.url('/kategori/' . $kategori->kategori_id . '/edit').'" class="btn btn-warning btn-sm">Edit</a> '; 
            $btn .= '<form class="d-inline-block" method="POST" action="'. url('/kategori/'.$kategori->kategori_id).'">' 
                    . csrf_field() . method_field('DELETE') .  
                    '<button type="submit" class="btn btn-danger btn-sm" 
                    onclick="return confirm(\'Apakah Anda yakit menghapus data ini?\');">Hapus</button></form>';      
            return $btn; 
        }) 
        ->rawColumns(['aksi']) // memberitahu bahwa kolom aksi adalah html 
        ->make(true); 
    } 

    public function create(){
        $breadcrumb = (object)[
            'title' => 'Tambah Kategori',
            'list' => ['Home', 'Kategori', 'Tambah']
        ];

        $page = (object)[
            'title' => 'Tambah kategori baru'
        ];

        $kategori = KategoriModel::all(); //ambil data kategori untuk ditampilkan di form
        $activeMenu = 'kategori'; //set menu sedang aktif

        return view('kategori.create', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'kategori' => $kategori,
            'activeMenu' => $activeMenu
        ]);
    }

    //UNTUK MENGHANDLE ATAU MENYIMPAN DATA BARU 
    public function store(Request $request){
        $request->validate([
            //kategori_kode harus diisi, berupa string, minimal 3 karakter dan bernilai unik di table m_kategori kolom kategori_kode
            
            'kategori_kode' => 'required|string|min:3|unique:m_kategori,kategori_kode',
            'kategori_nama' => 'required|string|max:100',
        ]);

        KategoriModel::create([
            'kategori_kode'=> $request -> kategori_kode,
            'kategori_nama'=> $request -> kategori_nama,
        ]);

        return redirect('/kategori')->with('success', 'Data kategori berhasil disimpan');
    }

    //MENAMPILKAN DETAIL kategori 
    public function show(string $id){
        $kategori = KategoriModel::find($id);

        $breadcrumb = (object)[
            'title' => 'Detail kategori',
            'list' => ['Home', 'kategori', 'Detail']
        ];

        $page = (object)[
            'title' => 'Detail kategori'
        ];

        $activeMenu = 'kategori'; // set menu yang aktif

        return view('kategori.show', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'kategori' => $kategori,
            'activeMenu' => $activeMenu]);
    }

    public function edit(string $id){
        $kategori = KategoriModel::find($id);

        $breadcrumb = (object)[
            'title' => 'Edit kategori',
            'list' => ['Home', 'Kategori', 'Edit']
        ];

        $page = (object)[
            'title' => 'Edit kategori'
        ];

        $activeMenu = 'kategori';

        return view('kategori.edit',[
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'kategori' => $kategori,
            'activeMenu' => $activeMenu
        ]);
    }

    public function update(Request $request, string $id){
        $request->validate([
            'kategori_kode' => 'required|string|min:3|unique:m_kategori,kategori_nama,' .$id. ',kategori_id',
            'kategori_nama' => 'required|string|max:100',
        ]);

        kategoriModel::find($id)->update([
            'kategori_kode' => $request-> kategori_kode,
            'kategori_nama' => $request->kategori_nama,
        ]);

        return redirect('/kategori')->with('success', 'Data berhasil diubah');
    }

    public function destroy(string $id){
        $check = KategoriModel::find($id);
        if(!$check){
            return redirect('/kategori')->with('error', 'Data kategori tidak ditemukan');
        }
        try{
            KategoriModel::destroy($id);

            return redirect('/kategori')->with('success', 'Data kategori berhasil dihapus');
        }catch(\Illuminate\Database\QueryException $e){

        return redirect('/kategori')->with('error', 'Data kategori gagal dihapus karena terdapat tabel lain yang terkait dengan data ini');
    }
}





    
    /*public function create(): View
    {
        return view('category.create');
    }

    public function store(StorePostRequest $request): RedirectResponse{
        
        // The incoming request is valid 

        //Retrive the validated input data 

        $validated = $request->validate();

        //Retrive a portion of the validated input data 
        $validated = $request->safe()->only(['kodeKategori', 'namaKategori']);
        $validated = $request->safe()->except(['kodeKategori', 'namaKategori']);

        // store the post

        return redirect('/kategori');

    }

    /*public function store(Request $request) : RedirectResponse {
        $validate = $request->validate([
            'kodeKategori' => 'required',
            'namaKategori' => 'required',
        ]);

        //DISESUAIKAN DENGAN YANG TERSEDIA, DI CREATE KATEGORI TIDAK ADA TITLE DAN BODY ADANYA NAMA DAN KODE
        $request->validate([
            'title'=> 'bail|required|unique:posts|max:255',
            'body'=> 'required',
        ]);

        $validateData = $request->validate([
            'title' => ['required', 'unique:posts', 'max:255'],
            'body' => ['required'],
        ]);

        $validateData = $request->validateWithBag('post', [
            'title' => ['required', 'unique:posts', 'max:255'],
            'body' => ['required'],
        ]);

        KategoriModel::create([
            'kategori_kode' => $request->kodeKategori,
            'kategori_nama' => $request->namaKategori,
        ]);
        return redirect('/kategori');
    }*/




    //PERTEMUAN 5
    /*public function index(KategoriDataTable $dataTable){
        return $dataTable -> render('category.index');
    }
    
    /*public function create(){
        return view('category.create');
    }

    public function store(Request $request){
        KategoriModel::create([
            'kategori_kode' => $request->kodeKategori,
            'kategori_nama' => $request->namaKategori,
        ]);
        return redirect('/kategori');
    }

    public function edit($id){
        $kategori = KategoriModel::find($id);
        return view('category.edit', ['data' => $kategori]);
    }

    public function update(Request $request, $id){
        $kategori=KategoriModel::find($id);
        $kategori->update($request->all());

        return redirect('/kategori');
    }

    public function delete($id){
        $kategori = KategoriModel::find($id);
        $kategori->delete();

        return redirect('/kategori');
    }*/

    //PERTEMUAN 4
    /*public function index(){
        /*$data = [
            'kategori_kode' => 'SNK',
            'kategori_nama' => 'Snack/Makanan Ringan',
            'created_at' => now()
        ];

        DB::table('m_kategori')->insert($data);
        return 'Insert data baru berhasil';*/

        /*$row = DB::table('m_kategori')->where('kategori_kode', 'SNK')
        ->update(['kategori_nama'=>'Camilan']);
        return 'Update data berhasil. Jumlah data yang diupdate : ' .$row .' baris';*/

        /*$row = DB::table('m_kategori')->where('kategori_kode', 'SNK')->delete();
        return 'Delete data berhasil. Jumlah data yang diupdate : ' .$row .' baris';

        $data = DB::select('select * from m_kategori');
        return view('kategori', ['data' => $data]);
        
    }*/

    
}
