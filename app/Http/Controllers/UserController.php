<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Project;
use App\Models\Category;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('check_role:admin');
    }

    public function index()
    {
        $title = 'Users';
        $subtitle = 'List of users';
        $users = User::with(['categories' => function ($query) {
            $query->orderBy('category_name', 'asc');
        }])->orderBy('name', 'asc')->get();


        return view('users.index', compact('title', 'subtitle', 'users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = 'Users';
        $subtitle = 'Add User';

        $projects = Project::where('project_status', '=', '1')->orderBy('project_code', 'asc')->get();
        $categories = Category::where('category_status', '=', '1')->orderBy('category_name', 'asc')->get();

        return view('users.create', compact('title', 'subtitle', 'projects', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validasi input
        $validatedData = $request->validate([
            'name' => 'required',
            'email' => 'required|email:dns|unique:users|ends_with:@arka.co.id',
            'password' => 'required|min:5',
            'level' => 'required',
            'project_id' => 'required',
            'user_status' => 'required',
            'categories' => 'array',  // Pastikan categories berupa array
            'categories.*' => 'exists:categories,id',  // Pastikan setiap kategori yang dipilih ada di database
        ], [
            'name.required' => 'Name is required',
            'email.required' => 'Email is required',
            'password.required' => 'Password is required',
            'categories.array' => 'Please select valid categories.',
        ]);

        // Enkripsi password
        $validatedData['password'] = Hash::make($validatedData['password']);

        // Membuat user baru
        $user = User::create($validatedData);

        // Menambahkan kategori yang dipilih oleh user
        if (isset($validatedData['categories'])) {
            $user->categories()->sync($validatedData['categories']);  // Menyinkronkan kategori yang dipilih
        }

        // Redirect setelah berhasil
        return redirect('users')->with('success', 'User added successfully!');
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $title = 'Users';
        $subtitle = 'Edit User';
        $user = User::find($id);
        $projects = Project::where('project_status', '=', '1')->orderBy('project_code', 'asc')->get();
        $categories = Category::where('category_status', '=', '1')->orderBy('category_name', 'asc')->get();

        return view('users.edit', compact('title', 'subtitle', 'user', 'projects', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Validasi input
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email',
            'categories' => 'array', // Pastikan categories berupa array
        ], [
            'name.required' => 'Name is required',
            'email.required' => 'Email is required',
            'categories.array' => 'Invalid categories format',
        ]);

        // Ambil data user berdasarkan ID
        $user = User::findOrFail($id);

        // Validasi tambahan untuk email jika berubah
        if ($request->email != $user->email) {
            $this->validate($request, [
                'email' => 'required|email:dns|unique:users|ends_with:@arka.co.id',
            ]);
        }

        // Proses input untuk password (jika ada)
        $input = $request->all();
        if (!empty($input['password'])) {
            $input['password'] = Hash::make($input['password']);
        } else {
            $input = Arr::except($input, array('password'));
        }

        // Update data user
        $user->update($input);

        // Assign categories ke user
        if ($request->has('categories')) {
            $user->categories()->sync($request->categories); // Sinkronisasi kategori yang dipilih
        } else {
            $user->categories()->detach(); // Jika tidak ada kategori, lepaskan semua relasi
        }

        // Redirect dengan pesan sukses
        return redirect('users')->with('success', 'User edited successfully');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->categories()->detach(); // Jika user dihapus, maka lepaskan relasi ke categories juga
        $user->delete();
        return redirect('users')->with('success', 'User deleted successfully');
    }
}
