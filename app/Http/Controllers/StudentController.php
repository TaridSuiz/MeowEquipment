<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request; //รับค่าจากฟอร์ม
use Illuminate\Support\Facades\Validator; //form validation
use RealRashid\SweetAlert\Facades\Alert; //sweet alert
use Illuminate\Support\Facades\Storage; //สำหรับเก็บไฟล์ภาพ
use Illuminate\Pagination\Paginator; //แบ่งหน้า
use App\Models\StudentModel; //model
use Illuminate\Validation\Rule;

class StudentController extends Controller
{

    public function index(){
        Paginator::useBootstrap(); // ใช้ Bootstrap pagination
        $students = StudentModel::orderBy('id', 'desc')->paginate(5); //order by & pagination
        //return response()->json(['error' => $e->getMessage()], 500); //สำหรับ debug
        return view('students.list', compact('students'));
    }

    public function adding() {
        return view('students.create');
    }


public function create(Request $request)
{

    // echo '<pre>';
    // dd($_POST);
    // exit();
    //msg
        // ส่งชื่อตัวแปรไปที่ view

    $messages = [
        'std_name.required' => 'กรุณากรอกชื่อนักศึกษา',
        'std_name.min' => 'ต้องมีอย่างน้อย :min ตัวอักษร',

        'std_phone.required' => 'กรุณากรอกเบอร์โทรนักศึกษา',
        'std_phone.min' => 'ต้องมีอย่างน้อย :min ตัวอักษร',
        'std_phone.max' => 'ห้ามเกิน :max ตัวอักษร',


        'std_code.required' => 'ห้ามว่าง',
        'std_code.min' => 'ต้องมีอย่างน้อย :min ตัวอักษร',
        'std_code.unique' => 'ข้อมูลซ้ำ',

        'std_img.mimes' => 'รองรับ jpeg, png, jpg เท่านั้น !!',
        'std_img.max' => 'ขนาดไฟล์ไม่เกิน 5MB !!',
    ];

    //rule ตั้งขึ้นว่าจะเช็คอะไรบ้าง
    $validator = Validator::make($request->all(), [
        'std_name' => 'required|min:3',
        'std_phone' => 'required|min:10|max:10',
        'std_code' => 'required|min:3|unique:tbl_student',
        'std_img' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
    ], $messages);
    

    //ถ้าผิดกฏให้อยู่หน้าเดิม และแสดง msg ออกมา
    if ($validator->fails()) {
        return redirect('student/adding')
            ->withErrors($validator)
            ->withInput();
    }


    //ถ้ามีการอัพโหลดไฟล์เข้ามา ให้อัพโหลดไปเก็บยังโฟลเดอร์ uploads/product
    try {
        $imagePath = null;
        if ($request->hasFile('std_img')) {
            $imagePath = $request->file('std_img')->store('uploads/students', 'public');
        }

        //insert เพิ่มข้อมูลลงตาราง
        StudentModel::create([
            'std_name' => strip_tags($request->std_name),
            'std_phone' => strip_tags($request->std_phone),
            'std_code' => $request->std_code,
            'std_img' => $imagePath,
        ]);

        //แสดง sweet alert
        Alert::success('Insert Successfully');
        return redirect('/student');

    } catch (\Exception $e) {  //error debug
        return response()->json(['error' => $e->getMessage()], 500); //สำหรับ debug
        //return view('errors.404');
    }
} //create 

public function edit($id)
    {
        try {
            $student = StudentModel::findOrFail($id); // ใช้ findOrFail เพื่อให้เจอหรือ 404

            //ประกาศตัวแปรเพื่อส่งไปที่ view
            if (isset($student)) {
                $id = $student->id;
                $std_name = $student->std_name;
                $std_phone = $student->std_phone;
                $std_code = $student->std_code;
                $std_img = $student->std_img;
                return view('students.edit', compact('id', 'std_name', 'std_phone', 'std_code', 'std_img'));
            }
        } catch (\Exception $e) {
            //return response()->json(['error' => $e->getMessage()], 500); //สำหรับ debug
            return view('errors.404');
        }
    } //func edit

public function update($id, Request $request)
{

    //error msg
     $messages = [
       

         'std_name.required' => 'กรุณากรอกชื่อนักศึกษา',
        'std_name.min' => 'ต้องมีอย่างน้อย :min ตัวอักษร',

        'std_phone.required' => 'กรุณากรอกเบอร์โทรนักศึกษา',
        'std_phone.min' => 'ต้องมีอย่างน้อย :min ตัวอักษร',
        'std_phone.max' => 'ห้ามเกิน :min ตัวอักษร',


        'std_code.required' => 'ห้ามว่าง',
        'std_code.min' => 'ต้องมีอย่างน้อย :min ตัวอักษร',

        'std_img.mimes' => 'รองรับ jpeg, png, jpg เท่านั้น !!',
        'std_img.max' => 'ขนาดไฟล์ไม่เกิน 5MB !!',
    ];


    // ตรวจสอบข้อมูลจากฟอร์มด้วย Validator
    $validator = Validator::make($request->all(), [
         'std_name' => 'required|min:3',
         'std_phone' => 'required|min:10|max:10',
         'std_code' => ['required',
                         'min:3',
                             Rule::unique('tbl_student','std_code')->ignore($id,'id'),
                         ],

        'std_img' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',

    ], $messages);

    // ถ้า validation ไม่ผ่าน ให้กลับไปหน้าฟอร์มพร้อมแสดง error และข้อมูลเดิม
    if ($validator->fails()) {
        return redirect('student/' . $id)
            ->withErrors($validator)
            ->withInput();
    }

     try {
        // ดึงข้อมูลสินค้าตามไอดี ถ้าไม่เจอจะ throw Exception
        $student = StudentModel::findOrFail($id);

        // ตรวจสอบว่ามีไฟล์รูปใหม่ถูกอัปโหลดมาหรือไม่
        if ($request->hasFile('std_img')) {
            // ถ้ามีรูปเดิมให้ลบไฟล์รูปเก่าออกจาก storage
            if ($student->std_img) {
                Storage::disk('public')->delete($student->std_img);
            }
            // บันทึกไฟล์รูปใหม่ลงโฟลเดอร์ 'uploads/product' ใน disk 'public'
            $imagePath = $request->file('std_img')->store('uploads/students', 'public');
            // อัปเดต path รูปภาพใหม่ใน model
            $student->std_img = $imagePath;
        }
        // อัปเดตชื่อสินค้า โดยใช้ strip_tags ป้องกันการแทรกโค้ด HTML/JS
        $student->std_name = strip_tags($request->std_name);
        // อัปเดตรายละเอียดสินค้า โดยใช้ strip_tags ป้องกันการแทรกโค้ด HTML/JS
        $student->std_phone = strip_tags($request->std_phone);
        // อัปเดตราคาสินค้า
        $student->std_code = $request->std_code;

        // บันทึกการเปลี่ยนแปลงในฐานข้อมูล
        $student->save();

        // แสดง SweetAlert แจ้งว่าบันทึกสำเร็จ
        Alert::success('Update Successfully');

        // เปลี่ยนเส้นทางกลับไปหน้ารายการสินค้า
        return redirect('/student');

    } catch (\Exception $e) {
       return response()->json(['error' => $e->getMessage()], 500); //สำหรับ debug
       //return view('errors.404');

         //return response()->json(['error' => $e->getMessage()], 500); //สำหรับ debug
        //return view('errors.404');
    }
} //update  



public function remove($id)
{
    try {
        $student = StudentModel::find($id); //คิวรี่เช็คว่ามีไอดีนี้อยู่ในตารางหรือไม่

        if (!$student) {   //ถ้าไม่มี
            Alert::error('Student not found.');
            return redirect('student');
        }

        //ถ้ามีภาพ ลบภาพในโฟลเดอร์ 
        if ($student->std_img && Storage::disk('public')->exists($student->std_img)) {
            Storage::disk('public')->delete($student->std_img);
        }

        // ลบข้อมูลจาก DB
        $student->delete();

        Alert::success('Delete Successfully');
        return redirect('student');

    } catch (\Exception $e) {
        Alert::error('เกิดข้อผิดพลาด: ' . $e->getMessage());
        return redirect('student');
    }
} //remove 



} //class
