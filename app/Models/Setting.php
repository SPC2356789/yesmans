<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use RalphJSmit\Laravel\SEO\Support\SEOData;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use RalphJSmit\Laravel\SEO\SchemaCollection;
use Illuminate\Support\Facades\Facade;
use Illuminate\Support\Facades\Storage;

class Setting extends Model
{
    use LogsActivity;
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
    ];

    protected $casts = [
        'value' => 'json',
        'tags' => 'json',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['key', 'value']);
        // Chain fluent methods for configuration options
    }

    public function Permission()
    {
        return $this->belongsTo(Permission::class);
    }

    public static function get(string $key = '*', mixed $default = null): mixed
    {
        $settings = cache()->rememberForever(config('settings.cache_key'), function () {
            $settings = [];

            \Outerweb\Settings\Models\Setting::all()->each(function ($setting) use (&$settings) {
                data_set($settings, $setting->key, $setting->value);
            });

            return $settings;
        });

        if ($key === '*') {
            return $settings;
        }

        return data_get($settings, $key, $default);
    }

    public static function set(string $key, mixed $value): mixed
    {
        $setting = self::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );

        cache()->forget(config('settings.cache_key'));

        return $setting->value;
    }

    public function getTable(): string
    {
        return config('settings.database_table_name', 'settings');
    }


    public function getBase($where, $cut = '_')
    {
        $results = Setting::where('key', 'like', $where . '_%')->pluck('value', 'key')->toArray();

        $processedResults = array_map(function ($key) use ($where, $cut) {
            return str_replace($where . $cut, '', $key);
        }, array_keys($results));

        $finalResults = array_combine($processedResults, $results);

        return $finalResults;
    }

    public function secure_url()
    {
        return env('app_url');
    }


    public function CheckProtocol($imagePath)
    {
        if (request()->isSecure()) {
            return $imagePath;
        } else {
            $imageUrl = url($imagePath);
            return str_replace('https://', 'http://', $imageUrl);
        }
    }


    public function SEOdata($where = 'index')
    {

//        dd(env('APP_DEV'));
        $Base = $this->getBase($where);
        $General = $this->getElseOrGeneral();

        $schemaCollection = new SchemaCollection();


        $array = json_decode($Base['seo.schema_markup'], true); // 第二个参数设为 true 以将其转换为关联数组
        $schemaCollection->add($array);

//        dd($array);
        return $SEOData = new SEOData(
            title: $Base['seo.title'] ?? null, // 如果不存在，設置為 null
            description: $Base['seo.description'] ?? null,
            image: !empty($Base['OG.image']) ? $this->CheckProtocol(Storage::url($Base['OG.image'])) : null,
            url: request()->fullUrl(),
            tags: !empty($Base['seo.tag']) ? $Base['seo.tag'] : null,
            schema: $schemaCollection,
            site_name: $General['brand_name'] ?? null,
            favicon: !empty($General['favicon']) ? $this->CheckProtocol(Storage::url($General['favicon'])) : null,
            robots: $Base['seo.robots'] ?? null,
            openGraphTitle: $Base['OG.title'] ?? null
        );

    }

    public function getElseOrGeneral($where = 'general')
    {

        $results = Setting::where('key', 'like', $where . '.%')->pluck('value', 'key')->toArray();
        $processedResults = array_map(function ($key) use ($where) {
            return str_replace($where . '.', '', $key);
        }, array_keys($results));

        $finalResults = array_combine($processedResults, $results);

        return $finalResults;
    }


}
