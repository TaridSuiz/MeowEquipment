<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request; //รับค่าจากฟอร์ม
use Illuminate\Support\Facades\Validator; //form validation
use RealRashid\SweetAlert\Facades\Alert; //sweet alert
use Illuminate\Support\Facades\Storage; //สำหรับเก็บไฟล์ภาพ
use Illuminate\Pagination\Paginator; //แบ่งหน้า
use App\Models\MerchandiseModel; //model
use App\Models\ReviewModel;
use Illuminate\Support\Facades\DB;



class HomeController extends Controller
{

    public function index(){
        Paginator::useBootstrap(); // ใช้ Bootstrap pagination
        $merchandise = MerchandiseModel::orderBy('merchandise_id', 'desc')->paginate(8); //order by & pagination
         //return response()->json(['error' => $e->getMessage()], 500); //สำหรับ debug
         DB::table('tbl_counter')->insert([
            ['c_date' => now()]
        ]);
        return view('home.index', compact('merchandise'));
    }


public function detail($id)
    {
            $product = MerchandiseModel::findOrFail($id); // ใช้ findOrFail เพื่อให้เจอหรือ 404

            //ประกาศตัวแปรเพื่อส่งไปที่ view
            if (isset($product)) {
                $merchandise_id = $product->merchandise_id;
                $category_id = $product->category_id;
                $merchandise_name = $product->merchandise_name;
                $merchandise_image = $product->merchandise_image;
                $description = $product->description;
                $price = $product->price;
                $brand = $product->brand;
                $age_range = $product->age_range;
                $rating_avg = $product->rating_avg;
                $link_store = $product->link_store;
                $created_at = $product->created_at;

                return view('home.home_detail', compact('merchandise_id', 'category_id', 'merchandise_name', 'merchandise_image', 
                            'description','price','brand', 'age_range', 'rating_avg', 'link_store', 'created_at'));
            }
            else{
            //return response()->json(['error' => $e->getMessage()], 500); //สำหรับ debug
            return redirect('/');
        }
        }
    //func detail

    //show product with search
public function searchProduct(Request $request)
{
    // print_r($_GET);
    // exit;
    Paginator::useBootstrap(); // ใชั Bootstrap pagination
    $keyword = $request->keyword;
    if(strlen($keyword) > 0){
        //query data by searching
        $merchandise = MerchandiseModel::where('merchandise_name', 'like', "%{$keyword}%")->paginate(8);
    }else{
        $merchandise = MerchandiseModel::orderBy('merchandise_id', 'desc')->paginate(8); // 8 produc/page
    }
    $homeReviews = ReviewModel::with(['user','merchandise'])
        ->orderByDesc('created_at')
        ->limit(6)
        ->get();
    return view('home.index', compact('merchandise', 'keyword', 'homeReviews'));
}
}
//searchProduct