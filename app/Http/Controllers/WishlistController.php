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
            'merchandise_id' => 'required|integer',
        ]);

        $userId  = Auth::id();
        $merchId = (int) $request->merchandise_id;

        $existing = WishlistModel::where('user_id', $userId)
            ->where('merchandise_id', $merchId)
            ->first();

        if ($existing) {
            $existing->delete();
            return back()->with('success','นำออกจาก Wishlist แล้ว');
        }

        // ป้องกัน race condition/ซ้ำซ้อน
        WishlistModel::firstOrCreate(
            ['user_id' => $userId, 'merchandise_id' => $merchId],
            ['created_at' => now()]
        );

        return back()->with('success','บันทึกลง Wishlist แล้ว');
    }

}