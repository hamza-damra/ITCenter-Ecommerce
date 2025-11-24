<?php if($paginator->hasPages()): ?>
    <nav role="navigation" aria-label="Pagination Navigation" style="width: 100%;">
        <ul class="pagination" style="display: flex; gap: 0.5rem; align-items: center; justify-content: center; list-style: none; padding: 0; margin: 0;">
            
            <?php if($paginator->onFirstPage()): ?>
                <li class="page-item disabled" aria-disabled="true" aria-label="<?php echo app('translator')->get('pagination.previous'); ?>" style="list-style: none;">
                    <span class="page-link" style="display: flex; align-items: center; justify-content: center; min-width: 40px; height: 40px; padding: 0.5rem 1rem; background: #f5f5f5; border: 1px solid #ddd; border-radius: 8px; color: #999; cursor: not-allowed; font-size: 1.2rem;">&lsaquo;</span>
                </li>
            <?php else: ?>
                <li class="page-item" style="list-style: none;">
                    <a class="page-link" href="<?php echo e($paginator->previousPageUrl()); ?>" rel="prev" aria-label="<?php echo app('translator')->get('pagination.previous'); ?>" style="display: flex; align-items: center; justify-content: center; min-width: 40px; height: 40px; padding: 0.5rem 1rem; background: #fff; border: 1px solid #ddd; border-radius: 8px; color: #333; text-decoration: none; font-weight: 500; transition: all 0.3s; font-size: 1.2rem;">&lsaquo;</a>
                </li>
            <?php endif; ?>

            
            <?php $__currentLoopData = $elements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $element): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                
                <?php if(is_string($element)): ?>
                    <li class="page-item disabled" aria-disabled="true" style="list-style: none;">
                        <span class="page-link" style="display: flex; align-items: center; justify-content: center; min-width: 40px; height: 40px; padding: 0.5rem 1rem; background: #fff; border: 1px solid #ddd; border-radius: 8px; color: #999; font-weight: 500;"><?php echo e($element); ?></span>
                    </li>
                <?php endif; ?>

                
                <?php if(is_array($element)): ?>
                    <?php $__currentLoopData = $element; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page => $url): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if($page == $paginator->currentPage()): ?>
                            <li class="page-item active" aria-current="page" style="list-style: none;">
                                <span class="page-link" style="display: flex; align-items: center; justify-content: center; min-width: 40px; height: 40px; padding: 0.5rem 1rem; background: #e69270; border: 1px solid #e69270; border-radius: 8px; color: #fff; font-weight: 600;"><?php echo e($page); ?></span>
                            </li>
                        <?php else: ?>
                            <li class="page-item" style="list-style: none;">
                                <a class="page-link" href="<?php echo e($url); ?>" style="display: flex; align-items: center; justify-content: center; min-width: 40px; height: 40px; padding: 0.5rem 1rem; background: #fff; border: 1px solid #ddd; border-radius: 8px; color: #333; text-decoration: none; font-weight: 500; transition: all 0.3s;"><?php echo e($page); ?></a>
                            </li>
                        <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            
            <?php if($paginator->hasMorePages()): ?>
                <li class="page-item" style="list-style: none;">
                    <a class="page-link" href="<?php echo e($paginator->nextPageUrl()); ?>" rel="next" aria-label="<?php echo app('translator')->get('pagination.next'); ?>" style="display: flex; align-items: center; justify-content: center; min-width: 40px; height: 40px; padding: 0.5rem 1rem; background: #fff; border: 1px solid #ddd; border-radius: 8px; color: #333; text-decoration: none; font-weight: 500; transition: all 0.3s; font-size: 1.2rem;">&rsaquo;</a>
                </li>
            <?php else: ?>
                <li class="page-item disabled" aria-disabled="true" aria-label="<?php echo app('translator')->get('pagination.next'); ?>" style="list-style: none;">
                    <span class="page-link" style="display: flex; align-items: center; justify-content: center; min-width: 40px; height: 40px; padding: 0.5rem 1rem; background: #f5f5f5; border: 1px solid #ddd; border-radius: 8px; color: #999; cursor: not-allowed; font-size: 1.2rem;">&rsaquo;</span>
                </li>
            <?php endif; ?>
        </ul>
    </nav>

    <style>
        .pagination .page-link:hover {
            background: #e69270 !important;
            color: #fff !important;
            border-color: #e69270 !important;
        }
    </style>
<?php endif; ?>
<?php /**PATH C:\Users\rashe\Desktop\it-center\laravel-app\resources\views\vendor\pagination\default.blade.php ENDPATH**/ ?>