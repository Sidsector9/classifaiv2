<?php

namespace Classifai\Services;

use \Classifai\Services\TextGeneration\OpenAI\ChatGPTPostTitleGeneration;

class ProviderRegistry {
	public static function get_list() {
		return [
			ChatGPTPostTitleGeneration::ID => [
				'class' => ChatGPTPostTitleGeneration::class,
				'connector_id' => ChatGPTPostTitleGeneration::CONNECTOR_ID
			],
		];
	}
}
