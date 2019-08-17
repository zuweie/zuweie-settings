<?php 
namespace Zuweie\Setting;
use Illuminate\Support\Facades\DB;

class Settings {
    
    
    public static function get_value_by_key ($key,  $default='', $callback=null) {
        $setting = DB::table('admin_ext_settings')->where('key', $key)->first();
        if ($setting) {
            if ($callback){
                return $callback($setting);
            }
            return $setting->value;
        }
        return $default;
    }
    
    public static function get_values_by_keys($keys, $default=[], $callback=null) {
        
        if (!is_array($keys)) $keys = [$keys];
        
        $query = DB::table('admin_ext_settings');
        foreach ($keys as $k) {
            $query = $query->orWhere('key', $k);
        }
        $settings = $query->get();
        $values = [];
        if (is_array($settings) && count($settings) > 0) {
            foreach ($settings as $setting){
                if ($callback) {
                    array_push($values, $callback($setting));
                }
                array_push($values, $setting->value);
            }
        }
        return $default;
        
    }
    
    public static function get_values_by_tags($tags, $defalut=[], $callback=null) {
        
        $settings = DB::table('admin_ext_settings')->where('tags', 'like', '%'.tags.'%')->get();
        
        $values = [];
        if (is_array($settings) && count($settings) > 0) {
            foreach ($settings as $setting){
                if ($callback) {
                    array_push($values, $callback($setting));
                }
                array_push($values, $setting->value);
            }
        }
        return $default;
    }
    
    public static function split_value($setting, $split='') {

        $valuestring = $setting->value;
        empty($split) && $split = '/[\s,]+/';
        $values = preg_split($split, $valuestring, null, null);
        return $values;
        
    }
    
    
}
?>