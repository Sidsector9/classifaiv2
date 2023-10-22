<?php

namespace Classifai\Admin\Fields;

interface IFieldControl {
    public function sanitize_setting( $value );
    public function render();
}