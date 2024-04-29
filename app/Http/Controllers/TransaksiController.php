<?php

namespace App\Http\Controllers;

use App\Models\TransaksiModel;
use App\Models\UserModel;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class TransaksiController extends Controller
{
    public function index(){
        $breadcrumb = (object)[
            'title'=>'Daftar transaksi',
            'list' => ['Home', 'transaksi']  
        ];

        $page = (object)[
            'title' => 'Daftar transaksi yang terdaftar dalam sistem'
        ];

        $activeMenu = 'transaksi'; //set saat menu aktif
        $user = UserModel::all();

        return view('transaksi.index', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'user' => $user,
            'activeMenu' => $activeMenu]);
    }

    // Ambil data barang dalam bentuk json untuk datatables 
    public function list(Request $request) 
    { 
        $transaksis = TransaksiModel::select('penjualan_id', 'user_id', 'penjualan_kode', 'pembeli', 'penjualan_tanggal',) 
                ->with('user'); 

                //filter
                if($request->user_id){
                    $transaksis->where('user_id', $request->user_id);
                }
 
        return DataTables::of($transaksis) 
        ->addIndexColumn() // menambahkan kolom index / no urut (default nama kolom: DT_RowIndex) 
        ->addColumn('aksi', function ($penjualan) {  // menambahkan kolom aksi 
            $btn  = '<a href="'.url('/transaksi/' . $penjualan->penjualan_id).'" class="btn btn-info btn-sm">Detail</a> '; 
            $btn .= '<a href="'.url('/transaksi/' . $penjualan->penjualan_id . '/edit').'" class="btn btn-warning btn-sm">Edit</a> '; 
            $btn .= '<form class="d-inline-block" method="POST" action="'. url('/transaksi/'.$penjualan->penjualan_id).'">' 
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
            'title' => 'Tambah Transaksi',
            'list' => ['Home', 'Transaksi', 'Tambah']
        ];

        $page = (object)[
            'title' => 'Tambah Transaksi baru'
        ];

        $user = UserModel::all(); //ambil data kategori untuk ditampilkan di form
        $activeMenu = 'Transaksi'; //set menu sedang aktif

        return view('Transaksi.create', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'user' => $user,
            'activeMenu' => $activeMenu
        ]);
    }

    //UNTUK MENGHANDLE ATAU MENYIMPAN DATA BARU 
    public function store(Request $request){
        $request->validate([
            //barang_kode harus diisi, berupa string, minimal 3 karakter dan bernilai unik di table m_barang kolom barang_kode
            'penjualan_kode' => 'required|string|min:3|unique:t_penjualan,penjualan_kode',
            'pembeli' => 'required|string|max:100',
            'penjualan_tanggal' => 'required|date'
        ]);

        TransaksiModel::create([
            'user_id' => $request -> user_id,
            'penjualan_kode'=> $request -> penjualan_kode,
            'pembeli'=> $request -> pembeli,
            'penjualan_tanggal' => $request -> penjualan_tanggal,
        ]);

        return redirect('/transaksi')->with('success', 'Data transaksi berhasil disimpan');
    }

    //MENAMPILKAN DETAIL BARANG 
    public function show(string $id){
        $penjualan = TransaksiModel::with('user')-> find($id);

        $breadcrumb = (object)[
            'title' => 'Detail Transaksi',
            'list' => ['Home', 'Transaksi', 'Detail']
        ];

        $page = (object)[
            'title' => 'Detail Transaksi'
        ];

        $activeMenu = 'transaksi'; // set menu yang aktif

        return view('transaksi.show', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'penjualan' => $penjualan,
            'activeMenu' => $activeMenu]);
    }

    public function edit(string $id){
        $penjualan = TransaksiModel::find($id);
        $user = UserModel::all();

        $breadcrumb = (object)[
            'title' => 'Edit Transaksi',
            'list' => ['Home', 'Transaksi', 'Edit']
        ];

        $page = (object)[
            'title' => 'Edit Transaksi'
        ];

        $activeMenu = 'transaksi';

        return view('transaksi.edit',[
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'penjualan' => $penjualan,
            'user' => $user,
            'activeMenu' => $activeMenu
        ]);
    }

    public function update(Request $request, string $id){
        $request->validate([
            //barang_kode harus diisi, berupa string, minimal 3 karakter dan bernilai unik di table m_barang kolom barang_kode
            'pembeli' => 'required|string|max:100',
            'penjualan_tanggal' => 'required|date'
        ]);

        TransaksiModel::find($id)->update([
            'penjualan_kode'=> $request -> penjualan_kode,
            'pembeli'=> $request -> pembeli,
            'penjualan_tanggal' => $request -> penjualan_tanggal,
            'user_id' => $request -> user_id,
        ]);

        return redirect('/transaksi')->with('success', 'Data berhasil diubah');
    }

    public function destroy(string $id){
        $check = TransaksiModel::find($id);
        if(!$check){
            return redirect('/transaksi')->with('error', 'Data transaksi tidak ditemukan');
        }
        try{
            TransaksiModel::destroy($id);

            return redirect('/transaksi')->with('success', 'Data transaksi berhasil dihapus');
        }catch(\Illuminate\Database\QueryException $e){

        return redirect('/transaksi')->with('error', 'Data transaksi gagal dihapus karena terdapat tabel lain yang terkait dengan data ini');
    }
    }
}
