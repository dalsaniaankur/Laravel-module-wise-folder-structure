<?php
return [
	'tournaments_search' => [
		'title' => 'FIND A TOURNAMENT',
		'fields' => [
			'organizer'=>'Organizer',
			'tournament'=>'Tournament',
			'age_group'=>'Age Group',
			'competition_level'=>'Competition Level',
			'entry_fee_less_then'=>'Entry Fee Less Then',
			'state'=>'State',
			'city'=>'City',
			'date_range'=>'Date Range',
			'field_surface'=>'Field Surface',
			'zip_code'=>'Zip Code',
			'mile_radius'=>'Mile Radius',
			'sort_by'=>'Sort By',
			'start_date'=>'Start Date',
			'end_date'=>'End Date',
			'guaranteed_games'=>'Guaranteed Games',
			'hotel_required'=>'Hotel Required?',
		]
	],
	'tournaments_grid' => [
		'title' => 'TOURNAMENT LIST',
		'fields' => [
			'tournament'=>'Tournament',
			'location'=>'Location',
			'organizer'=>'Organizer',
			'start_date'=>'Start Date',
			'end_date'=>'End Date',
			'age_groups'=>'Age Groups',
		]
	],

	'team_search' => [
		'title' => 'FIND A TOURNAMENT',
		'fields' => [
			'team'=>'Team',
			'age_group'=>'Age Group',
			'mile_radius'=>'Mile Radius',
			'state'=>'State',
			'city'=>'City',
		]
	],
	'team_grid' => [
		'title' => 'TEAM LIST',
		'fields' => [
			'team'=>'Team',
			'location'=>'Location',
			'age_groups'=>'Age Groups',
			'state'=>'State',
			'city'=>'City',
		]
	],

	'academies_search' => [
		'title' => 'FIND A ACADEMY',
		'fields' => [
			'academy'=>'Academy',
			'service_id'=>'Services',
			'mile_radius'=>'Mile Radius',
			'state'=>'State',
			'city'=>'City',
		]
	],
	'academies_grid' => [
		'title' => 'FIND A LIST',
		'fields' => [
            'academy'=>'Academy',
			'location'=>'Location',
			'service_id'=>'Services',
		]
	],

	'showcases_search' => [
		'title' => 'FIND A SHOWCASES',
		'fields' => [
			'organizer'=>'Organizer',
			'showcase'=>'Showcase',
			'age_group'=>'Age Group',
			'position'=>'Position',
			'open_or_invite'=>'Open / Invite',
			'state'=>'State',
			'city'=>'City',
			'date_range'=>'Date Range',
			'zip_code'=>'Zip Code',
			'mile_radius'=>'Mile Radius',
			'start_date'=>'Start Date',
			'end_date'=>'End Date',
		]
	],
	'showcases_grid' => [
		'title' => 'SHOWCASES LIST',
		'fields' => [
			'showcase'=>'Showcase',
			'location'=>'Location',
			'organization'=>'Organization',
			'dates'=>'Dates',
			'age_groups'=>'Age Groups',
		]
	],

	'tryout_search' => [
		'title' => 'FIND A TRYOUT',
		'fields' => [
			'team_id'=>'Team',
			'tryout'=>'Tryout',
			'age_group'=>'Age Group',
			'position_id'=>'Position Needed',
			'open_or_invite'=>'Open / Invite',
			'state'=>'State',
			'city'=>'City',
			'date_range'=>'Date Range',
			'zip_code'=>'Zip Code',
			'mile_radius'=>'Mile Radius',
			'start_date'=>'Start Date',
			'end_date'=>'End Date',
			'sort_by'=>'Sort By',
		]
	],
	'tryout_grid' => [
		'title' => 'TRYOUT LIST',
		'fields' => [
			'tryout'=>'Tryout',
			'team'=>'Team',
            'dates'=>'Dates',
			'age_groups'=>'Age Groups',
		]
	],

	'categories_grid' => [
		'title' => 'Categories LIST',
		'fields' => [
			'page_title'=>'Title',
		]
	],

    'send_email' => [
        'first_name' => 'First Name',
        'last_name' => 'Last Name',
        'email' => 'Email',
        'message' => 'Message',
        'captcha' => 'Captcha',
        'send_button' => 'Send Email',
    ],

	'qa_all' => 'All',
	'qa_search' => 'Search',
	'instagram_access_token_invalid' => 'The access_token provided is invalid.',
];