<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;         // สำคัญ! สำหรับ Hash::make
use App\Models\UserModel;

class AuthController extends Controller
{
    /** GET /login */
    public function showLoginForm()
    {
        return view('auth.login'); // ใช้วิวเบา ๆ ของตัวเอง
    }

    /** POST /login */
    public function login(Request $request)
    {
        $request->validate([
            'email'    => ['required','email'],
            'password' => ['required'],
        ]);

        $cred = [
            'email'    => strtolower($request->email),
            'password' => $request->password,
        ];

        if (Auth::attempt($cred, $request->boolean('remember'))) {
            $request->session()->regenerate();
            // กลับไปหน้าที่ผู้ใช้ตั้งใจเข้า หรือหน้าแรก
            return redirect()->intended('/')->with('success', 'เข้าสู่ระบบสำเร็จ');
        }

        return back()->withErrors(['email' => 'อีเมลหรือรหัสผ่านไม่ถูกต้อง'])->withInput();
    }

    /** POST /logout */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login')->with('success', 'ออกจากระบบแล้ว');
    }

    /** GET /register */
    public function showRegisterForm()
    {
        return view('auth.register'); // ใช้วิวเบา ๆ ของตัวเอง
    }

    /** POST /register */
    public function register(Request $request)
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
            'password.confirmed'=> 'รหัสผ่านยืนยันไม่ตรงกัน',
        ];

        $validator = Validator::make($request->all(), [
            'name'     => ['required','min:3'],
            'email'    => ['required','email','unique:tbl_user,email'],
            'password' => [
                'required','min:8',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/',
                'confirmed'
            ],
        ], $messages);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // สมัครสมาชิกใหม่ => role = user เสมอ
        $user = UserModel::create([
            'name'     => strip_tags($request->name),
            'email'    => strtolower($request->email),
            'password' => Hash::make($request->password),
            'role'     => 'user',
        ]);

        // ล็อกอินให้ทันที (ถ้าไม่ต้องการ ให้คอมเมนต์สองบรรทัดนี้ออก)
        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->intended('/')->with('success', 'สมัครสมาชิกสำเร็จ');
    }
}
