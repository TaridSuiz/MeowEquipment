<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\UserModel;          // นับ admin จากตาราง user
use App\Models\CounterModel;
use App\Models\MerchandiseModel;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct()
    {
        // ต้องล็อกอิน + ต้องเป็น admin
        $this->middleware(['auth', 'admin']);
    }

    public function index()
    {
        try {
            // ราคาสินค้ารวม
            $sumPrice = MerchandiseModel::sum('price');

            // จำนวนสินค้า
            $countProduct = MerchandiseModel::count();

            // จำนวนแอดมิน (จาก role)
            $countAdmin = UserModel::where('role', 'admin')->count();

            // จำนวนการนับวิวทั้งหมด
            $countView = CounterModel::count();

            // จำนวนการเข้าชมรายเดือน (24 เดือนล่าสุด)
            $monthlyVisits = DB::table('tbl_counter')
                ->selectRaw('DATE_FORMAT(c_date, "%M-%Y") as ym, COUNT(*) as total')
                ->groupBy('ym')
                ->orderByRaw('DATE_FORMAT(c_date, "%Y-%m") DESC')
                ->limit(24)
                ->get();

            // สำหรับ Chart.js
            $label = $monthlyVisits->pluck('ym')->toArray();
            $data  = $monthlyVisits->pluck('total')->toArray();

            return view('admin.dashboard', compact(
                'sumPrice', 'countProduct', 'countView', 'countAdmin', 'label', 'data'
            ));

        } catch (\Exception $e) {
            // สำหรับ debug ช่วงพัฒนา
            return response()->json(['error' => $e->getMessage()], 500);
            // production: return view('errors.404');
        }
    }
}
