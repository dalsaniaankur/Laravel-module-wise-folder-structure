<?php
namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateUsersProfileRequest;
use App\Classes\CropAvatar;

class UsersProfileController extends Controller
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
		$image_upload_path='images/employee/';
		$crop = new \App\Classes\CropAvatar(
		  $image_upload_path,
		  isset($_POST['avatar_src']) ? $_POST['avatar_src'] : null,
		  isset($_POST['avatar_data']) ? $_POST['avatar_data'] : null,
		  isset($_FILES['avatar_file']) ? $_FILES['avatar_file'] : null
		);
		
		$response = array(
		  'state'  => 200,
		  'message' => $crop->getMsg(),
		  'result' => $crop->getResult()
		);
		if(isset($response['result'])&& ($response['result']!='')){
			$user = Auth::user();
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
       // $roles = \App\Role::get()->pluck('title', 'id')->prepend(trans('quickadmin.qa_please_select'), '');
        $user=Auth::guard()->user();
        return view('profile', compact('user'));
    }
	/**
     * Update User Profile.
     *
     * @param  \App\Http\Requests\UpdateUserProfileRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function changeProfile(UpdateUsersProfileRequest $request)
    {
       	$user=Auth::guard()->user();
		$user->update($request->all());
		return redirect()->route('profile')->with('success', 'Profile change Successfully','');
    }
}
