<?php

namespace App\Http\Controllers;

use App\Models\ReviewModel;
use App\Models\UserModel;
use App\Models\MerchandiseModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Pagination\Paginator;
use RealRashid\SweetAlert\Facades\Alert;

class ReviewController extends Controller
{
    /** GET /reviews */
    public function index(Request $request)
    {
        Paginator::useBootstrap();

        $q = ReviewModel::with(['user','merchandise']);

        // ค้นหา/กรอง
        if ($s = trim($request->get('s',''))) {
            $q->where('comment', 'like', "%{$s}%");
        }
        if ($uid = $request->get('user_id')) {
            $q->where('user_id', $uid);
        }
        if ($mid = $request->get('merchandise_id')) {
            $q->where('merchandise_id', $mid);
        }
        if ($minR = $request->get('min_rating')) {
            $q->where('rating', '>=', $minR);
        }

        $reviews = $q->orderBy('review_id','desc')->paginate(10)->withQueryString();

        // dropdown filter
        $users = UserModel::orderBy('name')->get();
        $merch = MerchandiseModel::orderBy('merchandise_name')->get();

        return view('reviews.list', compact('reviews','users','merch'));
    }

    /** GET /reviews/adding */
    public function adding()
    {
        $users = UserModel::orderBy('name')->get();
        $merch = MerchandiseModel::orderBy('merchandise_name')->get();
        return view('reviews.create', compact('users','merch'));
    }

    /** POST /reviews */
    public function create(Request $request)
    {
        $messages = [
            'user_id.required'         => 'กรุณาเลือกผู้ใช้',
            'user_id.exists'           => 'ผู้ใช้ไม่ถูกต้อง',
            'merchandise_id.required'  => 'กรุณาเลือกสินค้า',
            'merchandise_id.exists'    => 'สินค้าไม่ถูกต้อง',
            'rating.required'          => 'กรุณาให้คะแนน',
            'rating.integer'           => 'คะแนนต้องเป็นจำนวนเต็ม',
            'rating.between'           => 'คะแนนต้องอยู่ระหว่าง 0 ถึง 5',
            'comment.max'              => 'ความยาวความคิดเห็นต้องไม่เกิน :max ตัวอักษร',
        ];

        $validator = Validator::make($request->all(), [
            'user_id'        => 'required|exists:tbl_user,user_id',
            'merchandise_id' => 'required|exists:tbl_merchandise,merchandise_id',
            'rating'         => 'required|integer|between:0,5',   // ตารางของคุณเป็น int
            'comment'        => 'nullable|max:2000',
        ], $messages);

        if ($validator->fails()) {
            return redirect('/reviews/adding')->withErrors($validator)->withInput();
        }

        try {
            $review = ReviewModel::create([
                'user_id'        => $request->user_id,
                'merchandise_id' => $request->merchandise_id,
                'rating'         => $request->rating,
                'comment'        => $request->comment ? strip_tags($request->comment) : null,
                'created_at'     => now(),
            ]);

            // อัปเดตค่าเฉลี่ยเรตติ้งของสินค้า
            $this->recomputeRating($review->merchandise_id);

            Alert::success('บันทึกรีวิวเรียบร้อย');
            return redirect('/reviews');
        } catch (\Exception $e) {
            return view('errors.404');
        }
    }

    /** GET /reviews/{id} */
    public function edit($id)
    {
        try {
            $review = ReviewModel::with(['user','merchandise'])->findOrFail($id);
            $users  = UserModel::orderBy('name')->get();
            $merch  = MerchandiseModel::orderBy('merchandise_name')->get();
            return view('reviews.edit', compact('review','users','merch'));
        } catch (\Exception $e) {
            return view('errors.404');
        }
    }

    /** PUT /reviews/{id} */
    public function update($id, Request $request)
    {
        $messages = [
            'user_id.required'         => 'กรุณาเลือกผู้ใช้',
            'user_id.exists'           => 'ผู้ใช้ไม่ถูกต้อง',
            'merchandise_id.required'  => 'กรุณาเลือกสินค้า',
            'merchandise_id.exists'    => 'สินค้าไม่ถูกต้อง',
            'rating.required'          => 'กรุณาให้คะแนน',
            'rating.integer'           => 'คะแนนต้องเป็นจำนวนเต็ม',
            'rating.between'           => 'คะแนนต้องอยู่ระหว่าง 0 ถึง 5',
            'comment.max'              => 'ความยาวความคิดเห็นต้องไม่เกิน :max ตัวอักษร',
        ];

        $validator = Validator::make($request->all(), [
            'user_id'        => 'required|exists:tbl_user,user_id',
            'merchandise_id' => 'required|exists:tbl_merchandise,merchandise_id',
            'rating'         => 'required|integer|between:0,5',
            'comment'        => 'nullable|max:2000',
        ], $messages);

        if ($validator->fails()) {
            return redirect('/reviews/'.$id)->withErrors($validator)->withInput();
        }

        try {
            $review = ReviewModel::findOrFail($id);

            $review->user_id        = $request->user_id;
            $review->merchandise_id = $request->merchandise_id;
            $review->rating         = $request->rating;
            $review->comment        = $request->comment ? strip_tags($request->comment) : null;
            $review->save();

            // อัปเดตค่าเฉลี่ยเรตติ้ง
            $this->recomputeRating($review->merchandise_id);

            Alert::success('อัปเดตรีวิวเรียบร้อย');
            return redirect('/reviews');
        } catch (\Exception $e) {
            return view('errors.404');
        }
    }

    /** DELETE /reviews/remove/{id} */
    public function remove($id)
    {
        try {
            $review = ReviewModel::find($id);

            if (!$review) {
                Alert::error('ไม่พบรีวิว');
                return redirect('/reviews');
            }

            $mid = $review->merchandise_id;
            $review->delete();

            // อัปเดตค่าเฉลี่ยเรตติ้งหลังลบ
            $this->recomputeRating($mid);

            Alert::success('ลบรีวิวเรียบร้อย');
            return redirect('/reviews');
        } catch (\Exception $e) {
            Alert::error('เกิดข้อผิดพลาด: ' . $e->getMessage());
            return redirect('/reviews');
        }
    }

    /** คำนวณค่าเฉลี่ยเรตติ้งของสินค้าแล้วอัปเดตกลับไปที่ tbl_merchandise.rating_avg */
    private function recomputeRating($merchandise_id)
    {
        $avg = ReviewModel::where('merchandise_id', $merchandise_id)->avg('rating');
        $avg = $avg !== null ? round($avg, 2) : null;

        $product = MerchandiseModel::find($merchandise_id);
        if ($product) {
            $product->rating_avg = $avg;
            $product->save();
        }
    }
}
