<?php

use Illuminate\Database\Seeder;

class SecurityModuleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       $items = [
	        ['link_id'=>1,'label'=>'User Management','code'=>'user_management','level'=>0,'sort_order'=>1,'parent_link_id'=>0],
		    ['link_id'=>2,'label'=>'Add User','code'=>'user_management_add','level'=>1,'sort_order'=>1,'parent_link_id'=>1],
			['link_id'=>3,'label'=>'Edit User','code'=>'user_management_edit','level'=>1,'sort_order'=>2,'parent_link_id'=>1],
			['link_id'=>4,'label'=>'Delete User','code'=>'user_management_delete','level'=>1,'sort_order'=>3,'parent_link_id'=>1],

		    ['link_id'=>5,'label'=>'Members','code'=>'members','level'=>0,'sort_order'=>'2','parent_link_id'=>'0'],
		    ['link_id'=>6,'label'=>'Add Member','code'=>'member_add','level'=>1,'sort_order'=>'1','parent_link_id'=>5],
			['link_id'=>7,'label'=>'Edit Member','code'=>'member_edit','level'=>1,'sort_order'=>'2','parent_link_id'=>5],
			['link_id'=>8,'label'=>'Delete Member','code'=>'member_delete','level'=>1,'sort_order'=>'3','parent_link_id'=>5],

			['link_id'=>9,'label'=>'Events','code'=>'events','level'=>0,'sort_order'=>'3','parent_link_id'=>'0'],
		    ['link_id'=>10,'label'=>'Add Event','code'=>'event_add','level'=>1,'sort_order'=>'1','parent_link_id'=>9],
			['link_id'=>11,'label'=>'Edit Event','code'=>'event_edit','level'=>1,'sort_order'=>'2','parent_link_id'=>9],
			['link_id'=>12,'label'=>'Delete Event','code'=>'event_delete','level'=>1,'sort_order'=>'3','parent_link_id'=>9],

			['link_id'=>13,'label'=>'Instructors','code'=>'instructors','level'=>0,'sort_order'=>'4','parent_link_id'=>'0'],
		    ['link_id'=>14,'label'=>'Add Instructor','code'=>'instructor_add','level'=>1,'sort_order'=>'1','parent_link_id'=>13],
			['link_id'=>15,'label'=>'Edit Instructor','code'=>'instructor_edit','level'=>1,'sort_order'=>'2','parent_link_id'=>13],
			['link_id'=>16,'label'=>'Delete Instructor','code'=>'instructor_delete','level'=>1,'sort_order'=>'3','parent_link_id'=>13],
			['link_id'=>17,'label'=>'Review Submissions Instructor','code'=>'instructor_review_submissions','level'=>1,'sort_order'=>'4','parent_link_id'=>13],

			['link_id'=>18,'label'=>'Academies','code'=>'academies','level'=>0,'sort_order'=>'5','parent_link_id'=>'0'],
		    ['link_id'=>19,'label'=>'Add Academie','code'=>'academie_add','level'=>1,'sort_order'=>'1','parent_link_id'=>18],
			['link_id'=>20,'label'=>'Edit Academie','code'=>'academie_edit','level'=>1,'sort_order'=>'2','parent_link_id'=>18],
			['link_id'=>21,'label'=>'Delete Academie','code'=>'academie_delete','level'=>1,'sort_order'=>'3','parent_link_id'=>18],

			['link_id'=>22,'label'=>'Teams','code'=>'teams','level'=>0,'sort_order'=>'6','parent_link_id'=>'0'],
		    ['link_id'=>23,'label'=>'Add Team','code'=>'team_add','level'=>1,'sort_order'=>'1','parent_link_id'=>22],
			['link_id'=>24,'label'=>'Edit Team','code'=>'team_edit','level'=>1,'sort_order'=>'2','parent_link_id'=>22],
			['link_id'=>25,'label'=>'Delete Team','code'=>'team_delete','level'=>1,'sort_order'=>'3','parent_link_id'=>22],

			['link_id'=>26,'label'=>'Tryouts','code'=>'tryouts','level'=>0,'sort_order'=>'7','parent_link_id'=>'0'],
		    ['link_id'=>27,'label'=>'Add Tryout','code'=>'tryout_add','level'=>1,'sort_order'=>'1','parent_link_id'=>26],
			['link_id'=>28,'label'=>'Edit Tryout','code'=>'tryout_edit','level'=>1,'sort_order'=>'2','parent_link_id'=>26],
			['link_id'=>29,'label'=>'Delete Tryout','code'=>'tryout_delete','level'=>1,'sort_order'=>'3','parent_link_id'=>26],

			['link_id'=>30,'label'=>'Tryout AgeGroup','code'=>'tryout_agegroup','level'=>0,'sort_order'=>'8','parent_link_id'=>'26'],

			['link_id'=>31,'label'=>'Tryout AgeGroup Positions','code'=>'tryout_agegroup_positions','level'=>0,'sort_order'=>'9','parent_link_id'=>'0'],
			['link_id'=>32,'label'=>'Add Tryout AgeGroup Position','code'=>'tryout_agegroup_position_add','level'=>1,'sort_order'=>'1','parent_link_id'=>31],
			['link_id'=>33,'label'=>'Edit Tryout AgeGroup Position','code'=>'tryout_agegroup_position_edit','level'=>1,'sort_order'=>'2','parent_link_id'=>31],
			['link_id'=>34,'label'=>'Delete Tryout AgeGroup Position','code'=>'tryout_agegroup_position_delete','level'=>1,'sort_order'=>'3','parent_link_id'=>31],

			['link_id'=>35,'label'=>'Photo Gallery Team','code'=>'team_photo_gallery','level'=>1,'sort_order'=>'10','parent_link_id'=>22],
			['link_id'=>36,'label'=>'Add Photo Gallery Team','code'=>'team_photo_gallery_add','level'=>1,'sort_order'=>'1','parent_link_id'=>35],
			['link_id'=>37,'label'=>'Edit Photo Gallery Team','code'=>'team_photo_gallery_edit','level'=>1,'sort_order'=>'2','parent_link_id'=>35],
			['link_id'=>38,'label'=>'Delete Photo Gallery Team','code'=>'team_photo_gallery_delete','level'=>1,'sort_order'=>'3','parent_link_id'=>35],

		    ['link_id'=>39,'label'=>'Photo Gallery Images Team','code'=>'team_photo_gallery_images','level'=>1,'sort_order'=>'11','parent_link_id'=>35],
			['link_id'=>40,'label'=>'Add Photo Gallery Images Team','code'=>'team_photo_gallery_images_add','level'=>1,'sort_order'=>'1','parent_link_id'=>39],
			['link_id'=>41,'label'=>'Edit Photo Gallery Images Team','code'=>'team_photo_gallery_images_edit','level'=>1,'sort_order'=>'2','parent_link_id'=>39],
			['link_id'=>42,'label'=>'Delete Photo Gallery Images Team','code'=>'team_photo_gallery_images_delete','level'=>1,'sort_order'=>'3','parent_link_id'=>39],

			['link_id'=>43,'label'=>'Group Team','code'=>'team_groups','level'=>1,'sort_order'=>'12','parent_link_id'=>22],
			['link_id'=>44,'label'=>'Add Group Team','code'=>'team_add_group','level'=>1,'sort_order'=>'1','parent_link_id'=>43],
			['link_id'=>45,'label'=>'Edit Group Team','code'=>'team_edit_group','level'=>1,'sort_order'=>'2','parent_link_id'=>43],
			['link_id'=>46,'label'=>'Delete Group Team','code'=>'team_delete_group','level'=>1,'sort_order'=>'3','parent_link_id'=>43],

		    ['link_id'=>47,'label'=>'Tournament Organizations','code'=>'tournament_organizations','level'=>0,'sort_order'=>'13','parent_link_id'=>'0'],
		    ['link_id'=>48,'label'=>'Add Tournament Organization','code'=>'tournament_organization_add','level'=>1,'sort_order'=>'1','parent_link_id'=>47],
			['link_id'=>49,'label'=>'Edit Tournament Organization','code'=>'tournament_organization_edit','level'=>1,'sort_order'=>'2','parent_link_id'=>47],
			['link_id'=>50,'label'=>'Delete Tournament Organization','code'=>'tournament_organization_delete','level'=>1,'sort_order'=>'3','parent_link_id'=>47],

			['link_id'=>51,'label'=>'Tournaments','code'=>'tournaments','level'=>0,'sort_order'=>'14','parent_link_id'=>47],
		    ['link_id'=>52,'label'=>'Add Tournament','code'=>'tournament_add','level'=>1,'sort_order'=>'1','parent_link_id'=>51],
			['link_id'=>53,'label'=>'Edit Tournament','code'=>'tournament_edit','level'=>1,'sort_order'=>'2','parent_link_id'=>51],
			['link_id'=>54,'label'=>'Delete Tournament','code'=>'tournament_delete','level'=>1,'sort_order'=>'3','parent_link_id'=>51],

			['link_id'=>55,'label'=>'Coaches Needed','code'=>'coaches_needed','level'=>0,'sort_order'=>'15','parent_link_id'=>'0'],
		    ['link_id'=>56,'label'=>'Add Coaches Needed','code'=>'coaches_needed_add','level'=>1,'sort_order'=>'1','parent_link_id'=>55],
			['link_id'=>57,'label'=>'Edit Coaches Needed','code'=>'coaches_needed_edit','level'=>1,'sort_order'=>'2','parent_link_id'=>55],
			['link_id'=>58,'label'=>'Delete Coaches Needed','code'=>'coaches_needed_delete','level'=>1,'sort_order'=>'3','parent_link_id'=>55],

		   	['link_id'=>59,'label'=>'Players Looking For A Team','code'=>'players_looking_for_a_team','level'=>0,'sort_order'=>'16','parent_link_id'=>'0'],
		    ['link_id'=>60,'label'=>'Add Coaches Players Looking For A Team','code'=>'players_looking_for_a_team_add','level'=>1,'sort_order'=>'1','parent_link_id'=>59],
			['link_id'=>61,'label'=>'Edit Players Looking For A Team','code'=>'players_looking_for_a_team_edit','level'=>1,'sort_order'=>'2','parent_link_id'=>59],
			['link_id'=>62,'label'=>'Delete Players Looking For A Team','code'=>'players_looking_for_a_team_delete','level'=>1,'sort_order'=>'3','parent_link_id'=>59],

			['link_id'=>63,'label'=>'Showcase Organizations','code'=>'showcase_organizations','level'=>0,'sort_order'=>'17','parent_link_id'=>'0'],
		    ['link_id'=>64,'label'=>'Add Showcase Organization','code'=>'showcase_organization_add','level'=>1,'sort_order'=>'1','parent_link_id'=>63],
			['link_id'=>65,'label'=>'Edit Showcase Organization','code'=>'showcase_organization_edit','level'=>1,'sort_order'=>'2','parent_link_id'=>63],
			['link_id'=>66,'label'=>'Delete Showcase Organization','code'=>'showcase_organization_delete','level'=>1,'sort_order'=>'3','parent_link_id'=>63],

		   	['link_id'=>67,'label'=>'Camp / Clinic','code'=>'camp_or_clinic','level'=>0,'sort_order'=>'18','parent_link_id'=>63],
			['link_id'=>68,'label'=>'Add Camp / Clinic','code'=>'camp_or_clinic_add','level'=>0,'sort_order'=>'1','parent_link_id'=>67],
			['link_id'=>69,'label'=>'Edit Camp / Clinic','code'=>'camp_or_clinic_edit','level'=>0,'sort_order'=>'2','parent_link_id'=>67],
			['link_id'=>70,'label'=>'Delete Camp / Clinic','code'=>'camp_or_clinic_delete','level'=>0,'sort_order'=>'3','parent_link_id'=>67],

			['link_id'=>71,'label'=>'Showcase/Prospect','code'=>'showcase_or_prospect','level'=>1,'sort_order'=>'19','parent_link_id'=>63],
			['link_id'=>72,'label'=>'Add Showcase/Prospect','code'=>'showcase_or_prospect_add','level'=>0,'sort_order'=>'1','parent_link_id'=>71],
			['link_id'=>73,'label'=>'Edit Showcase/Prospect','code'=>'showcase_or_prospect_edit','level'=>0,'sort_order'=>'2','parent_link_id'=>71],
			['link_id'=>74,'label'=>'Delete Showcase/Prospect','code'=>'showcase_or_prospect_delete','level'=>0,'sort_order'=>'3','parent_link_id'=>71],

			['link_id'=>75,'label'=>'Email Template','code'=>'email_templates','level'=>1,'sort_order'=>'20','parent_link_id'=>0],
			['link_id'=>76,'label'=>'Add Email Template','code'=>'email_template_add','level'=>0,'sort_order'=>'1','parent_link_id'=>75],
			['link_id'=>77,'label'=>'Edit Email Template','code'=>'email_template_edit','level'=>0,'sort_order'=>'2','parent_link_id'=>75],
			['link_id'=>78,'label'=>'Delete Email Template','code'=>'email_template_delete','level'=>0,'sort_order'=>'3','parent_link_id'=>75],
			['link_id'=>79,'label'=>'Send Email Template','code'=>'email_template_send','level'=>0,'sort_order'=>'4','parent_link_id'=>75],

			['link_id'=>80,'label'=>'Categories','code'=>'categories','level'=>1,'sort_order'=>'21','parent_link_id'=>0],
			['link_id'=>81,'label'=>'Add Category','code'=>'category_add','level'=>0,'sort_order'=>'1','parent_link_id'=>80],
			['link_id'=>82,'label'=>'Edit Category','code'=>'category_edit','level'=>0,'sort_order'=>'2','parent_link_id'=>80],
			['link_id'=>83,'label'=>'Delete Category','code'=>'category_delete','level'=>0,'sort_order'=>'3','parent_link_id'=>80],

			['link_id'=>84,'label'=>'Page Builder','code'=>'page_builder','level'=>1,'sort_order'=>'22','parent_link_id'=>0],
			['link_id'=>85,'label'=>'Add Page Builder','code'=>'page_builder_add','level'=>0,'sort_order'=>'1','parent_link_id'=>84],
			['link_id'=>86,'label'=>'Edit Page Builder','code'=>'page_builder_edit','level'=>0,'sort_order'=>'2','parent_link_id'=>84],
			['link_id'=>87,'label'=>'Delete Page Builder','code'=>'page_builder_delete','level'=>0,'sort_order'=>'3','parent_link_id'=>84],

			['link_id'=>88,'label'=>'Banner Ads Category','code'=>'banner_ads_category','level'=>1,'sort_order'=>'23','parent_link_id'=>0],
			['link_id'=>89,'label'=>'Add Banner Ads Category','code'=>'banner_ads_category_add','level'=>0,'sort_order'=>'1','parent_link_id'=>88],
			['link_id'=>90,'label'=>'Edit Banner Ads Category','code'=>'banner_ads_category_edit','level'=>0,'sort_order'=>'2','parent_link_id'=>88],
			['link_id'=>91,'label'=>'Delete Banner Ads Category','code'=>'banner_ads_category_delete','level'=>0,'sort_order'=>'3','parent_link_id'=>88],

			['link_id'=>92,'label'=>'Banner Ads','code'=>'banner_ads','level'=>1,'sort_order'=>'24','parent_link_id'=>0],
			['link_id'=>93,'label'=>'Add Banner Ads','code'=>'banner_ads_add','level'=>0,'sort_order'=>'1','parent_link_id'=>92],
			['link_id'=>94,'label'=>'Edit Banner Ads','code'=>'banner_ads_edit','level'=>0,'sort_order'=>'2','parent_link_id'=>92],
			['link_id'=>95,'label'=>'Delete Banner Ads','code'=>'banner_ads_delete','level'=>0,'sort_order'=>'3','parent_link_id'=>92],

			['link_id'=>96,'label'=>'Subscribes','code'=>'subscribes','level'=>1,'sort_order'=>'25','parent_link_id'=>0],
			['link_id'=>97,'label'=>'Edit Subscribes','code'=>'subscribes_edit','level'=>0,'sort_order'=>'2','parent_link_id'=>96],
			['link_id'=>98,'label'=>'Delete Subscribes','code'=>'subscribes_delete','level'=>0,'sort_order'=>'3','parent_link_id'=>96],

			['link_id'=>99,'label'=>'Configuration','code'=>'configuration','level'=>1,'sort_order'=>'26','parent_link_id'=>0],

			['link_id'=>100,'label'=>'Banner Trackings','code'=>'banner_trackings','level'=>1,'sort_order'=>'27','parent_link_id'=>0],
			['link_id'=>101,'label'=>'Delete Banner Tracking','code'=>'banner_tracking_delete','level'=>0,'sort_order'=>'3','parent_link_id'=>100],

		];
        foreach ($items as $item) {
            \App\Classes\Models\SecurityModule\SecurityModule::create($item);
        }
    }
}
