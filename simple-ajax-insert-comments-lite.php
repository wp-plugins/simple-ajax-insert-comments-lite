<?php
/*
Plugin Name: Simple Ajax Insert Comments Lite
Description: Inserts comments wherever you want with Ajax and jQuery. Put <code>&lt;?php if(function_exists('display_saic')) { echo display_saic();} ?&gt;</code> where you want to show comments. The plugin <a href="edit-comments.php?page=simple-ajax-insert-comments.php">configuration</a> page.
Version: 1.2.1
Author: Max López
*/
//Copyright 2013 Max López
 
/* --------------------------------------------------------------------
   Definimos Constantes
-------------------------------------------------------------------- */	
define( 'SAIC_PLUGIN_NAME', 'Simple Ajax Insert Comments Lite' );
define( 'SAIC_VERSION', '1.2.1' );
define( 'SAIC_PATH', dirname( __FILE__ ) );
define( 'SAIC_FOLDER', basename( SAIC_PATH ) );
define( 'SAIC_URL', plugins_url() . '/' . SAIC_FOLDER );
  
/* --------------------------------------------------------------------
   Configuración de Acciones y Ganchos
-------------------------------------------------------------------- */	
register_activation_hook(__FILE__, 'install_options_SAIC');
//register_uninstall_hook
register_deactivation_hook(__FILE__, 'delete_options_SAIC');
add_action('admin_init', 'requires_wp_version_SAIC' );
add_action('admin_init', 'register_options_SAIC' );
add_action('admin_menu', 'add_options_page_SAIC');
add_filter('plugin_action_links', 'plugin_action_links_SAIC', 10, 2 );
add_action('wp_enqueue_scripts', 'add_styles_SAIC' );
add_action('wp_enqueue_scripts', 'add_scripts_SAIC' );
add_action( 'admin_enqueue_scripts', 'add_admin_styles_SAIC');
//add_action( 'admin_enqueue_scripts', 'add_admin_scripts_SAIC');
add_action( 'plugins_loaded', 'plugin_textdomain_SAIC');

/* --------------------------------------------------------------------
   Activamos Soporte para la Traduccion del Plugin
-------------------------------------------------------------------- */	
function plugin_textdomain_SAIC() {
    load_plugin_textdomain( 'SAIC', false, dirname( plugin_basename(__FILE__) ) . '/languages/' );
}
/* --------------------------------------------------------------------
   Comprobamos si la version actual de WordPress es Compatible con el Plugin
-------------------------------------------------------------------- */	
function requires_wp_version_SAIC() {
	global $wp_version;
	$plugin = plugin_basename( __FILE__ );
	$plugin_data = get_plugin_data( __FILE__, false );

	if ( version_compare($wp_version, "3.2", "<" ) ) {
		if( is_plugin_active($plugin) ) {
			deactivate_plugins( $plugin );
			wp_die( "'".$plugin_data['Name']."' requires Wordpress 3.2 or higher, and is disabled, you must update Wordpress.<br /><br />Return to the <a href='".admin_url()."'>desktop WordPress</a>." );
		}
	}
}
/* --------------------------------------------------------------------
   Carga de Scripts jQuery y Estilos CSS
-------------------------------------------------------------------- */	
function add_admin_scripts_SAIC(){
	//Loading JS using wp_enqueue
	wp_register_script( 'saic_admin_js_script', SAIC_URL.'/js/saic_admin_script.js', array('jquery'), '1.0', true );
	wp_enqueue_script( 'saic_admin_js_script' );
}
function add_scripts_SAIC() {
	//Loading JS using wp_enqueue
	$options = get_option('saic_options');
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
		wp_register_script( 'saic_js_script', SAIC_URL.'/js/saic_script.min.js', array('jquery'), '1.0', true );
		wp_enqueue_script( 'saic_js_script' );
		wp_localize_script('saic_js_script','SAIC_WP',
			array(
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
				'saicNonce' => wp_create_nonce('saic-nonce'),
				'jpages' => $options['jpages'],
				'jPagesNum' => $options['num_comments_by_page'],
				'textCounter' => $options['text_counter'],
				'textCounterNum' => $options['text_counter_num'],
				'widthWrap' => $options['width_comments'],
			)
		);
		//Si está activado Contador de Caracteres
		if( $options['text_counter'] == 'true' ) {
			wp_register_script( 'saic_textCounter', SAIC_URL.'/js/libs/jquery.textareaCounter.js', array('jquery'), '2.0', true );
			wp_enqueue_script( 'saic_textCounter' );
		}
		//PlaceHolder
		wp_register_script( 'saic_placeholder', SAIC_URL.'/js/libs/jquery.placeholder.min.js', array('jquery'), '2.0.7', true );
		wp_enqueue_script( 'saic_placeholder' );
		//Autosize
		wp_register_script( 'saic_autosize', SAIC_URL.'/js/libs/jquery.autosize.min.js', array('jquery'), '1.14', true );
		wp_enqueue_script( 'saic_autosize' );
		
	}
}
function add_admin_styles_SAIC() {
	//Loading CSS using wp_enqueue
	if ( is_admin() ) {
		wp_register_style( 'saic_admin_style', SAIC_URL.'/css/saic_admin_style.css', array(), '1.0', 'screen' );
		wp_enqueue_style( 'saic_admin_style' );
	}
}
function add_styles_SAIC() {
	//Loading CSS using wp_enqueue
	if ( !is_admin() ) {
		wp_register_style( 'saic_style', SAIC_URL.'/css/saic_style.css', array(), '1.0', 'screen' );
		wp_enqueue_style( 'saic_style' );
	}
}
/* --------------------------------------------------------------------
   Registramos las Opciones del Plugin
-------------------------------------------------------------------- */
function register_options_SAIC(){
	register_setting('saic_group_options','saic_options','validate_options_SAIC' );
	$options = get_option('saic_options');
	$default = 'false';
	if(isset($options['default_options'])){
		$default = $options['default_options'];
	}
	//Si está marcada la opción de restaurar a los valores por defecto
	if(($default=='true')) install_options_SAIC();
}
/* --------------------------------------------------------------------
   Valores por Defecto de las Opciones del Plugin
-------------------------------------------------------------------- */	
function install_options_SAIC() {
	$val_defaults = array(
		"saic_auto_show" => "true",
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
		"max_width_images" => "100",
		"unit_images_size" => '%',
		
		
		"jpages" => "true",
		"num_comments_by_page" => "10",
		"text_counter" => "true",
		"text_counter_num" => "300",
		
		"default_options" => "false"
	);
	update_option('saic_options', $val_defaults);
	
}
/* --------------------------------------------------------------------
   Eliminamos las Opciones del Plugin cuado este se Desactiva
-------------------------------------------------------------------- */	
function delete_options_SAIC() {
	delete_option('saic_options');
}
/* --------------------------------------------------------------------
   Función para validar los campos del Formulario de Opciones
-------------------------------------------------------------------- */
function validate_options_SAIC($input) {
	$input['num_comments'] =  wp_filter_nohtml_kses($input['num_comments']);
	return $input;
}
/* --------------------------------------------------------------------
   Añadimos La Página de Opciones al Ménu
-------------------------------------------------------------------- */	
function add_options_page_SAIC() {
	$page_saic = add_submenu_page('edit-comments.php', sprintf(__('%s Settings','SAIC'), SAIC_PLUGIN_NAME ), SAIC_PLUGIN_NAME , 'manage_options', 'simple-ajax-insert-comments.php', 'add_options_form_SAIC');
	
	//Link Scripts Only on a Plugin Administration Screen
	add_action('admin_print_scripts-' . $page_saic, 'add_admin_scripts_SAIC');
	
}
/* --------------------------------------------------------------------
   Añadimos el Formulario de Opciones a la Página
-------------------------------------------------------------------- */
function add_options_form_SAIC() {
	include_once( 'inc/saic-options-page.php' );
}

/* --------------------------------------------------------------------
    Mostramos el Link de Ajastes al Plugin
-------------------------------------------------------------------- */
function plugin_action_links_SAIC( $links, $file ) {
	if ( $file == plugin_basename( __FILE__ ) ) {
		$saic_links = '<a href="'.get_admin_url().'edit-comments.php?page=simple-ajax-insert-comments.php">'.__('Settings', 'SAIC').'</a>';
		// make the 'Settings' link appear first
		array_unshift( $links, $saic_links );
	}
	return $links;
}

/* --------------------------------------------------------------------
   Añadimos las Fuciones para Insertar Comentarios
-------------------------------------------------------------------- */
include_once( 'inc/saic-functions.php' );