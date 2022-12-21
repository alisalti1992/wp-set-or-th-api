<div class="wrap">
    <h1><?php _e( 'Set.or.th API Options' ); ?></h1>
	<?php do_action( 'set_api_retrieve_data' ); ?>
    <form method="post" action="options.php">
		<?php
		settings_fields( 'set-api' );
		do_settings_sections( 'set-api' );

        ?>
        <table class="form-table">
            <tr>
                <th scope="row"><?php _e( 'Company Name' ); ?></th>
                <td><input type="text" name="set_api_company_name"
                           value="<?php echo esc_attr( get_option( 'set_api_company_name' ) ); ?>"/></td>
            </tr>
            <tr>
                <th scope="row"><?php _e( 'Description' ); ?></th>
                <td>
					<?php _e( 'Go to https://www.set.or.th/ Search for the company, copy the name and paste it here.' ); ?>
                </td>
            </tr>
        </table>
        <h2><?php _e( 'Shortcodes' ); ?></h2>
		<?php $data = set_api_get_data(); ?>
        <table class="form-table">
            <tr>
                <th scope="row">[set_api_last_update]</th>
                <td><input type="text" name="set_api_update_date"
                           value="<?php echo esc_attr( get_option( 'set_api_update_date' ) ); ?>"/></td>
            </tr>
            <tr>
                <th scope="row">[set_api_last_update_time]</th>
                <td><input type="text" name="set_api_update_time"
                           value="<?php echo esc_attr( get_option( 'set_api_update_time' ) ); ?>"/></td>
            </tr>
            <tr>
                <th scope="row">[set_api_up_or_down]</th>
                <td>
                    <select name="set_api_up_down">
                        <option value="up" <?php if ( get_option( 'set_api_up_down' ) == 'up' ) {
							echo 'selected';
						} ?> >Up
                        </option>
                        <option value="down" <?php if ( get_option( 'set_api_up_down' ) == 'down' ) {
							echo 'selected';
						} ?>>Down
                        </option>
                        <option value="equal" <?php if ( get_option( 'set_api_up_down' ) == 'equal' ) {
							echo 'selected';
						} ?>>Equal
                        </option>
                    </select>
                </td>
            </tr>
            <tr>
                <th scope="row">[set_api_last]</th>
                <td><input type="text" name="set_api_last"
                           value="<?php echo esc_attr( get_option( 'set_api_last' ) ); ?>"/></td>
            </tr>
            <tr>
                <th scope="row">[set_api_prior]</th>
                <td><input type="text" name="set_api_prior"
                           value="<?php echo esc_attr( get_option( 'set_api_prior' ) ); ?>"/></td>
            </tr>
            <tr>
                <th scope="row">[set_api_change]</th>
                <td><input type="text" name="set_api_change"
                           value="<?php echo esc_attr( get_option( 'set_api_change' ) ); ?>"/></td>
            </tr>
            <tr>
                <th scope="row">[set_api_change_percent]</th>
                <td><input type="text" name="set_api_change_percent"
                           value="<?php echo esc_attr( get_option( 'set_api_change_percent' ) ); ?>"/></td>
            </tr>
            <tr>
                <th scope="row">[set_api_volume]</th>
                <td><input type="text" name="set_api_volume"
                           value="<?php echo esc_attr( get_option( 'set_api_volume' ) ); ?>"/></td>
            </tr>
            <tr>
                <th scope="row">[set_api_value]</th>
                <td><input type="text" name="set_api_value"
                           value="<?php echo esc_attr( get_option( 'set_api_value' ) ); ?>"/></td>
            </tr>
            <tr>
                <th scope="row"><?php _e( 'Use class "set-api-class" to set the up and down class for the element' ); ?></th>
                <td></td>
            </tr>
        </table>
		<?php submit_button(); ?>

    </form>
</div>