<?php /**
* WordPress Settings Page
*/


function easy_ufdc_page() {
// Check the user capabilities
	if ( !current_user_can( 'manage_woocommerce' ) ) {
		wp_die( __( 'You do not have sufficient permissions to access this page.', 'easy-ufdc' ) );
	}
// Save the field values
	if ( isset( $_POST['ufdc_fields_submitted'] ) && $_POST['ufdc_fields_submitted'] == 'submitted' ) {
		delete_option('easy_ufdc_use_style');
		foreach ( $_POST as $key => $value ) {
		
			//pre($key.'>'.$value);
			
			if ( get_option( $key ) != $value ) {
				update_option( $key, $value );
			} else {
				add_option( $key, $value, '', 'no' );
			}
		}
		
		
	}
global $easy_ufdc_page, $ufdc_custom, $eufdc_data, $ufdc_premium_link;
$easy_ufdc_page = get_option( 'easy_ufdc_page' );
	
?>

<div class="wrap">
	<div id="icon-options-general" class="icon32"></div>
	<h2><?php _e( 'Easy Upload Files During Checkout', 'easy-ufdc' ); ?> <?php echo '('.$eufdc_data['Version'].($ufdc_custom?') Pro':')'); ?> - Settings</h2>
	<?php if ( isset( $_POST['ufdc_fields_submitted'] ) && $_POST['ufdc_fields_submitted'] == 'submitted' ) { ?>
	<div id="message" class="updated fade"><p><strong><?php _e( 'Your settings have been saved.', 'easy-ufdc' ); ?></strong></p></div>
	<?php } ?>
	<div id="content">
		<form method="post" action="" id="ufdc_settings">
			<input type="hidden" name="ufdc_fields_submitted" value="submitted">
			<div id="poststuff">
				<div style="float:left; width:100%;">
					<div class="postbox">
						
						<div class="inside ufdc-settings">
							<table class="form-table">
                            
                            
<tr>
    								<th>
    									<label for="easy_ufdc_caption"><b><?php _e( 'Caption:', 'easy-ufdc' ); ?></b></label>
    								</th>
    								<td>


<textarea id="easy_ufdc_caption" style="width:50%; height:60px" name="easy_ufdc_caption" placeholder="Do you have something to attach?"><?php echo get_option( 'easy_ufdc_caption' ); ?></textarea>
                                        
    								</td>
    							</tr>                            
                            
							<tr>
    								<th>
    									<label for="easy_ufdc_page"><b><?php _e( 'Display on:', 'easy-ufdc' ); ?></b></label>
    								</th>
    								<td>


                                    <ul>
                                    <li><input type="radio" name="easy_ufdc_page" id="easy_ufdc_page_cart" value="cart" <?php if($easy_ufdc_page=='cart' || !$easy_ufdc_page) { echo 'checked="checked"'; } ?> />
                                    <label for="easy_ufdc_page_cart">&nbsp;Cart Page</label>
                                    </li>
                                    <li><input type="radio" name="easy_ufdc_page" id="easy_ufdc_page_checkout" value="checkout" <?php if($easy_ufdc_page=='checkout'){ echo 'checked="checked"'; } ?> />
                                     <label for="easy_ufdc_page_checkout">&nbsp;Checkout Page</label>
                                     </li>
                                    </ul>
                                    
    									
                                        
    								</td>
    							</tr>
                                                            

<tr>
<th>
<label for="easy_ufdc_limit"><b><?php _e( 'Multiple files:', 'easy-ufdc' ); ?></b></label><br />
<small><?php echo (!$ufdc_custom?'<a style="color:red; font-weight:normal;" href="'.$ufdc_premium_link.'" target="_blank">Premium Feature</a>':''); ?></small>
</th>
<td>
<input type="text" name="easy_ufdc_limit" class="regular-text" value="<?php if(!get_option( 'easy_ufdc_limit' )) { echo '1'; } else { echo stripslashes(get_option( 'easy_ufdc_limit' )); }?>"/><br />
<span class="description"><?php
echo __( 'Specify number of files allowed to upload, number only.', 'easy-ufdc' );
?></span>
</td>
</tr>
                                
                                
                                                                               
								<tr>
    								<th>
    									<label for="easy_ufdc_allowed_file_types"><b><?php _e( 'Allowed file types:', 'easy-ufdc' ); ?></b></label>
    								</th>
    								<td>
    									<input type="text" name="easy_ufdc_allowed_file_types" class="regular-text" value="<?php if(!get_option( 'easy_ufdc_allowed_file_types' )) { echo 'doc,txt'; } else { echo stripslashes(get_option( 'easy_ufdc_allowed_file_types' )); }?>"/><br />
    									<span class="description"><?php
    										echo __( 'Specify which file types are allowed for uploading, seperate by commas.', 'easy-ufdc' );
    									?></span>
    								</td>
    							</tr>
                                <tr>
    								<th>
    									<label for="easy_ufdc_req"><b><?php _e( 'Make upload field required?', 'easy-ufdc' ); ?></b></label>
    								</th>
    								<td>
                             
                             
<input type="radio" name="easy_ufdc_req"  value="1" <?php if(get_option( 'easy_ufdc_req' ) && get_option( 'easy_ufdc_req' )==1) { echo 'checked="checked"'; } ?> />
<label>Yes</label>
<br />
<input type="radio" name="easy_ufdc_req" value="0" <?php if(!get_option( 'easy_ufdc_req' ) || get_option( 'easy_ufdc_req' )!=1) { echo 'checked="checked"'; } ?> />
<label>No</label>
<br />


    									<span class="description"><?php
    										echo __( '&nbsp;', 'easy-ufdc' );
    									?></span>
    								</td>
    							</tr>
                                
								<tr>
    								<th>
    									<label for="easy_ufdc_max_uploadsize"><b><?php _e( 'Maximum upload size:', 'easy-ufdc' ); ?></b></label>
    								</th>
    								<td>
    									<input type="text" name="easy_ufdc_max_uploadsize" class="short" value="<?php if(!get_option( 'easy_ufdc_max_uploadsize' )) { echo ini_get('upload_max_filesize'); } else { echo stripslashes(get_option( 'easy_ufdc_max_uploadsize' )); }?>"/><br />
    									<span class="description"><?php
    										echo __( 'Specify maximum upload size for all files in MegaBytes. Cannot exceed max. PHP upload size.', 'easy-ufdc' ).'<br>';
											echo __( 'Note: recommended max. upload size below 8MB.', 'easy-ufdc' );
    									?></span>
    								</td>
    							</tr>

								
    
<?php if($ufdc_custom): ?>                                
<tr>
    								<th>
    									<label for="woocommerce_ufdc_max_wh"><b><?php _e( 'Dimensions Check:', 'woocommerce-ufdc' ); ?></b><br />
<small>*For Images Only</small>
</label>
    								</th>
    								<td>
    									<span class="min_max">Min Width:</span> <input type="text" name="woocommerce_ufdc_min_w" class="short min_max" value="<?php if(!get_option( 'woocommerce_ufdc_min_w' )) { echo ''; } else { echo stripslashes(get_option( 'woocommerce_ufdc_min_w' )); }?>"/>&nbsp;
                                        <span class="min_max">Max Width:</span> <input type="text" name="woocommerce_ufdc_max_w" class="short min_max" value="<?php if(!get_option( 'woocommerce_ufdc_max_w' )) { echo ''; } else { echo stripslashes(get_option( 'woocommerce_ufdc_max_w' )); }?>"/><br />

                                        <span class="min_max">Min Height:</span> <input type="text" name="woocommerce_ufdc_min_h" class="short min_max" value="<?php if(!get_option( 'woocommerce_ufdc_min_h' )) { echo ''; } else { echo stripslashes(get_option( 'woocommerce_ufdc_min_h' )); }?>"/>&nbsp;
                                        <span class="min_max">Max Height:</span> <input type="text" name="woocommerce_ufdc_max_h" class="short min_max" value="<?php if(!get_option( 'woocommerce_ufdc_max_h' )) { echo ''; } else { echo stripslashes(get_option( 'woocommerce_ufdc_max_h' )); }?>"/>
    								<br />
	
 <span class="description"><?php
echo __( 'Leave empty for no restrictions.', 'woocommerce-ufdc' );
?></span>                                       
    								</td>
    							</tr>																
                                               
<?php endif; ?>                                                                
								<tr>
									<td colspan="2" style="padding:0">
										<p class="submit"><input type="submit" name="Submit" class="button-primary" value="<?php _e( 'Save Changes', 'easy-ufdc' ); ?>" /></p>
									</td>
								</tr>
							</table>
						</div>
					</div>
				</div>
				
			</div>
		</form>
	</div>
</div>
<?php }

?>
