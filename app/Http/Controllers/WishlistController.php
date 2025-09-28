<?php

namespace App\Http\Controllers;

use App\Models\WishlistModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function index()
    {
        $items = WishlistModel::with('merchandise')
            ->where('user_id', Auth::id())
            ->orderBy('created_at','desc')
            ->paginate(12);

        return view('wishlist.index', compact('items'));
    }

    public function toggle(Request $request)
    {
        $request->validate([
            'merchandise_id' => 'required|integer|exists:tbl_merchandise,merchandise_id',
        ]);

        $exists = WishlistModel::where('user_id', Auth::id())
            ->where('merchandise_id', $request->merchandise_id)
            ->first();

        if ($exists) {
            $exists->delete();
            return back()->with('success','นำออกจาก Wishlist แล้ว');
        }

        WishlistModel::create([
            'user_id'        => Auth::id(),
            'merchandise_id' => $request->merchandise_id,
            'created_at'     => now(),
        ]);

        return back()->with('success','บันทึกลง Wishlist แล้ว');
    }
}
