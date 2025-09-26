<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Auth;


use App\Models\UserModel;

class UserController extends Controller
{
    /** GET /user */
    public function index()
    {
        Paginator::useBootstrap();
        $users = UserModel::orderBy('user_id', 'desc')->paginate(5);
        return view('user.list', compact('users'));
    }

    /** GET /user/adding */
    public function adding()
    {
        return view('user.create');
    }

    /** POST /user */
    public function create(Request $request)
    {
        $messages = [
            'name.required'        => 'กรุณากรอกชื่อ',
            'name.min'             => 'ชื่อต้องมีอย่างน้อย :min ตัวอักษร',
            'email.required'       => 'กรุณากรอกอีเมล',
            'email.email'          => 'รูปแบบอีเมลไม่ถูกต้อง',
            'email.unique'         => 'อีเมลนี้ถูกใช้งานแล้ว',
            'password.required'    => 'กรุณากรอกรหัสผ่าน',
            'password.min'         => 'รหัสผ่านต้องมีอย่างน้อย :min ตัวอักษร',
            'password.regex'       => 'รหัสผ่านต้องมีทั้งตัวพิมพ์เล็ก พิมพ์ใหญ่ และตัวเลข',
            'role.in'              => 'สิทธิ์ผู้ใช้ต้องเป็น admin หรือ user เท่านั้น',
            'profile_img.image'    => 'ไฟล์ต้องเป็นรูปภาพ',
            'profile_img.mimes'    => 'รองรับไฟล์ jpeg, png, jpg เท่านั้น',
            'profile_img.max'      => 'ขนาดไฟล์ไม่เกิน 5MB',
        ];

        $validator = Validator::make($request->all(), [
            'name'        => 'required|min:3',
            'email'       => 'required|email|unique:tbl_user,email',
            // อย่างน้อย 8 ตัวอักษร และต้องมี a-z, A-Z, และตัวเลข
            'password'    => [
                'required',
                'min:8',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/'
            ],
            // อาจไม่ส่งมาก็ได้ จะ default เป็น user
            'role'        => ['nullable', Rule::in(['admin','user'])],
            'profile_img' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
        ], $messages);

        if ($validator->fails()) {
            return redirect('user/adding')->withErrors($validator)->withInput();
        }

        try {
            $imagePath = null;
            if ($request->hasFile('profile_img')) {
                $imagePath = $request->file('profile_img')->store('uploads/users', 'public');
            }

            UserModel::create([
                'name'        => strip_tags($request->name),
                'email'       => strtolower($request->email),
                // bcrypt ด้วย Hash::make
                'password'    => Hash::make($request->password),
                // หากไม่ได้ส่ง role มา ให้ตั้งต้นเป็น user
                'role'        => $request->role ?: 'user',
                'profile_img' => $imagePath,
            ]);

            Alert::success('เพิ่มผู้ใช้สำเร็จ');
            return redirect('/user');
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /** GET /user/{id} */
    public function edit($id)
    {
        try {
            $user = UserModel::findOrFail($id);
            return view('user.edit', compact('user'));
        } catch (\Exception $e) {
            return view('errors.404');
        }
    }

    /** PUT /user/{id} */
    public function update($id, Request $request)
    {
        $messages = [
            'name.required'        => 'กรุณากรอกชื่อ',
            'name.min'             => 'ชื่อต้องมีอย่างน้อย :min ตัวอักษร',
            'email.required'       => 'กรุณากรอกอีเมล',
            'email.email'          => 'รูปแบบอีเมลไม่ถูกต้อง',
            'email.unique'         => 'อีเมลนี้ถูกใช้งานแล้ว',
            'role.in'              => 'สิทธิ์ผู้ใช้ต้องเป็น admin หรือ user เท่านั้น',
            'profile_img.image'    => 'ไฟล์ต้องเป็นรูปภาพ',
            'profile_img.mimes'    => 'รองรับไฟล์ jpeg, png, jpg เท่านั้น',
            'profile_img.max'      => 'ขนาดไฟล์ไม่เกิน 5MB',
        ];

        $validator = Validator::make($request->all(), [
            'name'        => 'required|min:3',
            'email'       => [
                'required', 'email',
                Rule::unique('tbl_user', 'email')->ignore($id, 'user_id'),
            ],
            // อนุญาตผ่าน validation เฉพาะค่าในชุด แต่จะอัปเดตจริงเฉพาะ admin
            'role'        => ['required', Rule::in(['admin','user'])],
            'profile_img' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
        ], $messages);

        if ($validator->fails()) {
            return redirect('user/' . $id)->withErrors($validator)->withInput();
        }

        try {
            $user = UserModel::findOrFail($id);

            // อัปเดตรูปหากมีการอัปโหลดใหม่
            if ($request->hasFile('profile_img')) {
                if ($user->profile_img) {
                    Storage::disk('public')->delete($user->profile_img);
                }
                $user->profile_img = $request->file('profile_img')->store('uploads/users', 'public');
            }

            $user->name  = strip_tags($request->name);
            $user->email = strtolower($request->email);

            // อนุญาตให้เปลี่ยน role เฉพาะ admin เท่านั้น
            if (Auth::check() && Auth::user()->role === 'admin') {
    $user->role = $request->role;
}

            $user->save();

            Alert::success('แก้ไขผู้ใช้สำเร็จ');
            return redirect('/user');
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /** DELETE /user/remove/{id} */
    public function remove($id)
    {
        try {
            $user = UserModel::find($id);

            if (!$user) {
                Alert::error('ไม่พบผู้ใช้');
                return redirect('user');
            }

            if ($user->profile_img && Storage::disk('public')->exists($user->profile_img)) {
                Storage::disk('public')->delete($user->profile_img);
            }

            $user->delete();

            Alert::success('ลบผู้ใช้สำเร็จ');
            return redirect('user');
        } catch (\Exception $e) {
            Alert::error('เกิดข้อผิดพลาด: ' . $e->getMessage());
            return redirect('user');
             //return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /** GET /user/reset/{id} */
    public function reset($id)
    {
        try {
            $user = UserModel::findOrFail($id);
            return view('user.reset', compact('user'));
        } catch (\Exception $e) {
            //return view('errors.404');
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /** PUT /user/reset/{id} */
    public function resetPassword($id, Request $request)
    {
        $messages = [
            'password.required'  => 'กรุณากรอกรหัสผ่านใหม่',
            'password.min'       => 'รหัสผ่านต้องมีอย่างน้อย :min ตัวอักษร',
            'password.regex'     => 'รหัสผ่านต้องมีทั้งตัวพิมพ์เล็ก พิมพ์ใหญ่ และตัวเลข',
            'password.confirmed' => 'รหัสผ่านยืนยันไม่ตรงกัน',
        ];

        $validator = Validator::make($request->all(), [
            'password' => [
                'required',
                'min:8',
                // อย่างน้อย 8 ตัวอักษร และต้องมี a-z, A-Z, และตัวเลข
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/',
                'confirmed' // ต้องมี input ชื่อ password_confirmation
            ],
        ], $messages);

        if ($validator->fails()) {
            return redirect('user/reset/' . $id)->withErrors($validator)->withInput();
        }

        try {
            $user = UserModel::findOrFail($id);
            $user->password = Hash::make($request->password); // bcrypt
            $user->save();

            Alert::success('รีเซ็ตรหัสผ่านสำเร็จ');
            return redirect('/user');
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
