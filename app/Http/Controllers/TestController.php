<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use RealRashid\SweetAlert\Facades\Alert;
use App\Models\TestModel;
use Illuminate\Pagination\Paginator;

class TestController extends Controller
{

public function index()
{
    try {
        Paginator::useBootstrap();
        $testList = TestModel::orderBy('id', 'desc')->paginate(10); //order by & pagination
        return view('test.list', compact('testList'));
    } catch (\Exception $e) {
       // \Log::error('Admin list error: '.$e->getMessage());
         return view('errors.404');
    }
}

    public function adding() {
        return view('test.create');
    }

    public function create(Request $request)
    {
        //  echo '<pre>';
        //  dd($_POST);
        //  exit();

        //vali msg 
        $messages = [
            'name.required' => 'กรุณากรอกข้อมูล',
            'name.min' => 'กรอกข้อมูลขั้นต่ำ :min ตัวอักษร',
            'name.unique' => 'ชื่อซ้ำ เพิ่มใหม่อีกครั้ง',
            'lastname.required' => 'กรุณากรอกข้อมูล',
            'lastname.min' => 'กรอกข้อมูลขั้นต่ำ :min ตัวอักษร',
            'email.required' => 'กรุณากรอกข้อมูล',
            'email.email' => 'กรอกอีเมลให้ถูกต้อง',
        ];

        //rule 
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3|unique:tbl_test',
            'lastname' => 'required|min:3',
            'email' => 'required|email',
        ], $messages);

        //check vali 
        if ($validator->fails()) {
            return redirect('test/adding')
                ->withErrors($validator)
                ->withInput();
        }

        try {

            //ปลอดภัย: กัน XSS ที่มาจาก <script>, <img onerror=...> ได้
            // insert data เพิ่มข้อมูล
            TestModel::create([
                'name' => strip_tags($request->input('name')),
                'lastname' => strip_tags($request->input('lastname')),
                'email' => strip_tags($request->input('email')),
            ]);
            // แสดง Alert ก่อน return
            Alert::success('เพิ่มข้อมูลสำเร็จ');
            return redirect('/test');
        } catch (\Exception $e) {
            //return response()->json(['error' => $e->getMessage()], 500); //สำหรับ debug
            return view('errors.404');
        }
    } //fun create



 public function edit($id)
    {
        try {
            //query data for form edit 
            $test = TestModel::findOrFail($id); // ใช้ findOrFail เพื่อให้เจอหรือ 404
            if (isset($test)) {
                $id = $test->id;
                $name = $test->name;
                $lastname = $test->lastname;
                $email = $test->email;
                return view('test.edit', compact('id', 'name','lastname','email'));
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
            'name.required' => 'กรุณากรอกข้อมูล',
            'name.min' => 'กรอกข้อมูลขั้นต่ำ :min ตัวอักษร',
            'name.unique' => 'ชื่อนี้ถูกใช้งานแล้ว',  //ป้องกันแก้ซ้ำกับ row อื่นๆ จ้าาา
            'lastname.required' => 'กรุณากรอกข้อมูล',
            'lastname.min' => 'กรอกข้อมูลขั้นต่ำ :min ตัวอักษร',
            'email.required' => 'กรุณากรอกข้อมูล',
            'email.email' => 'กรอกอีเมลให้ถูกต้อง',
        ];

        //rule
        $validator = Validator::make($request->all(), [
            'name' => [
                    'required',
                    'min:3',
                        Rule::unique('tbl_test', 'name')->ignore($id, 'id'), //ห้ามแก้ซ้ำ
            ],
            'lastname' => 'required|min:3',
            'email' => 'required|email',
    ], $messages);

    //check 
        if ($validator->fails()) {
            return redirect('test/' . $id)
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $test = TestModel::find($id);
            $test->update([
                    'name' => strip_tags($request->input('name')), //column update 
                    'lastname' => strip_tags($request->input('lastname')),
                    'email' => strip_tags($request->input('email')),
                ]);
            // แสดง Alert ก่อน return
            Alert::success('แก้ไขข้อมูลสำเร็จ');
            return redirect('/test');
        } catch (\Exception $e) {
            //return response()->json(['error' => $e->getMessage()], 500); //สำหรับ debug
            return view('errors.404');
        }
    } //fun update 


    public function remove($id)
    {
        try {
            $test = TestModel::find($id);  //query หาว่ามีไอดีนี้อยู่จริงไหม 
            $test->delete();
            Alert::success('ลบข้อมูลสำเร็จ');
            return redirect('/test');
        } catch (\Exception $e) {
            //return response()->json(['error' => $e->getMessage()], 500); //สำหรับ debug
            return view('errors.404');
        }
    } //remove 


} //class
