<?php

namespace App\Http\Controllers;

use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function __construct()
    {
        // กันคนไม่ล็อกอินเข้า
        $this->middleware('auth');
    }

    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        // ถ้าจะใช้ authorize แบบนี้ ต้องมี Gate/Policy ชื่อ 'edit-user'
        // $this->authorize('edit-user', $user);

        // ทำอีเมลเป็น lower ก่อน validate เพื่อลดโอกาสชนเคสพิมพ์ใหญ่/เล็ก
        $request->merge([
            'email' => strtolower($request->input('email'))
        ]);

        $request->validate([
            'name'        => 'required|min:3',
            'email'       => [
                'required',
                'email',
                Rule::unique('tbl_user', 'email')->ignore($user->user_id, 'user_id'),
            ],
            'profile_img' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
        ]);

        // จัดการรูปโปรไฟล์
        if ($request->hasFile('profile_img')) {
            // ลบไฟล์เก่า (ถ้ามีและมีอยู่จริง)
            if ($user->profile_img && Storage::disk('public')->exists($user->profile_img)) {
                Storage::disk('public')->delete($user->profile_img);
            }
            // อัปโหลดไฟล์ใหม่
            $user->profile_img = $request->file('profile_img')->store('uploads/users', 'public');
        }

        $user->name  = strip_tags($request->name);
        $user->email = $request->email;
        $user->save();

        return back()->with('success', 'อัปเดตโปรไฟล์แล้ว');
    }

    public function updatePassword(Request $request)
    {
        // ถ้าอยากบังคับใส่รหัสผ่านเดิมด้วย ให้ใช้ current_password:guard
        // เช่น: 'current_password' => ['required','current_password:web']
        $request->validate([
            'password' => [
                'required',
                'min:8',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/',
                'confirmed', // ต้องมี field password_confirmation
            ],
        ]);

        $user = Auth::user();
        $user->password = Hash::make($request->password);
        $user->save();

        return back()->with('success', 'เปลี่ยนรหัสผ่านสำเร็จ');
    }
}
