<div class="wrap">
    <h2>Prophet ClickToTweet Settings</h2>
	<form method="post" id="pbs_clicktotweet_form" action="options.php">
		<?php settings_fields( 'pbs_clicktotweet_option_group' ); ?>
		<table class="form-table">
			<tr valign="top">
				<th scope="row">Popup Dimensions</th>
				<td>
					<input type="number" step="100" name="popup_width" value="<?php echo get_option("popup_width") ?>" style="width: 100px; text-align: right;"> &times
					<input type="number" step="100" name="popup_height" value="<?php echo get_option("popup_height") ?>" style="width: 100px; text-align: right;">
					<small> (w&times;h, pixels)</small>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">Sharing Link</th>
				<td>
					<textarea name="popup_url" style="font: 14px monospace; width: 100%; "><?php echo get_option("popup_url") ?></textarea>
					<small><strong>IMPORTANT:</strong>
						The url can contain up to two <code>%s</code> placeholders.
						The first is replaced with the text that should be tweeted, the second will be replaced with the backlink to the page.
					</small>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">Truncate Text?</th>
				<td>
					<input type="checkbox" name="truncate" id="truncate" value="1"<?php echo (get_option('truncate')) == 1 ? " checked='checked'" : ""; ?>" />
					<label for="truncate">Enable text truncation</label><br><small>If enabled, the to-be-tweeted text will be truncated to fit the 140 chars limit.</small>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">Truncate URL Length</th>
				<td>
					<input type="number" step="1" name="truncate_url_length" value="<?php echo get_option("truncate_url_length") ?>" style="width: 100px; text-align: right;">
					<br><small>
						Indicates how many chars should be removed additionally in case you want to automatically add a backlink to the tweet. The following settings apply:<br>
						<code>-1</code> &mdash; determine automatically (based on permalink url of post)<br>
						<code>&#8469;</code> &mdash; natural number, fixed length
					</small>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">Truncate Ellipsis</th>
				<td>
					<input type="text" name="truncate_ellipsis" value="<?php echo get_option("truncate_ellipsis") ?>" style="width: 30px;">
				</td>
			</tr>
		</table>
		<?php submit_button() ?>
	</form>
</div>
<script>
jQuery(document).ready(function (){
	jQuery("#pbs_clicktotweet_form").submit(function () {
		var ok = true;
		jQuery(":input", this).each(function () {
			//
			// popup size validation
			//
			if (jQuery(this).attr("name") == "popup_width" || jQuery(this).attr("name") == "popup_height") {
				if (jQuery(this).val() <= 0) {
					alert("Popup window size cannot be smaller or equals 0");
					ok = false;
				}
			}

			//
			// URL validation
			//
			if (jQuery(this).attr("name") == "popup_url" && jQuery.trim(jQuery(this).val()).length == 0) {
				alert("URL cannot be blank.");
				ok = false;
			}
		});
		return ok;
	});
});
</script>