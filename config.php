<?php
$plugin_data = get_plugin_data( PLUGIN_NAME_REPLACE_ME_ROOT, false, false );

return [
	'configuration' => [
		'plugin' => [
			'file'                => PLUGIN_NAME_REPLACE_ME_ROOT,
			'root'                => __DIR__,
			'name'                => $plugin_data['Name'],
			'description'         => $plugin_data['Description'],
			'version'             => $plugin_data['Version'],
			'url'                 => plugin_dir_url( PLUGIN_NAME_REPLACE_ME_ROOT ),
			'minimum_php_version' => $plugin_data['RequiresPHP'],
			'minimum_wp_version'  => $plugin_data['RequiresWP'],
		],
	],
];