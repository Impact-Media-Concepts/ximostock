<?php
namespace App\Helpers;

use App\Models\Category;
use Illuminate\Support\Facades\DB;
class CategoryHelper
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public static function getAllCategoryIdsWithChildren(array $categoryIds)
    {
        $allCategoryIds = $categoryIds;

        foreach ($categoryIds as $categoryId) {
            $childCategoryIds = self::getChildCategoryIds($categoryId);
            $allCategoryIds = array_merge($allCategoryIds, $childCategoryIds);
        }

        return array_unique($allCategoryIds);
    }

    private static function getChildCategoryIds($categoryId)
    {
        $category = Category::find($categoryId);
        $childCategoryIds = [];

        if ($category) {
            foreach ($category->children as $child) {
                $childCategoryIds[] = $child->id;
                $childCategoryIds = array_merge($childCategoryIds, self::getChildCategoryIds($child->id));
            }
        }

        return $childCategoryIds;
    }

    public static function getExternalIdsForCategories(array $categoryIds, $salesChannelId)
    {
        return DB::table('category_sales_channel')
            ->whereIn('category_id', $categoryIds)
            ->where('sales_channel_id', $salesChannelId)
            ->pluck('external_id')
            ->toArray();
    }

    public static function getExternalIdsGroupedBySalesChannel(array $categoryIds)
    {
        $result = DB::table('category_sales_channel')
            ->whereIn('category_id', $categoryIds)
            ->select('sales_channel_id', 'external_id')
            ->get()
            ->groupBy('sales_channel_id')
            ->map(function ($group) {
                return $group->pluck('external_id')->toArray();
            });

        return $result->toArray();
    }
}
