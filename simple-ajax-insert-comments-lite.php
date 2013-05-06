<?php
/*
Plugin Name: Simple Ajax Insert Comments Lite
Description: Inserts comments wherever you want with Ajax and jQuery. Put <code>&lt;?php if(function_exists('display_saic')) { echo display_saic();} ?&gt;</code> where you want to show comments. The plugin <a href="edit-comments.php?page=simple-ajax-insert-comments.php">configuration</a> page.
Version: 1.1.2
Author: Max López
*/
//Copyright 2013 Max López
 
/* --------------------------------------------------------------------
   Definimos Constantes
-------------------------------------------------------------------- */	
define( 'SAICL_PLUGIN_NAME', 'Simple Ajax Insert Comments Lite' );
define( 'SAICL_VERSION', '1.1' );
define( 'SAICL_PATH', dirname( __FILE__ ) );
define( 'SAICL_FOLDER', basename( SAICL_PATH ) );
define( 'SAICL_URL', plugins_url() . '/' . SAICL_FOLDER );
  
/* --------------------------------------------------------------------
   Configuración de Acciones y Ganchos
-------------------------------------------------------------------- */	
register_activation_hook(__FILE__, 'install_options_SAICL');
//register_uninstall_hook
register_deactivation_hook(__FILE__, 'delete_options_SAICL');
add_action('admin_init', 'requires_wordpress_version_SAICL' );
add_action('admin_init', 'register_options_SAICL' );
add_action('admin_menu', 'add_options_page_SAICL');
add_filter('plugin_action_links', 'plugin_action_links_SAICL', 10, 2 );
add_action('wp_enqueue_scripts', 'add_styles_SAICL' );
add_action('wp_enqueue_scripts', 'add_scripts_SAICL' );
add_action( 'admin_enqueue_scripts', 'add_admin_styles_SAICL');
//add_action( 'admin_enqueue_scripts', 'add_admin_scripts_SAICL');
add_action( 'plugins_loaded', 'plugin_textdomain_SAICL');

/* --------------------------------------------------------------------
   Activamos Soporte para la Traduccion del Plugin
-------------------------------------------------------------------- */	
function plugin_textdomain_SAICL() {
    load_plugin_textdomain( 'SAICL', false, dirname( plugin_basename(__FILE__) ) . '/languages/' );
}
/* --------------------------------------------------------------------
   Comprobamos si la version actual de WordPress es Compatible con el Plugin
-------------------------------------------------------------------- */	
function requires_wordpress_version_SAICL() {
	global $wp_version;
	$plugin = plugin_basename( __FILE__ );
	$plugin_data = get_plugin_data( __FILE__, false );

	if ( version_compare($wp_version, "3.3", "<" ) ) {
		if( is_plugin_active($plugin) ) {
			deactivate_plugins( $plugin );
			wp_die( "'".$plugin_data['Name']."' requires Wordpress 3.3 or higher, and is disabled, you must update Wordpress.<br /><br />Return to the <a href='".admin_url()."'>desktop WordPress</a>." );
		}
	}
}
/* --------------------------------------------------------------------
   Carga de Scripts jQuery y Estilos CSS
-------------------------------------------------------------------- */	
function add_admin_scripts_SAICL(){
	//Loading JS using wp_enqueue
	wp_register_script( 'saicl_admin_js_script', SAICL_URL.'/js/saicl_admin_script.js', array('jquery'), '1.0', true );
	wp_enqueue_script( 'saicl_admin_js_script' );
}
function add_scripts_SAICL() {
	//Loading JS using wp_enqueue
	$options = get_option('saicl_options');
	$jquery_url = isset($options['jquery-load']) ? $options['jquery-load']: '';
	if ( !is_admin() ) {
		//wordpress jquery load
		if($jquery_url == ''){
			wp_enqueue_script('jquery');
		}
		//jquery load the user's url
		else {
			wp_deregister_script('jquery');
			wp_register_script('jquery', $jquery_url, false, false, true);
		}
		
		//Añadimos el Script JS Principal
		wp_register_script( 'saicl_js_script', SAICL_URL.'/js/saicl_script.min.js', array('jquery'), '1.0', true );
		wp_enqueue_script( 'saicl_js_script' );
		wp_localize_script('saicl_js_script','SAICL_WP',
			array(
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
				'saiclNonce' => wp_create_nonce('saicl-nonce'),
				'jpages' => $options['jpages'],
				'jPagesNum' => $options['num_comments_by_page'],
				'textCounter' => $options['text_counter'],
				'textCounterNum' => $options['text_counter_num'],
				'widthWrap' => $options['width_comments'],
			)
		);
		//Si está activado Contador de Caracteres
		if( $options['text_counter'] == 'true' ) {
			wp_register_script( 'saicl_textCounter', SAICL_URL.'/js/libs/jquery.textareaCounter.js', array('jquery'), '2.0', true );
			wp_enqueue_script( 'saicl_textCounter' );
		}
		//PlaceHolder
		wp_register_script( 'saicl_placeholder', SAICL_URL.'/js/libs/jquery.placeholder.min.js', array('jquery'), '2.0.7', true );
		wp_enqueue_script( 'saicl_placeholder' );
		//Autosize
		wp_register_script( 'saicl_autosize', SAICL_URL.'/js/libs/jquery.autosize.min.js', array('jquery'), '1.14', true );
		wp_enqueue_script( 'saicl_autosize' );
		
	}
}
function add_admin_styles_SAICL() {
	//Loading CSS using wp_enqueue
	if ( is_admin() ) {
		wp_register_style( 'saicl_admin_style', SAICL_URL.'/css/saicl_admin_style.css', array(), '1.0', 'screen' );
		wp_enqueue_style( 'saicl_admin_style' );
	}
}
function add_styles_SAICL() {
	//Loading CSS using wp_enqueue
	if ( !is_admin() ) {
		wp_register_style( 'saicl_style', SAICL_URL.'/css/saicl_style.css', array(), '1.0', 'screen' );
		wp_enqueue_style( 'saicl_style' );
	}
}
/* --------------------------------------------------------------------
   Registramos las Opciones del Plugin
-------------------------------------------------------------------- */
function register_options_SAICL(){
	register_setting('saicl_group_options','saicl_options','validate_options_SAICL' );
	$options = get_option('saicl_options');
	$default = 'false';
	if(isset($options['default_options'])){
		$default = $options['default_options'];
	}
	//Si está marcada la opción de restaurar a los valores por defecto
	if(($default=='true')) install_options_SAICL();
}
/* --------------------------------------------------------------------
   Valores por Defecto de las Opciones del Plugin
-------------------------------------------------------------------- */	
function install_options_SAICL() {
	$val_defaults = array(
		"saicl_auto_show" => "true",
		"num_comments" => "20",
		"order_comments" => "DESC",
		"only_registered" => "false",
		"text_only_registered" => "",
		"jquery-load" => "",
		
		"theme" => "default",
		"width_comments" => "",
		"border" => "true",
		"display_form" => "true",
		"display_captcha" => "all",
		"display_media_btns" => "false",
		"text_0_comments" => "#N# Comments",
		"text_1_comment" => "#N# Comment",
		"text_more_comments" => "#N# Comments",
		"icon-link" => 'true',
		"date_format" => 'date_wp',
		
		
		"jpages" => "true",
		"num_comments_by_page" => "10",
		"text_counter" => "true",
		"text_counter_num" => "300",
		
		"default_options" => "false"
	);
	update_option('saicl_options', $val_defaults);
	
}
/* --------------------------------------------------------------------
   Eliminamos las Opciones del Plugin cuado este se Desactiva
-------------------------------------------------------------------- */	
function delete_options_SAICL() {
	delete_option('saicl_options');
}
/* --------------------------------------------------------------------
   Función para validar los campos del Formulario de Opciones
-------------------------------------------------------------------- */
function validate_options_SAICL($input) {
	$input['num_comments'] =  wp_filter_nohtml_kses($input['num_comments']);
	return $input;
}
/* --------------------------------------------------------------------
   Añadimos La Página de Opciones al Ménu
-------------------------------------------------------------------- */	
function add_options_page_SAICL() {
	$page_saicl = add_submenu_page('edit-comments.php', sprintf(__('%s Settings','SAICL'), SAICL_PLUGIN_NAME ), SAICL_PLUGIN_NAME , 10, 'simple-ajax-insert-comments.php', 'add_options_form_SAICL');
	
	//Link Scripts Only on a Plugin Administration Screen
	add_action('admin_print_scripts-' . $page_saicl, 'add_admin_scripts_SAICL');
	
}
/* --------------------------------------------------------------------
   Añadimos el Formulario de Opciones a la Página
-------------------------------------------------------------------- */
function add_options_form_SAICL() {
	include_once( 'inc/saicl-options-page.php' );
}

/* --------------------------------------------------------------------
    Mostramos el Link de Ajastes al Plugin
-------------------------------------------------------------------- */
function plugin_action_links_SAICL( $links, $file ) {
	if ( $file == plugin_basename( __FILE__ ) ) {
		$saicl_links = '<a href="'.get_admin_url().'edit-comments.php?page=simple-ajax-insert-comments.php">'.__('Settings', 'SAICL').'</a>';
		// make the 'Settings' link appear first
		array_unshift( $links, $saicl_links );
	}
	return $links;
}

/* --------------------------------------------------------------------
   Añadimos las Fuciones para Insertar Comentarios
-------------------------------------------------------------------- */
include_once( 'inc/saicl-functions.php' );