<?php

return [
	'mode'                  => 'utf-8',
	'format'                => [215, 330],
	'author'                => 'asd412id',
	'subject'               => getenv('APP_NAME'),
	'keywords'              => getenv('APP_NAME') . ', asd412id',
	'creator'               => 'asd412id',
	'display_mode'          => 'fullpage',
	'font_path'		          => public_path('assets'),
	'font_data'		          => [
		'bookmanoldstyle' => [
			'R' => 'fonts/bookman_old_style/BOOKOSB.TTF'
		]
	],
	'tempDir'               => storage_path('temp')
];
