<?php

namespace App\Http\Controllers;

use App\Models\Media;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Models\Setting;
use RalphJSmit\Laravel\SEO\SchemaCollection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;


    protected array $Media;
    protected Setting $Settings;


    public function __construct()
    {
        $this->Media = Media::getData();//取照片

        $this->Settings = new Setting();

    }


    public function verifyCaptcha(Request $request)
    {

        // 驗證驗證碼
        $rules = ['captcha' => 'required|captcha_api:' . request('key') . ',math'];
        $validator = validator()->make(request()->all(), $rules);


        if ($validator->fails()) {

            return response()->json(['error' => 'NO PASS'], 500);
        }

    }

    /**
     * schema統一整合
     */
    public function schema($items, $type = '', $slug = '', $General = ''): SchemaCollection
    {
        $schemaCollection = new SchemaCollection();

        // 初始化數據結構
        $data = [
            "@context" => "https://schema.org",
            "@type" => "ItemList",
            "url" => url($slug), // 總攬頁面的 URL
            "numberOfItems" => $items->count(),
            "itemListElement" => []
        ];

        // 根據 $type 決定具體項目的 Schema 類型
        $itemType = match (strtolower($type)) {
            'blog' => 'BlogPosting',
            'product' => 'Product',
            default => 'Thing', // 預設通用類型，根據需求可調整
        };

        foreach ($items as $index => $item) {

            $listItem = [
                "@type" => "ListItem",
                "position" => $index + 1,
                "item" => [
                    "@type" => $itemType,
                    "@id" => url($slug . "/" . ($item->categories->slug ?? '') . "/item/" . $item->slug),
                    "url" => url($slug . "/" . ($item->categories->slug ?? '') . "/item/" . $item->slug),
                    "name" => $item->seo_title ?? $item->title ?? $item->name, // 共用名稱字段
                    "image" => $this->Settings->CheckProtocol(Storage::url($item->carouselOne ?? '')),
                ]
            ];

            // 根據類型添加特定屬性
            if ($itemType === 'BlogPosting') {
                $listItem["item"]["description"] = $item->seo_description ?? $item->subtitle ?? $item->description ?? '';
                $listItem["item"]["datePublished"] = $this->formatDate($item->published_at ?? $item->created_at);


                $listItem["item"]["publisher"] = [
                    "@type" => "Organization",
                    "name" => $General['brand_name'] . '小編', // 替換為實際名稱
                    "logo" => [
                        "@type" => "ImageObject",
                        "url" => !empty($General['favicon']) ? $this->Settings->CheckProtocol(Storage::url($General['favicon'])) : null,
                    ]
                ];
                if (!empty($item->updated_at)) {
                    $listItem["item"]["dateModified"] = $this->formatDate(max(strtotime($item->published_at), strtotime($item->updated_at)));
                }
            } elseif ($itemType === 'Product') {

                $listItem["item"]["description"] = $item->seo_description ?? '';
                if (!empty($item->amount)) {
                    $offers = [];

                    // 處理 trip_times 作為變體
                    if (!empty($item->trip_times)) {
                        foreach ($item->trip_times as $trip) {

                            $offers[] = [
                                '@type' => 'Offer',
                                'price' => (string)($trip->amount ?? $item->amount), // 使用 trip 價格或預設
                                'priceCurrency' => $item->currency ?? 'TWD',
                                'availability' => ($trip['quota'] ?? 0) > 0 ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock',
                                'name' => "{$item->title} from {$trip->date_range} ",
                                'validFrom' => $trip->date_start, // "2025-04-01"
                                'validThrough' => $trip->date_end ?? $trip->date_start, // "2025-04-02"
                                'sku' => $trip->uuid,
                            ];

                        }
                    }

                    $listItem['item']['offers'] = $offers;
                }
            }

            $data['itemListElement'][] = $listItem;
        }

        // 添加到 SchemaCollection
        $schemaCollection->add($data);

        return $schemaCollection;
    }

    /**
     * ISO 8601 格式
     */
    public function formatDate($date)
    {
        try {
            // 如果是 Carbon 實例，直接使用；否則解析輸入
            $carbonDate = $date instanceof \Carbon\Carbon ? $date : \Carbon\Carbon::parse($date);

            // 從 env 獲取時區，默認為 'Asia/Taipei'
            $timezone = env('APP_TIMEZONE', 'Asia/Taipei');

            // 設置時區
            $carbonDate->setTimezone($timezone);

            // 返回 ISO 8601 格式
            return $carbonDate->toIso8601String();
        } catch (\Exception $e) {
            // 如果無法解析，返回 null 或默認值
            return null;
        }
    }


}
