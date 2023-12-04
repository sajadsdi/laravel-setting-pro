<?php

namespace Sajadsdi\LaravelSettingPro\Model;

use Jenssegers\Mongodb\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;
use Sajadsdi\LaravelSettingPro\Contracts\DatabaseModelInterface;

class MongoSetting extends Model implements DatabaseModelInterface
{
    use SoftDeletes;

    protected $table = "settings";
    protected $connection = 'mongodb';
    protected $fillable = ["setting", "data"];

    /**
     * Retrieves the value of a setting based on the provided key.
     *
     * @param string $key The key of the setting.
     * @return mixed|null The value of the setting, or null if not found.
     */
    public function getSetting(string $key): mixed
    {
        return self::where('setting', $key)->first()?->data;
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
        self::updateOrCreate(['setting' => $key], ['data' => $data]);
    }
}
