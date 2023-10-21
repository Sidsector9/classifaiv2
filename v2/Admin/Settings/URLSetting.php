<?php

namespace Classifai\Admin\Settings;

class URLSetting {
	private $manager;

	public function __construct( $manager ) {
		$this->manager = $manager;
	}

	public function sanitize( $value ) {
		return sanitize_url( $value );;
	}

	public function save( $feature, $value ) {
		$value = $this->sanitize( $value );
		$this->manager->set_setting( $feature, 'url', $value );
	}

	public function render( $feature ) {
		$url_value = $this->manager->get_setting( $feature, 'url' );
		?>
		<label for="classifai_<?php echo $feature; ?>_url">url:</label>
		<input type="text" name="classifai_features_config[<?php echo $feature; ?>][url]" id="classifai_<?php echo $feature; ?>_url" value="<?php echo $url_value; ?>">
		<p class="description">Description of the url setting.</p>
		<?php
	}
}

