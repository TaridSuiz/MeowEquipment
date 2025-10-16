<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tbl_wishlist', function (Blueprint $table) {
            // ลองลบ unique เดิมของ merchandise_id (ชื่ออาจต่างกัน จัดการหลายแบบ)
            try { $table->dropUnique('tbl_wishlist_merchandise_id_unique'); } catch (\Throwable $e) {}
            try { $table->dropUnique(['merchandise_id']); } catch (\Throwable $e) {}
            try { DB::statement('ALTER TABLE tbl_wishlist DROP INDEX merchandise_id'); } catch (\Throwable $e) {}
            try { DB::statement('ALTER TABLE tbl_wishlist DROP INDEX tbl_wishlist_merchandise_id_unique'); } catch (\Throwable $e) {}

            // เพิ่ม unique เป็นคู่ user_id + merchandise_id
            try { $table->unique(['user_id','merchandise_id'], 'wishlist_user_merch_unique'); } catch (\Throwable $e) {}
            // เพิ่ม index ปกติให้ merchandise_id
            try { $table->index('merchandise_id', 'wishlist_merch_idx'); } catch (\Throwable $e) {}
        });
    }

    public function down(): void
    {
        Schema::table('tbl_wishlist', function (Blueprint $table) {
            try { $table->dropIndex('wishlist_merch_idx'); } catch (\Throwable $e) {}
            try { $table->dropUnique('wishlist_user_merch_unique'); } catch (\Throwable $e) {}

            // ใส่กลับ unique เดี่ยว (หากต้องย้อน)
            try { $table->unique('merchandise_id', 'tbl_wishlist_merchandise_id_unique'); } catch (\Throwable $e) {}
        });
    }
};
