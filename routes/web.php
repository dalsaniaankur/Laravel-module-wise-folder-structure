<?php
Route::get('/','HomeController@index');

Route::get('administrator/', function () { return redirect('/administrator/home'); });

$this->get('administrator_login', 'Administrator\Auth\LoginController@showLoginForm')->name('administrator_login');
$this->post('administrator_login', 'Administrator\Auth\LoginController@login');
$this->post('administrator_logout', 'Administrator\Auth\LoginController@logout')->name('administrator_logout');

//Super Admin Update Profile Route
$this->get('administrator_profile', 'Administrator\Profile\IndexController@showProfile')->name('administrator_profile')->middleware('administrator');
$this->post('administrator_change_profile', ['uses' => 'Administrator\Profile\IndexController@changeProfile', 'as' => 'administrator_change_profile'])
->middleware('administrator');

//Admin Change profile picture route
$this->post('administrator_change_profile_picture', ['uses' => 'Administrator\Profile\IndexController@changeProfilePicture', 'as' => 'administrator_change_profile_picture'])->middleware('administrator');

//Super Admin registration Routes
$this->get('administrator_register', 'Administrator\Auth\RegisterController@showRegistrationForm')->name('administrator_register');
$this->post('administrator_register', 'Administrator\Auth\RegisterController@register');

//Super Admin Change Password Routes
$this->get('administrator_change_password', 'Administrator\Auth\ChangePasswordController@showChangePasswordForm')->name('administrator.auth.change_password');
$this->patch('administrator_change_password', 'Administrator\Auth\ChangePasswordController@changePassword')->name('administrator.auth.change_password');

//Super Admin Password Reset Routes
$this->get('administrator_password/reset', 'Administrator\Auth\ForgotPasswordController@showLinkRequestForm')->name('administrator.password.request');//ok
$this->post('administrator_password/email', 'Administrator\Auth\ForgotPasswordController@sendResetLinkEmail')->name('administrator.password.email');
$this->get('administrator_password/reset/{token}', 'Administrator\Auth\ResetPasswordController@showResetForm')->name('administrator.password.reset');
$this->post('administrator_password/reset', 'Administrator\Auth\ResetPasswordController@reset')->name('administrator.password.reset');//ok


Route::get('member_profile', 'Member\Profile\IndexController@showProfile')->name('member_profile')->middleware('member');
Route::post('member_change_profile', ['uses' => 'Member\Profile\IndexController@changeProfile', 'as' => 'member_change_profile'])
    ->middleware('member');

Route::group(['middleware' => ['administrator'], 'prefix' => 'administrator', 'as' => 'administrator.'],function () {

    Route::get('/home', 'Administrator\HomeController@index');

   Route::get('configuration','Administrator\Configuration\IndexController@configuration')->name('configuration');
   Route::post('configuration','Administrator\Configuration\IndexController@saveConfiguration')->name('configuration');

   Route::resource('members', 'Administrator\Members\IndexController'); 
   Route::post('members_save', ['uses' => 'Administrator\Members\IndexController@save','as' =>'members.save']);
   //Done
   Route::resource('users', 'Administrator\User\IndexController');
   Route::post('user_save', ['uses' => 'Administrator\User\IndexController@save', 'as' => 'user.save']);
  //done
   Route::resource('events', 'Administrator\Events\IndexController'); 
   Route::post('event_save', ['uses' => 'Administrator\Events\IndexController@save', 'as' => 'event.save']);
   Route::get('events_duplicate/{event_id}', ['uses' => 'Administrator\Events\IndexController@duplicate', 'as' => 'event.duplicate']);

   Route::post('export_csv', ['uses' => 'Administrator\Export\IndexController@postExportCsv']);
   Route::get('download_csv', ['uses' => 'Administrator\Export\IndexController@getDownloadCsv']);
   Route::post('import_csv', ['uses' => 'Administrator\Import\IndexController@postImportCsv']);

   //done
   Route::resource('instructors', 'Administrator\Instructor\IndexController'); 
   Route::post('instructor_save', ['uses' => 'Administrator\Instructor\IndexController@save', 'as' => 'instructor.save']);
   Route::get('instructors_duplicate/{instructor_id}', ['uses' => 'Administrator\Instructor\IndexController@duplicate', 'as' => 'instructor.duplicate']);

   //--
   Route::resource('academies', 'Administrator\Academies\IndexController'); 
   Route::post('academy_save', ['uses' => 'Administrator\Academies\IndexController@save', 'as' => 'academy.save']);
   Route::get('academies_duplicate/{academy_id}', ['uses' => 'Administrator\Academies\IndexController@duplicate', 'as' => 'academy.duplicate']);

   Route::resource('categories', 'Administrator\Categories\IndexController'); 
   Route::post('categories_save', ['uses' => 'Administrator\Categories\IndexController@save', 'as' => 'categories.save']);  
   
   Route::resource('page_builder', 'Administrator\PageBuilder\IndexController'); 
   Route::post('page_builder_save', ['uses' => 'Administrator\PageBuilder\IndexController@save', 'as' => 'page_builder.save']);  
   Route::get('page_builder_duplicate/{page_builder_id}', ['uses' => 'Administrator\PageBuilder\IndexController@duplicate', 'as' => 'page_builder.duplicate']);

   Route::resource('banner_ads_category', 'Administrator\BannerAdsCategory\IndexController');
   Route::post('banner_ads_category_save', ['uses' => 'Administrator\BannerAdsCategory\IndexController@save', 'as' => 'banner_ads_category.save']);  
   
   Route::resource('subscribes', 'Administrator\Subscribes\IndexController'); 
   Route::post('subscribes_save', ['uses' => 'Administrator\Subscribes\IndexController@save', 'as' => 'subscribes.save']);  

   Route::resource('banner_tracking', 'Administrator\BannerTracking\IndexController'); 
   Route::post('banner_tracking_save', ['uses' => 'Administrator\BannerTracking\IndexController@save', 'as' => 'banner_tracking.save']); 

   Route::post('export_csv_for_banner_tracking', ['uses' => 'Administrator\BannerTracking\IndexController@postExportCsv']);   
   Route::get('download_csv_for_banner_tracking/{filename}', ['uses' => 'Administrator\BannerTracking\IndexController@getDownloadCsvForBannerTracking']);   
   
   Route::get('banner_ads/{banner_ads_category_id?}', ['uses' => 'Administrator\BannerAds\IndexController@index', 'as' => 'banner_ads.index']);     
   Route::get('banner_ads/create/{banner_ads_category_id?}', ['uses' => 'Administrator\BannerAds\IndexController@create', 'as' => 'banner_ads.create']); 
   Route::delete('banner_ads/destroy/{id}/{banner_ads_category_id}', ['uses' => 'Administrator\BannerAds\IndexController@destroy', 'as' => 'banner_ads.destroy']);
   Route::get('banner_ads/edit/{id}/{banner_ads_category_id}', ['uses' => 'Administrator\BannerAds\IndexController@edit', 'as' => 'banner_ads.edit']);
   Route::post('banner_ads/save/{banner_ads_category_id}', ['uses' => 'Administrator\BannerAds\IndexController@save', 'as' => 'banner_ads.save']);  

   
   Route::resource('coaches_needed', 'Administrator\CoachesNeeded\IndexController'); 
   Route::post('coaches_needed_save', ['uses' => 'Administrator\CoachesNeeded\IndexController@save', 'as' => 'coaches_needed.save']);
   Route::get('coaches_needed_duplicate/{coaches_needed_id}', ['uses' => 'Administrator\CoachesNeeded\IndexController@duplicate', 'as' => 'coaches_needed.duplicate']);

   Route::resource('lookup_for_player_experience', 'Administrator\LookupForPlayerExperience\IndexController'); 
   Route::post('lookup_for_player_experience_save', ['uses' => 'Administrator\LookupForPlayerExperience\IndexController@save', 'as' => 'lookup_for_player_experience.save']);
   Route::get('lookup_for_player_experience_duplicate/{lookup_for_player_experience_id}', ['uses' => 'Administrator\LookupForPlayerExperience\IndexController@duplicate', 'as' => 'lookup_for_player_experience.duplicate']);
   /**********************************************/
   Route::resource('email_template', 'Administrator\EmailTemplate\IndexController'); 
   Route::post('email_template_save', ['uses' => 'Administrator\EmailTemplate\IndexController@save', 'as' => 'email_template.save']);
   /***********************************************/
   Route::resource('instructor_review', 'Administrator\InstructorReview\IndexController'); 
   Route::post('instructor_review_save', ['uses' => 'Administrator\InstructorReview\IndexController@save', 'as' => 'instructor_review.save']);

   Route::resource('team_review', 'Administrator\TeamReview\IndexController');
   Route::post('team_review_save', ['uses' => 'Administrator\TeamReview\IndexController@save', 'as' => 'team_review.save']);

   Route::resource('academy_review', 'Administrator\AcademyReview\IndexController'); 
   Route::post('academy_review_save', ['uses' => 'Administrator\AcademyReview\IndexController@save', 'as' => 'academy_review.save']);

   Route::get('tryout/{team_id?}', ['uses' => 'Administrator\Tryout\IndexController@index', 'as' => 'tryout.index']);
   Route::get('tryout/create/{team_id?}', ['uses' => 'Administrator\Tryout\IndexController@create', 'as' => 'tryout.create']);
   Route::delete('tryout/destroy/{id}/{team_id}', ['uses' => 'Administrator\Tryout\IndexController@destroy', 'as' => 'tryout.destroy']);
   Route::get('tryout/edit/{id}/{team_id}', ['uses' => 'Administrator\Tryout\IndexController@edit', 'as' => 'tryout.edit']);
   Route::post('tryout/save/{team_id}', ['uses' => 'Administrator\Tryout\IndexController@save', 'as' => 'tryout.save']);
   
   Route::get('send_mail/player','Administrator\SendEmail\IndexController@PlayerSendMail'); 
   Route::get('send_mail/parent','Administrator\SendEmail\IndexController@ParentSendMail'); 
   Route::get('send_mail/coach','Administrator\SendEmail\IndexController@CoachSendMail'); 
   Route::get('send_mail/other','Administrator\SendEmail\IndexController@OtherSendMail'); 
   Route::get('send_mail/instructor','Administrator\SendEmail\IndexController@InstructorSendMail'); 
   Route::get('send_mail/academy','Administrator\SendEmail\IndexController@AcademySendMail'); 
   Route::get('send_mail/team','Administrator\SendEmail\IndexController@TeamSendMail');
   Route::get('send_mail/organizations','Administrator\SendEmail\IndexController@OrganizationSendMail'); 
   Route::get('send_mail/coaches_needed','Administrator\SendEmail\IndexController@CoachesNeededSendMail'); 
   Route::get('send_mail/players_looking_for_team','Administrator\SendEmail\IndexController@PlayersLookingForTeamSendMail');  

   Route::post('fileuploadfortinymce', ['uses' => 'Administrator\SendEmail\IndexController@FileuploadForTinymce', 'as' => 'fileuploadfortinymce']);
   
   Route::resource('teams', 'Administrator\Team\IndexController');
   Route::post('team_save', ['uses' => 'Administrator\Team\IndexController@save', 'as' => 'team_save.save']);
   Route::get('team_duplicate/{team_id}', ['uses' => 'Administrator\Team\IndexController@duplicate', 'as' => 'team.duplicate']);

   Route::resource('gallery', 'Administrator\Gallery\IndexController'); 
   Route::post('gallery_save', ['uses' => 'Administrator\Gallery\IndexController@save', 'as' => 'gallery.save']);

   Route::resource('gallery_images', 'Administrator\GalleryImages\IndexController'); 
   Route::post('gallery_images_save', ['uses' => 'Administrator\GalleryImages\IndexController@save', 'as' => 'gallery_images.save']);
   
   Route::resource('tournament_organizations', 'Administrator\TournamentOrganization\IndexController');
   Route::post('tournament_organization_save', ['uses' => 'Administrator\TournamentOrganization\IndexController@save', 'as' => 'tournament_organization.save']);
   Route::get('tournament_organization_duplicate/{tournament_organization_id?}', ['uses' => 'Administrator\TournamentOrganization\IndexController@duplicate', 'as' => 'tournament_organization.duplicate']);
   
   Route::get('tournament/{tournament_organization_id?}', ['uses' => 'Administrator\Tournament\IndexController@index', 'as' => 'tournament.index']);     
   Route::get('tournament/create/{tournament_organization_id?}', ['uses' => 'Administrator\Tournament\IndexController@create', 'as' => 'tournament.create']); 
   Route::delete('tournament/destroy/{id}/{tournament_organization_id}', ['uses' => 'Administrator\Tournament\IndexController@destroy', 'as' => 'tournament.destroy']);
   Route::get('tournament/edit/{id}/{tournament_organization_id}', ['uses' => 'Administrator\Tournament\IndexController@edit', 'as' => 'tournament.edit']);
   Route::post('tournament/save/{tournament_organization_id}', ['uses' => 'Administrator\Tournament\IndexController@save', 'as' => 'tournament.save']);

   Route::get('tournament_duplicate/{id}/{tournament_organization_id?}', ['uses' => 'Administrator\Tournament\IndexController@duplicate', 'as' => 'tournament.duplicate']);

   Route::resource('showcase_organization', 'Administrator\ShowcaseOrganization\IndexController');
   Route::post('showcase_organization_save', ['uses' => 'Administrator\ShowcaseOrganization\IndexController@save', 'as' => 'showcase_organization.save']);
   Route::get('showcase_organization_duplicate/{showcase_organization_id}', ['uses' => 'Administrator\ShowcaseOrganization\IndexController@duplicate', 'as' => 'showcase_organization.duplicate']);

   Route::resource('camp_or_clinic', 'Administrator\CampOrClinic\IndexController'); 
   Route::post('camp_or_clinic_save', ['uses' => 'Administrator\CampOrClinic\IndexController@save', 'as' => 'camp_or_clinic.save']);
   Route::get('camp_or_clinic_duplicate/{camp_clinic_id}', ['uses' => 'Administrator\CampOrClinic\IndexController@duplicate', 'as' => 'camp_or_clinic.duplicate']);

   Route::resource('showcase_or_prospect', 'Administrator\ShowcaseOrProspect\IndexController'); 
   Route::post('showcase_or_prospect_save', ['uses' => 'Administrator\ShowcaseOrProspect\IndexController@save', 'as' => 'showcase_or_prospect.save']);
   Route::get('showcase_or_prospect_duplicate/{showcase_or_prospect_id}', ['uses' => 'Administrator\ShowcaseOrProspect\IndexController@duplicate', 'as' => 'showcase_or_prospect.duplicate']);

   Route::resource('showcase_age_groups', 'Administrator\ShowcaseAgeGroup\IndexController');
   Route::post('showcase_age_group_save', ['uses' => 'Administrator\ShowcaseAgeGroup\IndexController@save', 'as' => 'showcase_age_group.save']);

   Route::resource('team_group', 'Administrator\TeamGroup\IndexController');
   Route::post('team_group_save', ['uses' => 'Administrator\TeamGroup\IndexController@save', 'as' => 'team_group.save']);
   Route::get('team_group_unlink/{team_group_id}/{team_id}',['as' =>'team_group.unlink_team',
   'uses' => 'Administrator\TeamGroup\IndexController@unlinkTeam']);
   
   Route::get('tryout/{team_id?}', ['uses' => 'Administrator\Tryout\IndexController@index', 'as' => 'tryout.index']);
   Route::get('tryout/create/{team_id?}', ['uses' => 'Administrator\Tryout\IndexController@create', 'as' => 'tryout.create']);
   Route::delete('tryout/destroy/{id}/{team_id}', ['uses' => 'Administrator\Tryout\IndexController@destroy', 'as' => 'tryout.destroy']);
   Route::get('tryout/edit/{id}/{team_id}', ['uses' => 'Administrator\Tryout\IndexController@edit', 'as' => 'tryout.edit']);
   Route::post('tryout/save/{team_id}', ['uses' => 'Administrator\Tryout\IndexController@save', 'as' => 'tryout.save']);
   Route::get('tryout_duplicate/{tryout_id}/{team_id}', ['uses' => 'Administrator\Tryout\IndexController@duplicate', 'as' => 'tryout.duplicate']);

   Route::get('agegroup/{tryout_id?}', ['uses' => 'Administrator\Tryout\AgeGroup\IndexController@index', 'as' => 'agegroup.index']);     


   Route::get('agegroup_position/{age_group_id}/{tryout_id}', ['uses' => 'Administrator\Tryout\AgeGroup\Position\IndexController@index', 'as' => 'position.index']);     
   Route::get('agegroup_position/create/{age_group_id}/{tryout_id}', ['uses' => 'Administrator\Tryout\AgeGroup\Position\IndexController@create', 'as' => 'position.create']); 
   Route::delete('agegroup_position/destroy/{id}/{age_group_id}/{tryout_id}', ['uses' => 'Administrator\Tryout\AgeGroup\Position\IndexController@destroy', 'as' => 'position.destroy']);
   Route::get('agegroup_position/edit/{id}/{age_group_id}/{tryout_id}', ['uses' => 'Administrator\Tryout\AgeGroup\Position\IndexController@edit', 'as' => 'position.edit']);
   Route::post('agegroup_position/save/{age_group_id}/{tryout_id}', ['uses' => 'Administrator\Tryout\AgeGroup\Position\IndexController@save', 'as' => 'position.save']);
   
   Route::post('get_google_longitude_latitude', ['uses' => 'Administrator\Academies\IndexController@getGoogleLongitudeLatitude', 'as' => 'get_google_longitude_latitude']);

   Route::get('getcitydropdown', ['uses' => 'Administrator\Academies\IndexController@getCityDropdown', 'as' => 'getcitydropdown']);
      
});

Route::group(['middleware' => ['member'], 'prefix' => 'member', 'as' => 'member.'],function () {

    Route::get('/home', 'Member\HomeController@index');
    Route::post('get_google_longitude_latitude', ['uses' => 'Administrator\Academies\IndexController@getGoogleLongitudeLatitude', 'as' => 'get_google_longitude_latitude']);
    Route::get('getcitydropdown', ['uses' => 'Administrator\Academies\IndexController@getCityDropdown', 'as' => 'getcitydropdown']);

    Route::resource('tournament_organizations', 'Member\TournamentOrganization\IndexController');
    Route::post('tournament_organization_save', ['uses' => 'Member\TournamentOrganization\IndexController@save', 'as' => 'tournament_organization.save']);

    Route::get('tournament/{tournament_organization_id?}', ['uses' => 'Member\Tournament\IndexController@index', 'as' => 'tournament.index']);
    Route::get('tournament/create/{tournament_organization_id?}', ['uses' => 'Member\Tournament\IndexController@create', 'as' => 'tournament.create']);
    Route::delete('tournament/destroy/{id}/{tournament_organization_id}', ['uses' => 'Member\Tournament\IndexController@destroy', 'as' => 'tournament.destroy']);
    Route::get('tournament/edit/{id}/{tournament_organization_id}', ['uses' => 'Member\Tournament\IndexController@edit', 'as' => 'tournament.edit']);
    Route::post('tournament/save/{tournament_organization_id}', ['uses' => 'Member\Tournament\IndexController@save', 'as' => 'tournament.save']);

    Route::resource('instructors', 'Member\Instructor\IndexController');
    Route::post('instructor_save', ['uses' => 'Member\Instructor\IndexController@save', 'as' => 'instructor.save']);

    Route::resource('instructor_review', 'Member\InstructorReview\IndexController');
    Route::post('instructor_review_save', ['uses' => 'Member\InstructorReview\IndexController@save', 'as' => 'instructor_review.save']);

    Route::resource('academies', 'Member\Academies\IndexController');
    Route::post('academy_save', ['uses' => 'Member\Academies\IndexController@save', 'as' => 'academy.save']);

    Route::resource('academy_review', 'Member\AcademyReview\IndexController');
    Route::post('academy_review_save', ['uses' => 'Member\AcademyReview\IndexController@save', 'as' => 'academy_review.save']);

    Route::resource('coaches_needed', 'Member\CoachesNeeded\IndexController');
    Route::post('coaches_needed_save', ['uses' => 'Member\CoachesNeeded\IndexController@save', 'as' => 'coaches_needed.save']);

    Route::resource('lookup_for_player_experience', 'Member\LookupForPlayerExperience\IndexController');
    Route::post('lookup_for_player_experience_save', ['uses' => 'Member\LookupForPlayerExperience\IndexController@save', 'as' => 'lookup_for_player_experience.save']);

    Route::resource('showcase_organization', 'Member\ShowcaseOrganization\IndexController');
    Route::post('showcase_organization_save', ['uses' => 'Member\ShowcaseOrganization\IndexController@save', 'as' => 'showcase_organization.save']);

    Route::resource('camp_or_clinic', 'Member\CampOrClinic\IndexController');
    Route::post('camp_or_clinic_save', ['uses' => 'Member\CampOrClinic\IndexController@save', 'as' => 'camp_or_clinic.save']);

    Route::resource('showcase_or_prospect', 'Member\ShowcaseOrProspect\IndexController');
    Route::post('showcase_or_prospect_save', ['uses' => 'Member\ShowcaseOrProspect\IndexController@save', 'as' => 'showcase_or_prospect.save']);

    Route::resource('showcase_age_groups', 'Member\ShowcaseAgeGroup\IndexController');
    Route::post('showcase_age_group_save', ['uses' => 'Member\ShowcaseAgeGroup\IndexController@save', 'as' => 'showcase_age_group.save']);

    Route::resource('teams', 'Member\Team\IndexController');
    Route::post('team_save', ['uses' => 'Member\Team\IndexController@save', 'as' => 'team_save.save']);

    Route::resource('team_review', 'Member\TeamReview\IndexController');
    Route::post('team_review_save', ['uses' => 'Member\TeamReview\IndexController@save', 'as' => 'team_review.save']);

    Route::get('tryout/{team_id?}', ['uses' => 'Member\Tryout\IndexController@index', 'as' => 'tryout.index']);
    Route::get('tryout/create/{team_id?}', ['uses' => 'Member\Tryout\IndexController@create', 'as' => 'tryout.create']);
    Route::delete('tryout/destroy/{id}/{team_id}', ['uses' => 'Member\Tryout\IndexController@destroy', 'as' => 'tryout.destroy']);
    Route::get('tryout/edit/{id}/{team_id}', ['uses' => 'Member\Tryout\IndexController@edit', 'as' => 'tryout.edit']);
    Route::post('tryout/save/{team_id}', ['uses' => 'Member\Tryout\IndexController@save', 'as' => 'tryout.save']);

    Route::get('agegroup/{tryout_id?}', ['uses' => 'Member\Tryout\AgeGroup\IndexController@index', 'as' => 'agegroup.index']);

    Route::get('agegroup_position/{age_group_id}/{tryout_id}', ['uses' => 'Member\Tryout\AgeGroup\Position\IndexController@index', 'as' => 'position.index']);
    Route::get('agegroup_position/create/{age_group_id}/{tryout_id}', ['uses' => 'Member\Tryout\AgeGroup\Position\IndexController@create', 'as' => 'position.create']);
    Route::delete('agegroup_position/destroy/{id}/{age_group_id}/{tryout_id}', ['uses' => 'Member\Tryout\AgeGroup\Position\IndexController@destroy', 'as' => 'position.destroy']);
    Route::get('agegroup_position/edit/{id}/{age_group_id}/{tryout_id}', ['uses' => 'Member\Tryout\AgeGroup\Position\IndexController@edit', 'as' => 'position.edit']);
    Route::post('agegroup_position/save/{age_group_id}/{tryout_id}', ['uses' => 'Member\Tryout\AgeGroup\Position\IndexController@save', 'as' => 'position.save']);

});

//FrontEnd Memebertryout
Route::get('/home', ['uses' => 'HomeController@index', 'as' => 'home']);

Route::get('member_login', ['uses' => 'Auth\Member\LoginController@showLoginForm','as' =>'member_login']);
Route::post('member_login', ['uses' => 'Auth\Member\LoginController@login','as' =>'member_login']);
Route::post('member_logout', ['uses' => 'Auth\Member\LogoutController@logout','as' =>'member_logout']);

Route::get('member_register', ['uses' => 'Auth\Member\RegisterController@showRegistrationForm', 'as' =>'member_register']);
Route::post('member_register', ['uses' => 'Auth\Member\RegisterController@register', 'as' =>'member_register']);

Route::get('member_change_password', ['uses' => 'Auth\Member\ChangePasswordController@showChangePasswordForm', 'as' => 'member_change_password']);
Route::patch('member_change_password', ['uses' => 'Auth\Member\ChangePasswordController@changePassword', 'as' =>'member_change_password' ]);

Route::get('member_reset_password', ['uses' => 'Auth\Member\ForgotPasswordController@showLinkRequestForm', 'as' => 'member_password.reset']);
Route::post('member_email_password', ['uses' => 'Auth\Member\ForgotPasswordController@sendResetLinkEmail', 'as' => 'member_password.email']);

Route::get('member_reset_password/{token}', ['uses' => 'Auth\Member\ResetPasswordController@showResetForm', 'as' => 'member_password.reset']);
Route::post('member_reset_password', ['uses' => 'Auth\Member\ResetPasswordController@reset', 'as' => 'member_password.reset']);

//Member Authentication Routes...
Route::group(['prefix' => 'member', 'as' => 'member.'], function () {
	
   Route::get('profile', ['uses' => 'Profile\IndexController@showProfile', 'as' => 'profile']);
   Route::post('change_profile', ['uses' => 'Profile\IndexController@changeProfile', 'as' => 'change_profile']);
});

Route::get('tournaments', ['uses' => 'Tournament\IndexController@index', 'as' => 'tournaments.index']);
Route::get('teams', ['uses' => 'Team\IndexController@index', 'as' => 'teams.index']);
Route::get('tryouts', ['uses' => 'Tryout\IndexController@index', 'as' => 'tryouts.index']);
Route::get('academies', ['uses' => 'Academies\IndexController@index', 'as' => 'academies.index']);
Route::get('showcases', ['uses' => 'Showcase\IndexController@index', 'as' => 'showcases.index']);
Route::post('google_recaptcha_validation', ['uses' => 'HomeController@postGoogleRecaptchaValidation', 'as' => 'postGoogleRecaptchaValidation']);

Route::get('tournaments/{url_key}', ['uses' => 'Tournament\IndexController@getDetailsPage']);
Route::get('teams/{url_key}', ['uses' => 'Team\IndexController@getDetailsPage']);
Route::get('tryouts/{url_key}', ['uses' => 'Tryout\IndexController@getDetailsPage']);
Route::get('academies/{url_key}', ['uses' => 'Academies\IndexController@getDetailsPage']);
Route::get('showcases/{url_key}', ['uses' => 'Showcase\IndexController@getDetailsPage']);
//Route::get('categories/{url_key}', ['uses' => 'Categories\IndexController@index']);

Route::get('search', ['uses' => 'HomeController@search']);
Route::get('getcitydropdown', ['uses' => 'HomeController@getCityDropdown', 'as' => 'getcitydropdown']);
Route::get('get_citydropdown_for_registration_page', ['uses' => 'HomeController@getCityDropdownForRegistrationPage', 'as' => 'get_citydropdown_for_registration_page']);
Route::post('subscribe_newsletter', ['uses' => 'HomeController@getSubscribeNewsletter', 'as' => 'getSubscribeNewsletter']);

Route::get('importcitystatecsv', ['uses' => 'HomeController@importCityStateCsv']);

Route::post('banner_tracking', ['uses' => 'BannerTracking\IndexController@postBannerTracking']);

Route::post('send_enquiry_mail', ['uses' => 'Enquiry\IndexController@postSendEnquiryMail', 'as' => 'send_enquiry_mail']);

Route::get('{member_url_key}/tournaments', ['uses' => 'Tournament\IndexController@index']);
Route::get('{member_url_key}/teams', ['uses' => 'Team\IndexController@index']);
Route::get('{member_url_key}/tryouts', ['uses' => 'Tryout\IndexController@index']);
Route::get('{member_url_key}/academies', ['uses' => 'Academies\IndexController@index']);
Route::get('{member_url_key}/showcases', ['uses' => 'Showcase\IndexController@index']);

Route::get('{url_key}', function($url_key){

    /* Member Page */
    $memberObj = new \App\Classes\Models\Members\Members();
    $member = $memberObj->checkIsExistMember($url_key);
    if(!empty($member->member_id) && $member->member_id > 0){
        $member_id = $member->member_id;
        return App::call('App\Http\Controllers\MemberFront\IndexController@index', ['member_id' => $member_id, 'url_key' => $url_key]);
    }

    /* Category */
    $categoriesObj = new \App\Classes\Models\Categories\Categories();
    $isCategory = $categoriesObj->checkIsCategory($url_key, $categoryLevel = 0);
    if($isCategory){
        return App::call('App\Http\Controllers\Categories\IndexController@index', ['url_key' => $url_key]);
    }

    /* Page Builder */
    $pageBuilderObj = new \App\Classes\Models\PageBuilder\PageBuilder();
    $isPageBuilder = $pageBuilderObj->checkIsPageBuilder($url_key);
    if($isPageBuilder) {
        return App::call('App\Http\Controllers\PageBuilder\IndexController@index', ['url_key' => $url_key]);
    }

    return abort(404);

});

Route::get('{url_key_1}/{url_key_2}', function($url_key_1, $url_key_2){


    /* Category */
    $categoriesObj = new \App\Classes\Models\Categories\Categories();
    $isCategory = $categoriesObj->checkIsCategory($url_key_2, $categoryLevel = 1);
    if($isCategory){
        return App::call('App\Http\Controllers\Categories\IndexController@index', ['url_key' => $url_key_2]);
    }

    /* Page Builder */
   /* $pageBuilderObj = new \App\Classes\Models\PageBuilder\PageBuilder();
    $isPageBuilder = $pageBuilderObj->checkIsPageBuilder($url_key_1."/".$url_key_2);
    if($isPageBuilder){
        $url_key = $url_key_1."/".$url_key_2;
    }

    if(!empty($url_key)) {
        return App::call('App\Http\Controllers\PageBuilder\IndexController@index', ['url_key' => $url_key]);
    }*/

    return abort(404);

});

//Route::get('{url_key}', ['uses' => 'PageBuilder\IndexController@index']);