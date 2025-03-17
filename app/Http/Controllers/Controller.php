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
    public function schema($items, $type = '', $Slug = ''): SchemaCollection
    {
        $schemaCollection = new SchemaCollection();
        // 初始化数据结构
        $data = [
            "@context" => "https://schema.org",
            "@type" => "ItemList",
            "itemListElement" => []
        ];
        foreach ($items as $index => $item) {
            $data['itemListElement'][] = [
                "@type" => $type,
                "position" => $index + 1,  // position 从 1 开始
                "url" => url($Slug . "/" . $item->category_slug . "/item/" . $item->slug),
                "name" => $item['seo_title'],  // 假设 $item 中有 title 字段
                "description" => $item['seo_description'],  // 假设 $item 中有 subtitle 字段
                "datePublished" => $this->formatDate($item['published_at']),  // 使用格式化日期的方法
                "image" => $this->Settings->CheckProtocol(Storage::url($item['featured_image'])),  // 假设 $item 中有 image_url 字段
                "@id" => url($Slug . "/" . $item->category_slug . "/item/" . $item->slug),
            ];
            // 将构建的 Schema 添加到 SchemaCollection 中
        }
        $schemaCollection->add($data);
        return $schemaCollection;  // 这里我们将 JSON 字符串转为数组再返
    }

    /**
     * ISO 8601 格式
     */
    private function formatDate($date)

    {

        if ($date instanceof \Carbon\Carbon) {
            return $date->toIso8601String();
        }
        try {
            return \Carbon\Carbon::parse($date)->toIso8601String();
        } catch (\Exception $e) {
            // 如果无法解析，返回空或默认日期
            return null;
        }
    }


}
