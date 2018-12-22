<?php
namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use App\Classes\Models\Administrator\Administrator;
use Illuminate\Support\Facades\Auth;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
		
		Gate::define('user_management', function ($user) {
		   return $user->checkPermission($user,'user_management');
		});
		Gate::define('user_management_add', function ($user) {
		   return $user->checkPermission($user,'user_management_add');
		});
		Gate::define('user_management_edit', function ($user) {
		   return $user->checkPermission($user,'user_management_edit');
		});
		Gate::define('user_management_delete', function ($user) {
		   return $user->checkPermission($user,'user_management_delete');
		});
		
		
		Gate::define('members', function ($user) {
		   return $user->checkPermission($user,'members');
		});	
		Gate::define('member_add', function ($user) {
		   return $user->checkPermission($user,'member_add');
		});
		Gate::define('member_edit', function ($user) {
		   return $user->checkPermission($user,'member_edit');
		});
		Gate::define('member_delete', function ($user) {
		   return $user->checkPermission($user,'member_delete');
		});

		
		Gate::define('events', function ($user) {
		   return $user->checkPermission($user,'events');
		});
		Gate::define('event_add', function ($user) {
		   return $user->checkPermission($user,'event_add');
		});
		Gate::define('event_edit', function ($user) {
		   return $user->checkPermission($user,'event_edit');
		});
		Gate::define('event_delete', function ($user) {
		   return $user->checkPermission($user,'event_delete');
		});

		
		Gate::define('instructors', function ($user) {
		   return $user->checkPermission($user,'instructors');
		});
		Gate::define('instructor_add', function ($user) {
		   return $user->checkPermission($user,'instructor_add');
		});
		Gate::define('instructor_edit', function ($user) {
		   return $user->checkPermission($user,'instructor_edit');
		});
		Gate::define('instructor_delete', function ($user) {
		   return $user->checkPermission($user,'instructor_delete');
		});
		Gate::define('instructor_review_submissions', function ($user) {
		   return $user->checkPermission($user,'instructor_review_submissions');
		});

	
		Gate::define('academies', function ($user) {
		   return $user->checkPermission($user,'academies');
		});
		Gate::define('academie_add', function ($user) {
		   return $user->checkPermission($user,'academie_add');
		});
		Gate::define('academie_edit', function ($user) {
		   return $user->checkPermission($user,'academie_edit');
		});
		Gate::define('academie_delete', function ($user) {
		   return $user->checkPermission($user,'academie_delete');
		});

		
		Gate::define('teams', function ($user) {
		   return $user->checkPermission($user,'teams');
		});
		Gate::define('team_add', function ($user) {
		   return $user->checkPermission($user,'team_add');
		});
		Gate::define('team_edit', function ($user) {
		   return $user->checkPermission($user,'team_edit');
		});
		Gate::define('team_delete', function ($user) {
		   return $user->checkPermission($user,'team_delete');
		});


		Gate::define('tryouts', function ($user) {
		   return $user->checkPermission($user,'tryouts');
		});
		Gate::define('tryout_add', function ($user) {
		   return $user->checkPermission($user,'tryout_add');
		});
		Gate::define('tryout_edit', function ($user) {
		   return $user->checkPermission($user,'tryout_edit');
		});
		Gate::define('tryout_delete', function ($user) {
		   return $user->checkPermission($user,'tryout_delete');
		});


		Gate::define('tryout_agegroup', function ($user) {
		   return $user->checkPermission($user,'tryout_agegroup');
		});


		Gate::define('tryout_agegroup_positions', function ($user) {
		   return $user->checkPermission($user,'tryout_agegroup_positions');
		});
		Gate::define('tryout_agegroup_position_add', function ($user) {
		   return $user->checkPermission($user,'tryout_agegroup_position_add');
		});
		Gate::define('tryout_agegroup_position_edit', function ($user) {
		   return $user->checkPermission($user,'tryout_agegroup_position_edit');
		});
		Gate::define('tryout_agegroup_position_delete', function ($user) {
		   return $user->checkPermission($user,'tryout_agegroup_position_delete');
		});

		
		Gate::define('team_photo_gallery', function ($user) {
		   return $user->checkPermission($user,'team_photo_gallery');
		});
		Gate::define('team_photo_gallery_add', function ($user) {
		   return $user->checkPermission($user,'team_photo_gallery_add');
		});
		Gate::define('team_photo_gallery_edit', function ($user) {
		   return $user->checkPermission($user,'team_photo_gallery_edit');
		});
		Gate::define('team_photo_gallery_delete', function ($user) {
		   return $user->checkPermission($user,'team_photo_gallery_delete');
		});


		Gate::define('team_photo_gallery_images', function ($user) {
		   return $user->checkPermission($user,'team_photo_gallery_images');
		});
		Gate::define('team_photo_gallery_images_add', function ($user) {
		   return $user->checkPermission($user,'team_photo_gallery_images_add');
		});
		Gate::define('team_photo_gallery_images_edit', function ($user) {
		   return $user->checkPermission($user,'team_photo_gallery_images_edit');
		});
		Gate::define('team_photo_gallery_images_delete', function ($user) {
		   return $user->checkPermission($user,'team_photo_gallery_images_delete');
		});


		Gate::define('team_groups', function ($user) {
		   return $user->checkPermission($user,'team_groups');
		});
		Gate::define('team_add_group', function ($user) {
		   return $user->checkPermission($user,'team_add_group');
		});
		Gate::define('team_edit_group', function ($user) {
		   return $user->checkPermission($user,'team_edit_group');
		});
		Gate::define('team_delete_group', function ($user) {
		   return $user->checkPermission($user,'team_delete_group');
		});
			

		Gate::define('tournament_organizations', function ($user) {
		   return $user->checkPermission($user,'tournament_organizations');
		});
		Gate::define('tournament_organization_add', function ($user) {
		   return $user->checkPermission($user,'tournament_organization_add');
		});
		Gate::define('tournament_organization_edit', function ($user) {
		   return $user->checkPermission($user,'tournament_organization_edit');
		});
		Gate::define('tournament_organization_delete', function ($user) {
		   return $user->checkPermission($user,'tournament_organization_delete');
		});


		Gate::define('tournaments', function ($user) {
		   return $user->checkPermission($user,'tournaments');
		});
		Gate::define('tournament_add', function ($user) {
		   return $user->checkPermission($user,'tournament_add');
		});
		Gate::define('tournament_edit', function ($user) {
		   return $user->checkPermission($user,'tournament_edit');
		});
		Gate::define('tournament_delete', function ($user) {
		   return $user->checkPermission($user,'tournament_delete');
		});

		
		Gate::define('coaches_needed', function ($user) {
		   return $user->checkPermission($user,'coaches_needed');
		});
		Gate::define('coaches_needed_add', function ($user) {
		   return $user->checkPermission($user,'coaches_needed_add');
		});
		Gate::define('coaches_needed_edit', function ($user) {
		   return $user->checkPermission($user,'coaches_needed_edit');
		});
		Gate::define('coaches_needed_delete', function ($user) {
		   return $user->checkPermission($user,'coaches_needed_delete');
		});

		
		Gate::define('players_looking_for_a_team', function ($user) {
		   return $user->checkPermission($user,'players_looking_for_a_team');
		});
		Gate::define('players_looking_for_a_team_add', function ($user) {
		   return $user->checkPermission($user,'players_looking_for_a_team_add');
		});
		Gate::define('players_looking_for_a_team_edit', function ($user) {
		   return $user->checkPermission($user,'players_looking_for_a_team_edit');
		});
		Gate::define('players_looking_for_a_team_delete', function ($user) {
		   return $user->checkPermission($user,'players_looking_for_a_team_delete');
		});

		
		Gate::define('showcase_organizations', function ($user) {
		   return $user->checkPermission($user,'showcase_organizations');
		});
		Gate::define('showcase_organization_add', function ($user) {
		   return $user->checkPermission($user,'showcase_organization_add');
		});
		Gate::define('showcase_organization_edit', function ($user) {
		   return $user->checkPermission($user,'showcase_organization_edit');
		});
		Gate::define('showcase_organization_delete', function ($user) {
		   return $user->checkPermission($user,'showcase_organization_delete');
		});

		
		Gate::define('camp_or_clinic', function ($user) {
		   return $user->checkPermission($user,'camp_or_clinic');
		});
		Gate::define('camp_or_clinic_add', function ($user) {
		   return $user->checkPermission($user,'camp_or_clinic_add');
		});
		Gate::define('camp_or_clinic_edit', function ($user) {
		   return $user->checkPermission($user,'camp_or_clinic_edit');
		});
		Gate::define('camp_or_clinic_delete', function ($user) {
		   return $user->checkPermission($user,'camp_or_clinic_delete');
		});
		

		Gate::define('showcase_or_prospect', function ($user) {
		   return $user->checkPermission($user,'showcase_or_prospect');
		});
		Gate::define('showcase_or_prospect_add', function ($user) {
		   return $user->checkPermission($user,'showcase_or_prospect_add');
		});
		Gate::define('showcase_or_prospect_edit', function ($user) {
		   return $user->checkPermission($user,'showcase_or_prospect_edit');
		});
		Gate::define('showcase_or_prospect_delete', function ($user) {
		   return $user->checkPermission($user,'showcase_or_prospect_delete');
		});
		

		Gate::define('email_templates', function ($user) {
		   return $user->checkPermission($user,'email_templates');
		});
		Gate::define('email_template_add', function ($user) {
		   return $user->checkPermission($user,'email_template_add');
		});
		Gate::define('email_template_edit', function ($user) {
		   return $user->checkPermission($user,'email_template_edit');
		});
		Gate::define('email_template_delete', function ($user) {
		   return $user->checkPermission($user,'email_template_delete');
		});
		Gate::define('email_template_send', function ($user) {
		   return $user->checkPermission($user,'email_template_send');
		});


		Gate::define('categories', function ($user) {
		   return $user->checkPermission($user,'categories');
		});
		Gate::define('category_add', function ($user) {
		   return $user->checkPermission($user,'category_add');
		});
		Gate::define('category_edit', function ($user) {
		   return $user->checkPermission($user,'category_edit');
		});
		Gate::define('category_delete', function ($user) {
		   return $user->checkPermission($user,'category_delete');
		});


		Gate::define('page_builder', function ($user) {
		   return $user->checkPermission($user,'page_builder');
		});
		Gate::define('page_builder_add', function ($user) {
		   return $user->checkPermission($user,'page_builder_add');
		});
		Gate::define('page_builder_edit', function ($user) {
		   return $user->checkPermission($user,'page_builder_edit');
		});
		Gate::define('page_builder_delete', function ($user) {
		   return $user->checkPermission($user,'page_builder_delete');
		});


		Gate::define('banner_ads_category', function ($user) {
		   return $user->checkPermission($user,'banner_ads_category');
		});
		Gate::define('banner_ads_category_add', function ($user) {
		   return $user->checkPermission($user,'banner_ads_category_add');
		});
		Gate::define('banner_ads_category_edit', function ($user) {
		   return $user->checkPermission($user,'banner_ads_category_edit');
		});
		Gate::define('banner_ads_category_delete', function ($user) {
		   return $user->checkPermission($user,'banner_ads_category_delete');
		});


		Gate::define('banner_ads', function ($user) {
		   return $user->checkPermission($user,'banner_ads');
		});
		Gate::define('banner_ads_add', function ($user) {
		   return $user->checkPermission($user,'banner_ads_add');
		});
		Gate::define('banner_ads_edit', function ($user) {
		   return $user->checkPermission($user,'banner_ads_edit');
		});
		Gate::define('banner_ads_delete', function ($user) {
		   return $user->checkPermission($user,'banner_ads_delete');
		});


		Gate::define('subscribes', function ($user) {
		   return $user->checkPermission($user,'subscribes');
		});
		Gate::define('subscribes_edit', function ($user) {
		   return $user->checkPermission($user,'subscribes_edit');
		});
		Gate::define('subscribes_delete', function ($user) {
		   return $user->checkPermission($user,'subscribes_delete');
		});


		Gate::define('configuration', function ($user) {
		   return $user->checkPermission($user,'configuration');
		});


		Gate::define('banner_trackings', function ($user) {
		   return $user->checkPermission($user,'banner_trackings');
		});
		
		Gate::define('banner_tracking_delete', function ($user) {
		   return $user->checkPermission($user,'banner_tracking_delete');
		});		


		/* For Member Panel*/

        Gate::define('member_academies', function ($member) {
            return $member->checkPermission($member,'academies');
        });

        Gate::define('member_teams', function ($member) {
            return $member->checkPermission($member,'teams');
        });

        Gate::define('member_tournaments', function ($member) {
            return $member->checkPermission($member,'tournaments');
        });

        Gate::define('member_tryouts', function ($member) {
            return $member->checkPermission($member,'tryouts');
        });

        Gate::define('member_showcase', function ($member) {
            return $member->checkPermission($member,'showcase');
        });

    }
}
