<?php if($paginator->hasPages()): ?>
    <nav role="navigation" aria-label="<?php echo e(__('Pagination Navigation')); ?>" style="width: 100%;">
        <div style="display: flex; align-items: center; justify-content: center; gap: 0.5rem; width: 100%;">
            
            <?php if($paginator->onFirstPage()): ?>
                <span aria-disabled="true" aria-label="<?php echo e(__('pagination.previous')); ?>" style="display: inline-flex; align-items: center; justify-content: center; padding: 0.5rem 1rem; background: #f5f5f5; border: 1px solid #ddd; border-radius: 8px; color: #999; cursor: not-allowed; font-size: 0.9rem; font-weight: 500;">
                    « Previous
                </span>
            <?php else: ?>
                <a href="<?php echo e($paginator->previousPageUrl()); ?>" rel="prev" aria-label="<?php echo e(__('pagination.previous')); ?>" style="display: inline-flex; align-items: center; justify-content: center; padding: 0.5rem 1rem; background: #fff; border: 1px solid #ddd; border-radius: 8px; color: #333; text-decoration: none; font-weight: 500; transition: all 0.3s; font-size: 0.9rem;">
                    « Previous
                </a>
            <?php endif; ?>

            
            <?php $__currentLoopData = $elements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $element): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                
                <?php if(is_string($element)): ?>
                    <span aria-disabled="true" style="display: inline-flex; align-items: center; justify-content: center; min-width: 40px; height: 40px; padding: 0.5rem 1rem; background: #fff; border: 1px solid #ddd; border-radius: 8px; color: #999; font-weight: 500;">
                        <?php echo e($element); ?>

                    </span>
                <?php endif; ?>

                
                <?php if(is_array($element)): ?>
                    <?php $__currentLoopData = $element; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page => $url): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if($page == $paginator->currentPage()): ?>
                            <span aria-current="page" style="display: inline-flex; align-items: center; justify-content: center; min-width: 40px; height: 40px; padding: 0.5rem 1rem; background: #e69270; border: 1px solid #e69270; border-radius: 8px; color: #fff; font-weight: 600;">
                                <?php echo e($page); ?>

                            </span>
                        <?php else: ?>
                            <a href="<?php echo e($url); ?>" aria-label="<?php echo e(__('Go to page :page', ['page' => $page])); ?>" style="display: inline-flex; align-items: center; justify-content: center; min-width: 40px; height: 40px; padding: 0.5rem 1rem; background: #fff; border: 1px solid #ddd; border-radius: 8px; color: #333; text-decoration: none; font-weight: 500; transition: all 0.3s;">
                                <?php echo e($page); ?>

                            </a>
                        <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            
            <?php if($paginator->hasMorePages()): ?>
                <a href="<?php echo e($paginator->nextPageUrl()); ?>" rel="next" aria-label="<?php echo e(__('pagination.next')); ?>" style="display: inline-flex; align-items: center; justify-content: center; padding: 0.5rem 1rem; background: #fff; border: 1px solid #ddd; border-radius: 8px; color: #333; text-decoration: none; font-weight: 500; transition: all 0.3s; font-size: 0.9rem;">
                    Next »
                </a>
            <?php else: ?>
                <span aria-disabled="true" aria-label="<?php echo e(__('pagination.next')); ?>" style="display: inline-flex; align-items: center; justify-content: center; padding: 0.5rem 1rem; background: #f5f5f5; border: 1px solid #ddd; border-radius: 8px; color: #999; cursor: not-allowed; font-size: 0.9rem; font-weight: 500;">
                    Next »
                </span>
            <?php endif; ?>
        </div>
    </nav>

    <style>
        nav[aria-label="Pagination Navigation"] a:hover {
            background: #e69270 !important;
            color: #fff !important;
            border-color: #e69270 !important;
        }
        nav[aria-label="Pagination Navigation"] svg {
            display: none !important;
        }
    </style>
<?php endif; ?>
<?php /**PATH C:\Users\Hamza Damra\ITCenter-Ecommerce\resources\views/vendor/pagination/tailwind.blade.php ENDPATH**/ ?>