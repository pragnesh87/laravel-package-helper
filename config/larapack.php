<?php

return [
	'package-namespace' => 'AppTest\LPH', //please update according to your namespace
	'paths' => [
		'controller' => 'appTest/src/Http/Controllers',
		'model' => 'appTest/src/Models',
		'factory' => 'appTest/database/factories',
		'migration' => 'appTest/database/migrations',
		'seeder' => 'appTest/database/seeders',
		'policy' => 'appTest/src/Policies',
		'request' => 'appTest/src/Http/Requests',
		//'feature_test' => 'tests/Feature',
		//'unit_test' => 'tests/Unit',	

	],
	'namespace' => [
		'controller' => 'Http\Controllers',
		'model' => 'Models',
		'factory' => 'Database\Factories',
		'seeder' => 'Database\Seeders',
		'policy' => 'Policies',
		'request' => 'Http\Requests',
		//'feature_test' => 'Tests\Feature',
		//'unit_test' => 'Tests\Unit',
	]
];