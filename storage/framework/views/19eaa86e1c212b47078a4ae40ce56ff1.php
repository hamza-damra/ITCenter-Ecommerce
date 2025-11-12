<!-- Global Confirmation Modal Component -->
<div id="globalConfirmModal" class="confirm-modal-overlay" style="display: none;">
    <div class="confirm-modal-container">
        <div class="confirm-modal-content">
            <div class="confirm-modal-header" id="confirmModalHeader">
                <h3 id="confirmModalTitle"><?php echo e(__('messages.confirm_action')); ?></h3>
                <button type="button" class="confirm-modal-close" onclick="window.confirmModal.cancel()">&times;</button>
            </div>
            <div class="confirm-modal-body">
                <div class="confirm-modal-icon" id="confirmModalIcon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <p id="confirmModalMessage"><?php echo e(__('messages.are_you_sure')); ?></p>
            </div>
            <div class="confirm-modal-footer">
                <button type="button" class="confirm-modal-btn confirm-modal-btn-cancel" onclick="window.confirmModal.cancel()">
                    <?php echo e(__('messages.Cancel')); ?>

                </button>
                <button type="button" class="confirm-modal-btn confirm-modal-btn-confirm" id="confirmModalConfirmBtn" onclick="window.confirmModal.confirm()">
                    <?php echo e(__('messages.confirm')); ?>

                </button>
            </div>
        </div>
    </div>
</div>

<style>
/* Confirmation Modal Overlay */
.confirm-modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.6);
    z-index: 99999;
    display: flex;
    align-items: center;
    justify-content: center;
    animation: confirmFadeIn 0.2s ease-out;
}

@keyframes confirmFadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

/* Modal Container */
.confirm-modal-container {
    max-width: 500px;
    width: 90%;
    max-height: 90vh;
    overflow-y: auto;
    animation: confirmSlideDown 0.3s ease-out;
}

@keyframes confirmSlideDown {
    from {
        transform: translateY(-50px);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}

/* Modal Content */
.confirm-modal-content {
    background: white;
    border-radius: 12px;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    overflow: hidden;
}

/* Modal Header */
.confirm-modal-header {
    padding: 20px 24px;
    border-bottom: 1px solid #e2e8f0;
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
}

.confirm-modal-header.success {
    background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%);
    border-bottom-color: #86efac;
}

.confirm-modal-header.success h3 {
    color: #15803d;
}

.confirm-modal-header.danger {
    background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
    border-bottom-color: #fca5a5;
}

.confirm-modal-header.danger h3 {
    color: #991b1b;
}

.confirm-modal-header.warning {
    background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
    border-bottom-color: #fcd34d;
}

.confirm-modal-header.warning h3 {
    color: #92400e;
}

.confirm-modal-header.info {
    background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
    border-bottom-color: #93c5fd;
}

.confirm-modal-header.info h3 {
    color: #1e40af;
}

.confirm-modal-header h3 {
    margin: 0;
    font-size: 20px;
    font-weight: 600;
    color: #1e293b;
    display: flex;
    align-items: center;
    gap: 10px;
}

.confirm-modal-header h3 i {
    font-size: 22px;
}

.confirm-modal-close {
    background: none;
    border: none;
    font-size: 28px;
    color: #64748b;
    cursor: pointer;
    padding: 0;
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 6px;
    transition: all 0.2s;
}

.confirm-modal-close:hover {
    background: rgba(0, 0, 0, 0.05);
    color: #1e293b;
}

/* Modal Body */
.confirm-modal-body {
    padding: 30px 24px;
    text-align: center;
}

.confirm-modal-icon {
    width: 64px;
    height: 64px;
    margin: 0 auto 20px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 32px;
}

.confirm-modal-icon.warning {
    background: #fef3c7;
    color: #f59e0b;
}

.confirm-modal-icon.danger {
    background: #fee2e2;
    color: #ef4444;
}

.confirm-modal-icon.info {
    background: #dbeafe;
    color: #3b82f6;
}

.confirm-modal-icon.success {
    background: #dcfce7;
    color: #10b981;
}

.confirm-modal-body p {
    margin: 0;
    font-size: 16px;
    color: #475569;
    line-height: 1.6;
}

/* Modal Footer */
.confirm-modal-footer {
    padding: 20px 24px;
    border-top: 1px solid #e2e8f0;
    display: flex;
    justify-content: flex-end;
    gap: 12px;
    background: #f8fafc;
}

/* Modal Buttons */
.confirm-modal-btn {
    padding: 10px 24px;
    border: none;
    border-radius: 8px;
    font-size: 15px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.confirm-modal-btn-cancel {
    background: #e2e8f0;
    color: #475569;
}

.confirm-modal-btn-cancel:hover {
    background: #cbd5e0;
}

.confirm-modal-btn-confirm {
    background: #3b82f6;
    color: white;
}

.confirm-modal-btn-confirm:hover {
    background: #2563eb;
}

.confirm-modal-btn-confirm.danger {
    background: #ef4444;
}

.confirm-modal-btn-confirm.danger:hover {
    background: #dc2626;
}

.confirm-modal-btn-confirm.warning {
    background: #f59e0b;
}

.confirm-modal-btn-confirm.warning:hover {
    background: #d97706;
}

.confirm-modal-btn-confirm.success {
    background: #10b981;
}

.confirm-modal-btn-confirm.success:hover {
    background: #059669;
}

/* RTL Support */
[dir="rtl"] .confirm-modal-header,
[dir="rtl"] .confirm-modal-footer {
    flex-direction: row-reverse;
}

[dir="rtl"] .confirm-modal-body {
    text-align: right;
}

[dir="rtl"] .confirm-modal-icon {
    margin: 0 auto 20px;
}

/* Responsive */
@media (max-width: 640px) {
    .confirm-modal-container {
        width: 95%;
    }
    
    .confirm-modal-footer {
        flex-direction: column;
    }
    
    .confirm-modal-btn {
        width: 100%;
        justify-content: center;
    }
    
    [dir="rtl"] .confirm-modal-footer {
        flex-direction: column;
    }
}
</style>

<script>
/**
 * Global Confirmation Modal Manager
 * Replaces all confirm() dialogs with a unified custom modal
 */
(function() {
    'use strict';
    
    window.confirmModal = {
        resolveCallback: null,
        rejectCallback: null,
        isOpen: false,
        pendingPromise: null,
        
        /**
         * Show confirmation modal
         * @param {Object} options - Modal configuration
         * @returns {Promise<boolean>}
         */
        show: function(options) {
            try {
                console.debug('[CONFIRM MODAL] show() called with options:', options);
            } catch (e) {}

            // If a modal is already open, return the existing promise to avoid overwriting callbacks
            if (this.isOpen && this.pendingPromise) {
                try { console.debug('[CONFIRM MODAL] Modal already open, returning existing promise'); } catch (e) {}
                return this.pendingPromise;
            }

            const defaults = {
                title: '<?php echo e(__('messages.confirm_action')); ?>',
                message: '<?php echo e(__('messages.are_you_sure')); ?>',
                confirmText: '<?php echo e(__('messages.confirm')); ?>',
                cancelText: '<?php echo e(__('messages.Cancel')); ?>',
                type: 'warning', // warning, danger, info, success
                confirmButtonType: 'primary' // primary, danger, warning, success
            };
            
            const config = { ...defaults, ...options };
            
            // Set modal content
            const titleEl = document.getElementById('confirmModalTitle');
            const headerEl = document.getElementById('confirmModalHeader');
            
            // Update icon based on type
            const iconMap = {
                'warning': 'fa-exclamation-triangle',
                'danger': 'fa-exclamation-circle',
                'info': 'fa-info-circle',
                'success': 'fa-check-circle'
            };
            const iconClass = iconMap[config.type] || 'fa-exclamation-triangle';
            
            // Set title with icon
            titleEl.innerHTML = `<i class="fas ${iconClass}"></i> ${config.title}`;
            
            // Set header style based on type
            headerEl.className = 'confirm-modal-header ' + config.type;
            
            document.getElementById('confirmModalMessage').textContent = config.message;
            
            // Set icon type and icon itself
            const iconEl = document.getElementById('confirmModalIcon');
            iconEl.className = 'confirm-modal-icon ' + config.type;
            iconEl.innerHTML = `<i class="fas ${iconClass}"></i>`;
            
            // Set button text
            const confirmBtn = document.getElementById('confirmModalConfirmBtn');
            confirmBtn.textContent = config.confirmText;
            
            // Set button style
            confirmBtn.className = 'confirm-modal-btn confirm-modal-btn-confirm ' + config.confirmButtonType;
            
            // Show modal
            document.getElementById('globalConfirmModal').style.display = 'flex';
            
            // Prevent body scroll
            document.body.style.overflow = 'hidden';
            
            // Return promise
            this.isOpen = true;
            this.pendingPromise = new Promise((resolve, reject) => {
                this.resolveCallback = resolve;
                this.rejectCallback = reject;
            });

            return this.pendingPromise;
        },
        
        /**
         * Confirm action
         */
        confirm: function() {
            try { console.debug('[CONFIRM MODAL] confirm()'); } catch (e) {}
            // Cache callbacks before hide clears them
            const resolveCb = this.resolveCallback;
            const rejectCb = this.rejectCallback;
            this.hide();
            if (typeof resolveCb === 'function') {
                try { resolveCb(true); } catch (e) { try { console.error('[CONFIRM MODAL] resolve error:', e); } catch(_) {} }
            }
        },
        
        /**
         * Cancel action
         */
        cancel: function() {
            try { console.debug('[CONFIRM MODAL] cancel()'); } catch (e) {}
            // Cache callbacks before hide clears them
            const resolveCb = this.resolveCallback;
            const rejectCb = this.rejectCallback;
            this.hide();
            if (typeof resolveCb === 'function') {
                try { resolveCb(false); } catch (e) { try { console.error('[CONFIRM MODAL] resolve error:', e); } catch(_) {} }
            }
        },
        
        /**
         * Hide modal
         */
        hide: function() {
            try { console.debug('[CONFIRM MODAL] hide()'); } catch (e) {}
            document.getElementById('globalConfirmModal').style.display = 'none';
            document.body.style.overflow = '';
            this.resolveCallback = null;
            this.rejectCallback = null;
            this.isOpen = false;
            this.pendingPromise = null;
        }
    };
    
    /**
     * Override native confirm() to use custom modal
     * This ensures all existing confirm() calls use the new modal
     */
    window.originalConfirm = window.confirm;
    window.confirm = function(message) {
        console.warn('Native confirm() is deprecated. Use confirmModal.show() instead.');
        // For synchronous compatibility, we'll show the modal but can't wait
        // Better to refactor code to use async confirmModal.show()
        return window.originalConfirm(message);
    };
    
    // Close on ESC key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const modal = document.getElementById('globalConfirmModal');
            if (modal && modal.style.display === 'flex') {
                window.confirmModal.cancel();
            }
        }
    });
    
    // Close on overlay click
    document.addEventListener('click', function(e) {
        if (e.target && e.target.id === 'globalConfirmModal') {
            window.confirmModal.cancel();
        }
    });
})();
</script>
<?php /**PATH C:\Users\rashe\Desktop\it-center\laravel-app\resources\views/components/confirm-modal.blade.php ENDPATH**/ ?>