
<?php
    $sectionProducts = $section->products()
        ->with(['brand:id,name_en,name_ar,name_he,slug', 'category:id,name_en,name_ar,name_he,slug'])
        ->where('is_active', true)
        ->limit($section->getSetting('max_products', 8))
        ->get();
?>

<?php if($sectionProducts->count() > 0): ?>
    <div class="container" <?php if($section->getSetting('background_color')): ?> style="background-color: <?php echo e($section->getSetting('background_color')); ?>;" <?php endif; ?>>
        <?php if (isset($component)) { $__componentOriginal87d4e907aef0f5d4d0507d4d54c177ce = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal87d4e907aef0f5d4d0507d4d54c177ce = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.horizontal-product-scroller','data' => ['products' => $sectionProducts,'title' => $section->title,'autoScroll' => (bool) $section->getSetting('auto_scroll', false),'autoScrollInterval' => (int) $section->getSetting('auto_scroll_interval', 5000),'cardsToScroll' => (int) $section->getSetting('cards_to_scroll', 1),'cartProductIds' => $cartProductIds ?? [],'containerId' => 'custom-section-'.e($section->id).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('horizontal-product-scroller'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['products' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($sectionProducts),'title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($section->title),'autoScroll' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute((bool) $section->getSetting('auto_scroll', false)),'autoScrollInterval' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute((int) $section->getSetting('auto_scroll_interval', 5000)),'cardsToScroll' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute((int) $section->getSetting('cards_to_scroll', 1)),'cartProductIds' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($cartProductIds ?? []),'containerId' => 'custom-section-'.e($section->id).'']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal87d4e907aef0f5d4d0507d4d54c177ce)): ?>
<?php $attributes = $__attributesOriginal87d4e907aef0f5d4d0507d4d54c177ce; ?>
<?php unset($__attributesOriginal87d4e907aef0f5d4d0507d4d54c177ce); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal87d4e907aef0f5d4d0507d4d54c177ce)): ?>
<?php $component = $__componentOriginal87d4e907aef0f5d4d0507d4d54c177ce; ?>
<?php unset($__componentOriginal87d4e907aef0f5d4d0507d4d54c177ce); ?>
<?php endif; ?>
    </div>
<?php endif; ?>
<?php /**PATH C:\Users\Hamza Damra\Documents\ITCenter-Ecommerce\resources\views/home/sections/custom-product-section.blade.php ENDPATH**/ ?>