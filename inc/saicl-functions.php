<?php

/* --------------------------------------------------------------------
   Función que Inserta el Enlace para Mostrar y Ocultar Comentarios
-------------------------------------------------------------------- */

add_shortcode('simple-comments', 'display_saic');
add_filter('widget_text', 'shortcode_unautop');
add_filter('widget_text', 'do_shortcode', 11);

function display_saic($atts = '') {
	global $post, $user_ID, $user_email;
	$options = get_option('saicl_options');
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
	
	$data = "<div class='saicl-wrapper saicl-{$style}";
	if( $border == 'true' ) $data.= " saicl-border";
	$data .= "' style='overflow: hidden;";
	if( $width_comments ) $data.= " width: {$width_comments}px; ";
	$data .= "'>";
		
		// ENLACE DE MOSTRAR COMENTARIOS
		$data .= "<div class='saicl-wrap-link'>";
			$data .= "<div class='saicl-style-link'>";
				$data .="<a id='saicl-link-{$post_id}' class='saicl-link saicl-icon-link saicl-icon-link-{$icon_link}' href='?post_id={$post_id}&amp;comments={$num}&amp;get={$get}' title='{$title_link}'>{$text_link}</a>";
			$data .= "</div><!--.saicl-style-link-->";
		$data .= "</div><!--.saicl-wrap-link-->";
		
		// CONTENEDOR DE LOS COMENTARIOS
		$data .= "<div id='saicl-wrap-commnent-{$post_id}' class='saicl-wrap-comments' style='display:none;'>";
		if ( post_password_required() ) { 
			$data .= '<p style="padding: 8px 15px;">'.__('This post is password protected. Enter the password to view comments', 'SAICL').'.</p>';
		} else {
			if(comments_open($post_id) && $form == 'true'){
				$data .= "<div id='saicl-wrap-form-{$post_id}' class='saicl-wrap-form";
				if( !is_user_logged_in() ) $data.= " saicl-no-login";
				$data .= "'>";
					$data .= "<div class='saicl-current-user-avatar'>";
						$data .= get_avatar($user_email, $size= '25');
					$data .= "</div>";
					$data .= "<div id='saicl-container-form-{$post_id}' class='saicl-container-form saicl-clearfix'>";
					if( $only_registered == 'true' && !is_user_logged_in() ){
						$data .= "<p>{$options['text_only_registered']} ".__('Please', 'SAICL')." <a href='".wp_login_url(get_permalink())."'>".__('login', 'SAICL')."</a> ".__('to comment', 'SAICL')."</p>";
					} else {
						
						//Formulario
						$data .= get_comment_form_SAICL($post_id);
						$data .= "<div style='padding-top:15px;'></div>";
					}
					$data .= "</div><!--.saicl-container-form-->";
				$data .= "</div><!--.saicl-wrap-form-->";
				
			} // end if comments_open
			$data .= "<div id='saicl-comment-status-{$post_id}'  class='saicl-comment-status'></div>";
			$data .= "<ul id='saicl-container-comment-{$post_id}' class='saicl-container-comments'></ul>";
			
		} // end if post_password_required
		
		$data .= "</div><!--.saicl-wrap-comments-->";
		
	$data .= "</div><!--.saicl-wrapper-->";
	
	return $data;
}


/* --------------------------------------------------------------------
   Función para extraer el formulario de comentarios
-------------------------------------------------------------------- */
function get_comment_form_SAICL($post_id = null) {
	global $id;
	if ( null === $post_id )
		$post_id = $id;
	else
		$id = $post_id;
	
	$fields =  array(
		'author' => '<p class="comment-form-author"><input id="author" name="author" type="text" aria-required="true" class="saicl-input" placeholder="'.__('Name', 'SAICL').'" /><span class="saicl-required">*</span><span class="saicl-error-info saicl-error-info-name">'.__('Enter your name', 'SAICL').'</span></p>',
		'email'  => '<p class="comment-form-email"><input id="email" name="email" type="text" aria-required="true" class="saicl-input" placeholder="e-mail" /><span class="saicl-required">*</span><span class="saicl-error-info saicl-error-info-email">'.__('The entered E-mail is invalid', 'SAICL').'.</span></p>',
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
		'comment_field' => '<div class="saicl-wrap-textarea"><textarea id="saicl-textarea-'.$post_id.'" class="waci_comment saicl-textarea autosize-textarea" name="comment" aria-required="true" placeholder="'.__('Write comment', 'SAICL').'"></textarea><span class="saicl-required">*</span><span class="saicl-error-info saicl-error-info-text">'.__('2 characters minimum', 'SAICL').'.</span></div>'
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
				$form.= "<input name='submit' id='".$args['id_submit']."' value='".$args['label_submit']."' type='submit' />";
				$form .= get_comment_id_fields( $post_id );
			$form .= "</p>";
			if ( current_user_can( 'unfiltered_html' ) ) {
				$form .= wp_nonce_field( 'unfiltered-html-comment_' . $post_id,'_wp_unfiltered_html_comment_disabled', false, false );
				$form .= "<script>(function(){if(window===window.parent){document.getElementById('_wp_unfiltered_html_comment_disabled').name='_wp_unfiltered_html_comment';}})();</script>\n";
			}
		$form .= "</form>";
	$form .= "<div class='clear'></div></div>";
	return $form;
}

/* --------------------------------------------------------------------
   Función que Inserta un Nuevo Cometario
-------------------------------------------------------------------- */
add_action('comment_post', 'ajax_comment_SAICL', 20, 2);
function ajax_comment_SAICL($comment_ID, $comment_status){
	// Si el comentario se ejecutó con AJAX
	if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
		//Comprobamos el estado del comentario
		switch($comment_status){
			//Si el comentario no está aprobado 'hold = 0'
			case "0":
				//Notificamos al moderador
				wp_notify_moderator($comment_ID);
			//Si el comentario está aprobado 'approved = 1'
			case "1":
				// Obtenemos los datos del comentario
				$comment = get_comment($comment_ID);
				//Obtenemos HTML del nuevo comentario
				ob_start(); // Activa almacenamiento en bufer 
				$args = array();
				$depth = 0;
				get_comment_HTML_SAICL($comment,$args, $depth);
				$commentData =  ob_get_clean();// Obtiene el contenido del búfer actual y elimina el búfer de salida actual.
				
				//Notificamos al autor del post de un nuevo comentario
				wp_notify_postauthor($comment_ID, $comment->comment_type);
				echo $commentData;
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
add_action('wp_ajax_get_comments', 'get_comments_SAICL');
add_action('wp_ajax_nopriv_get_comments', 'get_comments_SAICL');

function get_comments_SAICL(){
	global $post, $id;
	$nonce = $_POST['nonce'];
    if (!wp_verify_nonce($nonce, 'saicl-nonce')){
		die ( 'Busted!');
	}
	$options = get_option('saicl_options');
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
	
	ob_start(); // Activa almacenamiento en bufer 
	
	//Display the list of comments
	wp_list_comments(array(
		'callback'=> 'get_comment_HTML_SAICL'
	), $comments);
	
	// Obtiene el contenido del búfer actual y elimina el búfer de salida actual.
	
	$listComment =  ob_get_clean();
	
	echo $listComment;
	
	die(); // this is required to return a proper result
	
}

/* --------------------------------------------------------------------
   Función que extrae HTML de un Comentario
-------------------------------------------------------------------- */
function get_comment_HTML_SAICL($comment,$args, $depth){
	$GLOBALS['comment'] = $comment;
	extract($args, EXTR_SKIP);
	$commentPostID = $comment->comment_post_ID;
	$commentContent = convert_smilies($comment->comment_content);
	$commentID = $comment->comment_ID;
	$commentDate = $comment->comment_date;
	$autorID = $comment->user_id;
	$autorEmail = $comment->comment_author_email;
	$autorName = $comment->comment_author;
	$autorUrl = $comment->comment_author_url;
	$userFirstName = get_user_meta( $autorID, 'first_name', true);
	if($userFirstName) $autorName = $userFirstName;
	
	$options = get_option('saicl_options');
	$date_format = $options['date_format'];
	?>
	<li <?php comment_class('saicl-item-comment'); ?> id="saicl-item-comment-<?php comment_ID(); ?>">
    	<div id="saicl-comment-<?php comment_ID(); ?>" class="saicl-comment">
            <div class="saicl-comment-left">
                <div class="saicl-comment-avatar">
                    <?php echo get_avatar($autorEmail, $size= '25');?>
                </div><!--.saicl-comment-avatar-->
            </div><!--.saicl-comment-left-->
            <div class="saicl-comment-right">
                <div class="saicl-comment-content">
                    <div class="saicl-comment-info">
                        <a href="<?php echo comment_author_link_SAICL($autorName, $autorUrl);?>" class="saicl-commenter-name" title="<?php echo $autorName;?>"><?php echo $autorName;?></a><span class="saicl-comment-time"> <?php echo get_comment_date('m/j/Y', $commentID);?></span>
                    </div><!--.saicl-comment-info-->
                    <div class="saicl-comment-text">
                        <span class="saicl-comment-text"><?php echo $commentContent;?></span>
                    </div><!--.saicl-comment-text-->
                </div><!--.saicl-comment-content-->
                
            </div><!--.saicl-comment-right-->
        
        </div><!--.saicl-comment-->
        	
		<!--</li>-->
       
	<?php
}

/* --------------------------------------------------------------------
   Función que retorna el link que un usuario escribió en los comentarios
-------------------------------------------------------------------- */
function comment_author_link_SAICL($autorName = '1', $autorUrl = '#'){
	if ( username_exists( $autorName ) ){
		$user_link = $autorUrl;
		if(is_bp_active_SAICL()){
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
function is_bp_active_SAICL(){
	if(class_exists( 'BuddyPress' ))
		return true;
	else
		return false;
}

/* --------------------------------------------------------------------
   Función para insertar automaticamente el Plugín
-------------------------------------------------------------------- */
$options = get_option('saicl_options');
if($options['saicl_auto_show'] == 'true') {
	function auto_show_SAICL($content) {
		$content = $content.display_saic();
    	return $content;
	}
	add_filter('the_content','auto_show_SAICL');
}



?>