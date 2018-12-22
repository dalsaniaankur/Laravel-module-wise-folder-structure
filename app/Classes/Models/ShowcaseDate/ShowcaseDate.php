<?php
namespace App\Classes\Models\ShowcaseDate;

use DB;
use Illuminate\Database\Eloquent\Model;
use App\Classes\Models\BaseModel;


class ShowcaseDate extends BaseModel{
    
	protected $table = 'sbc_showcase_date';
    protected $primaryKey = 'showcase_date_id';
  	protected $entity='showcase_date';

    protected $fillable = [ 'showcase_or_prospect_id',
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
	public function addShowcaseDateIdFilter($showcase_date_id=0)
	{
		$this->queryBuilder->where('showcase_date_id', $showcase_date_id);
		return $this;
	}

	public function addShowcaseIdFilter($showcase_or_prospect_id=0)
	{
		$this->queryBuilder->where('showcase_or_prospect_id',$showcase_or_prospect_id);
		return $this;
	}

	public function getDateList($showcase_or_prospect_id){

  		$list = $this->setSelect()
                    ->addShowcaseIdFilter($showcase_or_prospect_id)
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

    public function CreateOrUpdate($showcase_or_prospect_id, $dates){

        /* Create Or Update */
        if(!empty($dates)) {
            $short_order = 1;
            foreach ($dates as $date) {
                $showcaseDate = ShowcaseDate::firstOrNew(array('showcase_or_prospect_id' => $showcase_or_prospect_id, 'date' => $date));
                $showcaseDate->sort_order = $short_order;
                $showcaseDate->save();
                $short_order ++;
            }
        }

        /* Delete Record */
        ShowcaseDate::where('showcase_or_prospect_id' , $showcase_or_prospect_id)
            ->whereNotIn('date', $dates)
            ->delete();
    }

    public function checkDateDuplicate($dates){
        $dates = (array)$dates;
        return count($dates) !== count(array_unique($dates));
    }

    public function getDateListByShowcaseOrProspectId($showcase_or_prospect_id){

        return $this->setSelect()
                ->addShowcaseIdFilter($showcase_or_prospect_id)
                ->addOrderBy('sort_order', 'asc')
                ->get()
                ->pluck('date')
                ->toArray();

    }
}