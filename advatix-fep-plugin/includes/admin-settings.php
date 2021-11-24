<style>
	.pass-container {
		position: relative;
	}
	.pass-icons {
		position: absolute;
		left: 325px;
		top: 5px;
		cursor: pointer;
	}
	.pass-container input {
		padding-right: 30px;
	}
</style>
<div class="wrap">

	<h1><?php esc_html_e( 'Advatix Settings', 'advatix-fep-plugin' ); ?></h1><hr>

	<form method="post" action="options.php">
		
		<?php settings_fields( 'theme_options' ); ?>

		<table class="form-table wpex-custom-admin-login-table">

			<tr valign="top">
				<th scope="row"><?php esc_html_e( 'Company Name', 'advatix-fep-plugin' ); ?></th>
				<td>
					<?php $value = self::get_theme_option( 'company_name' ); ?>
					<input class="regular-text" type="text" name="theme_options[company_name]" value="<?php echo esc_attr( $value ); ?>">
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><?php esc_html_e( 'LOB', 'advatix-fep-plugin' ); ?></th>
				<td>
					<?php $value = self::get_theme_option( 'input_lob' ); ?>
					<select class="regular-text" name="theme_options[input_lob]">
						<option value="">-- Select LOB --</option>
						<option value="3" <?php echo esc_attr( $value )==3?'selected':''; ?>>D2C</option>
						<option value="9" <?php echo esc_attr( $value )==9?'selected':''; ?>>B2B</option>
						<option value="2" <?php echo esc_attr( $value )==2?'selected':''; ?>>Micro kitchen</option>
					</select>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><?php esc_html_e( 'API Key', 'advatix-fep-plugin' ); ?></th>
				<td>
					<div class="pass-container">
						<?php $value = self::get_theme_option( 'input_api_key' ); ?>
						<input class="regular-text" type="password" name="theme_options[input_api_key]" value="<?php echo esc_attr( $value ); ?>">
						<span class="dashicons dashicons-visibility pass-icons"></span>
					</div>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><?php esc_html_e( 'API Base Url', 'advatix-fep-plugin' ); ?></th>
				<td>
					<?php $value = self::get_theme_option( 'input_api_url' ); ?>
					<input class="regular-text" type="text" placeholder="https://xyz.xpdel.com" name="theme_options[input_api_url]" value="<?php echo esc_attr( $value ); ?>">
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><?php esc_html_e( 'Account ID', 'advatix-fep-plugin' ); ?></th>
				<td>
					<?php $value = self::get_theme_option( 'account_id' ); ?>
					<input class="regular-text" type="text" name="theme_options[account_id]" value="<?php echo esc_attr( $value ); ?>">
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><?php esc_html_e( 'Sync Inventory', 'advatix-fep-plugin' ); ?></th>
				<td>
					<?php $value = self::get_theme_option( 'sync_inventory' ); ?>
					<select class="regular-text" name="theme_options[sync_inventory]">
						<option value="0" <?php echo esc_attr( $value )==0?'selected':''; ?>>No</option>
						<option value="1" <?php echo esc_attr( $value )==1?'selected':''; ?>>Yes</option>
					</select>
				</td>
			</tr>

		</table><hr>

		<?php submit_button(); ?>

	</form>

</div>
<script>
	jQuery(document).ready(function(){
		jQuery('.pass-icons').click(function(){
			if(jQuery(this).hasClass('dashicons-visibility')){
				jQuery(this).removeClass('dashicons-visibility');
				jQuery(this).addClass('dashicons-hidden');
				jQuery('input[name="theme_options[input_api_key]"]').attr('type','text');
			}else{
				jQuery(this).removeClass('dashicons-hidden');
				jQuery(this).addClass('dashicons-visibility');
				jQuery('input[name="theme_options[input_api_key]"]').attr('type','password');
			}
		});
	});
</script>