<?php

namespace Zuweie\Setting\Http\Controllers;

use Encore\Admin\Layout\Content;
use Encore\Admin\Admin;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

use Zuweie\Setting\Settings;

class SettingController extends Controller
{
    public function index(Content $content)
    {
        // 引入bootstrap-table
        Admin::css('https://unpkg.com/bootstrap-table@1.15.4/dist/bootstrap-table.min.css');
        Admin::js('https://unpkg.com/bootstrap-table@1.15.4/dist/bootstrap-table.min.js');
        // 引入table-editable
       Admin::css('https://cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.1/bootstrap3-editable/css/bootstrap-editable.css');
       Admin::js('https://unpkg.com/bootstrap-table@1.15.4/dist/extensions/editable/bootstrap-table-editable.min.js');
       
       $tags = request('tags', '');
       
        return $content
            ->header('通用配置')
            ->description('组件')
            ->body(view('setting::index', ['tags'=>$tags]));
    }
    
    public function updateSetting() {
        $id = request('id', 0);
        
        if (!empty($id)) {
            
            $key = request('key');
            $alias = request('alias');
            $tags = request('tags');
            $value = request('value');
            
            $update_data = [];
            
            !empty($key) &&  $update_data['key'] = $key;
            !empty($alias) && $update_data['alias'] = $alias;
            !empty($tags) && $update_data['tags'] = $tags;
            !empty($value) && $update_data['value'] = $value;
            
            count($update_data) > 0 && $res = Settings::update_setting($id, $key, $alias, $tags, $value);
            return response()->json(['errcode'=>0, 'errmsg'=>'', 'data'=>$update_data]);
        }
        return response()->json(['errcode'=>-1, 'errmsg'=> 'no found', 'data'=>[]]);
    }
    
    public function settingdata () {
        
        $tags = request('tags', '');
        $page = request('page', 1);
        $perpage = request('perpage', 20);
        
        $query = DB::table('admin_ext_settings')->select('id', 'key', 'alias', 'tags', 'value');
        
        if (!empty($tags)) {
            $query = $query->where('tags', 'like', '%'.$tags.'%');
        }
        $settings = $query->offset(($page-1)*$perpage)->limit($perpage)->get();
        
        return response()->json($settings);
        
    }
    public function createSetting() {
        
       $tags = request('tags', 'tags');
       $key = request('key', microtime());
       $alias = request('alias', 'alias');
       $value = request('value', 'value');
       
        $id = Settings::create_setting($key, $alias, $tags, $value);
        
        $default_setting['id'] = $id;
        $default_setting['key'] = $key;
        $default_setting['alias'] = $alias;
        $default_setting['tags'] = $tags;
        $default_setting['value'] = $value;
        
        if ($id > 0) {
            return response()->json(['errcode'=>0, 'errmsg'=>'', 'data'=>$default_setting]);
        }else{
            return response()->json(['errcode'=>-1, 'errmsg'=>'fail', 'data'=>[]]);
        }
    }
    
    public function getTags() {
        $query = DB::table('admin_ext_settings')->select('tags')->groupBy('tags')->get();
        foreach($query as $q) {
            $tags[] = $q->tags;
        }
        return response()->json($tags);
    }
    
    public function deleteSettings () {
         $ids = request('ids');
         $res = Settings::delete_settings($ids);
         return response()->json(['errcode'=>0, 'errmsg'=>'', 'data'=>[]]);
    }
    
    public function debugCache() {
        $type = request('type', 'key');
        $p1 = request('p1');
        $p2 = request('p2');
        if ($type == 'key') {
            $value = Settings::get($p1, $p2, true);
        }else {
            $value = Settings::getMuilt($p1, true);
        }
        return response()->json(['data'=>$value]);
    }
    
}