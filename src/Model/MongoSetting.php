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
    protected $fillable   = ["setting", "data"];

    /**
     * @param string $key
     * @return mixed
     */
    public function getSetting(string $key): mixed
    {
        return self::where('setting', $key)->first()?->data;
    }

    /**
     * @param string $key
     * @param mixed $data
     * @return void
     */
    public function setSetting(string $key, mixed $data): void
    {
        self::updateOrCreate(['setting' => $key], ['data' => $data]);
    }
}
