<?php
namespace App\Classes\Models\AgeGroupEntryFee;

use App\Classes\Models\BaseModel;
use App\Classes\Models\AgeGroup\AgeGroup;

class AgeGroupEntryFee extends BaseModel
{
	protected $table      ='sbc_age_group_entry_fee';
    protected $primaryKey ='age_group_entry_fee_id';
  	protected $entity     ='sbc_age_group_entry_fee';
	protected $searchableColumns=[];
    protected $fillable=['age_group_id','tournament_id','entry_fee'];
    protected $ageGroupObj;

	public function __construct(array $attributes = [])
    {
        $this->bootIfNotBooted();
        $this->syncOriginal();
        $this->fill($attributes);
        $this->ageGroupObj = new AgeGroup();
    }
	
	public function addTournamentIdFilter($tournament_id)
	{
		$this->queryBuilder->where('tournament_id',$tournament_id);
		return $this;
	}
    public function addAgeGroupIdFilter($age_group_id)
    {
        $this->queryBuilder->where('age_group_id',$age_group_id);
        return $this;
    }

	public function getAgeGroupEntryFeeByTournamentId($tournament_id){
        $this->reset();
	    return  $this->setSelect()
		   			  ->addTournamentIdFilter($tournament_id)
                      ->get()
	                  ->pluck('entry_fee', 'age_group_id');

  	}

    public function updateAgeGroupEntryFee($tournament_id,$data){

        $age_group_array = array();
        if(!empty($data['age_group_id'])){
            $age_group_array = explode(',', $data['age_group_id']);
        }

        /* Insert Update */
        foreach ($age_group_array as $key => $value) {
            $ageGroupEntryFee = AgeGroupEntryFee::firstOrNew(array('age_group_id' => $value, 'tournament_id' => $tournament_id));
            $ageGroupEntryFee->entry_fee = $data['entry_fee_'.$value];
            $ageGroupEntryFee->save();
        }
        /* Delete Record */
        AgeGroupEntryFee::where('tournament_id', $tournament_id)
                            ->whereNotIn('age_group_id', $age_group_array)
                            ->delete();
    }
    public function joinAgeGroup($searchable=false)
    {

        $ageGroupTable = $this->ageGroupObj->getTable();
        $searchableColumns = $this->ageGroupObj->getSearchableColumns();

        $this->joinTables[]=array('table'=>$ageGroupTable,'searchable'=>$searchable,'searchableColumns'=>$searchableColumns);
        $this->queryBuilder->join($ageGroupTable,function($join) use($ageGroupTable) {
            $join->on($this->table.'.age_group_id', '=', $ageGroupTable.'.age_group_id');
        });
        return $this;
    }

    public function getAgeGroupEntryFeeByTournamentIdForFront($tournament_id){
        $this->reset();
        $ageGroupTable = $this->ageGroupObj->getTable();
        $selectColoumn = [$ageGroupTable.'.name',$this->table.'.entry_fee'];
        return  $this->setSelect()
            ->joinAgeGroup()
            ->addTournamentIdFilter($tournament_id)
            ->get($selectColoumn);
    }

    public function getListByTournamentId($tournament_id){
        return $this->setSelect()
            ->addTournamentIdFilter($tournament_id)
            ->get();
    }

    public function getEntryFeeByAgeGroupAndTournamentId($age_group_id, $tournament_id){

	    return $this->setSelect()
            ->addTournamentIdFilter($tournament_id)
            ->addAgeGroupIdFilter($age_group_id)
            ->get(['entry_fee'])
            ->pluck('entry_fee')
            ->first();
    }
}