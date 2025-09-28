<?php

namespace App\Http\Controllers;

use App\Models\WishlistModel;
use App\Models\UserModel;
use App\Models\MerchandiseModel;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use RealRashid\SweetAlert\Facades\Alert;

class WishlistController extends Controller
{
    /** GET /wishlist */
    public function index(Request $request)
    {
        Paginator::useBootstrap();

        $q = WishlistModel::with(['user','merchandise']);

        if ($uid = $request->get('user_id')) {
            $q->where('user_id', $uid);
        }
        if ($mid = $request->get('merchandise_id')) {
            $q->where('merchandise_id', $mid);
        }

        $items = $q->orderBy('wishlist_id','desc')->paginate(10)->withQueryString();

        $users = UserModel::orderBy('name')->get();
        $merch = MerchandiseModel::orderBy('merchandise_name')->get();

        return view('wishlist.list', compact('items','users','merch'));
    }

    /** GET /wishlist/adding */
    public function adding()
    {
        $users = UserModel::orderBy('name')->get();
        $merch = MerchandiseModel::orderBy('merchandise_name')->get();
        return view('wishlist.create', compact('users','merch'));
    }

    /** POST /wishlist */
    public function create(Request $request)
    {
        $messages = [
            'user_id.required'        => 'กรุณาเลือกผู้ใช้',
            'user_id.exists'          => 'ผู้ใช้ไม่ถูกต้อง',
            'merchandise_id.required' => 'กรุณาเลือกสินค้า',
            'merchandise_id.exists'   => 'สินค้าไม่ถูกต้อง',
        ];

        // กันข้อมูลซ้ำ (user เดียวกัน + สินค้าชิ้นเดิม)
        $validator = Validator::make($request->all(), [
            'user_id'        => ['required','exists:tbl_user,user_id'],
            'merchandise_id' => [
                'required',
                'exists:tbl_merchandise,merchandise_id',
                Rule::unique('tbl_wishlist')->where(fn($q) =>
                    $q->where('user_id', $request->user_id)
                      ->where('merchandise_id', $request->merchandise_id)
                ),
            ],
        ], $messages + [
            'merchandise_id.unique' => 'สินค้านี้อยู่ใน Wishlist ของผู้ใช้นี้อยู่แล้ว',
        ]);

        if ($validator->fails()) {
            return redirect('/wishlist/adding')->withErrors($validator)->withInput();
        }

        WishlistModel::create([
            'user_id'        => $request->user_id,
            'merchandise_id' => $request->merchandise_id,
            'created_at'     => now(),
        ]);

        Alert::success('เพิ่มรายการ Wishlist สำเร็จ');
        return redirect('/wishlist');
    }

    /** DELETE /wishlist/remove/{id} */
    public function remove($id)
    {
        $item = WishlistModel::find($id);

        if (!$item) {
            Alert::error('ไม่พบรายการ');
            return redirect('/wishlist');
        }

        $item->delete();
        Alert::success('ลบรายการสำเร็จ');
        return redirect('/wishlist');
    }
}
