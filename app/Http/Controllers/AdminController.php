<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use RealRashid\SweetAlert\Facades\Alert;
use App\Models\AdminModel;
use Illuminate\Pagination\Paginator;

class AdminController extends Controller
{

public function index()
{
    try {
        Paginator::useBootstrap();
        $AdminList = AdminModel::orderBy('id', 'desc')->paginate(10); //order by & pagination
        return view('admin.list', compact('AdminList'));
    } catch (\Exception $e) {
       // \Log::error('Admin list error: '.$e->getMessage());
         return view('errors.404');
        //return response()->json(['error' => $e->getMessage()], 500); //สำหรับ debug

    }
}

    public function adding() {
        return view('admin.create');
    }

    public function create(Request $request)
    {
        //  echo '<pre>';
        //  dd($_POST);
        //  exit();

        //vali msg 
        $messages = [
            'admin_username.required' => 'กรุณากรอกข้อมูล',
            'admin_username.min' => 'กรอกข้อมูลขั้นต่ำ :min ตัวอักษร',
            'admin_username.unique' => 'ชื่อซ้ำ เพิ่มใหม่อีกครั้ง',

            'admin_password.required' => 'กรุณากรอกข้อมูล',
            'admin_password.min' => 'กรอกข้อมูลขั้นต่ำ :min ตัว',

            'admin_name.required' => 'กรุณากรอกข้อมูล',
            'admin_name.email' => 'กรอกข้อมูลขั้นต่ำ :min ตัว',
        ];

        //rule 
        $validator = Validator::make($request->all(), [
            'admin_username' => 'required|email|min:4|unique:tbl_admin',
            'admin_password' => 'required|min:4',
            'admin_name' => 'required|min:4',
        ], $messages);

        //check vali 
        if ($validator->fails()) {
            return redirect('admin/adding')
                ->withErrors($validator)
                ->withInput();
        }

        try {

            //ปลอดภัย: กัน XSS ที่มาจาก <script>, <img onerror=...> ได้
            // insert data เพิ่มข้อมูล
            AdminModel::create([
                'admin_username' => strip_tags($request->input('admin_username')),
                'admin_password' => bcrypt($request->input('admin_password')),
                'admin_name' => strip_tags($request->input('admin_name')),
            ]);
            // แสดง Alert ก่อน return
            Alert::success('เพิ่มข้อมูลสำเร็จ');
            return redirect('/admin');
        } catch (\Exception $e) {
            //return response()->json(['error' => $e->getMessage()], 500); //สำหรับ debug
            return view('errors.404');
        }
    } //fun create



 public function edit($id)
    {
        try {
            //query data for form edit 
            $admin = AdminModel::findOrFail($id); // ใช้ findOrFail เพื่อให้เจอหรือ 404
            if (isset($admin)) {
                $id = $admin->id;
                $admin_name = $admin->admin_name;
                $admin_username = $admin->admin_username;
                $admin_password = $admin->admin_password;
                return view('admin.edit', compact('id', 'admin_username','admin_password','admin_name'));
            }
        } catch (\Exception $e) {
            //return response()->json(['error' => $e->getMessage()], 500); //สำหรับ debug
            return view('errors.404');
        }
    } //func edit


 public function update($id, Request $request)
    {
        //vali msg 
        $messages = [
            // 'name.required' => 'กรุณากรอกข้อมูล',
            // 'name.min' => 'กรอกข้อมูลขั้นต่ำ :min ตัวอักษร',
            // 'name.unique' => 'ชื่อนี้ถูกใช้งานแล้ว',  //ป้องกันแก้ซ้ำกับ row อื่นๆ จ้าาา
            // 'lastname.required' => 'กรุณากรอกข้อมูล',
            // 'lastname.min' => 'กรอกข้อมูลขั้นต่ำ :min ตัวอักษร',
            // 'email.required' => 'กรุณากรอกข้อมูล',
            // 'email.email' => 'กรอกอีเมลให้ถูกต้อง',



            'admin_username.required' => 'กรุณากรอกข้อมูล',
            'admin_username.min' => 'กรอกข้อมูลขั้นต่ำ :min ตัวอักษร',
            'admin_username.unique' => 'ชื่อซ้ำ เพิ่มใหม่อีกครั้ง',

            'admin_password.required' => 'กรุณากรอกข้อมูล',
            'admin_password.min' => 'กรอกข้อมูลขั้นต่ำ :min ตัว',

            'admin_name.required' => 'กรุณากรอกข้อมูล',
            'admin_name.email' => 'กรอกข้อมูลขั้นต่ำ :min ตัว',
        ];

        //rule
        $validator = Validator::make($request->all(), [
            'admin_username' => [
                    'required',
                    'min:3',
                        Rule::unique('tbl_admin', 'admin_username')->ignore($id, 'id'), //ห้ามแก้ซ้ำ
            ],
            'admin_password' => 'required|min:3',
            'admin_name' => 'required|email',
    ], $messages);

    //check 
        if ($validator->fails()) {
            return redirect('admin/' . $id)
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $test = AdminModel::find($id);
            $test->update([
                    'name' => strip_tags($request->input('name')), //column update 
                    'lastname' => strip_tags($request->input('lastname')),
                    'email' => strip_tags($request->input('email')),
                ]);
            // แสดง Alert ก่อน return
            Alert::success('แก้ไขข้อมูลสำเร็จ');
            return redirect('/admin');
        } catch (\Exception $e) {
            //return response()->json(['error' => $e->getMessage()], 500); //สำหรับ debug
            return view('errors.404');
        }
    } //fun update 


    public function remove($id)
    {
        try {
            $admin = AdminModel::find($id);  //query หาว่ามีไอดีนี้อยู่จริงไหม 
            $admin->delete();
            Alert::success('ลบข้อมูลแอดมินสำเร็จ');
            return redirect('/admin');
        } catch (\Exception $e) {
            //return response()->json(['error' => $e->getMessage()], 500); //สำหรับ debug
            return view('errors.404');
        }
    } //remove 

     public function reset($id)
    {
        try {
            //query data for form edit 
            $admin = AdminModel::findOrFail($id); // ใช้ findOrFail เพื่อให้เจอหรือ 404
            if (isset($admin)) {
                $id = $admin->id;
                $admin_name = $admin->admin_name;
                $admin_username = $admin->admin_username;
                return view('admin.editPassword', compact('id', 'admin_username','admin_name'));
            }
        } catch (\Exception $e) {
            //return response()->json(['error' => $e->getMessage()], 500); //สำหรับ debug
            return view('errors.404');
        }
    } //func reset pwd

    



     public function resetPassword($id, Request $request)
    {
        //vali msg 
        $messages = [
            // 'name.required' => 'กรุณากรอกข้อมูล',
            // 'name.min' => 'กรอกข้อมูลขั้นต่ำ :min ตัวอักษร',
            // 'name.unique' => 'ชื่อนี้ถูกใช้งานแล้ว',  //ป้องกันแก้ซ้ำกับ row อื่นๆ จ้าาา
            // 'lastname.required' => 'กรุณากรอกข้อมูล',
            // 'lastname.min' => 'กรอกข้อมูลขั้นต่ำ :min ตัวอักษร',
            // 'email.required' => 'กรุณากรอกข้อมูล',
            // 'email.email' => 'กรอกอีเมลให้ถูกต้อง',



            'password.required' => 'กรุณากรอกรหัสผ่าน',
            'password.min' => 'กรอกข้อมูลขั้นต่ำ :min ตัวอักษร',
            'password.confirmed' => 'รหัสผ่านไม่ตรงกัน',

            'password_confirmation.required' => 'กรุณากรอกรหัสผ่านยืนยัน',
            'password_confirmation.min' => 'กรอกข้อมูลขั้นต่ำ :min ตัว',

        ];

        //rule
        $validator = Validator::make($request->all(), [
            'password' => 'required|min:4|confirmed',
            'password_confirmation' => 'required|min:4',
    ], $messages);

    //check 
        if ($validator->fails()) {
            return redirect('admin/reset/' . $id)
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $admin = AdminModel::find($id);
            $admin->update([
                    'admin_password' => bcrypt($request->input('password')), //column update 
                ]);
            // แสดง Alert ก่อน return
            Alert::success('แก้ไขรหัสผ่านสำเร็จ');
            return redirect('/admin');
        } catch (\Exception $e) {
            //return response()->json(['error' => $e->getMessage()], 500); //สำหรับ debug
            return view('errors.404');
        }
    } //fun resetPassword 



} //class
