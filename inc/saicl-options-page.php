<?php
$options = get_option('saicl_options');
?>
<div class="wrap">
	<!-- Display Plugin Icon and Header -->
	<?php screen_icon('saicl'); ?>
	<h2><?php _e( SAICL_PLUGIN_NAME.' Settings', 'SAICL' );?></h2>
	
    <?php
		if ( ! isset( $_REQUEST['settings-updated'] ) )
			$_REQUEST['settings-updated'] = false;
		?>
		<?php if ( false !== $_REQUEST['settings-updated'] ) : ?>
		<div class="message updated" style="width:80%"><p><strong><?php _e( 'Options saved', 'SAICL'); ?></strong></p></div>
	<?php endif; ?>

<p class="saicl-info-free2" style="width:78.2%"><?php echo sprintf(__( 'You can download the full version from %shere%s', 'SAICL' ), '<a href="http://bit.ly/YxoS4t">', '</a>' ); ?></p>
    
<h2 id="saicl-tabs" class="nav-tab-wrapper"> 
    <a class="nav-tab" href="#saicl-tab1"><?php _e( 'General', 'SAICL' );?></a>
    <a class="nav-tab" href="#saicl-tab2"><?php _e( 'Content and Style', 'SAICL' );?></a>
    <a class="nav-tab" href="#saicl-tab3"><?php _e( 'Additional', 'SAICL' );?></a>
    <a class="nav-tab" href="#saicl-tab4"><?php _e( 'Help Fast', 'SAICL' );?></a>
</h2> 
<form id="saicl-form" action="<?php echo admin_url('options.php');?>" method="post" >
	<?php settings_fields('saicl_group_options'); ?>
    <div class="saicl-tab-container">
        <div id="saicl-tab1" class="saicl-tab-content"> 
        
            <!-- Activar Automáticamente -->
            <fieldset class="saicl-control-group">
                <div class="saicl-control-label">
                    <label><?php _e( 'Auto Show '.SAICL_PLUGIN_NAME.' ?', 'SAICL' ); ?></label>
                </div><!--.saicl-control-label-->
                <div class="saicl-controls">
                
                	<div class="saicl-radio saicl-radio-h saicl-float-l">
                        <input id="saicl-auto-show-true" name="saicl_options[saicl_auto_show]" type="radio" value="true" <?php checked('true', $options['saicl_auto_show']); ?> />
                        <label for="saicl-auto-show-true"><?php _e( 'Yes', 'SAICL' ); ?></label>
                    </div><!--.saicl-radio-->
                    <div class="saicl-radio saicl-radio-h saicl-float-l">
                        <input id="saicl-auto-show-false" name="saicl_options[saicl_auto_show]" type="radio" value="false" <?php checked('false', $options['saicl_auto_show']); ?> />
                        <label for="saicl-auto-show-false"><?php _e( 'Not', 'SAICL' ); ?></label>
                        
                    </div><!--.saicl-radio-->
					<p class="saicl-descrip-item"><?php echo sprintf(__( 'If you do not want to automatically display, disable this option and insert %s where you want to show comments.', 'SAICL' ), '<strong>&lt;?php display_saic(); ?&gt;</strong>'); ?></p>
                </div><!--.saicl-controls-->
            </fieldset>
            <div class="saicl-line-sep"></div>
            
            <!-- Número de Comentarios -->
            <fieldset class="saicl-control-group">
                <div class="saicl-control-label">
                    <label for="num_comments"><?php _e( 'Number maximum of comments to load', 'SAICL' ); ?></label>
                </div><!--.saicl-control-label-->
                <div class="saicl-controls">
                    <input id="num_comments" type="text" name="saicl_options[num_comments]" value="<?php echo $options['num_comments']; ?>" />
                    <p class="saicl-descrip-item"><?php _e( 'Default value', 'SAICL' ); ?>: 20. <?php _e( 'Indicates the maximum number of comments of a post to be extracted from the data base.', 'SAICL' ); ?></p>
                </div><!--.saicl-controls-->
            </fieldset>
            
            <!-- Orden de los Comentarios -->
            <fieldset class="saicl-control-group">
                <div class="saicl-control-label">
                    <label><?php _e( 'Order of the comments', 'SAICL' ); ?></label>
                </div><!--.saicl-control-label-->
                <div class="saicl-controls">
                	<div class="saicl-radio saicl-radio-v">
                        <input id="saicl-order_comments-des" name="saicl_options[order_comments]" type="radio" value="DESC" <?php checked('DESC', $options['order_comments']); ?> />
                        <label for="saicl-order_comments-des"><?php _e( 'The first new comments', 'SAICL' ); ?></label>
                        <span class="saicl-descrip-item"><?php _e( 'Sorts the comments from newest to oldest', 'SAICL' ); ?></span>
                    </div><!--.saicl-radio-->
                    <div class="saicl-radio saicl-radio-v">
                        <input id="saicl-order_comments-asc" name="saicl_options[order_comments]" type="radio" value="ASC" <?php checked('ASC', $options['order_comments']); ?> />
                        <label for="saicl-order_comments-asc"><?php _e( 'The first ancient comments', 'SAICL' ); ?></label>
                        <span class="saicl-descrip-item"><?php _e( 'Sorts the comments from the oldest to the newest', 'SAICL' ); ?></span>
                    </div><!--.saicl-radio-->
                   
                </div><!--.saicl-controls-->
            </fieldset>
            <div class="saicl-line-sep"></div>
            
            <!-- Quién puede Comentar -->
            <fieldset class="saicl-control-group">
                <div class="saicl-control-label">
                    <label><?php _e( 'Who can comment?', 'SAICL' ); ?></label>
                </div><!--.saicl-control-label-->
                <div class="saicl-controls">
                	<input id="saicl-only-registered" name="saicl_options[only_registered]" type="checkbox" value="true" <?php if (isset($options['only_registered'])) { checked('true', $options['only_registered']); } ?> />
                    <label for="saicl-only-registered"><?php _e( 'Only registered users can comment', 'SAICL' ); ?></label>
                    <br/>
					<p class="saicl-descrip-item"></p>
                </div><!--.saicl-controls-->
            </fieldset>
            
            <!-- Texto quién puede Comentar -->
            <fieldset class="saicl-control-group" style="padding-top:2px;">
                <div class="saicl-control-label">
                    <label for="saicl-text-only-registered"><?php _e( 'Text for Only registered users can comment', 'SAICL' ); ?></label>
                </div><!--.saicl-control-label-->
                <div class="saicl-controls">
                	<input id="saicl-text-only-registered" name="saicl_options[text_only_registered]" type="text" value="<?php echo $options['text_only_registered']; ?>" />
					<p class="saicl-descrip-item"><?php _e( 'If the user is not registered, a link is displayed to log, you can accompany with some text', 'SAICL' ); ?></p>
                </div><!--.saicl-controls-->
            </fieldset>
            
            <div class="saicl-line-sep"></div>
            
            <!-- Carga de jQuery -->
            <fieldset class="saicl-control-group">
                <div class="saicl-control-label">
                    <label><?php _e( 'How to load jQuery?', 'SAICL' ); ?></label>
                    <p class="saicl-descrip-item"><?php echo SAICL_PLUGIN_NAME;?> <?php _e( 'need jQuery to run, as you want to load it?', 'SAICL' ); ?></p>
                </div><!--.saicl-control-label-->
                <div class="saicl-controls">
                	<input id="saicl-jquery-load" name="saicl_options[jquery-load]" type="text" value="<?php echo $options['jquery-load']; ?>" />
					<p class="saicl-descrip-item"><?php _e( 'By default it uses the jquery of your Theme or Wodpress, but if you load it on your own enter the url here', 'SAICL' ); ?></p>
                </div><!--.saicl-controls-->
            </fieldset>
            
            <div style="margin-top:6px; border-bottom: 2px dashed #DDD;"></div>
            <fieldset class="saicl-control-group">
                <div class="saicl-control-label">
                    <label for="group-name"><?php _e( 'Reset options to default', 'SAICL' ); ?></label>
                    <p class="saicl-descrip-item"></p>
                    
                </div><!--.saicl-control-label-->
                <div class="saicl-controls">
                    <input id="saicl-defaults" name="saicl_options[default_options]" type="checkbox" value="true" <?php if (isset($options['default_options'])) { checked('true', $options['default_options']); } ?> />
                    <label for="saicl-defaults"><span style="color:#333333;margin-left:3px;"><?php _e( 'Restore to default values', 'SAICL' ); ?></span></label>
                    <p class="saicl-descrip-item"><?php _e( 'Mark this option only if you want to return to the original settings of the plugin.', 'SAICL' ); ?></p>
                </div><!--.saicl-controls-->
            </fieldset>
           
            
            
        </div><!--.saicl-tab1-->
        
        
        <div id="saicl-tab2" class="saicl-tab-content">
        
        	<!-- Estilo -->
            <fieldset class="saicl-control-group">
                <div class="saicl-control-label">
                    <label for="saicl-theme"><?php _e( 'Comments Box Style', 'SAICL' ); ?></label>
                </div><!--.saicl-control-label-->
                <div class="saicl-controls">
                	<select name="saicl_options[theme]" id="saicl-theme">
                        <option value='default' <?php selected('default', $options['theme']); ?> style="padding:2px 8px;" ><?php _e( 'Default', 'SAICL' ); ?></option>
                        <option value='' <?php selected('', $options['theme']); ?> style="padding:2px 8px;" ><?php _e( 'Facebook', 'SAICL' ); ?></option>
                        <option value='' <?php selected('', $options['theme']); ?> style="padding:2px 8px;" ><?php _e( 'Golden', 'SAICL' ); ?></option>
                        <option value='dark' <?php selected('dark', $options['theme']); ?> style="padding:2px 8px;" ><?php _e( 'Dark', 'SAICL' ); ?></option>
					</select>
                    <span class="saicl-descrip-item"><?php _e( 'Select a style for your comments box', 'SAICL' ); ?></span>
                    <!--<br/>
					<p class="saicl-descrip-item"></p>-->
                    <p class="saicl-info-free"><?php _e( '* Only available "Default style" and "Dark Style" in this version.', 'SAICL' ); ?></p>
                </div><!--.saicl-controls-->
            </fieldset>
           
            <!-- Ancho de la caja de Comentarios -->
            <fieldset class="saicl-control-group">
                <div class="saicl-control-label">
                    <label for="width_comments"><?php _e( 'Width of the container of the comments', 'SAICL' ); ?></label>
                </div><!--.saicl-control-label-->
                <div class="saicl-controls">
                	<div class="saicl-float-l saicl-2-box">
                    	<input id="width_comments" type="text" name="saicl_options[width_comments]" value="<?php echo $options['width_comments']; ?>" />
                    </div><!--.saicl-3-box-->
                    <div class="saicl-float-l saicl-2-box saicl-last" style="padding-top:6px;">
                    	<input id="saicl-border" name="saicl_options[border]" type="checkbox" value="false" <?php if (isset($options['border'])) { checked('false', $options['border']); } ?> />
                    	<label for="saicl-border"><?php _e( 'Remove the container edge', 'SAICL' ); ?></label>
                    </div><!--.saicl-3-box-->
                    
                    <p class="saicl-descrip-item"><?php _e( 'Minimum width 180px. It adds the width in pixels of the box containing the comments. If you leave blank are shall refer to the width of the parent div.', 'SAICL' ); ?></p>
                </div><!--.saicl-controls-->
            </fieldset>
            
            
            <!-- Formulario de Comentarios -->
            <fieldset class="saicl-control-group" style="padding-top:2px;">
                <div class="saicl-control-label">
                    <label><?php _e( 'Display the comment form?', 'SAICL' ); ?></label>
                </div><!--.saicl-control-label-->
                <div class="saicl-controls">
                	<div class="saicl-radio saicl-radio-v">
                        <input id="saicl-display-form-true" name="saicl_options[display_form]" type="radio" value="true" <?php checked('true', $options['display_form']); ?> />
                        <label for="saicl-display-form-true"><?php _e( 'Yes', 'SAICL' ); ?></label>
                        <span class="saicl-descrip-item"><?php _e( 'It displays the form to add a comment next to the list of comments', 'SAICL' ); ?></span>
                    </div><!--.saicl-radio-->
                    <div class="saicl-radio saicl-radio-v">
                       <input id="saicl-display-form-false" name="saicl_options[display_form]" type="radio" value="false" <?php checked('false', $options['display_form']); ?> />
                        <label for="saicl-display-form-false"><?php _e( 'Not', 'SAICL' ); ?></label>
                        <span class="saicl-descrip-item"><?php _e( 'It does not show the comments form', 'SAICL' ); ?></span>
                    </div><!--.saicl-radio-->
                </div><!--.saicl-controls-->
            </fieldset>
            
            <!-- Captcha -->
            <fieldset class="saicl-control-group" style="padding-top:2px;">
                <div class="saicl-control-label">
                    <label><?php _e( 'Who show Captcha?', 'SAICL' ); ?></label>
                </div><!--.saicl-control-label-->
                <div class="saicl-controls">
                	<div class="saicl-radio saicl-radio-h saicl-4-box saicl-float-l">
                        <input id="saicl-captcha-all" name="saicl_options[display_captcha]" type="radio" value="all" <?php checked('all', $options['display_captcha']); ?> />
                        <label for="saicl-captcha-all"><?php _e( 'Show all', 'SAICL' ); ?></label>
                        
                    </div><!--.saicl-radio-->
                    <div class="saicl-radio saicl-radio-h saicl-2-box saicl-float-l">
                       <input id="saicl-captcha-non-registered" name="saicl_options[display_captcha]" type="radio" value="non-registered" <?php checked('non-registered', $options['display_captcha']); ?> />
                        <label for="saicl-captcha-non-registered"><?php _e( 'Only to non-registered users', 'SAICL' ); ?></label>
                        
                    </div><!--.saicl-radio-->
                    <div class="saicl-radio saicl-radio-h saicl-4-box saicl-float-l saicl-last">
                       <input id="saicl-captcha-not-show" name="saicl_options[display_captcha]" type="radio" value="not-show" <?php checked('not-show', $options['display_captcha']); ?> />
                        <label for="saicl-captcha-not-show"><?php _e( 'Not show', 'SAICL' ); ?></label>
                        
                    </div><!--.saicl-radio-->
                    <p class="saicl-descrip-item"><?php _e( 'It is important to use a captcha to give more security to your forms.', 'SAICL' ); ?></p>
                    
                    <p class="saicl-info-free"><?php _e( '* This functionality is only available in the Premium Version', 'SAICL' ); ?></p>
                    
                </div><!--.saicl-controls-->
            </fieldset>
            
            <!-- Botones para insertar imagenes, video y enlaces -->
            <fieldset class="saicl-control-group" style="padding-top:2px;">
                <div class="saicl-control-label">
                    <label><?php _e( 'Display buttons to share pictures, videos, and links?', 'SAICL' ); ?></label>
                </div><!--.saicl-control-label-->
                <div class="saicl-controls">
                	<div class="saicl-radio saicl-radio-h">
                        <input id="saicl-display_media_btns-true" name="saicl_options[display_media_btns]" type="radio" value="true" <?php checked('true', $options['display_media_btns']); ?> />
                        <label for="saicl-display_media_btns-true"><?php _e( 'Yes', 'SAICL' ); ?></label>
                        <span class="saicl-descrip-item"><?php _e( '', 'SAICL' ); ?></span>
                    </div><!--.saicl-radio-->
                    <div class="saicl-radio saicl-radio-h">
                       <input id="saicl-display_media_btns-false" name="saicl_options[display_media_btns]" type="radio" value="false" <?php checked('false', $options['display_media_btns']); ?> />
                        <label for="saicl-display_media_btns-false"><?php _e( 'Not', 'SAICL' ); ?></label>
                        <span class="saicl-descrip-item"><?php _e( '', 'SAICL' ); ?></span>
                    </div><!--.saicl-radio-->
                    <p class="saicl-descrip-item"><?php _e( '', 'SAICL' ); ?></p>
                    <p class="saicl-info-free"><?php _e( '* This functionality is only available in the Premium Version', 'SAICL' ); ?></p>
                </div><!--.saicl-controls-->
            </fieldset>
            
            
            <div class="saicl-line-sep"></div>
            
            <!-- Texto del enlace Mostrar Comentarios -->
            <fieldset class="saicl-control-group" style="padding-top:6px;">
                <div class="saicl-control-label">
                    <label><?php _e( 'Show comments link text', 'SAICL' ); ?></label>
                </div><!--.saicl-control-label-->
                <div class="saicl-controls">
                	<div class="saicl-float-l saicl-3-box">
                    	<input id="text_0_comments" type="text" name="saicl_options[text_0_comments]" value="<?php echo $options['text_0_comments']; ?>" />
                        <span class="saicl-descrip-item saicl-first"><?php _e( 'If the post has no comments', 'SAICL' ); ?></span>
                    </div><!--.saicl-3-box-->
                    <div class="saicl-float-l saicl-3-box">
                   		<input id="text_1_comment" type="text" name="saicl_options[text_1_comment]" value="<?php echo $options['text_1_comment']; ?>" />
                        <span class="saicl-descrip-item saicl-first"><?php _e( 'If the post has 1 comment', 'SAICL' ); ?></span>
                    </div><!--.saicl-3-box-->
                    <div class="saicl-float-l saicl-3-box saicl-last">
                    	<input id="text_more_comments" type="text" name="saicl_options[text_more_comments]" value="<?php echo $options['text_more_comments']; ?>" />
                        <span class="saicl-descrip-item saicl-first"><?php _e( 'For more than one comment', 'SAICL' ); ?></span>
                    </div><!--.saicl-3-box-->
                    
                    <p class="saicl-descrip-item"><?php _e( 'Use #N# to display the number of comments,  remove it if you don\'t want to show it.', 'SAICL' ); ?></p>
                </div><!--.saicl-controls-->
            </fieldset>
            
            <!-- Icono del enlace Mostrar Comentarios -->
            <fieldset class="saicl-control-group"  style="padding-top:2px;">
                <div class="saicl-control-label">
                    <label for="width_comments"><?php _e( 'The link icon', 'SAICL' ); ?></label>
                </div><!--.saicl-control-label-->
                <div class="saicl-controls">
                    <div class="saicl-radio saicl-radio-h saicl-float-l">
                        <input id="saicl-icon-link-true" name="saicl_options[icon-link]" type="radio" value="true" <?php checked('true', $options['icon-link']); ?> />
						<label for="saicl-icon-link-true"><?php _e( 'Show icon', 'SAICL' ); ?></label>
                        <span class="saicl-descrip-item"></span>
                    </div><!--.saicl-radio-->
                    <div class="saicl-radio saicl-radio-h saicl-float-l">
                        <input id="saicl-icon-link-false" name="saicl_options[icon-link]" type="radio" value="false" <?php checked('false', $options['icon-link']); ?> />
						<label for="saicl-icon-link-false"><?php _e( 'Not show icon', 'SAICL' ); ?></label>
                        <span class="saicl-descrip-item"></span>
                    </div><!--.saicl-radio-->
                    <p class="saicl-descrip-item"><?php _e( 'You can hide or show the icon that appears next to the link to show all comments.', 'SAICL' ); ?></p>
                </div><!--.saicl-controls-->
            </fieldset>
            <div class="saicl-line-sep"></div>
            
            <!-- Formato de la fecha de los Comentarios -->
            <fieldset class="saicl-control-group">
                <div class="saicl-control-label">
                    <label><?php _e( 'Comments date format', 'SAICL' ); ?></label>
                </div><!--.saicl-control-label-->
                <div class="saicl-controls">
                
                    <div class="saicl-radio saicl-radio-h saicl-2-box saicl-float-l">
                        <input id="saicl-date-format-true" name="saicl_options[date_format]" type="radio" value="date_fb" <?php checked('date_fb', $options['date_format']); ?> />
						<label for="saicl-date-format-true"><?php _e( 'Facebook-style format', 'SAICL' ); ?></label>
                        <span class="saicl-descrip-item"><?php _e( 'E.g: 8 mins ago', 'SAICL' ); ?></span>
                    </div><!--.saicl-radio-->
                    <div class="saicl-radio saicl-radio-h saicl-2-box saicl-float-l saicl-last">
                        <input id="saicl-date-format-false" name="saicl_options[date_format]" type="radio" value="date_wp" <?php checked('date_wp', $options['date_format']); ?> />
						<label for="saicl-date-format-false"><?php _e( 'Wordpress default format', 'SAICL' ); ?></label>
                        <span class="saicl-descrip-item"><?php _e( 'E.g: 05/09/2013', 'SAICL' ); ?></span>
                    </div><!--.saicl-radio-->
                    <p class="saicl-descrip-item"><?php _e( 'Use the format of facebook makes more appealing to your comments.', 'SAICL' ); ?></p>
                    <p class="saicl-info-free"><?php _e( '* "Facebook-style format"  is only available in the Premium Version', 'SAICL' ); ?></p>
                </div><!--.saicl-controls-->
            </fieldset>
            
            <div style="margin-top:6px; border-bottom: 2px dashed #DDD;"></div>
            
        </div><!--.saicl-tab2-->
        
        
        <div id="saicl-tab3" class="saicl-tab-content">
        	<!-- Paginación de Comentarios -->
            <fieldset class="saicl-control-group">
                <div class="saicl-control-label">
                    <label for="group-name"><?php _e( 'Activate pagination of comments', 'SAICL' ); ?></label>
                </div><!--.saicl-control-label-->
                <div class="saicl-controls">
                	<div class="saicl-radio saicl-radio-v">
                       <input id="saicl-jpages-true" name="saicl_options[jpages]" type="radio" value="true" <?php checked('true', $options['jpages']); ?> />
                        <label for="saicl-jpages-true"><?php _e( 'Yes', 'SAICL' ); ?></label>
                        <span class="saicl-descrip-item"></span>
                    </div><!--.saicl-radio-->
                    <div class="saicl-radio saicl-radio-v">
                       <input id="saicl-jpages-false" name="saicl_options[jpages]" type="radio" value="false" <?php checked('false', $options['jpages']); ?> />
                       <label for="saicl-jpages-false"><?php _e( 'Not', 'SAICL' ); ?></label>
                        <span class="saicl-descrip-item"></span>
                    </div><!--.saicl-radio-->
                    <p class="saicl-info-free"><?php _e( '* This functionality is only available in the Premium Version', 'SAICL' ); ?></p>
                </div><!--.saicl-controls-->
            </fieldset>
            
            <!-- Número de Comentarios por Página -->
            <fieldset class="saicl-control-group">
                <div class="saicl-control-label">
                    <label for="group-name"><?php _e( 'Number of comments per page', 'SAICL' ); ?></label>
                    <p class="saicl-descrip-item"></p>
                    
                </div><!--.saicl-control-label-->
                <div class="saicl-controls">
                    <input id="saicl-num-comments-by-page" type="text" name="saicl_options[num_comments_by_page]" value="<?php echo $options['num_comments_by_page']; ?>" />
                    <p class="saicl-descrip-item"><?php _e( 'Default value', 'SAICL' ); ?>: 10<br/><strong><?php _e( 'Note:', 'SAICL' ); ?></strong><?php _e( 'If the total number of comments is less than the number of comments per page, the pager will not be displayed.', 'SAICL' ); ?></p>
                </div><!--.saicl-controls-->
            </fieldset>
            <div class="saicl-line-sep"></div>
            
            <!-- Activar Textarea Counter -->
            <fieldset class="saicl-control-group">
                <div class="saicl-control-label">
                    <label for="group-name"><?php _e( 'Activate character limiter', 'SAICL' ); ?></label>
                </div><!--.saicl-control-label-->
                <div class="saicl-controls">
                	<div class="saicl-radio saicl-radio-v">
                       <input id="saicl-text_counter-true" name="saicl_options[text_counter]" type="radio" value="true" <?php checked('true', $options['text_counter']); ?> />
                        <label for="saicl-text_counter-true"><?php _e( 'Yes', 'SAICL' ); ?></label>
                        <span class="saicl-descrip-item"></span>
                    </div><!--.saicl-radio-->
                    <div class="saicl-radio saicl-radio-v">
                       <input id="saicl-text_counter-false" name="saicl_options[text_counter]" type="radio" value="false" <?php checked('false', $options['text_counter']); ?> />
                       <label for="saicl-text_counter-false"><?php _e( 'Not', 'SAICL' ); ?></label>
                       <span class="saicl-descrip-item"></span>
                    </div><!--.saicl-radio-->
                </div><!--.saicl-controls-->
            </fieldset>
            
            <!-- Número de Máximo de Caracteres -->
            <fieldset class="saicl-control-group">
                <div class="saicl-control-label">
                    <label for="group-name"><?php _e( 'Maximum number of characters for comment', 'SAICL' ); ?></label>
                    <p class="saicl-descrip-item"></p>
                    
                </div><!--.saicl-control-label-->
                <div class="saicl-controls">
                    <input id="saicl-text_counter_num" type="text" name="saicl_options[text_counter_num]" value="<?php echo $options['text_counter_num']; ?>" />
                    <p class="saicl-descrip-item"><?php _e( 'Default value', 'SAICL' ); ?>: 300.</p>
                </div><!--.saicl-controls-->
            </fieldset>
            
        </div><!--.saicl-tab3-->
        
        <div id="saicl-tab4" class="saicl-tab-content saicl-help">
        	<h3><?php _e( 'How to display the '.SAICL_PLUGIN_NAME.'?', 'SAICL' ); ?></h3> 
            <p><?php echo sprintf(__( 'Check Yes the box %s "Auto Show Simple Ajax Insert Comments?" %s this options panel. %s If you do not want to automatically display, insert %s where you want to show comments.', 'SAICL' ), '<strong>', '</strong>', '<br/>', '<strong>&lt;?php if(function_exists("display_saic")) { echo display_saic();} ?&gt;</strong>' ); ?>  </p>
            
            <div class="saicl-line-sep"></div>
            
            <p class="saicl-easy"><?php _e( 'This plugin works super easy, I do not think that you need more help!', 'SAICL' ); ?></p>
            
        </div><!--.saicl-tab4-->
        
    </div><!--.saicl-tab-container-->
    
    <fieldset id="saicl-item-submit" class="saicl-control-group" style="padding:0">
        <div class="saicl-control-label">
            <p class="submit">
                <input type="submit" name="Submit" class="button-primary" value="<?php _e('Save Changes', 'SAICL') ?>" />
            </p>
        </div><!--.saicl-control-label-->
        <div class="saicl-controls">
        </div><!--.saicl-controls-->
    </fieldset>
    
	</form>

</div><!--.wrap-->
<?php	
?>