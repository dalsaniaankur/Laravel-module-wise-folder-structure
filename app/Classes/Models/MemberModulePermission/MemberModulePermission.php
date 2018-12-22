<?php
namespace App\Classes\Models\MemberModulePermission;

use DB;
use App\Classes\Models\BaseModel;

class MemberModulePermission extends BaseModel{
    
	protected $table = 'sbc_member_module_permission';
    protected $primaryKey = 'member_module_permission_id';
    
  	protected $entity='sbc_member_module_permission';
	protected $searchableColumns=[];

    protected $fillable = ['member_id','module_name'];

	public function __construct(array $attributes = [])
    {
        $this->bootIfNotBooted();
        $this->syncOriginal();
        $this->fill($attributes);
    }

    public function addMemberIdFilter($member_id = 0){
        if($member_id > 0) {
            $this->queryBuilder->where('member_id', $member_id);
        }
        return $this;
    }

    public function addModuleName($module_name = ''){
        if(!empty(trim($module_name))) {
            $this->queryBuilder->where('module_name', $module_name);
        }
        return $this;
    }

    public function updateMemberModulePermission($member_id, $member_module){

	        /* Insert Update */
            foreach ($member_module as $key => $value) {
                MemberModulePermission::updateOrCreate(array('member_id' => $member_id,
                                                            'module_name' => $value));
            }
            /* Delete Record */
            MemberModulePermission::where('member_id', $member_id)
                                    ->whereNotIn('module_name', $member_module)
                                    ->delete();
    }
    public function getMemberModuleListByMemberId($member_id=0){
        return  $this->setSelect()
                    ->addMemberIdFilter($member_id)
                    ->get()
                    ->pluck('module_name')
                    ->toArray();
    }

    public function checkPermission($member_id,$module_name)
    {
        $list=$this->setSelect()
            ->addMemberIdFilter($member_id)
            ->addModuleName($module_name)
            ->get();

        if(count($list)>0){
            return true;
        }
        return false;
    }
}