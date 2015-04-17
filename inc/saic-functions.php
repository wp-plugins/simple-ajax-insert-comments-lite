<?php

/* --------------------------------------------------------------------
   Función que Inserta el Enlace para Mostrar y Ocultar Comentarios
-------------------------------------------------------------------- */

add_shortcode('simple-comments', 'display_saic');
add_filter('widget_text', 'shortcode_unautop');
add_filter('widget_text', 'do_shortcode', 11);

function display_saic($atts = '') {
	global $post, $user_ID, $user_email;
	$options = get_option('saic_options');
	$icon_link = $options['icon-link'];
	$width_comments = (int) $options['width_comments'];
	$only_registered = isset($options['only_registered']) ? $options['only_registered'] : false;
	$text_link = 'Show Comments';
	
	//Shortcode Attributes
	extract(shortcode_atts(array(
		'post_id' => $post->ID,
		'get' => (int) $options['num_comments'],
		'style' => $style = $options['theme'],
		'border' => isset($options['border']) ? $options['border'] : 'true',
		'form' => $options['display_form']
    ), $atts));
		
	$num = get_comments_number($post_id);//Solo comentarios aprovados
	
	switch($num){
		case 0:
			$text_link = str_replace('#N#','<span>'.$num.'</span>',$options['text_0_comments']);
			$title_link = str_replace('#N#',$num,$options['text_0_comments']);
			break;
		case 1:
			$text_link = str_replace('#N#','<span>'.$num.'</span>',$options['text_1_comment']);
			$title_link = str_replace('#N#',$num,$options['text_1_comment']);
			break;
		default:
			$text_link = str_replace('#N#','<span>'.$num.'</span>',$options['text_more_comments']);
			$title_link = str_replace('#N#',$num,$options['text_1_comment']);
			break;
	}
	
	$data = "<div class='saic-wrapper saic-{$style}";
	if( $border == 'true' ) $data.= " saic-border";
	$data .= "' style='overflow: hidden;";
	if( $width_comments ) $data.= " width: {$width_comments}px; ";
	$data .= "'>";
		
		// ENLACE DE MOSTRAR COMENTARIOS
		$data .= "<div class='saic-wrap-link'>";
			$data .= "<div class='saic-style-link'>";
				$data .="<a id='saic-link-{$post_id}' class='saic-link saic-icon-link saic-icon-link-{$icon_link}' href='?post_id={$post_id}&amp;comments={$num}&amp;get={$get}' title='{$title_link}'>{$text_link}</a>";
			$data .= "</div><!--.saic-style-link-->";
		$data .= "</div><!--.saic-wrap-link-->";
		
		// CONTENEDOR DE LOS COMENTARIOS
		$data .= "<div id='saic-wrap-commnent-{$post_id}' class='saic-wrap-comments' style='display:none;'>";
		if ( post_password_required() ) { 
			$data .= '<p style="padding: 8px 15px;">'.__('This post is password protected. Enter the password to view comments', 'SAIC').'.</p>';
		} else {
			if(comments_open($post_id) && $form == 'true'){
				$data .= "<div id='saic-wrap-form-{$post_id}' class='saic-wrap-form";
				if( !is_user_logged_in() ) $data.= " saic-no-login";
				$data .= "'>";
					$data .= "<div class='saic-current-user-avatar'>";
						$data .= get_avatar($user_email, $size= '25');
					$data .= "</div>";
					$data .= "<div id='saic-container-form-{$post_id}' class='saic-container-form saic-clearfix'>";
					if( $only_registered == 'true' && !is_user_logged_in() ){
						$data .= "<p>{$options['text_only_registered']} ".__('Please', 'SAIC')." <a href='".wp_login_url(get_permalink())."'>".__('login', 'SAIC')."</a> ".__('to comment', 'SAIC')."</p>";
					} else {
						
						//Formulario
						$data .= get_comment_form_SAIC($post_id);
						$data .= "<div style='padding-top:15px;'></div>";
					}
					$data .= "</div><!--.saic-container-form-->";
				$data .= "</div><!--.saic-wrap-form-->";
				
			} // end if comments_open
			$data .= "<div id='saic-comment-status-{$post_id}'  class='saic-comment-status'></div>";
			$data .= "<ul id='saic-container-comment-{$post_id}' class='saic-container-comments'></ul>";
			
		} // end if post_password_required
		
		$data .= "</div><!--.saic-wrap-comments-->";
		
	$data .= "</div><!--.saic-wrapper-->";
	
	return $data;
}


/* --------------------------------------------------------------------
   Función para extraer el formulario de comentarios
-------------------------------------------------------------------- */
function get_comment_form_SAIC($post_id = null) {
	global $id;
	if ( null === $post_id )
		$post_id = $id;
	else
		$id = $post_id;
	
	$fields =  array(
		'author' => '<p class="comment-form-author"><input id="author" name="author" type="text" aria-required="true" class="saic-input" placeholder="'.__('Name', 'SAIC').'" /><span class="saic-required">*</span><span class="saic-error-info saic-error-info-name">'.__('Enter your name', 'SAIC').'</span></p>',
		'email'  => '<p class="comment-form-email"><input id="email" name="email" type="text" aria-required="true" class="saic-input" placeholder="e-mail" /><span class="saic-required">*</span><span class="saic-error-info saic-error-info-email">'.__('The entered E-mail is invalid', 'SAIC').'.</span></p>',
		'url'    => '<p class="comment-form-url"><input id="url" name="url" type="text" value="" placeholder="Website"  /></p>',
	);
	$args = array(
		'title_reply'=> '',
		'comment_notes_before' => '',
		'comment_notes_after' => '',
		'logged_in_as' => '',
		'id_form' => 'commentform-'.$post_id,
		'id_submit' => 'submit-'.$post_id,
		'label_submit' => 'Send',
		'fields' => apply_filters( 'comment_form_default_fields', $fields),
		'comment_field' => '<div class="saic-wrap-textarea"><textarea id="saic-textarea-'.$post_id.'" class="waci_comment saic-textarea autosize-textarea" name="comment" aria-required="true" placeholder="'.__('Write comment', 'SAIC').'"></textarea><span class="saic-required">*</span><span class="saic-error-info saic-error-info-text">'.__('2 characters minimum', 'SAIC').'.</span></div>'
	);
	$form = "";
	$form = "<div id='respond-{$post_id}' class='respond clearfix'>";
		$form .= "<form action='".site_url( '/wp-comments-post.php' )."' method='post' id='".$args['id_form']."'>";
			if ( !is_user_logged_in() ) {
				foreach ( (array) $args['fields'] as $name => $field ) {
					$form.= apply_filters( "comment_form_field_{$name}", $field );
				}
			}
			$form.= $args['comment_field'];
			$form.= "<p class='form-submit'>";
			//Prueba para evitar Spam
				$form .= '<span class="saic-hide">'.__( "Do not change these fields following", "SAIC" ).'</span><input type="text" class="saic-hide" name="name" value="saic"><input type="text" class="saic-hide" name="nombre" value=""><input type="text" class="saic-hide" name="form-saic" value="">';
				
				$form.= "<input name='submit' id='".$args['id_submit']."' value='".$args['label_submit']."' type='submit' />";
				$form .= get_comment_id_fields( $post_id );
			$form .= "</p>";
			if ( current_user_can( 'unfiltered_html' ) ) {
				$form .= wp_nonce_field( 'unfiltered-html-comment_' . $post_id,'_wp_unfiltered_html_comment_disabled', false, false );
				/*$form .= "<script>(function(){if(window===window.parent){document.getElementById('_wp_unfiltered_html_comment_disabled').name='_wp_unfiltered_html_comment';}})();</script>\n";*/
			}
		$form .= "</form>";
	$form .= "<div class='clear'></div></div>";
	return $form;
}

/* --------------------------------------------------------------------
   Función para evitar Spam
-------------------------------------------------------------------- */
add_action('pre_comment_on_post', 'remove_spam_SAIC');
function remove_spam_SAIC($comment_post_ID){
	// Si el comentario se ha enviado desde este plugin
	if(isset($_POST['form-saic'])){
		// Si los campos ocultos no se han modificado
		if($_POST['name'] != 'saic' || $_POST['nombre'] != ''){
			wp_die( __('<strong>ERROR</strong>: Your comment has been detected as Spam!') );
		}
	}
}

/* --------------------------------------------------------------------
   Función que Inserta un Nuevo Cometario
-------------------------------------------------------------------- */
add_action('comment_post', 'ajax_comment_SAIC', 20, 2);
function ajax_comment_SAIC($comment_ID, $comment_status){
	// Si el comentario se ejecutó con AJAX
	if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
		//Comprobamos el estado del comentario
		switch($comment_status){
			//Si el comentario no está aprobado 'hold = 0'
			case "0":
				//Notificamos al moderador
				if( get_option('comments_notify') == 1 ){
					wp_notify_moderator($comment_ID);
				}
			//Si el comentario está aprobado 'approved = 1'
			case "1":
				//Notificamos al autor del post de un nuevo comentario
				//get_option('moderation_notify');
				if( get_option('comments_notify') == 1 ){
					wp_notify_postauthor($comment_ID);
				}
				
				// Obtenemos los datos del comentario
				$comment = get_comment($comment_ID);
				//Obtenemos HTML del nuevo comentario
				//ob_start(); // Activa almacenamiento en bufer
				$args = array();
				$depth = 0;//nivel de comentario
				
				get_comment_HTML_SAIC($comment,$args, $depth);
				//$commentData =  ob_get_clean();// Obtiene el contenido del búfer actual y elimina el búfer de salida actual.
				
				//echo $commentData;
				
				break;
			default:
				echo "error";
		}
	exit;
	}
}
/* --------------------------------------------------------------------
   Función que obtiene Comentarios
-------------------------------------------------------------------- */
add_action('wp_ajax_get_comments', 'get_comments_SAIC');
add_action('wp_ajax_nopriv_get_comments', 'get_comments_SAIC');

function get_comments_SAIC(){
	global $post, $id;
	$nonce = $_POST['nonce'];
    if (!wp_verify_nonce($nonce, 'saic-nonce')){
		die ( 'Busted!');
	}
	$options = get_option('saic_options');
	$post_id = (int) isset($_POST['post_id']) ? $_POST['post_id']: $post->ID;
	$get = (int) isset($_POST['get']) ? $_POST['get'] : $options['num_comments'];
	$post = get_post($post_id);
	$numComments = $post->comment_count;
	$authordata = get_userdata($post->post_author);
	$orderComments = $options['order_comments'];
	$offset = '';
	if($orderComments != 'DESC')
		$offset = $numComments - $get;
	
	$comments_args = array(
        'post_id' => $post_id,
		'number' => $get,//Número Máximo de Comentarios a Cargar
		'order' => $orderComments,//Orden de los Comentarios
		'offset' => $offset,//Desplazamiento desde el último comentario
		'status' => 'approve'//Solo Comentarios Aprobados
	);
	
	$comments = get_comments($comments_args);
	
	//ob_start(); // Activa almacenamiento en bufer 
	
	//Display the list of comments
	wp_list_comments(array(
		'callback'=> 'get_comment_HTML_SAIC'
	), $comments);
	
	// Obtiene el contenido del búfer actual y elimina el búfer de salida actual.
	
	//$listComment =  ob_get_clean();
	
	//echo $listComment;
	
	die(); // this is required to return a proper result
	
}

/* --------------------------------------------------------------------
   Función que extrae el contenido de un comentario
-------------------------------------------------------------------- */

function my_get_comment_text($commentID){
	global $wpdb;
	//$_comment = $wpdb->get_results("SELECT * FROM $wpdb->comments WHERE comment_ID = " .$commentID );
	//$comment_content = $_comment[0]->comment_content;
	//$_comment = $wpdb->get_row("SELECT * FROM $wpdb->comments WHERE comment_ID = ".$commentID);
	//$comment_content = $_comment->comment_content;
	$comment_content = $wpdb->get_var("SELECT comment_content FROM $wpdb->comments WHERE comment_ID = ".$commentID);
	
	//$comment_content = wptexturize($comment_content);
	$comment_content = nl2br($comment_content); //Inserta saltos de línea al final de un string
	$comment_content = convert_chars($comment_content); //Traduce referencias Unicode no válidos a válidos
	$comment_content = make_clickable($comment_content); //Hace clickeable los enlaces
	$comment_content = convert_smilies($comment_content); //Conserva las caritas
	$comment_content = force_balance_tags($comment_content); //Equilibra etiquetas faltantes o mal cerradas
	$comment_content = wpautop($comment_content); //Cambia dobles saltos de línea en párrafos
	
	return $comment_content;
}

/* --------------------------------------------------------------------
   Función que extrae HTML de un Comentario
-------------------------------------------------------------------- */
function get_comment_HTML_SAIC($comment,$args, $depth){
	$GLOBALS['comment'] = $comment;
	extract($args, EXTR_SKIP);
	
	$commentPostID = $comment->comment_post_ID;
	$commentID = $comment->comment_ID;
	//$commentContent = $comment->comment_content;
	//$commentContent = apply_filters('comment_text', $commentContent);
	$commentContent = my_get_comment_text($commentID);
	$commentDate = $comment->comment_date;
	$autorID = $comment->user_id;
	$autorEmail = $comment->comment_author_email;
	$autorName = $comment->comment_author;
	$autorUrl = $comment->comment_author_url;
	$userFirstName = get_user_meta( $autorID, 'first_name', true);
	if($userFirstName) $autorName = $userFirstName;
	
	$options = get_option('saic_options');
	$date_format = $options['date_format'];
	?>
	<li <?php comment_class('saic-item-comment'); ?> id="saic-item-comment-<?php comment_ID(); ?>">
    	<div id="saic-comment-<?php comment_ID(); ?>" class="saic-comment">
            <div class="saic-comment-left">
                <div class="saic-comment-avatar">
                    <?php echo get_avatar($autorEmail, $size= '25');?>
                </div><!--.saic-comment-avatar-->
            </div><!--.saic-comment-left-->
            <div class="saic-comment-right">
                <div class="saic-comment-content">
                    <div class="saic-comment-info">
                        <a href="<?php echo comment_author_link_SAIC($autorName, $autorUrl);?>" class="saic-commenter-name" title="<?php echo $autorName;?>"><?php echo $autorName;?></a><span class="saic-comment-time"> <?php echo get_comment_date('m/j/Y', $commentID);?></span>
                    </div><!--.saic-comment-info-->
                    <div class="saic-comment-text">
                        <span class="saic-comment-text"><?php echo $commentContent;?></span>
                    </div><!--.saic-comment-text-->
                </div><!--.saic-comment-content-->
                
            </div><!--.saic-comment-right-->
        
        </div><!--.saic-comment-->
        	
		<!--</li>-->
       
	<?php
}

/* --------------------------------------------------------------------
   Función que retorna el link que un usuario escribió en los comentarios
-------------------------------------------------------------------- */
function comment_author_link_SAIC($autorName = '1', $autorUrl = '#'){
	if ( username_exists( $autorName ) ){
		$user_link = $autorUrl;
		if(is_bp_active_SAIC()){
			$user = get_user_by('login',$autorName);
			$user_link = bp_core_get_user_domain($user->ID);
		}
	} else {
		$user_link = $autorUrl;
	}
	return $user_link;
}

/* --------------------------------------------------------------------
   Función que comprueba si Buddypress está activo
-------------------------------------------------------------------- */
function is_bp_active_SAIC(){
	if(class_exists( 'BuddyPress' ))
		return true;
	else
		return false;
}

/* --------------------------------------------------------------------
   Función para insertar automaticamente el Plugín
-------------------------------------------------------------------- */
$options = get_option('saic_options');
if($options['saic_auto_show'] == 'true') {
	function auto_show_SAIC($content) {
		$content = $content.display_saic();
    	return $content;
	}
	add_filter('the_content','auto_show_SAIC');
}



?>