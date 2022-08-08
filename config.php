<?php
$plugin_data = get_file_data( plugin_dir_path( PLUGIN_NAME_REPLACE_ME_FILE ) . 'index.php', [
	'Requires PHP',
	'Requires at least',
	'Text Domain',
], 'plugin' );

return [
	'configuration' => [
		'plugin' => [
			'file'                => PLUGIN_NAME_REPLACE_ME_FILE,
			'root'                => __DIR__,
			'url'                 => plugin_dir_url( PLUGIN_NAME_REPLACE_ME_FILE ),
			'minimum_php_version' => $plugin_data[0],
			'minimum_wp_version'  => $plugin_data[1],
			'text_domain'         => $plugin_data[2],
		],
		'logger' => [
			'volume' => defined( 'WP_DEBUG' ) && WP_DEBUG === true ? 100 : 10,
		],
	],
];