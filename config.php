<?php
$plugin_data = get_plugin_data( plugin_dir_path( PLUGIN_NAME_REPLACE_ME_FILE ), false, false );

return [
	'configuration' => [
		'plugin' => [
			'file'                => PLUGIN_NAME_REPLACE_ME_FILE,
			'root'                => __DIR__,
			'name'                => $plugin_data['Name'],
			'description'         => $plugin_data['Description'],
			'version'             => $plugin_data['Version'],
			'url'                 => plugin_dir_url( PLUGIN_NAME_REPLACE_ME_FILE ),
			'minimum_php_version' => $plugin_data['RequiresPHP'],
			'minimum_wp_version'  => $plugin_data['RequiresWP'],
		],
	],
];