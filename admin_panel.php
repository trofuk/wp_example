<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}
/** Start code HERE **/
function department_admin_init() 
{
    add_action('admin_enqueue_scripts', 'department_admin_enqueue_assets', 10, 1); 
    add_meta_box(
        'department_address',
        __('Department address','departments'),
        'render_department_address_block',
        'department',
        'normal',
        'default'
    );
    add_meta_box(
        'department_contacts',
        __('Department contacts','departments'),
        'render_department_contacts_block',
        'department',
        'normal',
        'default'
    );
    add_meta_box(
        'department_working_hours',
        __('Department working hours','departments'),
        'render_department_working_hours_block',
        'department',
        'normal',
        'default'
    );
    add_meta_box(
        'department_services',
        __('Department services','departments'),
        'render_department_services_block',
        'department',
        'normal',
        'default'
    );
}

function department_admin_enqueue_assets()
{
    $current_screen = get_current_screen();
    if( $current_screen ->id === DEPARTMENTS_POST_TYPE) {
        department_admin_enqueue_scripts();
        department_admin_enqueue_styles();
    }
}

function department_admin_enqueue_scripts()
{
    wp_enqueue_script(
        'admin-map-control',
        DEPARTMENTS_URL.'/assets/backend/js/admin_panel_map_control.js',
        false,
        DEPARTMENTS_VERSION,
        true
    );

    wp_localize_script('admin-map-control', '_address', [
        'map' => [
            'center' => [
                'lat' => 49.836813,
                'lng' => 24.029331
            ],
            'zoom' => 12,
        ]
    ]);

    wp_enqueue_script(
        'google-maps',
        'https://maps.googleapis.com/maps/api/js?key=' . GOOGLE_API_KEY . '&callback=init',
        ['admin-map-control'],
        DEPARTMENTS_VERSION,
        true
    );

    wp_enqueue_script(
        'dpt-widget',
        DEPARTMENTS_URL.'/assets/backend/js/admin_panel_dpt-widget.js',
        ['jquery'],
        DEPARTMENTS_VERSION,
        true
    );
    wp_enqueue_script(
        'app',
        DEPARTMENTS_URL.'/assets/backend/js/app.js',
        ['jquery','dpt-widget'],
        DEPARTMENTS_VERSION,
        true
    );
    wp_localize_script('app', '_contacts', [
        'translate' => [
            'create' => __('Add new contact', DEPARTMENTS_TXT_DOMAIN),
            'save' => __('Save', DEPARTMENTS_TXT_DOMAIN),
            'delete' => __('Delete', DEPARTMENTS_TXT_DOMAIN),
            'labelForType' => __('Choose type', DEPARTMENTS_TXT_DOMAIN),
            'labelForValue' => __('Enter value', DEPARTMENTS_TXT_DOMAIN),
            
            'typeVariants' => [
                'phone' => __('Phone', DEPARTMENTS_TXT_DOMAIN),
                'email' => __('Email', DEPARTMENTS_TXT_DOMAIN),
            ],
        ]       
    ]);
    wp_localize_script('app', '_working_hours', [
        'translate' => [
            'create' => __('Add record', DEPARTMENTS_TXT_DOMAIN),
            'save' => __('Save', DEPARTMENTS_TXT_DOMAIN),
            'delete' => __('Delete', DEPARTMENTS_TXT_DOMAIN),
            'labelForDay' => __('Enter days', DEPARTMENTS_TXT_DOMAIN),
            'labelForTime' => __('Enter time', DEPARTMENTS_TXT_DOMAIN),
        ]       
    ]);
    wp_localize_script('app', '_services', [
        'translate' => [
            'category'=>[
                'create' => __('Add category', DEPARTMENTS_TXT_DOMAIN),
                'save' => __('Save', DEPARTMENTS_TXT_DOMAIN),
                'delete' => __('Delete', DEPARTMENTS_TXT_DOMAIN),
                'labelForTitle' => __('Enter name', DEPARTMENTS_TXT_DOMAIN),
                'labelForType' => __('Category', DEPARTMENTS_TXT_DOMAIN),
                'warning' =>__('Are you sure? Services in category will be also deleted!', DEPARTMENTS_TXT_DOMAIN),
            ],
            'service'=>[
                'create' => __('Add service', DEPARTMENTS_TXT_DOMAIN),
                'save' => __('Save', DEPARTMENTS_TXT_DOMAIN),
                'delete' => __('Delete', DEPARTMENTS_TXT_DOMAIN),
                'labelForTitle' => __('Enter name', DEPARTMENTS_TXT_DOMAIN),
                'labelForType' => __('Service', DEPARTMENTS_TXT_DOMAIN),  
            ],
        ]       
    ]);
}

function department_admin_enqueue_styles()
{
    wp_enqueue_style(
        'admin-map-control',
        DEPARTMENTS_URL.'/assets/backend/css/admin_panel_map_control.css',
        false,
        DEPARTMENTS_VERSION,
        false
    );
    wp_enqueue_style(
        'dpt-widget-style',
        DEPARTMENTS_URL.'/assets/backend/css/admin_panel_dpt-widget.css',
        false,
        DEPARTMENTS_VERSION,
        false  
    );
}

function render_department_working_hours_block($department)
{
    $model= new Model();
    // loads data from database to model //
    $model->get($department->ID,['working_hours']);
    View::renderPartial(
        'department_working_hours_block',
        [
            'working_hours'=>$model->working_hours
        ]
    );
}

function render_department_address_block($department)
{ 
    $model= new Model();
    // loads data from database to model //
    $model->get($department->ID,['address','coordinates']);
    View::renderPartial(
        'department_address_block',
        [
            'address'=>$model->address,
            'coordinates'=>$model->coordinates
        ]
    );
}

function render_department_contacts_block($department)
{
    $model= new Model();
    // loads data from database to model //
    $model->get($department->ID,['contacts']);
    View::renderPartial(
        'department_contacts_block',
        [
            'contacts'=>$model->contacts
        ]
    );   
}

function render_department_services_block( $department )
{
    $model= new Model();
    // loads data from database to model //
    $model->get($department->ID,['services']);
    View::renderPartial(
        'department_services_block',
        [
            'services'=>$model->services
        ]
    );   
}

function before_save($department_id, $department)
{
    if ( $department->post_type == 'department' )
    {
        $fields = ['address','coordinates','contacts','working_hours','services'];
        $model = new Model();
        $model->load($_POST, $fields);
        $model->save($department_id);
    }
}

add_action('admin_init', 'department_admin_init');
add_action('save_post', 'before_save', 10, 2);