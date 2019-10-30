<?php
/*
 * API Action: departments_list
 */
if ( ! defined( 'WPINC' ) ) {
	die;
}
/** Start code HERE **/
function departments_api_list()
{
	header('Content-Type: application/json');
	$args = ['post_type' => DEPARTMENTS_POST_TYPE,'nopaging' => true ];
	$query = new WP_Query( $args );

	$data = [];
	while ( $query->have_posts() ) 
	{
		$query->the_post();
		$model= new Model();
	    // loads data from database to model //
	    $model->get(get_the_ID(),['address','coordinates','contacts','working_hours','services']);
		$depertments_post = [];
		$depertments_post['title'] = get_the_title();
		$depertments_post['address'] = json_decode($model->address, true);
		$depertments_post['coordinates'] = json_decode($model->coordinates, true);
		$depertments_post['featured_image'] = get_the_post_thumbnail_url(get_the_ID());
		$depertments_post['url'] = get_bloginfo('url');
		$depertments_post['contacts'] = json_decode($model->contacts, true);
		$depertments_post['working_hours'] = json_decode($model->working_hours, true);
		$depertments_post['services'] = json_decode($model->services, true);
		array_push($data, $depertments_post);
	}

	echo wp_json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES );
	wp_die();
}
add_action('wp_ajax_departments_list', 'departments_api_list');
add_action('wp_ajax_nopriv_departments_list', 'departments_api_list');

function departments_assets(){
	wp_enqueue_style('departments-frontend', DEPARTMENTS_URL . '/assets/frontend/frontend.css', [], DEPARTMENTS_VERSION);
	wp_enqueue_script('departments-frontend', DEPARTMENTS_URL . '/assets/frontend/frontend.js', ['jquery'], DEPARTMENTS_VERSION);
	wp_localize_script('departments-frontend', 'DMConfig', [
		'plugins_url' => DEPARTMENTS_URL . '/assets/frontend',
		'endpoint' => admin_url('admin-ajax.php'),
		'marker_default' => DEPARTMENTS_URL . '/assets/frontend/marker-default.png',
		'marker_active' => DEPARTMENTS_URL . '/assets/frontend/marker-active.png',
		'map' => [
			'center' => [
				'lat' => '49.836813',
				'lng' => '24.029331'
			],
			'zoom' => 12,
			'apikey' => GOOGLE_API_KEY
		]
	]);
}
add_action('wp_enqueue_scripts', 'departments_assets');