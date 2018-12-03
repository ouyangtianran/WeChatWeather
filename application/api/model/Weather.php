<?php 
namespace app\api\model;

use think\Model;
use think\Db;

class Weather extends Model
{
    public function getWeather($citycode = 101010100)
    {
        $res = Db::name('weather')->where('code', $citycode)->select();
        return $res;
    }

    public function getWeatherList()
    {
        $res = Db::name('weather')->select();
        return $res;
    }
}