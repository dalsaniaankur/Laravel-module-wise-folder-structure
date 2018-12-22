<?php
namespace App\Http\Controllers\Administrator\Profile;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use App\Http\Requests\Administrator\UpdateAdminUserProfileRequest;
use App\Classes\CropAvatar;

class IndexController extends Controller
{
    /**
     * Display a listing of User.
     *
     * @return \Illuminate\Http\Response
     */
	 
    public function index()
    {
		
    }

    /**
     * Show the form for creating new User.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }
    /**
     * Store a newly created User in storage.
     *
     * @param  \App\Http\Requests\StoreUsersRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    	
    }
    /**
     * Show the form for editing User.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
       
    }

    /**
     * Update User in storage.
     *
     * @param  \App\Http\Requests\UpdateUsersRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
       
    }

    /**
     * Display User.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
       
    }


    /**
     * Remove User from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
      
    }
	
	public function changeProfilePicture(Request $request)
    {
		$image_upload_path='images/administrator/';
		$crop = new CropAvatar(
		  $image_upload_path,
		  isset($_POST['avatar_src']) ? $_POST['avatar_src'] : null,
		  isset($_POST['avatar_data']) ? $_POST['avatar_data'] : null,
		  isset($_FILES['avatar_file']) ? $_FILES['avatar_file'] : null
		);
		
		$response = array(
		  'state'  => 200,
		  'message' => $crop -> getMsg(),
		  'result' => $crop -> getResult()
		);
		if(isset($response['result'])&& ($response['result']!='')){
			$user = Auth::guard('administrator')->user();
			$user->profile_picture = $response['result'];
			$user->save();
		}
		echo json_encode($response);
	}
	
   /**
     * Show the form for profile.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showProfile()
    {
		$user=Auth::guard('administrator')->user();
        return view('administrator.profile', compact('user'));
    }
	/**
     * Update User Profile.
     *
     * @param  \App\Http\Requests\UpdateUserProfileRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function changeProfile(UpdateAdminUserProfileRequest $request)
    {
		$user=Auth::guard('administrator')->user();
		$user->update($request->all());
		return redirect()->route('administrator_profile')->with('success', 'Profile change Successfully','');
    }
}
