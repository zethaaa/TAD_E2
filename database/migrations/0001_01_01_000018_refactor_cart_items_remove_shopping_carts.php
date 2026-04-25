<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('cart_items', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('id');
        });

        $cartToUserMap = DB::table('shopping_carts')->pluck('user_id', 'id');

        DB::table('cart_items')
            ->select('id', 'cart_id')
            ->orderBy('id')
            ->chunkById(200, function ($cartItems) use ($cartToUserMap) {
                foreach ($cartItems as $item) {
                    $userId = $cartToUserMap[$item->cart_id] ?? null;
                    if ($userId !== null) {
                        DB::table('cart_items')
                            ->where('id', $item->id)
                            ->update(['user_id' => $userId]);
                    }
                }
            });

        $duplicates = DB::table('cart_items')
            ->select('user_id', 'product_id', DB::raw('SUM(quantity) as total_quantity'))
            ->whereNotNull('user_id')
            ->groupBy('user_id', 'product_id')
            ->havingRaw('COUNT(*) > 1')
            ->get();

        foreach ($duplicates as $dup) {
            $firstId = DB::table('cart_items')
                ->where('user_id', $dup->user_id)
                ->where('product_id', $dup->product_id)
                ->orderBy('id')
                ->value('id');

            DB::table('cart_items')
                ->where('id', $firstId)
                ->update(['quantity' => $dup->total_quantity]);

            DB::table('cart_items')
                ->where('user_id', $dup->user_id)
                ->where('product_id', $dup->product_id)
                ->where('id', '!=', $firstId)
                ->delete();
        }

        if (DB::table('cart_items')->whereNull('user_id')->exists()) {
            throw new \RuntimeException('Cannot convert cart_items to user_id relation: some rows do not map to users.');
        }

        Schema::table('cart_items', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unique(['user_id', 'product_id']);
            $table->dropConstrainedForeignId('cart_id');
        });

        Schema::dropIfExists('shopping_carts');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('shopping_carts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });

        $now = now();

        $userIds = DB::table('cart_items')
            ->select('user_id')
            ->whereNotNull('user_id')
            ->distinct()
            ->pluck('user_id');

        foreach ($userIds as $userId) {
            DB::table('shopping_carts')->insert([
                'user_id' => $userId,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        $userToCartMap = DB::table('shopping_carts')->pluck('id', 'user_id');

        Schema::table('cart_items', function (Blueprint $table) {
            $table->foreignId('cart_id')->nullable()->after('id');
        });

        DB::table('cart_items')
            ->select('id', 'user_id')
            ->orderBy('id')
            ->chunkById(200, function ($cartItems) use ($userToCartMap) {
                foreach ($cartItems as $item) {
                    $cartId = $userToCartMap[$item->user_id] ?? null;
                    if ($cartId !== null) {
                        DB::table('cart_items')
                            ->where('id', $item->id)
                            ->update(['cart_id' => $cartId]);
                    }
                }
            });

        Schema::table('cart_items', function (Blueprint $table) {
            $table->dropUnique('cart_items_user_id_product_id_unique');
            $table->dropConstrainedForeignId('user_id');
            $table->foreign('cart_id')->references('id')->on('shopping_carts')->onDelete('cascade');
        });
    }
};
