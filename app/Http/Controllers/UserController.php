<?php

namespace App\Http\Controllers;

use App\Models\LevelModel;
use App\Models\User;
use App\Models\UserModel;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Psy\TabCompletion\Matcher\FunctionsMatcher;
use Yajra\DataTables\Contracts\DataTable;
use Yajra\DataTables\Facades\DataTables;

use function PHPUnit\Framework\returnSelf;

class UserController extends Controller
{

    public function index(){
        $breadcrumb = (object)[
            'title'=>'Daftar User',
            'list' => ['Home', 'User']  
        ];

        $page = (object)[
            'title' => 'Daftar user yang terdaftar dalam sistem'
        ];

        $activeMenu = 'user'; //set saat menu aktif
        $level = LevelModel::all();

        return view('user.index', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'level' => $level,
            'activeMenu' => $activeMenu]);
    }

    // Ambil data user dalam bentuk json untuk datatables 
    public function list(Request $request) 
    { 
        $users = UserModel::select('user_id', 'foto', 'username', 'nama', 'level_id', 'activate') 
                ->with('level'); 

        //filter
        if($request->level_id){
            $users->where('level_id', $request->level_id);
        }
 
        return DataTables::of($users) 
        ->addIndexColumn() // menambahkan kolom index / no urut (default nama kolom: DT_RowIndex) 
        ->addColumn('aksi', function ($user) {  // menambahkan kolom aksi 
            $btn  = '<a href="'.url('/user/' . $user->user_id).'" class="btn btn-info btn-sm">Detail</a> '; 
            $btn .= '<a href="'.url('/user/' . $user->user_id . '/edit').'" class="btn btn-warning btn-sm">Edit</a> '; 
            $btn .= '<form class="d-inline-block" method="POST" action="'. url('/user/'.$user->user_id).'">' 
                    . csrf_field() . method_field('DELETE') .  
                    '<button type="submit" class="btn btn-danger btn-sm" 
                    onclick="return confirm(\'Apakah Anda yakin menghapus data ini?\');">Hapus</button></form>';
            if ($user->activate == 0) {
                $btn .= '<form class="d-inline-block ml-1" method="POST" action="'. url('/user/activ/'.$user->user_id).'">' 
                    . csrf_field() . method_field('PUT') .  
                    '<button type="submit" class="btn btn-primary btn-sm" 
                    onclick="return confirm(\'Apakah Anda yakin mengaktifkan user ini?\');">Active</button></form>';
            }

            return $btn; 
        }) 
        ->rawColumns(['aksi']) // memberitahu bahwa kolom aksi adalah html 
        ->make(true); 
    } 

    public function create(){
        $breadcrumb = (object)[
            'title' => 'Tambah User',
            'list' => ['Home', 'User', 'Tambah']
        ];

        $page = (object)[
            'title' => 'Tambah user baru'
        ];

        $level = LevelModel::all(); //ambil data level untuk ditampilkan di form
        $activeMenu = 'user'; //set menu sedang aktif

        return view('user.create', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'level' => $level,
            'activeMenu' => $activeMenu
        ]);
    }

    //UNTUK MENGHANDLE ATAU MENYIMPAN DATA BARU 
    public function store(Request $request){
        $request->validate([
            //username harus diisi, berupa string, minimal 3 karakter dan bernilai unik di table m_user kolom username
            'username' => 'required|string|min:3|unique:m_user,username',
            'nama' => 'required|string|max:100',
            'password' => 'required|min:5',
            'level_id' => 'required|integer',
            'foto' => 'nullable|image',
        ]);

        $fileName = time().$request->file('foto')->getClientOriginalName();
        $path = $request->file('foto')->storeAs('images', $fileName, 'public');

        UserModel::create([
            'username'=> $request -> username,
            'nama'=> $request -> nama,
            'password' => bcrypt($request -> password),
            'activate' => 0,
            'foto' => $path,
            'level_id' => $request -> level_id,
        ]);

        return redirect('/user')->with('success', 'Data user berhasil disimpan');
    }

    //MENAMPILKAN DETAIL USER 
    public function show(string $id){
        $user = UserModel::with('level')-> find($id);

        $breadcrumb = (object)[
            'title' => 'Detail User',
            'list' => ['Home', 'User', 'Detail']
        ];

        $page = (object)[
            'title' => 'Detail user'
        ];

        $activeMenu = 'user'; // set menu yang aktif

        return view('user.show', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'user' => $user,
            'activeMenu' => $activeMenu]);
    }

    public function edit(string $id){
        $user = UserModel::find($id);
        $level = LevelModel::all();

        $breadcrumb = (object)[
            'title' => 'Edit User',
            'list' => ['Home', 'User', 'Edit']
        ];

        $page = (object)[
            'title' => 'Edit user'
        ];

        $activeMenu = 'user';

        return view('user.edit',[
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'user' => $user,
            'level' => $level,
            'activeMenu' => $activeMenu
        ]);
    }

    public function update(Request $request, string $id){
        $request->validate([
            'username' => 'required|string|min:3|unique:m_user,username,' .$id. ',user_id',
            'nama' => 'required|string|max:100',
            'password' => 'nullable|min:5',
            'level_id' => 'required|integer',
            'foto' => 'nullable|image',
        ]);

        if (!empty($request->foto)) {
            if (UserModel::find($id)->foto !== $request->foto) {
                Storage::disk('public')->delete(UserModel::find($id)->foto);
            }
            
            $fileName = time().$request->file('foto')->getClientOriginalName();
            $path = $request->file('foto')->storeAs('images', $fileName, 'public');
        } else {
            $path = UserModel::find($id)->foto;
        }

        UserModel::find($id)->update([
            'username' => $request-> username,
            'nama' => $request->nama,
            'password' => $request->password? bcrypt($request->password):UserModel::find($id)->password,
            'foto' => $path,
            'level_id' =>$request -> level_id
        ]);

        return redirect('/user')->with('success', 'Data berhasil diubah');
    }

    public function destroy(string $id){
        $check = UserModel::find($id);
        if(!$check){
            return redirect('/user')->with('error', 'Data user tidak ditemukan');
        }
        try{
            Storage::disk('public')->delete(UserModel::find($id)->foto);
            UserModel::destroy($id);
            
            return redirect('/user')->with('success', 'Data user berhasil dihapus');
        }catch(\Illuminate\Database\QueryException $e){

        return redirect('/user')->with('error', 'Data user gagal dihapus karena terdapat tabel lain yang terkait dengan data ini');
    }
}

public function activate(string $id){
    $check = UserModel::find($id);
    if(!$check){
        return redirect('/user')->with('error', 'Data user tidak ditemukan');
    }
    try{
        $user = UserModel::find($id)->update([
            'activate' => 1,
        ]);
        return redirect('/user')->with('success', 'Data user berhasil dihapus');
    }catch(\Illuminate\Database\QueryException $e){

    return redirect('/user')->with('error', 'Data user gagal dihapus karena terdapat tabel lain yang terkait dengan data ini');
}
    /*public function index(){
        $user = UserModel::with('level')->get();
        return view('user', ['data' => $user]);
    }
    public function index(){
        $user = UserModel::all();
        return view('user.index', ['data' => $user]);
    }

    public function create(): View
    {
        return view('level.create_user');
    }

    public function store(Request $request) : RedirectResponse {
        $validate = $request->validate([
            'username' => 'required',
            'namaLevel' => 'required',
        ]);

        $request->validate([
            'title'=> 'bail|required|unique:posts|max:255',
            'body'=> 'required',
        ]);

        /*$validateData = $request->validate([
            'title' => ['required', 'unique:posts', 'max:255'],
            'body' => ['required'],
        ]);*/

        /*$validateData = $request->validateWithBag('post', [
            'title' => ['required', 'unique:posts', 'max:255'],
            'body' => ['required'],
        ]);

        UserModel::create([
            'level_kode' => $request->kodeLevel,
            'level_nama' => $request->namaLevel,
        ]);
        return redirect('/user');
    }
    
    /*public function create(){
        return view('level.create_level');
    }

    public function store(Request $request){
        LevelModel::create([
            'level_kode' => $request->kodeLevel,
            'level_nama' => $request->namaLevel,
        ]);
        return redirect('/level');
    }*/

    /*public function store(Request $request){
        UserModel::create([
            'username' => $request->username,
            'level_nama' => $request->namaLevel,
        ]);
        return redirect('/user');
    }*/

    /*public function tambah(){
        return view('user_tambah');
    }

    public function tambah_simpan(Request $request){
        UserModel::create([
            'username' => $request->username,
            'nama' => $request->nama,
            'password' => Hash::make('$request->password'),
            'level_id' => $request->level_id
        ]);

        return redirect('/user');
    }

    public function ubah($id){
        $user=UserModel::find($id);
        return view('user_ubah', ['data' => $user]);
    }

    public function ubah_simpan($id, Request $request){
        $user = UserModel::find($id);

        $user->username = $request->username;
        $user->nama = $request->nama;
        $user->password = Hash::make('$request -> password');
        $user->level_id = $request->level_id;

        $user->save();

        return redirect('/user');
    }

    public function hapus($id){
        $user = UserModel::find($id);
        $user->delete();

        return redirect('/user');
    }

    /*public function index(){
        $user = UserModel::create([
            'username' => 'manager11',
            'nama' => 'Manager11',
            'password' => Hash::make('12345'),
            'level_id' => 2,
        ]);

        $user -> username = 'manager12';

        $user -> save();

        $user->wasChanged(); // true
        $user->wasChanged('username'); // true
        $user->wasChanged(['username', 'level_id']); // true
        $user->wasChanged('nama'); // false
        dd($user->wasChanged(['nama', 'username'])); // true
    }*/
    /*public function index(){
        $user = UserModel::create(
            [
                'username' => 'manager55',
                'nama' => 'Manager55',
                'password' => Hash::make('12345'),
                'level_id' => 2,
            ]);

            $user -> username = 'manager56';

            $user->isDirty(); //true
            $user->isDirty('username'); //true
            $user->isDirty('nama'); //false
            $user->isDirty(['nama', 'username']); //true

            $user -> isClean(); //false
            $user -> isClean('username'); //false
            $user -> isClean('nama'); //true
            $user -> isClean(['nama', 'username']);//false

            $user -> save();

            $user->isDirty(); //false
            $user -> isClean(); //true
            dd($user->isDirty());
    }*/
    /*public function index(){
        $user = UserModel::firstOrNew(
            [
                'username' => 'manager33',
                'nama' => 'Manager Tiga Tiga',
                'password' => Hash::make('12345'),
                'level_id' => 2
            ],
        );
        $user->save();
        return view('user', ['data' => $user]);
        
    }*/
}
}
