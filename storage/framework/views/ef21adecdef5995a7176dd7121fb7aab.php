
<?php if(isset($specialDiscounts) && $specialDiscounts->count() > 0): ?>
    <div class="container">
        <?php if (isset($component)) { $__componentOriginal87d4e907aef0f5d4d0507d4d54c177ce = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal87d4e907aef0f5d4d0507d4d54c177ce = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.horizontal-product-scroller','data' => ['products' => $specialDiscounts,'title' => ''.e(__t('messages.special_discounts')).'','viewMoreUrl' => route('products', ['filter' => 'sale']),'autoScroll' => true,'autoScrollInterval' => 4500,'cardsToScroll' => 1,'cartProductIds' => $cartProductIds,'showDiscountPercentage' => true,'containerId' => 'special-discounts-scroller']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('horizontal-product-scroller'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['products' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($specialDiscounts),'title' => ''.e(__t('messages.special_discounts')).'','viewMoreUrl' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('products', ['filter' => 'sale'])),'autoScroll' => true,'autoScrollInterval' => 4500,'cardsToScroll' => 1,'cartProductIds' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($cartProductIds),'showDiscountPercentage' => true,'containerId' => 'special-discounts-scroller']); ?>
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
<?php /**PATH C:\Users\Hamza Damra\Documents\ITCenter-Ecommerce\resources\views/home/sections/special-discounts.blade.php ENDPATH**/ ?>