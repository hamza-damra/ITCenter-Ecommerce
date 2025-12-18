<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines.
    |
    */

    // Category hierarchy validation
    'category_parent_inactive' => 'The selected parent category is inactive.',
    'category_max_depth' => 'Cannot create a category at this level. Maximum hierarchy depth is 3 levels (parent → child → sub-child).',
    'category_circular_reference' => 'Cannot set this parent as it would create a circular reference.',
    'category_slug_unique' => 'This category slug is already in use. Please choose a different name.',
];
