<?php

namespace App\Classes\Models\ShowcaseOrganization;

use Auth;
use DB;
use App\Classes\Models\BaseModel;
use App\Classes\Models\Images\Images;
use App\Classes\Models\State\State;
use App\Classes\Models\City\City;
use App\Classes\Helpers\ShowcaseOrProspect\Helper;
use App\Classes\Models\ShowcaseOrganization\ShowcaseOrganization;
use App\Classes\Models\AgeGroup\AgeGroup;
use App\Classes\Models\Position\Position;
use App\Classes\Models\Members\Members;
use App\Classes\Models\ShowcaseDate\ShowcaseDate;

class ShowcaseOrProspect extends BaseModel
{

    protected $table = 'sbc_showcase_or_prospect';
    protected $primaryKey = 'showcase_or_prospect_id';

    protected $entity = 'showcase_or_prospect';
    protected $searchableColumns = ['name'];
    protected $_helper;
    protected $ageGroupObj;
    protected $positionObj;
    protected $stateObj;
    protected $memberObj;
    protected $cityObj;
    protected $ImagesObj;
    protected $showcaseOrganizationObj;
    protected $showcaseDateObj;

    protected $fillable = ['submitted_by_id',
        'type',
        'showcase_organization_id',
        'name',
        'url_key',
        'address_1',
        'address_2',
        'location',
        'city_id',
        'state_id',
        'zip',
        'phone_number',
        'email',
        'description',
        'age_group_id',
        'position_id',
        'open_or_invite',
        'longitude',
        'latitude',
        'attachment_name_1',
        'attachment_path_1',
        'attachment_name_2',
        'attachment_path_2',
        'website_url',
        'cost_or_notes',
        'other_information',
        'is_active',
        'is_send_email_to_user',
        'approval_status'
    ];


    /*Copy from parent*/
    public function __construct(array $attributes = [])
    {
        $this->bootIfNotBooted();
        $this->syncOriginal();
        $this->fill($attributes);
        $this->_helper = new Helper();
        $this->ageGroupObj = new AgeGroup();
        $this->positionObj = new Position();
        $this->stateObj = new State();
        $this->memberObj = new Members();
        $this->cityObj = new City();
        $this->ImagesObj = new Images();
        $this->showcaseOrganizationObj = new ShowcaseOrganization();
        $this->showcaseDateObj = new ShowcaseDate();
    }

    /**
     **    Model Relation Methods
     */

    public function getDateAttribute($value)
    {
        if (!empty($value)) {
            return \Carbon\Carbon::parse($value)->format('m/d/Y');
        }
        return '';
    }

    public function showcaseorganization()
    {
        return $this->belongsTo(ShowcaseOrganization::class, 'showcase_organization_id');
    }

    public function state()
    {

        return $this->belongsTo(State::class, 'state_id', 'state_id');
    }

    public function city()
    {

        return $this->belongsTo(City::class, 'city_id', 'city_id');
    }

    /**
     **    Model Attirbute Methods
     */

    public function setDateAttribute($value)
    {
        if ($value)
            $this->attributes['date'] = date("Y-m-d", strtotime($value));
        else
            $this->attributes['date'] = '';
    }

    /**
     **    Model Filter Methods
     */

    public function addShowcaseOrProspectIdFilter($showcase_or_prospect_id = 0)
    {
        $this->queryBuilder->where('showcase_or_prospect_id', $showcase_or_prospect_id);
        return $this;
    }

    public function addShowcaseOrganizationIdFilter($showcase_organization_id = 0)
    {
        if (!empty($showcase_organization_id) && $showcase_organization_id > 0) {
            $this->queryBuilder->where('showcase_organization_id', $showcase_organization_id);
        }
        return $this;
    }

    public function addSubmittedByIdFilter($submitted_by_id = 0)
    {
        if ($submitted_by_id > 0) {
            $this->queryBuilder->where('submitted_by_id', $submitted_by_id);
        }
        return $this;
    }

    /*
    **	Logic Methods
    */
    public function load($showcase_or_prospect_id)
    {
        $this->beforeLoad($showcase_or_prospect_id);

        $return = $this->setSelect()
            ->addShowcaseOrProspectIdFilter($showcase_or_prospect_id)
            ->get()
            ->first();

        $this->afterLoad($showcase_or_prospect_id, $return);

        return $return;
    }

    public function addOpenOrInviteFilter($open_or_invite = 0)
    {

        if (!empty($open_or_invite) && $open_or_invite[0] != 0 && count($open_or_invite) != 2) {
            $this->queryBuilder->where('open_or_invite', $open_or_invite[0]);
        }

        return $this;
    }

    public function addAgeGroupIdFilter($age_group_id_array = array())
    {

        if (!empty($age_group_id_array)) {
            $fieldName = $this->table . '.age_group_id';

            $this->queryBuilder->where(function ($q) use ($age_group_id_array, $fieldName) {
                foreach ($age_group_id_array as $key => $value) {
                    $q->orWhereRaw("find_in_set('" . $value . "'," . $fieldName . ")");
                }
            });
        }

        return $this;
    }

    public function addPositionFilter($position_id_array = array())
    {

        if (!empty($position_id_array)) {
            $fieldName = $this->table . '.position_id';

            $this->queryBuilder->where(function ($q) use ($position_id_array, $fieldName) {
                foreach ($position_id_array as $key => $value) {
                    $q->orWhereRaw("find_in_set('" . $value . "'," . $fieldName . ")");
                }
            });
        }

        return $this;
    }

    public function addDateFilter1($start_date = '', $end_date = '')
    {

        if (!empty($start_date) && !empty($end_date)) {

            $start_date = date("Y-m-d", strtotime($start_date));
            $end_date = date("Y-m-d", strtotime($end_date));

            $this->queryBuilder->where($this->table . '.date', '>=', $start_date);
            $this->queryBuilder->where($this->table . '.date', '<=', $end_date);
        }
        return $this;
    }

    public function addDateFilter($start_date='', $end_date=''){

        if(!empty($start_date) || !empty($end_date)){

            $showcaseDateObj = new ShowcaseDate();
            $showcaseDateTable= $showcaseDateObj->getTable();
            $this->queryBuilder->Join($showcaseDateTable, function ($join) use ($showcaseDateTable, $start_date,$end_date) {
                $join->on($this->table.'.showcase_or_prospect_id', '=', $showcaseDateTable.'.showcase_or_prospect_id');

            });
        }

        if(!empty($start_date)){
            $start_date = date("Y-m-d", strtotime($start_date));
            $this->queryBuilder->where(function($q) use ($showcaseDateTable, $start_date, $end_date) {
                $q->where($showcaseDateTable.'.date', '>=', "$start_date");
            });
        }

        if(!empty($end_date)){
            $end_date = date("Y-m-d", strtotime($end_date));
            $this->queryBuilder->where(function($q) use ($showcaseDateTable, $start_date, $end_date) {
                $q->Where($showcaseDateTable.'.date', '<=', "$end_date");
            });
        }

        return $this;
    }

    public function addMileRadiusFilter($redius, $latitude = '', $longitude = '')
    {

        if (!empty($redius) && $redius > 0 && !empty($latitude) && !empty($longitude)) {

            $this->queryBuilder->selectRaw('*, ( 6371 *
												        acos(
												            cos( radians( ' . $latitude . ' ) ) *
												            cos( radians( `latitude` ) ) *
												            cos(
												                radians( `longitude` ) - radians( ' . $longitude . ' )
												            ) +
												            sin(radians( ' . $latitude . ' )) *
												            sin(radians(`latitude`))
												        )
												    ) `distance`');

            $this->queryBuilder->having('distance', '<', $redius);

        }

        return $this;
    }

    public function addApprovalStatusFilter($approvalStatus = '')
    {

        if (!empty(trim($approvalStatus))) {
            $this->queryBuilder->where($this->table . '.approval_status', $approvalStatus);
        }
        return $this;
    }

    public function list($search = '', $page = 0, $type = 0, $state_id = 0, $city_id = 0, $redius = 0, $isFront = 0, $latitude = '', $longitude = '', $showcase_organization_id = 0, $open_or_invite = '', $age_group_id = array(), $position_id = array(), $start_date = '', $end_date = '', $name = '', $sortedBy = '', $sortedOrder = '', $is_active = 2, $per_page = 0, $selectColoumn = array('*'), $submitted_by_id = 0, $approvalStatus = '')
    {

        $per_page = $per_page == 0 ? $this->_helper->getConfigPerPageRecord() : $per_page;

        $list = $this->setSelect()
            ->addSearch($search)
            ->addNameFilter($name)
            ->addTypeFilter($type)
            ->addIsActiveFilter($is_active)
            ->addApprovalStatusFilter($approvalStatus)
            ->addSubmittedByIdFilter($submitted_by_id)
            ->addCityIdFilter($city_id)
            ->addStateIdFilter($state_id)
            ->addMileRadiusFilter($redius, $latitude, $longitude)
            ->addShowcaseOrganizationIdFilter($showcase_organization_id)
            ->addOpenOrInviteFilter($open_or_invite)
            ->addAgeGroupIdFilter($age_group_id)
            ->addPositionFilter($position_id)
            ->addDateFilter($start_date, $end_date)
            ->addOrderBy($sortedBy, $sortedOrder)
            ->addPaging($page, $per_page)
            ->addgroupBy($this->table . '.showcase_or_prospect_id')
            ->get();

        if (count($list) > 0) {
            $ageGroupForModuleWiseObj = new \App\Classes\Models\AgeGroup\AgeGroup();

            foreach ($list as $key => $row) {

                if (!empty($row->age_group_id)) {
                    $age_group_id_array = $this->getCSVToArray($row->age_group_id);
                }

                $age_group_list = array();

                if (!empty($age_group_id_array)) {
                    foreach ($age_group_id_array as $row_age_group_id) {
                        $age_group_result = $ageGroupForModuleWiseObj->getAllAgeGropuForCampOrClinic($row_age_group_id);
                        $age_group_list[] = $age_group_result->name;
                    }
                }
                if ($isFront == 0) {

                    $row['age_group'] = $age_group_list;
                } else {

                    $row['age_group'] = $this->getColumnList($age_group_list, ', ');

                    $dateList = $this->showcaseDateObj->getDateListByShowcaseOrProspectId($row['showcase_or_prospect_id']);
                    $list[$key]['dateList']= implode(', ',$dateList);
                }
            }

        }

        return $list;
    }

    public function listTotalCount($search = '', $page = 0, $type = 0, $state_id = 0, $city_id = 0, $redius = 0, $isFront = 0, $latitude = '', $longitude = '', $showcase_organization_id = 0, $open_or_invite = '', $age_group_id = array(), $position_id = array(), $start_date = '', $end_date = '', $name = '', $sortedBy = '', $sortedOrder = '', $is_active = 2, $submitted_by_id = 0, $approvalStatus = '')
    {
        $this->reset();
        $count = $this->setSelect()
            ->addSearch($search)
            ->addNameFilter($name)
            ->addTypeFilter($type)
            ->addIsActiveFilter($is_active)
            ->addApprovalStatusFilter($approvalStatus)
            ->addSubmittedByIdFilter($submitted_by_id)
            ->addMileRadiusFilter($redius, $latitude, $longitude)
            ->addCityIdFilter($city_id)
            ->addStateIdFilter($state_id)
            ->addShowcaseOrganizationIdFilter($showcase_organization_id)
            ->addOpenOrInviteFilter($open_or_invite)
            ->addAgeGroupIdFilter($age_group_id)
            ->addPositionFilter($position_id)
            ->addDateFilter($start_date, $end_date)
            ->addOrderBy($sortedBy, $sortedOrder)
            ->addgroupBy($this->table . '.showcase_or_prospect_id')
            ->get()
            ->count();

        return $count;
    }

    public function preparePagination($total, $basePath)
    {
        $perpage = $this->_helper->getConfigPerPageRecord();;
        $pageHelper = new \App\Classes\PageHelper($perpage, 'page');
        $pageHelper->set_total($total);
        $pageHelper->page_links($basePath);
        return $pageHelper->page_links($basePath);
    }

    public function saveRecord($data)
    {

        /* Check Duplicate or Form Submit Call */
        $is_form_submit = 0;
        if (!empty($data['_token'])) {
            $is_form_submit = 1;
        }

        $rules = array();
        $rules = [
            'submitted_by_id' => 'required',
            'type' => 'required',
            'showcase_organization_id' => 'required',
            'name' => 'required',
            'url_key' => 'required|unique:' . $this->table,
            'address_1' => 'required',
            'city_id' => 'required',
            'state_id' => 'required',
            'zip' => 'required',
            'phone_number' => 'required',
            'email' => 'required',
            'longitude' => 'required',
            'latitude' => 'required',
        ];

        if ($is_form_submit == 1) {
            $rules['attachment_name_1'] = 'mimes:application/msword,pdf,application/pdf,text/plain';
            $rules['attachment_name_2'] = 'mimes:application/msword,pdf,application/pdf,text/plain';
        }

        if (!empty($data['age_group_id'])) {
            $data['age_group_id'] = $this->getArrayToCSV($data['age_group_id']);
        } else {
            $data['age_group_id'] = '';
        }

        $data['url_key'] = str_slug($data['url_key']);
        if (isset($data['id']) && $data['id'] != '') {
            $id = $data['id'];
            $rules['url_key'] = 'required|unique:' . $this->table . ',url_key,' . $id . ',showcase_or_prospect_id';
        }

        $validationResult = $this->validateData($rules, $data);

        if($is_form_submit == 1) {
            $dateList = array();
            if (!empty($data['dates'])) {
                $dateArray = explode(',', $data['dates']);
                foreach ($dateArray as $date) {

                    /* Check date format Validate */
                    $validate = $this->validateDate($date);
                    if ($validate) {

                        $date = date_create($date);
                        $dateList[] = date_format($date, "Y-m-d");

                    } else {

                        $validationResult['success'] = false;
                        if (!isset($validationResult['message']) || !is_object($validationResult['message'])) {
                            $validationResult['message'] = new \stdClass();
                        }
                        $validationResult['message']->dates = array('Dates fields is invalid.');
                    }
                }

                /* Check date is duplicate */
                $isDuplicateDate = $this->showcaseDateObj->checkDateDuplicate($dateList);
                if ($isDuplicateDate) {

                    $validationResult['success'] = false;
                    if (!isset($validationResult['message']) || !is_object($validationResult['message'])) {
                        $validationResult['message'] = new \stdClass();
                    }
                    $validationResult['message']->dates = array('Date Range fields is invalid.');
                }
            }
        }
        
        $result = array();
        $result['id'] = '';

        if ($validationResult['success'] == false) {
            $result['success'] = false;
            $result['message'] = $validationResult['message'];
            $result['id'] = $data['id'];
            return $result;
        }

        if ($is_form_submit == 1) {
            $imageUploadPath = $this->_helper->getImageUploadPath();
            if (!empty($data['attachment_name_1'])) {
                $attachment_1 = $data['attachment_name_1'];
                $attachment_1_name = $attachment_1->getClientOriginalName();

                $destinationPath = public_path($imageUploadPath);
                $attachment_1->move($destinationPath, $attachment_1_name);

                $data['attachment_name_1'] = $attachment_1_name;
                $data['attachment_path_1'] = $imageUploadPath . '/' . $attachment_1_name;
            }

            if (!empty($data['attachment_name_2'])) {
                $attachment_2 = $data['attachment_name_2'];
                $attachment_2_name = $attachment_2->getClientOriginalName();

                $destinationPath = public_path($imageUploadPath);
                $attachment_2->move($destinationPath, $attachment_2_name);

                $data['attachment_name_2'] = $attachment_2_name;
                $data['attachment_path_2'] = $imageUploadPath . '/' . $attachment_2_name;
            }
        }
        if (!empty($data['position_id'])) {
            $data['position_id'] = $this->getArrayToCSV($data['position_id']);
        } else {
            $data['position_id'] = '';
        }

        if (!empty($data['is_active']) && $data['is_active'] = 'on') {
            $data['is_active'] = 1;
        } else {
            $data['is_active'] = 0;
        }

        if (!empty($data['is_send_email_to_user']) && $data['is_send_email_to_user'] = 'on') {
            $data['is_send_email_to_user'] = 1;
        } else {
            $data['is_send_email_to_user'] = 0;
        }
        $this->beforeSave($data);
        if (isset($data['id']) && $data['id'] != '') {
            $showcase_or_prospect = self::findOrFail($data['id']);
            $showcase_or_prospect->update($data);
            $this->afterSave($data, $showcase_or_prospect);
            $result['id'] = $showcase_or_prospect->showcase_or_prospect_id;

        } else {
            $showcase_or_prospect = self::create($data);
            $result['id'] = $showcase_or_prospect->showcase_or_prospect_id;
            $this->afterSave($data, $showcase_or_prospect);
        }

        if($is_form_submit==1 || $is_form_submit=='1'){
            $this->showcaseDateObj->CreateOrUpdate($result['id'], $dateList);
        }

        $result['success'] = true;
        $result['message'] = "Showcase Or Prospect Saved Successfully.";

        return $result;
    }

    public function display($id)
    {
        $return = $this->load($id);

        if (!empty($return->age_group_id)) {
            $return->age_group_id = $this->getCSVToArray($return->age_group_id);
        }
        if (!empty($return->position_id)) {
            $return->position_id = $this->getCSVToArray($return->position_id);
        }

        return $return;
    }

    public function removed($id)
    {
        $this->beforeRemoved($id);
        $organisation = $this->load($id);
        if (!empty($organisation)) {
            $delete = $organisation->delete();
            $this->afterRemoved($id);
            return $delete;
        }
        return false;
    }


    public function addIsActiveFilter($is_active = 2)
    {
        if ($is_active != 2) {
            $this->queryBuilder->where($this->table . '.is_active', $is_active);
        }
        return $this;
    }

    public function addTypeFilter($type = 0)
    {
        if ($type > 0) {
            $this->queryBuilder->where('type', $type);
        }
        return $this;
    }

    public function addOrderBy($sortedBy, $sortedOrder)
    {

        if (!empty($sortedBy) && !empty($sortedOrder)) {
            $this->queryBuilder->orderBy($sortedBy, $sortedOrder);
        }
        return $this;
    }

    public function addgroupBy($groupByName)
    {

        $this->queryBuilder->groupBy($groupByName);
        return $this;
    }

    public function convertDataToHtml($tournamentOrganizations)
    {

        $htmlContent = "";
        if (count($tournamentOrganizations) > 0) {
            foreach ($tournamentOrganizations as $key => $data) {

                $htmlContent .= "<tr>
									<td data-title='" . trans('front.showcases_grid.fields.showcase') . "'><a href='" . $data->getUrl() . "'>" . $data->name . "</a></td>
							        <td data-title='" . trans('front.showcases_grid.fields.location') . "'>" . $data->city->city . ', ' . $data->state->name . "</td>
							        <td data-title='" . trans('front.showcases_grid.fields.organization') . "'>" . $data->showcaseorganization->name . "</td>
							        <td data-title='" . trans('front.showcases_grid.fields.dates') . "'>" . $data->dateList . "</td>
							        <td data-title='" . trans('front.showcases_grid.fields.age_groups') . "'>" . $data->age_group . "</td>
							    </tr>";
            }
        } else {
            $htmlContent .= "<tr>
								<td colspan='100'>" . trans("quickadmin.qa_no_entries_in_table") . "</td>
							</tr>";
        }
        return $htmlContent;
    }

    /* Page Builder */
    public function addStateIdFilter($state_id = 0)
    {
        if ($state_id > 0) {
            $this->queryBuilder->where('state_id', $state_id);
        }
        return $this;
    }

    public function addCityIdFilter($city_id = 0)
    {

        if ($city_id > 0) {
            $this->queryBuilder->where('city_id', $city_id);
        }
        return $this;
    }

    public function addUrlKeyFilter($url_key)
    {
        if (!empty(trim($url_key))) {
            $this->queryBuilder->where('url_key', $url_key);
        }
        return $this;
    }

    public function getDetailPageByUrlKey($url_key)
    {
        $showcase = $this->setSelect()
            ->addUrlKeyFilter($url_key)
            ->addIsActiveFilter(1)
            ->addTypeFilter(1)
            ->get()
            ->first();

        if (!empty($showcase)) {
            /* Age group */
            $age_group_list = array();
            $age_group_id_array = $this->getCSVToArray($showcase->age_group_id);
            if (!empty($age_group_id_array)) {
                foreach ($age_group_id_array as $row_age_group_id) {
                    $age_group_result = $this->ageGroupObj->getAllAgeGropuForCampOrClinic($row_age_group_id);
                    $age_group_list[] = $age_group_result->name;
                }
            }
            $showcase['age_group'] = $this->getColumnList($age_group_list, ' - ');

            /* position_id */
            $position_list = array();
            $position_id_array = $this->getCSVToArray($showcase->position_id);
            if (!empty($position_id_array)) {
                foreach ($position_id_array as $row_position_id) {
                    $position_result = $this->positionObj->getAllPositionById($row_position_id);
                    $position_list[] = $position_result->name;
                }
            }
            $showcase['position'] = $this->getColumnList($position_list, ' - ');
        }
        return $showcase;
    }

    public function getUrl()
    {
        return \URL::to('showcases') . '/' . $this->url_key;
    }

    public function addNameFilter($name = '')
    {

        if (!empty($name)) {
            $this->queryBuilder->where($this->table . '.name', 'like', '%' . $name . '%');
        }
        return $this;
    }

    public function HeaderSearch($search, $city_id, $state_id, $latitude, $longitude, $redius)
    {
        $per_page = $this->_helper->getConfigRecordForTopsearch();
        $selectColoumn = ['showcase_or_prospect_id', 'name', 'url_key'];
        $listArray = array();
        $list = $this->list($search, $page = 0, $type = 1, $state_id, $city_id, $redius, $isFront = 1, $latitude, $longitude, $showcase_organization_id = 0, $open_or_invite = '', $age_group_id = array(), $position_id = array(), $start_date = '', $end_date = '', $name = '', $sortedBy = 'name', $sortedOrder = 'ASC', $is_active = 1, $per_page, $selectColoumn);

        if (!empty($list)) {
            foreach ($list as $key => $value) {
                $data['title'] = $value->name;
                $data['url'] = $value->getUrl();
                $listArray[] = $data;
            }
        }

        return $listArray;
    }

    public function checkDuplicateUrlKey($url_key)
    {
        return $this->setSelect()
            ->addUrlKeyFilter($url_key)
            ->get()
            ->count();
    }

    public function getShowcaseOrProspectWidget($submitted_by_id = 0)
    {

        return $this->setSelect()
            ->addSubmittedByIdFilter($submitted_by_id)
            ->get()
            ->count();
    }

    public function loadByShowcaseOrProspectIdForEnquiry($showcase_or_prospect_id)
    {

        $selectColoumn = [$this->table . '.name',
            $this->table . '.email'];

        $return = $this->setSelect()
            ->addShowcaseOrProspectIdFilter($showcase_or_prospect_id)
            ->get($selectColoumn)
            ->first();

        return $return;
    }

    public function exportCSV($entity, $search, $page)
    {

        $selectedColumns = ['showcase_or_prospect_id',
            'submitted_by_id',
            'type',
            'showcase_organization_id',
            'name',
            'address_1',
            'address_2',
            'location',
            'state_id',
            'city_id',
            'zip',
            'phone_number',
            'email',
            'description',
            'age_group_id',
            'position_id',
            'open_or_invite',
            'longitude',
            'latitude',
            'attachment_name_1',
            'attachment_path_1',
            'attachment_name_2',
            'attachment_path_2',
            'website_url',
            'cost_or_notes',
            'other_information',
            'is_active',
            'is_send_email_to_user',
            'approval_status',
            'dates'];

        $csvHeaderLable = ['showcase_or_prospect_id' => 'Showcase Or Prospect Id',
            'submitted_by_id' => 'Member',
            'type' => 'Type',
            'showcase_organization_id' => 'Organization',
            'name' => 'Name',
            'address_1' => 'Address 1',
            'address_2' => 'Address 2',
            'location' => 'Location',
            'state_id' => 'State',
            'city_id' => 'City',
            'zip' => 'Zip',
            'phone_number' => 'Phone Number',
            'email' => 'Email',
            'description' => 'Description',
            'age_group_id' => 'Age Groups',
            'position_id' => 'Position Id',
            'open_or_invite' => 'Open Or Invite',
            'longitude' => 'Longitude',
            'latitude' => 'Latitude',
            'attachment_name_1' => 'Attachment Name 1',
            'attachment_path_1' => 'Attachment Path 1',
            'attachment_name_2' => 'Attachment Name 2',
            'attachment_path_2' => 'Attachment Path 2',
            'website_url' => 'Website Url',
            'cost_or_notes' => 'Cost Or Notes',
            'other_information' => 'Other Information',
            'is_active' => 'Is Active',
            'is_send_email_to_user' => 'Is Send Email To User',
            'approval_status' => 'Approval Status',
            'dates' => 'Dates'];

        $results = $this->list($search, $page, $type = 0, $state_id = 0, $city_id = 0, $redius = 0, $isFront = 0, $latitude = '', $longitude = '', $showcase_organization_id = 0, $open_or_invite = '', $age_group_id = array(), $position_id = array(), $start_date = '', $end_date = '', $name = '', $sortedBy = '', $sortedOrder = '', $is_active = 2, $per_page = 0, $selectColoumn = array('*'), $submitted_by_id = 0, $approvalStatus = '');
        $csvExportPath = $this->_helper->getCsvExportFolderPath();

        /* Data Format */
        if (!empty($results)) {
            foreach ($results as $value) {

                if ($value->submitted_by_id > 0) {
                    $value->submitted_by_id = $this->memberObj->getMemberEmailByMemberId($value->submitted_by_id);
                } else {
                    $value->submitted_by_id = "";
                }
                $value->showcase_organization_id = $value->showcaseorganization->name;
                $value->state_id = $value->state->name;
                $value->city_id = $value->city->city;
                $value->type = $this->_helper->getTypeNameById($value->type);
                $value->open_or_invite = $this->_helper->getOpenOrInviteNameById($value->open_or_invite);
                $value->approval_status = ucfirst($value->approval_status);
                $value->is_active = ($value->is_active == 1) ? 'Active' : 'Deactive';
                $value->is_send_email_to_user = ($value->is_send_email_to_user == 1) ? 'Yes' : 'No';

                /* Age Group */
                if (!empty($value->age_group_id)) {
                    $ageGroupArray = array();
                    $age_group_array = explode(',', $value->age_group_id);

                    foreach ($age_group_array as $key => $value_age_group_Id) {
                        $ageGroupName = $this->ageGroupObj->getAgeGroupNameById($value_age_group_Id);
                        $ageGroupArray[] = $ageGroupName;
                    }

                    $value->age_group_id = implode($ageGroupArray, ':');
                }

                /* Position */
                if (!empty($value->position_id)) {
                    $positionArray = array();
                    $position_array = explode(',', $value->position_id);
                    foreach ($position_array as $key => $value_position_Id) {
                        $positionArray[] = $this->positionObj->getPositionNameById($value_position_Id);

                    }
                    $value->position_id = implode($positionArray, ':');
                }
            }

            $results = $results->toArray();
            foreach ($results as $key => $value) {

                $results[$key]['dates']= $this->showcaseDateObj->getDateList($value['showcase_or_prospect_id']);
            }
            $results = json_decode(json_encode($results), false);
            $results = ( object ) $results;
        }
        $response = $this->generateCSV($results, $entity, $csvHeaderLable, $selectedColumns, $csvExportPath);
        return $response;
    }

    public function checkUrlKeyDuplicate($url_key)
    {

        $result = $this->checkDuplicateUrlKey($url_key);
        if ($result == 1 || $result == '1') {
            $url_key = $this->generateDuplidateUrlKey($url_key);
            $url_key = $this->checkUrlKeyDuplicate($url_key);
        }
        return $url_key;

    }

    public function importCSV($data)
    {

        $response = array('success' => false);

        $csvImportFolderPath = $this->_helper->getCsvImportFolderPath();
        $csvImportResultsFolderPath = $this->_helper->getCsvImportResultsFolderPath();

        /* Upload File */
        $file = $data['csv_file'];
        $results = $this->uploadCSV($file, $csvImportFolderPath, $csvImportResultsFolderPath);

        if (!empty($results)) {

            $rowErrors = array();
            $successCount = 0;
            $errorCount = 0;
            $totalCsvRecord = 0;

            if (!empty($results['FilePath'])) {

                $filePath = $results['FilePath'];
                $resultFilePath = $results['ResultsFilePath'];

                $file = fopen(public_path($filePath), "r");
                $resultFile = fopen(public_path($resultFilePath), "w+");

                $csvHeader = fgetcsv($file); //CSV Header Columns
                $unsetBlankCsvColumnsIndexes = array();

                foreach ($csvHeader as $key => $value) {
                    $value = trim($value);
                    if ($value == '') {
                        $unsetBlankCsvColumnsIndexes[] = $key;
                        unset($csvHeader[$key]);
                    } else {
                        $csvHeader[$key] = trim($value);
                    }
                }

                /* Header Column */
                $header = array(
                    'Showcase Or Prospect Id' => array('db_column' => 'showcase_or_prospect_id'),
                    'Member' => array('db_column' => 'submitted_by_id', 'reference_key_with_zero_allow' => true, 'reference_function' => 'checkMemberReference'),
                    'Type' => array('db_column' => 'type', 'required' => true, 'custom_function' => 'checkTypeReference'),
                    'Organization' => array('db_column' => 'showcase_organization_id', 'required' => true, 'reference_key' => true, 'reference_function' => 'checkShowcaseOrganizationReference'),
                    'Name' => array('db_column' => 'name', 'required' => true),
                    'Address 1' => array('db_column' => 'address_1', 'required' => true),
                    'Address 2' => array('db_column' => 'address_2'),
                    'Location' => array('db_column' => 'location'),
                    'State' => array('db_column' => 'state_id', 'required' => true, 'reference_key' => true, 'reference_function' => 'checkStateReference'),
                    'City' => array('db_column' => 'city_id', 'required' => true, 'reference_key' => true, 'reference_function' => 'checkCityReference'),
                    'Zip' => array('db_column' => 'zip', 'required' => true),
                    'Phone Number' => array('db_column' => 'phone_number', 'required' => true),
                    'Email' => array('db_column' => 'email', 'required' => true),
                    'Description' => array('db_column' => 'description', 'required' => true),
                    'Age Groups' => array('db_column' => 'age_group_id', 'custom_function' => 'checkAgeGroupReference'),
                    'Position Id' => array('db_column' => 'position_id', 'custom_function' => 'checkPositionReference'),
                    'Open Or Invite' => array('db_column' => 'open_or_invite', 'required' => true, 'custom_function' => 'checkOpenOrInviteReference'),
                    'Longitude' => array('db_column' => 'longitude', 'required' => true),
                    'Latitude' => array('db_column' => 'latitude', 'required' => true),
                    'Attachment Name 1' => array('db_column' => 'attachment_name_1'),
                    'Attachment Path 1' => array('db_column' => 'attachment_path_1'),
                    'Attachment Name 2' => array('db_column' => 'attachment_name_2'),
                    'Attachment Path 2' => array('db_column' => 'attachment_path_2'),
                    'Website Url' => array('db_column' => 'website_url'),
                    'Cost Or Notes' => array('db_column' => 'cost_or_notes'),
                    'Other Information' => array('db_column' => 'other_information'),
                    'Is Active' => array('db_column' => 'is_active'),
                    'Is Send Email To User' => array('db_column' => 'is_send_email_to_user'),
                    'Approval Status' => array('db_column' => 'approval_status', 'custom_function' => 'checkStatusReference'),
                    'Dates'=>array('db_column'=>'dates','custom_function'=>'checkDateReference'),
                );


                // CSV Columns validation
                $rowErrors = $this->checkCsvColumnValidation($csvHeader, $header);

                if (count($rowErrors) > 0) {
                    return response()->json([
                            'success' => false,
                            'message' => 'Could not import csv file due to following errors. Please try again. <br>' . $rowErrors[0],
                        ]
                    );
                }

                /* Result add column. */
                $resultCsvHeader = $csvHeader;
                $resultCsvHeader[] = 'result';
                $resultCsvHeader[] = 'result_message';
                fputcsv($resultFile, $resultCsvHeader);

                while ($row = fgetcsv($file)) {
                    foreach ($row as $index => $value) {
                        if (in_array($index, $unsetBlankCsvColumnsIndexes)) {
                            unset($row[$index]);
                        } else {
                            $row[$index] = trim($value);
                        }
                    }

                    $totalCsvRecord++;
                    $csvDataValue = array_combine($csvHeader, $row);
                    $tableEntryRow = array();
                    $resultRow = $csvDataValue;
                    $resultRow['result'] = 'success';
                    $resultRow['result_message'] = '';
                    $validRow = true;

                    foreach ($header as $headerKey => $headerValue) {
                        $csvValue = trim($csvDataValue[$headerKey]);
                        $dbColumnName = $headerValue['db_column'];

                        if (!$validRow) {
                            continue;
                        }

                        if (!empty($headerValue['required']) && $headerValue['required']) {

                            if ($csvValue == '') {
                                $resultRow['result_message'] = $headerKey . ' is required.';
                                $validRow = false;
                                continue;
                            }
                        }

                        if (!empty($headerValue['reference_key']) && $headerValue['reference_key']) {

                            $reference_function = $headerValue['reference_function'];

                            /* For City */
                            if ($reference_function == "checkCityReference") {
                                $isExistReferenceID = $this->{$reference_function}($csvValue, $tableEntryRow['state_id']);
                            } else {
                                $isExistReferenceID = $this->{$reference_function}($csvValue);
                            }

                            if (!isset($isExistReferenceID) || empty($isExistReferenceID) || $isExistReferenceID == 0) {
                                $resultRow['result_message'] = 'Reference ' . $headerKey . ' is not exist';
                                $validRow = false;
                                continue;
                            }

                            $csvValue = $isExistReferenceID;
                        }

                        if (!empty($headerValue['reference_key_with_zero_allow']) && $headerValue['reference_key_with_zero_allow']) {

                            $reference_function = $headerValue['reference_function'];

                            if (!empty($csvValue) && $csvValue !== 0) {

                                $isExistReferenceID = $this->{$reference_function}($csvValue);

                                if (!isset($isExistReferenceID) || empty($isExistReferenceID) || $isExistReferenceID == 0) {
                                    $resultRow['result_message'] = 'Reference ' . $headerKey . ' is not exist';
                                    $validRow = false;
                                    continue;
                                }

                                $csvValue = $isExistReferenceID;

                            } else {
                                $csvValue = 0;
                            }
                        }

                        if (!empty($headerValue['custom_function']) && $headerValue['custom_function']) {

                            $custom_function = $headerValue['custom_function'];

                            if ($custom_function == "checkTypeReference") {

                                if (!empty($csvValue)) {

                                    $isExistReferenceID = $this->_helper->getTypeIdByName($csvValue);
                                    if (!isset($isExistReferenceID) || empty($isExistReferenceID) || $isExistReferenceID == 0) {
                                        $resultRow['result_message'] = 'Reference ' . $headerKey . ' is not exist';
                                        $validRow = false;
                                        continue;
                                    }

                                    $csvValue = $isExistReferenceID;
                                }
                            }
                            if ($custom_function == "checkOpenOrInviteReference") {

                                if (!empty($csvValue)) {

                                    $isExistReferenceID = $this->_helper->getOpenOrInviteIdByName($csvValue);
                                    if (!isset($isExistReferenceID) || empty($isExistReferenceID) || $isExistReferenceID == 0) {
                                        $resultRow['result_message'] = 'Reference ' . $headerKey . ' is not exist';
                                        $validRow = false;
                                        continue;
                                    }

                                    $csvValue = $isExistReferenceID;
                                }
                            }

                            if ($custom_function == "checkPositionReference") {

                                if (!empty($csvValue)) {
                                    $position = explode(':', $csvValue);
                                    $positionId = array();

                                    foreach ($position as $key => $positionValue) {
                                        $isExistReferenceID = $this->{$custom_function}($positionValue);

                                        $positionId[] = $isExistReferenceID;
                                        if (!isset($isExistReferenceID) || empty($isExistReferenceID) || $isExistReferenceID == 0) {
                                            $resultRow['result_message'] = 'Reference ' . $headerKey . ' is not exist';
                                            $validRow = false;
                                            continue;
                                        }
                                    }

                                    $csvValue = implode(',', $positionId);
                                }
                            }


                            if ($custom_function == "checkAgeGroupReference") {

                                if (!empty($csvValue)) {
                                    $ageGroup = explode(':', $csvValue);
                                    $ageGroupId = array();

                                    foreach ($ageGroup as $key => $ageGroupValue) {
                                        $isExistReferenceID = $this->{$custom_function}($ageGroupValue);

                                        $ageGroupId[] = $isExistReferenceID;
                                        if (!isset($isExistReferenceID) || empty($isExistReferenceID) || $isExistReferenceID == 0) {
                                            $resultRow['result_message'] = 'Reference ' . $headerKey . ' is not exist';
                                            $validRow = false;
                                            continue;
                                        }
                                    }
                                    $csvValue = implode(',', $ageGroupId);
                                }
                            }

                            if (!empty($headerValue['custom_function']) && $headerValue['custom_function']) {

                                $custom_function = $headerValue['custom_function'];

                                if ($custom_function == "checkStatusReference") {
                                    $isExistReferenceID = $this->{$custom_function}($csvValue);

                                    if (!isset($isExistReferenceID) || empty($isExistReferenceID)) {
                                        $resultRow['result_message'] = 'Reference ' . $headerKey . ' is not exist';
                                        $validRow = false;
                                        continue;
                                    }
                                }
                            }

                            if($custom_function == "checkDateReference") {

                                if(!empty($resultRow['Dates'])){
                                    $dateArray = explode(',', $csvValue);
                                    foreach ($dateArray as $date) {

                                        /* Check date format Validate */
                                        $validate = $this->validateDate($date);
                                        if ($validate) {
                                            $date = date_create($date);
                                            $dateList[] = date_format($date, "Y-m-d");
                                        } else {
                                            $resultRow['result_message'] = 'Invalid Dates format';
                                            $validRow = false;
                                            continue;
                                        }
                                    }

                                    /* Check date is duplicate */
                                    $isDuplicateDate = $this->showcaseDateObj->checkDateDuplicate($dateList);
                                    if ($isDuplicateDate) {

                                        $resultRow['result_message'] = 'Duplicate Dates';
                                        $validRow = false;
                                        continue;
                                    }

                                    $resultRow['DatesArray'] = $dateList;
                                }
                            }
                        }
                        $tableEntryRow[$headerValue['db_column']] = $csvValue;
                    }

                    if ($validRow) {

                        $tableEntryRow['is_send_email_to_user'] = (($tableEntryRow['is_send_email_to_user'] == 'Yes') ? 1 : 0);
                        $tableEntryRow['is_active'] = (($tableEntryRow['is_active'] == 'Active') ? 1 : 0);
                        $tableEntryRow['approval_status'] = strtolower($tableEntryRow['approval_status']);

                        if (!empty($tableEntryRow[$this->primaryKey]) && $tableEntryRow[$this->primaryKey] > 0) {
                            $id = $tableEntryRow[$this->primaryKey];
                            $results = self::findOrFail($id);
                            $results->update($tableEntryRow);

                        } else {
                            $url_key_date = "";
                            if (!empty($resultRow['Date'])) {
                                $date = explode('/', $resultRow['Date']);
                                $url_key_date = $date[2] . '-' . $date[1] . '-' . $date[0];
                            }
                            $tableEntryRow['url_key'] = $this->generateUrlKey([$resultRow['Name'], $resultRow['City'], $resultRow['State'], $url_key_date]);
                            $tableEntryRow['url_key'] = $this->checkUrlKeyDuplicate($tableEntryRow['url_key']);
                            $results = $this::create($tableEntryRow);
                        }

                        $showcase_or_prospect_id = $results->{$this->primaryKey};

                        /* Dates */
                        if(isset($resultRow['Dates'])){
                            $dateList = isset($resultRow['DatesArray']) ? $resultRow['DatesArray'] : array();
                            $this->showcaseDateObj->CreateOrUpdate($showcase_or_prospect_id, $dateList);
                            if(isset($resultRow['DatesArray'])){
                                unset($resultRow['DatesArray']);
                            }
                        }

                    }

                    if (!$validRow) {
                        $resultRow['result'] = 'failed';
                    } else if (!empty($results->{$this->primaryKey}) && $results->{$this->primaryKey} > 0) {
                        $successCount++;
                    }
                    fputcsv($resultFile, $resultRow);
                    $errorCount++;
                }

                fclose($resultFile);
                $fileUrl = \URL::to('administrator/download_csv?filepath=' . $resultFilePath);
                return response()->json([
                    'success' => true,
                    'message' => $successCount . ' out of ' . $totalCsvRecord . ' records imported sucessfully. </br> Click on <b>"Download Result File"</b> button to view import result.',
                    'resultFilePath' => $fileUrl,

                ]);
            }
        }

        return $response;
    }

    /* check State model reference key */
    public function checkStateReference($stateName)
    {
        return $this->stateObj->recordCount($stateName);
    }

    /* check City model reference key */
    public function checkCityReference($cityName, $state_id)
    {
        return $this->cityObj->recordCount($cityName, $state_id);
    }

    /* check Member model reference key */
    public function checkMemberReference($memberEmail)
    {
        return $this->memberObj->recordCount($memberEmail);
    }

    /* check AgeGroup model reference key */
    public function checkAgeGroupReference($name)
    {
        $module_id = $this->_helper->getModuleId();
        return $this->ageGroupObj->getAgeGroupIdByName($name, $module_id);
    }

    /* check Showcase Organization model reference key */
    public function checkShowcaseOrganizationReference($showcaseOrganizationName)
    {
        return $this->showcaseOrganizationObj->recordCount($showcaseOrganizationName);
    }

    /* check PositionReference model reference key */
    public function checkPositionReference($name)
    {
        $module_id = $this->_helper->getModuleId();
        return $this->positionObj->getPositionIdByName($name, $module_id);
    }

    /* check Status reference key */
    public function checkStatusReference($statusId)
    {
        return $this->_helper->checkStatusReference($statusId);
    }
}
