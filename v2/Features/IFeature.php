<?php

namespace Classifai\Features;

interface IFeature {
	public function generate( ...$args );
}