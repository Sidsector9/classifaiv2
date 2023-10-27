<?php

namespace Classifai\Features;

/**
 * Interface IFeature
 */
interface IFeature {
	/**
	 * Every feature must have a generate method.
	 * Most of the time, the generate method will have the same logic, but in some cases, it might be different.
	 * This is why this method is not defined in the \Classifai\Features\Feature class.
	 *
	 * @param mixed ...$args Every feature can have different arguments.
	 *
	 * @return mixed
	 */
	public function generate( ...$args );
}