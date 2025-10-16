<?php

namespace App\Http\Controllers;

use App\Models\UserModel;
//use Illuminate\Container\Attributes\Log;
use Illuminate\Support\Facades\Log;

use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use RealRashid\SweetAlert\Facades\Alert;


class UserController extends Controller
{
    // GET /admin/users
    public function index()
    {
        Paginator::useBootstrap();
        $users = UserModel::orderBy('user_id', 'desc')->paginate(10);
        return view('user.list', compact('users')); // โฟลเดอร์เดิมของโปรเจกต์
    }

    // GET /admin/users/create
    public function create() {
    $users = \App\Models\UserModel::orderBy('user_id','desc')->paginate(10);
    return view('user.create', compact('users'));
}

    // POST /admin/users
    public function store(Request $request)
    {
        $messages = [
            'name.required'     => 'กรุณากรอกชื่อ',
            'name.min'          => 'ชื่อต้องมีอย่างน้อย :min ตัวอักษร',
            'email.required'    => 'กรุณากรอกอีเมล',
            'email.email'       => 'รูปแบบอีเมลไม่ถูกต้อง',
            'email.unique'      => 'อีเมลนี้ถูกใช้งานแล้ว',
            'password.required' => 'กรุณากรอกรหัสผ่าน',
            'password.min'      => 'รหัสผ่านต้องมีอย่างน้อย :min ตัวอักษร',
            'password.regex'    => 'รหัสผ่านต้องมีทั้งตัวพิมพ์เล็ก พิมพ์ใหญ่ และตัวเลข',
            'role.in'           => 'สิทธิ์ผู้ใช้ต้องเป็น admin หรือ user เท่านั้น',
            'profile_img.image' => 'ไฟล์ต้องเป็นรูปภาพ',
            'profile_img.mimes' => 'รองรับไฟล์ jpeg, png, jpg เท่านั้น',
            'profile_img.max'   => 'ขนาดไฟล์ไม่เกิน 5MB',
        ];

        $validator = Validator::make($request->all(), [
            'name'        => 'required|min:3',
            'email'       => 'required|email|unique:tbl_user,email',
            'password'    => ['required','min:8','regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/'],
            'role'        => ['nullable', Rule::in(['admin','user'])],
            'profile_img' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
        ], $messages);

        if ($validator->fails()) {
            return redirect()->route('admin.users.create')->withErrors($validator)->withInput();
        }

        $imagePath = null;
        if ($request->hasFile('profile_img')) {
            $imagePath = $request->file('profile_img')->store('uploads/users', 'public');
        }

        UserModel::create([
            'name'        => strip_tags($request->name),
            'email'       => strtolower($request->email),
            'password'    => Hash::make($request->password),
            'role'        => $request->role ?: 'user',
            'profile_img' => $imagePath,
        ]);

        Alert::success('เพิ่มผู้ใช้สำเร็จ');
       // return redirect()->route('admin.users.index');
       // มันจอขาวอะเลยแก้ให้เด้งไปหน้าแรกแทน
           return redirect()->route('shop.index')->with('success', 'เพิ่มผู้ใช้สำเร็จ');

    }

    // GET /admin/users/{user}/edit
    public function edit($id)
    {
        $user = UserModel::findOrFail($id);
        return view('user.edit', compact('user'));
    }

    // PUT/PATCH /admin/users/{user}
    public function update($id, Request $request)
    {
        $messages = [
            'name.required'     => 'กรุณากรอกชื่อ',
            'name.min'          => 'ชื่อต้องมีอย่างน้อย :min ตัวอักษร',
            'email.required'    => 'กรุณากรอกอีเมล',
            'email.email'       => 'รูปแบบอีเมลไม่ถูกต้อง',
            'email.unique'      => 'อีเมลนี้ถูกใช้งานแล้ว',
            'role.in'           => 'สิทธิ์ผู้ใช้ต้องเป็น admin หรือ user เท่านั้น',
            'profile_img.image' => 'ไฟล์ต้องเป็นรูปภาพ',
            'profile_img.mimes' => 'รองรับไฟล์ jpeg, png, jpg เท่านั้น',
            'profile_img.max'   => 'ขนาดไฟล์ไม่เกิน 5MB',
        ];

        $rules = [
            'name'        => 'required|min:3',
            'email'       => ['required','email', Rule::unique('tbl_user','email')->ignore($id, 'user_id')],
            'profile_img' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
        ];

        if (Auth::user()->role === 'admin') {
            $rules['role'] = ['required', Rule::in(['admin','user'])];
        }

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return redirect()->route('admin.users.edit', $id)->withErrors($validator)->withInput();
        }

        $user = UserModel::findOrFail($id);

        if ($request->hasFile('profile_img')) {
            if ($user->profile_img && Storage::disk('public')->exists($user->profile_img)) {
                Storage::disk('public')->delete($user->profile_img);
            }
            $user->profile_img = $request->file('profile_img')->store('uploads/users', 'public');
        }

        $user->name  = strip_tags($request->name);
        $user->email = strtolower($request->email);

        if (Auth::user()->role === 'admin' && $request->filled('role')) {
            $user->role = $request->role;
        }

        $user->save();

        Alert::success('แก้ไขผู้ใช้สำเร็จ');
        return redirect()->route('admin.users.index');
    }

    // DELETE /admin/users/{user}
// public function destroy($id)
// {
//     try {
//         $user = \App\Models\UserModel::find($id);
//         if (!$user) {
//             return redirect()->route('admin.users.index')->with('error','ไม่พบผู้ใช้');
//         }

//         // กันลบตัวเอง
//         if (Auth::id() === (int) $user->user_id) {
//             return redirect()->route('admin.users.index')->with('error','ไม่สามารถลบบัญชีของตนเองได้');
//         }

//         // กันลบแอดมินคนสุดท้าย
//         if ($user->role === 'admin') {
//             $adminCount = \App\Models\UserModel::where('role', 'admin')->count();
//             if ($adminCount <= 1) {
//                 return redirect()->route('admin.users.index')->with('error','ไม่สามารถลบผู้ดูแลระบบคนสุดท้ายได้');
//             }
//         }

//         // ลบรูปโปรไฟล์ถ้ามี
//         if ($user->profile_img && Storage::disk('public')->exists($user->profile_img)) {
//             Storage::disk('public')->delete($user->profile_img);
//         }

//         $user->delete();

//         return redirect()->route('admin.users.index')->with('success','ลบผู้ใช้สำเร็จ');
//     } catch (\Throwable $e) {
//         return redirect()->route('admin.users.index')->with('error','ลบไม่สำเร็จ: '.$e->getMessage());
//     }
// }

public function destroy($id)
{
    try {
        $user = \App\Models\UserModel::find($id);
        if (!$user) {
            return redirect()->to(url()->previous() ?: route('admin.users.index'))
                             ->with('error','ไม่พบผู้ใช้');
        }

        // กันลบตัวเอง
        if (Auth::id() === (int) $user->user_id) {
            return redirect()->to(url()->previous() ?: route('admin.users.index'))
                             ->with('error','ไม่สามารถลบบัญชีของตนเองได้');
        }

        // กันลบแอดมินคนสุดท้าย
        if ($user->role === 'admin') {
            $adminCount = \App\Models\UserModel::where('role', 'admin')->count();
            if ($adminCount <= 1) {
                return redirect()->to(url()->previous() ?: route('admin.users.index'))
                                 ->with('error','ไม่สามารถลบผู้ดูแลระบบคนสุดท้ายได้');
            }
        }

        // ลบรูปโปรไฟล์ถ้ามี
        if ($user->profile_img && Storage::disk('public')->exists($user->profile_img)) {
            Storage::disk('public')->delete($user->profile_img);
        }

        $user->delete();

        return redirect()->to(url()->previous() ?: route('admin.users.index'))
                         ->with('success','ลบผู้ใช้สำเร็จ');
    } catch (\Throwable $e) {
        Log::error($e); // เก็บ log ไว้ตรวจ
        return redirect()->to(url()->previous() ?: route('admin.users.index'))
                         ->with('error','ลบไม่สำเร็จ: '.$e->getMessage());
    }
}




    // GET /admin/users/{user}/reset
    public function editReset($id)
    {
        $user = UserModel::findOrFail($id);
        return view('user.reset', compact('user')); // ใช้ไฟล์ view เดิม
    }

    // PUT /admin/users/{user}/reset
    public function updateReset($id, Request $request)
    {
        $messages = [
            'password.required'  => 'กรุณากรอกรหัสผ่านใหม่',
            'password.min'       => 'รหัสผ่านต้องมีอย่างน้อย :min ตัวอักษร',
            'password.regex'     => 'รหัสผ่านต้องมีทั้งตัวพิมพ์เล็ก พิมพ์ใหญ่ และตัวเลข',
            'password.confirmed' => 'รหัสผ่านยืนยันไม่ตรงกัน',
        ];

        $validator = Validator::make($request->all(), [
            'password' => ['required','min:8','regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/','confirmed'],
        ], $messages);

        if ($validator->fails()) {
            return redirect()->route('admin.users.reset.edit', $id)->withErrors($validator)->withInput();
        }

        $user = UserModel::findOrFail($id);
        $user->password = Hash::make($request->password);
        $user->save();

        Alert::success('รีเซ็ตรหัสผ่านสำเร็จ');
        return redirect()->route('admin.users.index');
    }

    // PUT /admin/users/{user}/role
public function updateRole($id, Request $request)
{
    $request->validate([
        'role' => ['required', Rule::in(['admin','user'])],
    ]);

    $user = \App\Models\UserModel::findOrFail($id);

    // กันลบ/ลดสิทธิ์แอดมินคนสุดท้าย
    if ($user->role === 'admin' && $request->role === 'user') {
        $adminCount = \App\Models\UserModel::where('role', 'admin')->count();
        if ($adminCount <= 1) {
            \RealRashid\SweetAlert\Facades\Alert::error('ไม่สามารถลดสิทธิ์ผู้ดูแลระบบคนสุดท้ายได้');
            return back();
        }
    }

    $user->role = $request->role;
    $user->save();

    \RealRashid\SweetAlert\Facades\Alert::success('อัปเดตสิทธิ์สำเร็จ');
    return back();
}

// PUT /admin/users/{user}/password-quick
public function updatePasswordQuick($id, Request $request)
{
    $messages = [
        'password.required'  => 'กรุณากรอกรหัสผ่านใหม่',
        'password.min'       => 'รหัสผ่านต้องมีอย่างน้อย :min ตัวอักษร',
        'password.regex'     => 'รหัสผ่านต้องมีทั้งตัวพิมพ์เล็ก พิมพ์ใหญ่ และตัวเลข',
        'password.confirmed' => 'รหัสผ่านยืนยันไม่ตรงกัน',
    ];

    $request->validate([
        'password' => [
            'required','min:8',
            'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/',
            'confirmed',
        ],
    ], $messages);

    $user = \App\Models\UserModel::findOrFail($id);
    $user->password = \Illuminate\Support\Facades\Hash::make($request->password);
    $user->save();

    \RealRashid\SweetAlert\Facades\Alert::success('เปลี่ยนรหัสผ่านสำเร็จ');
    return back();
}

}

