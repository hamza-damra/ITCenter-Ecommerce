@extends('admin.layout')

@section('title', __('messages.edit_template'))

@section('content')
<style>
    .fields-section {
        margin-top: 24px;
    }
    .field-card {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 16px;
        margin-bottom: 12px;
        cursor: grab;
        transition: all 0.2s ease;
    }
    .field-card:hover {
        border-color: var(--primary);
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }
    .field-card.dragging {
        opacity: 0.5;
        cursor: grabbing;
    }
    .field-card-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 12px;
    }
    .field-info {
        flex: 1;
    }
    .field-key {
        font-family: monospace;
        background: #e0f2fe;
        color: #0369a1;
        padding: 2px 8px;
        border-radius: 4px;
        font-size: 12px;
        margin-left: 8px;
    }
    .field-meta {
        display: flex;
        gap: 12px;
        margin-top: 8px;
        flex-wrap: wrap;
    }
    .field-meta-item {
        font-size: 12px;
        color: #64748b;
        display: flex;
        align-items: center;
        gap: 4px;
    }
    .field-type-badge {
        padding: 2px 8px;
        border-radius: 4px;
        font-size: 11px;
        font-weight: 600;
    }
    .field-type-text { background: #f0fdf4; color: #166534; }
    .field-type-number { background: #fef3c7; color: #92400e; }
    .field-type-boolean { background: #ede9fe; color: #6d28d9; }
    .field-type-select { background: #e0f2fe; color: #0369a1; }
    .field-required {
        color: #dc2626;
        font-size: 12px;
    }
    .field-actions {
        display: flex;
        gap: 8px;
        align-items: center;
    }
    .drag-handle {
        color: #94a3b8;
        cursor: grab;
        padding: 4px;
    }
    .add-field-form {
        background: #f0fdf4;
        border: 2px dashed #86efac;
        border-radius: 8px;
        padding: 20px;
        margin-top: 16px;
    }
    .add-field-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 16px;
    }
    .options-textarea {
        font-family: monospace;
        font-size: 13px;
    }
    .options-help {
        background: #fffbeb;
        border: 1px solid #fbbf24;
        border-radius: 6px;
        padding: 8px 12px;
        font-size: 12px;
        margin-top: 8px;
    }
    .edit-field-modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0,0,0,0.5);
        z-index: 1000;
        align-items: center;
        justify-content: center;
    }
    .edit-field-modal.active {
        display: flex;
    }
    .modal-content {
        background: white;
        border-radius: 12px;
        max-width: 600px;
        width: 90%;
        max-height: 90vh;
        overflow-y: auto;
        box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25);
    }
    .modal-header {
        padding: 20px;
        border-bottom: 1px solid #e5e7eb;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .modal-body {
        padding: 20px;
    }
    .modal-footer {
        padding: 16px 20px;
        border-top: 1px solid #e5e7eb;
        display: flex;
        justify-content: flex-end;
        gap: 12px;
    }
</style>

<div class="page-header">
    <div class="page-header-content">
        <h1><i class="fas fa-edit"></i> {{ __('messages.edit_template') }}</h1>
        <p>{{ $template->name }} - {{ $template->category?->name ?? 'N/A' }}</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('admin.spec-templates.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> {{ __('messages.back') }}
        </a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger">
        <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
    </div>
@endif

<!-- Template Info Card -->
<div class="card">
    <div class="card-header">
        <h2><i class="fas fa-info-circle"></i> {{ __('messages.template_information') }}</h2>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.spec-templates.update', $template) }}" method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" name="category_id" value="{{ $template->category_id }}">
            
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">{{ __('messages.category') }}</label>
                    <div style="padding: 10px; background: #f1f5f9; border-radius: 6px; font-weight: 500;">
                        <i class="fas fa-folder"></i> {{ $template->category?->name_en ?? 'N/A' }}
                    </div>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="name_en" class="form-label">{{ __('messages.template_name') }} (English) <span class="required">*</span></label>
                    <input type="text" id="name_en" name="name_en" class="form-control" 
                           value="{{ old('name_en', $template->name_en) }}" maxlength="100" required>
                </div>
                <div class="form-group">
                    <label for="name_ar" class="form-label">{{ __('messages.template_name') }} (العربية)</label>
                    <input type="text" id="name_ar" name="name_ar" class="form-control" 
                           value="{{ old('name_ar', $template->name_ar) }}" maxlength="100" dir="rtl">
                </div>
                <div class="form-group">
                    <label for="name_he" class="form-label">{{ __('messages.template_name') }} (עברית)</label>
                    <input type="text" id="name_he" name="name_he" class="form-control" 
                           value="{{ old('name_he', $template->name_he) }}" maxlength="100" dir="rtl">
                </div>
            </div>

            <div class="form-group">
                <label class="checkbox-group">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" value="1" {{ $template->is_active ? 'checked' : '' }}>
                    <span><strong>{{ __('messages.active') }}</strong></span>
                </label>
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> {{ __('messages.save_changes') }}
            </button>
        </form>
    </div>
</div>

<!-- Fields Section -->
<div class="card fields-section">
    <div class="card-header">
        <h2><i class="fas fa-list"></i> {{ __('messages.specification_fields') }} ({{ $template->fields->count() }})</h2>
        <p style="color: #64748b; font-size: 13px; margin-top: 4px;">
            <i class="fas fa-grip-vertical"></i> {{ __('messages.drag_to_reorder') }}
        </p>
    </div>
    <div class="card-body">
        @if($template->fields->count() > 0)
            <div id="fieldsList">
                @foreach($template->fields as $field)
                    <div class="field-card" data-field-id="{{ $field->id }}">
                        <div class="field-card-header">
                            <div class="field-info">
                                <div style="display: flex; align-items: center; flex-wrap: wrap; gap: 8px;">
                                    <span class="drag-handle"><i class="fas fa-grip-vertical"></i></span>
                                    <strong>{{ $field->label_en }}</strong>
                                    <span class="field-key">{{ $field->key }}</span>
                                    @if($field->is_required)
                                        <span class="field-required"><i class="fas fa-asterisk"></i> {{ __('messages.required') }}</span>
                                    @endif
                                </div>
                                <div class="field-meta">
                                    <span class="field-meta-item">
                                        <span class="field-type-badge field-type-{{ $field->type }}">{{ strtoupper($field->type) }}</span>
                                    </span>
                                    @if($field->unit)
                                        <span class="field-meta-item">
                                            <i class="fas fa-ruler"></i> {{ $field->unit }}
                                        </span>
                                    @endif
                                    @if($field->label_ar)
                                        <span class="field-meta-item">
                                            <i class="fas fa-globe"></i> {{ $field->label_ar }}
                                        </span>
                                    @endif
                                    @if($field->type === 'select' && $field->options)
                                        <span class="field-meta-item">
                                            <i class="fas fa-list-ul"></i> {{ count($field->options) }} {{ __('messages.options') }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="field-actions">
                                <button type="button" class="btn btn-sm btn-secondary" onclick="editField({{ $field->id }}, {{ json_encode($field) }})">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form action="{{ route('admin.spec-templates.fields.destroy', [$template, $field]) }}" 
                                      method="POST" style="display: inline;"
                                      onsubmit="return confirm('{{ __('messages.confirm_delete_field') }}')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p style="color: #64748b; text-align: center; padding: 20px;">
                <i class="fas fa-info-circle"></i> {{ __('messages.no_fields_added') }}
            </p>
        @endif

        <!-- Add New Field Form -->
        <div class="add-field-form">
            <div class="add-field-header">
                <h3 style="margin: 0; font-size: 16px;">
                    <i class="fas fa-plus-circle"></i> {{ __('messages.add_new_field') }}
                </h3>
            </div>
            <form action="{{ route('admin.spec-templates.fields.store', $template) }}" method="POST">
                @csrf
                <input type="hidden" name="spec_template_id" value="{{ $template->id }}">
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="new_label_en" class="form-label">{{ __('messages.field_label') }} (English) <span class="required">*</span></label>
                        <input type="text" id="new_label_en" name="label_en" class="form-control" 
                               placeholder="e.g., Processor, RAM" maxlength="100" required>
                    </div>
                    <div class="form-group">
                        <label for="new_label_ar" class="form-label">{{ __('messages.field_label') }} (العربية)</label>
                        <input type="text" id="new_label_ar" name="label_ar" class="form-control" 
                               placeholder="مثال: المعالج، الذاكرة" maxlength="100" dir="rtl">
                    </div>
                    <div class="form-group">
                        <label for="new_key" class="form-label">{{ __('messages.field_key') }}</label>
                        <input type="text" id="new_key" name="key" class="form-control" 
                               placeholder="e.g., cpu, ram (auto-generated if empty)" maxlength="50"
                               pattern="[a-z][a-z0-9_]*">
                        <p class="form-text">{{ __('messages.field_key_help') }}</p>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="new_type" class="form-label">{{ __('messages.field_type') }} <span class="required">*</span></label>
                        <select id="new_type" name="type" class="form-control" required onchange="toggleOptionsField(this, 'new')">
                            <option value="text">{{ __('messages.type_text') }}</option>
                            <option value="number">{{ __('messages.type_number') }}</option>
                            <option value="boolean">{{ __('messages.type_boolean') }}</option>
                            <option value="select">{{ __('messages.type_select') }}</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="new_unit" class="form-label">{{ __('messages.unit') }}</label>
                        <input type="text" id="new_unit" name="unit" class="form-control" 
                               placeholder="e.g., GB, GHz, inches" maxlength="30">
                    </div>
                    <div class="form-group">
                        <label class="checkbox-group" style="margin-top: 28px;">
                            <input type="hidden" name="is_required" value="0">
                            <input type="checkbox" name="is_required" value="1">
                            <span><strong>{{ __('messages.required') }}</strong></span>
                        </label>
                    </div>
                </div>

                <div class="form-group" id="new_options_group" style="display: none;">
                    <label for="new_options" class="form-label">{{ __('messages.options') }}</label>
                    <textarea id="new_options" name="options" class="form-control options-textarea" rows="4"
                              placeholder="Option 1&#10;Option 2&#10;Option 3"></textarea>
                    <div class="options-help">
                        <i class="fas fa-lightbulb"></i> {{ __('messages.options_help') }}
                    </div>
                </div>

                <button type="submit" class="btn btn-success" style="margin-top: 16px;">
                    <i class="fas fa-plus"></i> {{ __('messages.add_field') }}
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Edit Field Modal -->
<div id="editFieldModal" class="edit-field-modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3><i class="fas fa-edit"></i> {{ __('messages.edit_field') }}</h3>
            <button type="button" class="btn btn-sm btn-secondary" onclick="closeEditModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form id="editFieldForm" method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" name="spec_template_id" value="{{ $template->id }}">
            
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">{{ __('messages.field_label') }} (English) <span class="required">*</span></label>
                    <input type="text" name="label_en" id="edit_label_en" class="form-control" maxlength="100" required>
                </div>
                <div class="form-group">
                    <label class="form-label">{{ __('messages.field_label') }} (العربية)</label>
                    <input type="text" name="label_ar" id="edit_label_ar" class="form-control" maxlength="100" dir="rtl">
                </div>
                <div class="form-group">
                    <label class="form-label">{{ __('messages.field_key') }}</label>
                    <input type="text" name="key" id="edit_key" class="form-control" maxlength="50" pattern="[a-z][a-z0-9_]*">
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">{{ __('messages.field_type') }} <span class="required">*</span></label>
                        <select name="type" id="edit_type" class="form-control" required onchange="toggleOptionsField(this, 'edit')">
                            <option value="text">{{ __('messages.type_text') }}</option>
                            <option value="number">{{ __('messages.type_number') }}</option>
                            <option value="boolean">{{ __('messages.type_boolean') }}</option>
                            <option value="select">{{ __('messages.type_select') }}</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">{{ __('messages.unit') }}</label>
                        <input type="text" name="unit" id="edit_unit" class="form-control" maxlength="30">
                    </div>
                </div>
                <div class="form-group" id="edit_options_group" style="display: none;">
                    <label class="form-label">{{ __('messages.options') }}</label>
                    <textarea name="options" id="edit_options" class="form-control options-textarea" rows="4"></textarea>
                </div>
                <div class="form-group">
                    <label class="checkbox-group">
                        <input type="hidden" name="is_required" value="0">
                        <input type="checkbox" name="is_required" id="edit_is_required" value="1">
                        <span><strong>{{ __('messages.required') }}</strong></span>
                    </label>
                </div>
                <div class="form-group">
                    <label class="checkbox-group">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" id="edit_is_active" value="1">
                        <span><strong>{{ __('messages.active') }}</strong></span>
                    </label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeEditModal()">{{ __('messages.cancel') }}</button>
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save"></i> {{ __('messages.save_changes') }}
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Danger Zone -->
<div class="card" style="margin-top: 24px;">
    <div class="card-body">
        <div style="background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%); border: 1px solid #fca5a5; border-radius: 8px; padding: 20px;">
            <h3 style="color: #dc2626; font-size: 16px; margin-bottom: 8px;">
                <i class="fas fa-exclamation-triangle"></i> {{ __('messages.danger_zone') }}
            </h3>
            <p style="color: #7f1d1d; font-size: 13px; margin-bottom: 16px;">
                {{ __('messages.delete_template_warning') }}
            </p>
            <form action="{{ route('admin.spec-templates.destroy', $template) }}" method="POST" 
                  onsubmit="return confirm('{{ __('messages.confirm_delete_template') }}')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">
                    <i class="fas fa-trash-alt"></i> {{ __('messages.delete_template') }}
                </button>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Drag and drop reordering
    const fieldsList = document.getElementById('fieldsList');
    if (fieldsList) {
        let draggedItem = null;

        fieldsList.querySelectorAll('.field-card').forEach(item => {
            item.setAttribute('draggable', true);

            item.addEventListener('dragstart', function(e) {
                draggedItem = this;
                this.classList.add('dragging');
            });

            item.addEventListener('dragend', function() {
                this.classList.remove('dragging');
                saveFieldOrder();
            });

            item.addEventListener('dragover', function(e) {
                e.preventDefault();
                const afterElement = getDragAfterElement(fieldsList, e.clientY);
                if (afterElement == null) {
                    fieldsList.appendChild(draggedItem);
                } else {
                    fieldsList.insertBefore(draggedItem, afterElement);
                }
            });
        });
    }

    function getDragAfterElement(container, y) {
        const draggableElements = [...container.querySelectorAll('.field-card:not(.dragging)')];
        return draggableElements.reduce((closest, child) => {
            const box = child.getBoundingClientRect();
            const offset = y - box.top - box.height / 2;
            if (offset < 0 && offset > closest.offset) {
                return { offset: offset, element: child };
            } else {
                return closest;
            }
        }, { offset: Number.NEGATIVE_INFINITY }).element;
    }

    function saveFieldOrder() {
        const fieldCards = document.querySelectorAll('.field-card');
        const order = Array.from(fieldCards).map(card => card.dataset.fieldId);

        fetch('{{ route('admin.spec-templates.reorder-fields', $template) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ order: order })
        })
        .then(response => response.json())
        .then(data => {
            if (!data.success) {
                alert('Error saving order');
            }
        })
        .catch(error => console.error('Error:', error));
    }
});

function toggleOptionsField(select, prefix) {
    const optionsGroup = document.getElementById(prefix + '_options_group');
    if (select.value === 'select') {
        optionsGroup.style.display = 'block';
    } else {
        optionsGroup.style.display = 'none';
    }
}

function editField(fieldId, fieldData) {
    const modal = document.getElementById('editFieldModal');
    const form = document.getElementById('editFieldForm');
    
    form.action = '{{ route('admin.spec-templates.fields.update', [$template, ':field']) }}'.replace(':field', fieldId);
    
    document.getElementById('edit_label_en').value = fieldData.label_en || '';
    document.getElementById('edit_label_ar').value = fieldData.label_ar || '';
    document.getElementById('edit_key').value = fieldData.key || '';
    document.getElementById('edit_type').value = fieldData.type || 'text';
    document.getElementById('edit_unit').value = fieldData.unit || '';
    document.getElementById('edit_is_required').checked = fieldData.is_required;
    document.getElementById('edit_is_active').checked = fieldData.is_active;
    
    if (fieldData.options && Array.isArray(fieldData.options)) {
        document.getElementById('edit_options').value = fieldData.options.join('\n');
    } else {
        document.getElementById('edit_options').value = '';
    }
    
    toggleOptionsField(document.getElementById('edit_type'), 'edit');
    modal.classList.add('active');
}

function closeEditModal() {
    document.getElementById('editFieldModal').classList.remove('active');
}

// Close modal on outside click
document.getElementById('editFieldModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeEditModal();
    }
});
</script>
@endsection






