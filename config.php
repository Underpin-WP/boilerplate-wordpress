<?php
$plugin_data = get_file_data( plugin_dir_path( PLUGIN_NAME_REPLACE_ME_FILE ) . 'index.php', [
	'Plugin Name',
	'Description',
	'Version',
	'Requires PHP',
	'Requires at least',
	'Text Domain',
], 'plugin' );

return [
	'configuration' => [
		'plugin' => [
			'file'                => PLUGIN_NAME_REPLACE_ME_FILE,
			'dir'                 => __DIR__,
			'url'                 => plugin_dir_url( PLUGIN_NAME_REPLACE_ME_FILE ),
			'name'                => $plugin_data[0],
			'description'         => $plugin_data[1],
			'version'             => $plugin_data[2],
			'minimum_php_version' => $plugin_data[3],
			'minimum_wp_version'  => $plugin_data[4],
			'text_domain'         => $plugin_data[5],
		],
		'logger' => [
			'volume' => defined( 'WP_DEBUG' ) && WP_DEBUG === true ? 100 : 10,
		],
	],
];