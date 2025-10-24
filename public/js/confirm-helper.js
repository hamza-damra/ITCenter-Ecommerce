/**
 * Global Confirmation Helper
 * Provides helper functions to replace all confirm() calls with custom modals
 */

/**
 * Handle form submission with confirmation
 * @param {Event} event - Form submit event
 * @param {Object} options - Confirmation options
 */
async function handleFormConfirm(event, options = {}) {
    event.preventDefault();
    
    const form = event.target;
    const defaults = {
        title: document.documentElement.lang === 'ar' ? 'تأكيد الإجراء' : 
               document.documentElement.lang === 'he' ? 'אישור פעולה' : 'Confirm Action',
        message: document.documentElement.lang === 'ar' ? 'هل أنت متأكد؟' : 
                 document.documentElement.lang === 'he' ? 'האם אתה בטוח?' : 'Are you sure?',
        confirmText: document.documentElement.lang === 'ar' ? 'تأكيد' : 
                     document.documentElement.lang === 'he' ? 'אישור' : 'Confirm',
        type: 'warning',
        confirmButtonType: 'warning'
    };
    
    const config = { ...defaults, ...options };
    
    const confirmed = await window.confirmModal.show(config);
    
    if (confirmed) {
        // Remove the onsubmit to prevent recursion
        form.onsubmit = null;
        form.submit();
    }
}

/**
 * Handle button click with confirmation
 * @param {Function} callback - Function to execute after confirmation
 * @param {Object} options - Confirmation options
 */
async function handleButtonConfirm(callback, options = {}) {
    const defaults = {
        title: document.documentElement.lang === 'ar' ? 'تأكيد الإجراء' : 
               document.documentElement.lang === 'he' ? 'אישור פעולה' : 'Confirm Action',
        message: document.documentElement.lang === 'ar' ? 'هل أنت متأكد؟' : 
                 document.documentElement.lang === 'he' ? 'האם אתה בטוח?' : 'Are you sure?',
        confirmText: document.documentElement.lang === 'ar' ? 'تأكيد' : 
                     document.documentElement.lang === 'he' ? 'אישור' : 'Confirm',
        type: 'warning',
        confirmButtonType: 'warning'
    };
    
    const config = { ...defaults, ...options };
    
    const confirmed = await window.confirmModal.show(config);
    
    if (confirmed && typeof callback === 'function') {
        callback();
    }
}
