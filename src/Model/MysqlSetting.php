<?php

namespace Sajadsdi\LaravelSettingPro\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Sajadsdi\LaravelSettingPro\Contracts\DatabaseModelInterface;

class MysqlSetting extends Model implements DatabaseModelInterface
{
    use SoftDeletes;

    protected $table    = "settings";
    protected $fillable = ["setting", "data"];

    /**
     * @param string $key
     * @return mixed
     */
    public function getSetting(string $key): mixed
    {
        $setting = self::where('setting', $key)->first();
        return $setting ? json_decode($setting->data, true) : null;
    }

    /**
     * @param string $key
     * @param mixed $data
     * @return void
     */
    public function setSetting(string $key, mixed $data): void
    {
        self::updateOrCreate(['setting' => $key], ['data' => json_encode($data, JSON_NUMERIC_CHECK | JSON_PRESERVE_ZERO_FRACTION | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)]);
    }
}
