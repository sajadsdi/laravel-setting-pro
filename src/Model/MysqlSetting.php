<?php

namespace Sajadsdi\LaravelSettingPro\Model;

use Illuminate\Database\Eloquent\Model;
use Sajadsdi\LaravelSettingPro\Contracts\DatabaseModelInterface;

class MysqlSetting extends Model implements DatabaseModelInterface
{
    protected $table = "settings";
    protected $fillable = ["setting", "data"];

    /**
     * Retrieves the value of a setting based on the provided key.
     *
     * @param string $key The key of the setting.
     * @return mixed|null The value of the setting, or null if not found.
     */
    public function getSetting(string $key): mixed
    {
        $setting = self::where('setting', $key)->first();
        return $setting ? json_decode($setting->data, true) : null;
    }

    /**
     * Sets the value of a setting based on the provided key.
     *
     * @param string $key The key of the setting.
     * @param mixed $data The value to be set for the setting.
     * @return void
     */
    public function setSetting(string $key, mixed $data): void
    {
        self::updateOrCreate(['setting' => $key], ['data' => json_encode($data, JSON_NUMERIC_CHECK | JSON_PRESERVE_ZERO_FRACTION | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)]);
    }
}
