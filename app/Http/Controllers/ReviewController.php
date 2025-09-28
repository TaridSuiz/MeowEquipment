<?php

namespace App\Http\Controllers;

use App\Models\ReviewModel;
use App\Models\MerchandiseModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ReviewController extends Controller
{
    // user เพิ่มรีวิวของตัวเอง
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'merchandise_id' => 'required|integer|exists:tbl_merchandise,merchandise_id',
            'rating'         => 'required|integer|between:1,5',
            'comment'        => 'nullable|max:2000',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        ReviewModel::create([
            'merchandise_id' => $request->merchandise_id,
            'user_id'        => Auth::id(),
            'rating'         => $request->rating,
            'comment'        => $request->comment ?: null,
            'created_at'     => now(),
        ]);

        // อัปเดตค่าเฉลี่ย
        $avg = ReviewModel::where('merchandise_id', $request->merchandise_id)->avg('rating');
        MerchandiseModel::where('merchandise_id', $request->merchandise_id)->update(['rating_avg' => round($avg, 2)]);

        return back()->with('success', 'รีวิวเรียบร้อยแล้ว');
    }

    // user ลบเฉพาะรีวิวของตนเอง (authorize ผ่าน Gate)
    public function destroy($id)
    {
        $review = ReviewModel::findOrFail($id);
        $this->authorize('delete-review', $review);
        $review->delete();

        // อัปเดตค่าเฉลี่ย
        $avg = ReviewModel::where('merchandise_id', $review->merchandise_id)->avg('rating');
        MerchandiseModel::where('merchandise_id', $review->merchandise_id)->update(['rating_avg' => round($avg, 2)]);

        return back()->with('success', 'ลบรีวิวแล้ว');
    }

    // แอดมินลบรีวิว
    public function adminDestroy($id)
    {
        $review = ReviewModel::findOrFail($id);
        $review->delete();

        $avg = ReviewModel::where('merchandise_id', $review->merchandise_id)->avg('rating');
        MerchandiseModel::where('merchandise_id', $review->merchandise_id)->update(['rating_avg' => round($avg, 2)]);

        return back()->with('success','ลบรีวิว (admin) แล้ว');
    }
}
