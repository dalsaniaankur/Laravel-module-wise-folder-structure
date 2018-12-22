<?php
namespace App\Http\Controllers\Administrator\Configuration;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Config;
use App\Classes\Models\AdministratorConfiguration\AdministratorConfiguration;

class IndexController extends Controller{

    protected $themes=array('skin-black'=>'Black',
        'skin-black-light'=>'Black Light',
        'skin-blue'=>'Blue',
        'skin-blue-light'=>'Blue Light',
        'skin-green'=>'Green',
        'skin-green-light'=>'Green Light',
        'skin-purple'=>'Purple',
        'skin-purple-light'=>'Purple Light',
        'skin-red'=>'Red',
        'skin-red-light'=>'Red Light',
        'skin-yellow'=>'Yellow',
        'skin-yellow-light'=>'Yellow Light',
    );
    protected $approvalStatusList=array('1' => 'Approved','2' => 'Pending');

    public function configuration(){

        if (!Gate::allows('configuration')){ return abort(404); }

        $user  	 = Auth::guard('administrator')->user();
        $user_id = $user->user_id;

        //Read all config key
        $allKey       = Config::get('user-configuration');
        $configArray  = array();
        $prepareArray = array();

        foreach($allKey as $key=>$value){

            $manager_setting = AdministratorConfiguration::where('user_id',$user_id)->where('key',$key)->first();

            if($manager_setting===null){

                $returnArray['key']=$key;
                $returnArray['value']=$value['value'];
                $returnArray['label']=$value['label'];

            }else{

                $returnArray['key']  =$manager_setting->key;
                $returnArray['value']=$manager_setting->value;
                $returnArray['label']=$manager_setting->label;
            }
            $prepareArray[]=$returnArray;
        }
        $themes=$this->themes;
        $approvalStatusList=$this->approvalStatusList;
        return view('administrator.configuration.configuration',compact('prepareArray','themes','approvalStatusList'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function saveConfiguration(Request $request)
    {
        $user  = Auth::guard('administrator')->user();
        $user_id= $user->user_id;
        if ($request->isMethod('post')){

            $allvalue = $request->except('_token');

            foreach($allvalue as $key=>$value){

                $settingObj = AdministratorConfiguration::where('user_id',$user_id)->where('key',$key)->first();

                if($settingObj===null){
                    $row=array('user_id'=>$user_id,'key'=>$key,'value'=>$value);
                    AdministratorConfiguration::create($row);

                }else {
                    $row=array('user_id'=>$user_id,'value'=>$value);
                    $settingObj->update($row);
                }
            }
        }
        return redirect()->route('administrator.configuration');
    }
}
