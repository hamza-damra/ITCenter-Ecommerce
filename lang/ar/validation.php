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
    'category_parent_inactive' => 'الفئة الأم المحددة غير نشطة.',
    'category_max_depth' => 'لا يمكن إنشاء فئة في هذا المستوى. الحد الأقصى لعمق التسلسل الهرمي هو 3 مستويات (أب ← ابن ← حفيد).',
    'category_circular_reference' => 'لا يمكن تعيين هذا الأب لأنه سيؤدي إلى إنشاء مرجع دائري.',
    'category_slug_unique' => 'هذا الرابط المختصر للفئة مستخدم بالفعل. يرجى اختيار اسم مختلف.',
];
