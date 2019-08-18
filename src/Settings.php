<?php 
namespace Zuweie\Setting;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
class Settings {
    
    // 加上cache，加快速度。
    protected static function _set_key_cache($key, $setting) {
        Cache::forever($key, $setting);
    }
    protected static function _del_key_cache($key) {
        Cache::forget($key);
    }
    protected static function _set_tags_cache($tags, $keys) {
        Cache::forever($tags, $keys);
    }
    protected static function _del_tags_cache($tags) {
        Cache::forget($tags);
    }
    
    protected  static function _get_setting_by_key($key) {
        $setting = Cache::get($key);
        if (empty($setting)) { 
            $setting = DB::table('admin_ext_settings')->where('key', $key)->first();
            if ($setting) self::_set_key_cache($key, $setting);
        }
        return $setting;
    }
    
    protected static function _get_settings_by_tags($tags) {
        
        $keys = Cache::get($tags);
        
        $settings = [];
        
        if (empty($keys)) {
            // 如果没有key，则把key捞一遍。
            $keys = [];
            $setting_keys = DB::table('admin_ext_settings')->select('key')->where('tags', $tags)->get();
            
            foreach($setting_keys as $setting_key) {
                array_push($keys, $setting_key->key);
            }
            self::_set_tags_cache($tags, $keys);
        }
        
        // 捞完了，获取setting。
        foreach ($keys as $key) {
            array_push($settings, self::_get_setting_by_key($key));
        }
        
        return $settings;
    }

    
    public static function get_value_by_key ($key,  $default='', $callback=null, $keepKey=false) {
        
        // TODO : get it from cache.
        $setting = self::_get_setting_by_key($key);
        
        $value = $default;
        if ($setting) {
            if ($callback){
               $value = $callback($setting);
            }
            $value = $setting->value;
        }
        if ($keepKey)
            return [$key=>$value];
        
        return $value;
    }
    
    public static function get_values_by_keys($keys, $callback=null, $keepKey=false) {
        $default = [];
        foreach ($keys as $key) {
            
            $setting = self::_get_setting_by_key(key);
            if ($setting) {
                if ($callback) {
                    $value  = $callback($setting);
                }else{
                    $value = $setting->value;
                }
                
                if ($keepKey) {
                    $default[$key] = $value;
                }else{
                    $default[] = $value;
                }
            }
        }
        return $default;
    }
    
    public static function get_values_by_tags($tags,  $callback=null, $keepKey=false) {
        
        $defalut=[];
        $settings = self::_get_settings_by_tags($tags);
        foreach ($settings as $setting) {
            if ($callback) {
                $value = $callback($setting);
            }else{
                $value = $setting->value;
            }
            
            if ($keepKey) {
                $default[$key] = $value;
            }else{
                $default[] = $value;
            }
        }
        return $default;
    }
    
    // 写的时候毁灭cache
    public static function create_setting($key, $alias, $tags, $value) {
        
        $setting['key'] = $key;
        $setting['alias'] = $alias;
        $setting['tags'] = $tags;
        $setting['value'] = $value;
        
        $res = DB::table('admin_ext_settings')->insertGetId($setting);
        
        if ($res && !empty($tags)) {
            self::_del_tags_cache($tags);
        }
        return $res;
    }
    
    // 写的时候毁灭cache
    public static function update_setting($id, $key, $alias, $tags, $value) {
        
        $update_data = [];
        
        !empty($key) &&  $update_data['key'] = $key;
        !empty($alias) && $update_data['alias'] = $alias;
        !empty($tags) && $update_data['tags'] = $tags;
        !empty($value) && $update_data['value'] = $value;
        
        $old_setting = DB::table('admin_ext_settings')->select('key', 'tags')->where('id', $id)->first();
        
        $res =  DB::table('admin_ext_settings')->where('id', $id)->update($update_data); 
        
        if ($res) {
            if (!empty($key) || !empty($tags)) {
                // 把旧的毁灭掉
                self::_del_key_cache($old_setting->key);
                self::_del_tags_cache($old_setting->tags);
                // 把新的毁灭掉
                self::_del_tags_cache($tags);
            }else if (!empty($alias) || !empty($value)) {
                self::_del_key_cache($old_setting->key);
            } 
        }
        
        return $res;
    }
    
    // 写的时候毁灭cache
    public static function delete_settings($ids) {
        $old_settings = DB::table('admin_ext_settings')->select('key', 'tags')->whereIn('id', $ids)->get();
        $res = DB::table('admin_ext_settings')->whereIn('id', $ids)->delete();
        if ($res) {
            foreach ($old_settings as $setting) {
                self::_del_key_cache($setting->key);
                self::_del_tags_cache($setting->tags);
            }
        }
        return $res;
    }
    
    
    
    
    public static function split_value($setting, $split='') {
        $valuestring = $setting->value;
        empty($split) && $split = '/[\s,]+/';
        $values = preg_split($split, $valuestring, null, null);
        return $values;
    }
    
    public static function to_map ($setting) {
        return [];
    }
}
?>