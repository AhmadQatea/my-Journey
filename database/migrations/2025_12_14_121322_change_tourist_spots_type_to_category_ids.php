<?php

use App\Models\Category;
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
        // إضافة عمود category_ids كـ JSON
        Schema::table('tourist_spots', function (Blueprint $table) {
            $table->json('category_ids')->nullable()->after('type');
        });

        // إنشاء الفئات إذا لم تكن موجودة
        $typeToCategoryMap = [
            'تاريخي' => 'تاريخي',
            'طبيعي' => 'طبيعي',
            'منتزه' => 'منتزه',
            'جبال ومرتفعات' => 'جبال ومرتفعات',
            'مغامرات ومخاطر' => 'مغامرات ومخاطر',
        ];

        foreach ($typeToCategoryMap as $typeName) {
            $category = Category::firstOrCreate(['name' => $typeName]);
        }

        // تحويل البيانات من type (أسماء) إلى category_ids (IDs)
        $touristSpots = DB::table('tourist_spots')->get();

        foreach ($touristSpots as $spot) {
            if ($spot->type) {
                $types = json_decode($spot->type, true);
                if (is_array($types)) {
                    $categoryIds = [];
                    foreach ($types as $typeName) {
                        $category = Category::where('name', $typeName)->first();
                        if ($category) {
                            $categoryIds[] = $category->id;
                        }
                    }
                    if (! empty($categoryIds)) {
                        DB::table('tourist_spots')
                            ->where('id', $spot->id)
                            ->update(['category_ids' => json_encode($categoryIds)]);
                    }
                }
            }
        }

        // حذف عمود type
        Schema::table('tourist_spots', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // إعادة إضافة عمود type
        Schema::table('tourist_spots', function (Blueprint $table) {
            $table->json('type')->nullable()->after('coordinates');
        });

        // تحويل البيانات من category_ids إلى type (أسماء)
        $touristSpots = DB::table('tourist_spots')->get();

        foreach ($touristSpots as $spot) {
            if ($spot->category_ids) {
                $categoryIds = json_decode($spot->category_ids, true);
                if (is_array($categoryIds)) {
                    $typeNames = [];
                    foreach ($categoryIds as $categoryId) {
                        $category = Category::find($categoryId);
                        if ($category) {
                            $typeNames[] = $category->name;
                        }
                    }
                    if (! empty($typeNames)) {
                        DB::table('tourist_spots')
                            ->where('id', $spot->id)
                            ->update(['type' => json_encode($typeNames)]);
                    }
                }
            }
        }

        // حذف category_ids
        Schema::table('tourist_spots', function (Blueprint $table) {
            $table->dropColumn('category_ids');
        });
    }
};
