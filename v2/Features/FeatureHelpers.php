<?php

use \Classifai\Admin\Settings;
use \Classifai\Features\FeaturePostTitleGeneration;
use \Classifai\Services\TextGeneration\OpenAI\ChatGPTPostTitleGeneration;

function generate_post_title( $post_id, $args ) {
	$feature_settings = new Settings( 'feature', FeaturePostTitleGeneration::ID );
	$generator = new FeaturePostTitleGeneration( $feature_settings );

	return $generator->generate( $post_id, $args );
}
