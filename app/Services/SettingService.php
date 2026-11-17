<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\Models\AppSettingEloquentStorage;
use Illuminate\Contracts\Cache\Factory as CacheContract;
use App\IServices\ISettingService;

class SettingService implements ISettingService
{
    protected $storage = null;
    protected $cache = null;

    public function __construct(AppSettingEloquentStorage $storage, CacheContract $cache)
    {
        $this->storage = $storage;
        $this->cache = $cache;
    }

    public function all()
    {
        return $this->storage->all();
    }

    public function getAllExtraWithPagination(Request $request)
    {
        $itemSize = env('APP_ITEM_PER_PAGE', 15);
        $sortOrder = $request->get('SortOrder');
        $sortColumn = $request->get('SortColumn');
        if($sortOrder != null || $sortColumn != null)
        {
            if($sortOrder == null)
            {
                $sortOrder = 'desc';
            }
            if($sortColumn == null)
            {
                $sortColumn = 'extra_sort_no';
            }
            $extras = $this->storage->where('is_extra',true)->where('branch_id', session('current_branch_id'))->orderBy($sortColumn, $sortOrder)->paginate($itemSize);
        }
        else
        {
            $extras = $this->storage->where('is_extra',true)->where('branch_id', session('current_branch_id'))->paginate($itemSize);
        } 
        return $extras;
    }

    public function getAllExtra()
    { 
        return $this->storage->where('is_extra',true)->where('branch_id', session('current_branch_id'))->get();
    }
    
    public function getByStorageId($id)
    {
        return $this->storage->where( 'id' ,$id)->where('branch_id', session('current_branch_id'))->firstOrFail();
    }

    public function create(Request $request,$isExtra = false)
    {
        $extra = new AppSettingEloquentStorage();
        $sortNo = $this->storage->max('extra_sort_no') + 1;
        $extra->extra_sort_no = $sortNo ;
        $extra->value = $request->value;
        $extra->extra_key = $request->extra_key;
        $extra->is_extra = $isExtra;
        $extra->key = env('APP_EXTRA_SETTING_PREFIX','extra_').$extra->extra_sort_no;
        $extra->branch_id = session('current_branch_id');
        $extra->save();
        return $extra;
    }

    /**
     * Return setting value or default value by key.
     *
     * @param  string  $key
     * @param  string  $value
     * @return string|null
     */
    public function get($key, $default_value = null)
    {
        $setting = $this->storage->where('key', $key )->where('branch_id' , session('current_branch_id'))->first();
        if($setting != null){
            return $setting->value;
        }
        return $default_value ;
    }

    /**
     * Set the setting by key and value.
     *
     * @param  string  $key
     * @param  mixed   $value
     * @return void
     */

    public function set($key, $value)
    {
        $setting = $this->storage->where('key', $key )->where('branch_id' , session('current_branch_id'))->first();
        $setting->value = $value;
        $setting->save();
        return;
    }


    public function delete($id)
    {
        $setting = $this->storage->find($id);
        $setting->delete();
    }

    public function getSettingByKey($key)
    {
        $setting = $this->storage->where('key', $key )->where('branch_id' , session('current_branch_id'))->first();
        return $setting;
    }
}
