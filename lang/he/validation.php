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
    'category_parent_inactive' => 'קטגוריית האב שנבחרה אינה פעילה.',
    'category_max_depth' => 'לא ניתן ליצור קטגוריה ברמה זו. עומק ההיררכיה המרבי הוא 3 רמות (הורה ← ילד ← נכד).',
    'category_circular_reference' => 'לא ניתן להגדיר הורה זה מכיוון שהוא ייצור הפניה מעגלית.',
    'category_slug_unique' => 'ה-slug של הקטגוריה כבר בשימוש. אנא בחר שם אחר.',
];
