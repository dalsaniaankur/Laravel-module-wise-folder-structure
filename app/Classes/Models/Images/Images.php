<?php
namespace App\Classes\Models\Images;

use App\Classes\Models\BaseModel;

class Images extends BaseModel
{
    protected $table = 'sbc_images';
    protected $primaryKey = 'image_id';
    protected $entity = 'images';
    protected $searchableColumns = [];

    protected $fillable = ['image_id', 'image_name', 'image_path', 'module_id', 'created_at', 'updated_at'];


    public function __construct(array $attributes = [])
    {
        $this->bootIfNotBooted();
        $this->syncOriginal();
        $this->fill($attributes);
    }

    public function addModuleFilter($module_id = 0){
        if($module_id > 0) {
            $this->queryBuilder->where('module_id', $module_id);
        }
        return $this;
    }

    public function addImageIdFilter($image_id){

        $this->queryBuilder->where('image_id', $image_id);
        return $this;
    }
    public function addImagePathFilter($image_path){

        if(!empty($image_path)) {
            $this->queryBuilder->where('image_path', '=',$image_path);
        }
        return $this;
    }

    public function addOrderBy($columeName, $orderBy)
    {
        $this->queryBuilder->orderBy($columeName, $orderBy);
        return $this;
    }

    public function getImagesByModuleId($module_id)
    {
        return $this->setSelect()
            ->addModuleFilter($module_id)
            ->addOrderBy('image_name', 'asc')
            ->get()
            ->pluck('image_name', 'image_id')
            ->prepend(trans('quickadmin.qa_none'), 0);
    }

    public function recordCount($imagePath, $module_id = 0){
        return $this->setSelect()
            ->addModuleFilter($module_id)
            ->addImagePathFilter($imagePath)
            ->get(['image_id'])
            ->pluck('image_id')
            ->first();
    }

    public function getImagePathByImageId($image_id){

        return $this->setSelect()
            ->addImageIdFilter($image_id)
            ->get(['image_path'])
            ->pluck('image_path')
            ->first();
    }
}