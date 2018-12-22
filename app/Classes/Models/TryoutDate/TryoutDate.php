<?php
namespace App\Classes\Models\TryoutDate;

use DB;
use Illuminate\Database\Eloquent\Model;
use App\Classes\Models\BaseModel;


class TryoutDate extends BaseModel{
    
	protected $table = 'sbc_tryout_date';
    protected $primaryKey = 'tryout_date_id';
  	protected $entity='tryout_date';

    protected $fillable = [ 'tryout_id',
							'date',
                            'sort_order'
                          ];


	/*Copy from parent*/
	public function __construct(array $attributes = [])
    {	
        $this->bootIfNotBooted();
        $this->syncOriginal();
        $this->fill($attributes);
    }

    public function addOrderBy($columeName, $orderBy)
    {
        $this->queryBuilder->orderBy($columeName, $orderBy);
        return $this;
    }
	public function addTryoutDateIdFilter($tryout_date_id=0)
	{
		$this->queryBuilder->where('tryout_date_id', $tryout_date_id);
		return $this;
	}

	public function addTryoutIdFilter($tryout_id=0)
	{
		$this->queryBuilder->where('tryout_id',$tryout_id);
		return $this;
	}

	public function getDateList($tryout_id){

  		$list = $this->setSelect()
                    ->addTryoutIdFilter($tryout_id)
                    ->addOrderBy('sort_order', 'asc')
  				    ->get()
                    ->pluck('date')
                    ->toArray();

        $dateList = array();
        if(!empty($list)){
            foreach ($list as $date){
                $dateList[] = date("m/d/Y", strtotime($date));

            }
        }
        return (!empty($dateList)) ? implode($dateList,',') : "";
   	}

    public function CreateOrUpdate($tryout_id, $dates){

        /* Create Or Update */
        if(!empty($dates)) {
            $short_order = 1;
            foreach ($dates as $date) {
                $tryoutDate = TryoutDate::firstOrNew(array('tryout_id' => $tryout_id, 'date' => $date));
                $tryoutDate->sort_order = $short_order;
                $tryoutDate->save();
                $short_order ++;
            }
        }

        /* Delete Record */
        TryoutDate::where('tryout_id' , $tryout_id)
            ->whereNotIn('date', $dates)
            ->delete();
    }

    public function checkDateDuplicate($dates){
        $dates = (array)$dates;
        return count($dates) !== count(array_unique($dates));
    }

    public function getDateListByTryoutId($tryout_id){

        return $this->setSelect()
                ->addTryoutIdFilter($tryout_id)
                ->addOrderBy('sort_order', 'asc')
                ->get()
                ->pluck('date')
                ->toArray();

    }
}