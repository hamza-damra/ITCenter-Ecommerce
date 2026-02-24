{{-- Admin Filter Create/Edit Form Partial --}}
@php
    $isEdit = !is_null($filter);
    $existingOptions = $isEdit ? $filter->options->toArray() : [];
    $existingAssignments = $isEdit ? $filter->assignments->toArray() : [];
@endphp

<style>
    .options-container { margin-top: 12px; }
    .option-row {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr auto auto auto 40px;
        gap: 10px;
        align-items: end;
        padding: 12px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        margin-bottom: 10px;
    }
    @media (max-width: 1024px) {
        .option-row { grid-template-columns: 1fr 1fr; }
    }
    @media (max-width: 640px) {
        .option-row { grid-template-columns: 1fr; }
    }
    .option-row .form-group { margin-bottom: 0; }
    .option-row .form-label { font-size: 11px; margin-bottom: 4px; }
    .option-row .form-control { font-size: 13px; padding: 8px 10px; }
    .btn-remove-option {
        background: #fee2e2; color: #991b1b; border: none; border-radius: 6px;
        width: 36px; height: 36px; display: flex; align-items: center; justify-content: center;
        cursor: pointer; transition: all 0.2s;
    }
    .btn-remove-option:hover { background: #fecaca; }
    .btn-add-option {
        display: inline-flex; align-items: center; gap: 8px;
        padding: 10px 18px; background: #eff6ff; color: #1e40af;
        border: 1px dashed #93c5fd; border-radius: 8px;
        cursor: pointer; font-weight: 600; font-size: 13px; transition: all 0.2s;
    }
    .btn-add-option:hover { background: #dbeafe; border-color: #60a5fa; }

    .assignment-row {
        display: grid;
        grid-template-columns: 1fr auto 40px;
        gap: 10px;
        align-items: center;
        padding: 10px 12px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        margin-bottom: 8px;
    }
    .btn-remove-assignment {
        background: #fee2e2; color: #991b1b; border: none; border-radius: 6px;
        width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;
        cursor: pointer; transition: all 0.2s;
    }
    .btn-remove-assignment:hover { background: #fecaca; }
    .btn-add-assignment {
        display: inline-flex; align-items: center; gap: 8px;
        padding: 8px 16px; background: #f0fdf4; color: #166534;
        border: 1px dashed #86efac; border-radius: 8px;
        cursor: pointer; font-weight: 600; font-size: 13px; transition: all 0.2s;
    }
    .btn-add-assignment:hover { background: #dcfce7; border-color: #4ade80; }

    .inherit-toggle {
        display: flex; align-items: center; gap: 6px; font-size: 12px; color: #64748b; white-space: nowrap;
    }
    .inherit-toggle input { width: 16px; height: 16px; }
</style>

<form action="{{ $action }}" method="POST" class="form-layout" id="filterForm">
    @csrf
    @if($method === 'PUT')
        @method('PUT')
    @endif

    {{-- ═══ FILTER INFORMATION ═══ --}}
    <div class="card">
        <div class="card-header">
            <h2><i class="fas fa-info-circle"></i> {{ __('messages.filter_titles_multilang') }}</h2>
        </div>
        <div class="card-body">
            <div class="form-row">
                <div class="form-group">
                    <label for="title_en" class="form-label">{{ __('messages.title_english') }} <span class="required">*</span></label>
                    <input type="text" id="title_en" name="title_en" dir="ltr"
                           class="form-control @error('title_en') is-invalid @enderror"
                           value="{{ old('title_en', $filter->title_en ?? '') }}" required
                           placeholder="e.g., Screen Size">
                    @error('title_en') <span class="error-message">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label for="title_ar" class="form-label">{{ __('messages.title_arabic') }}</label>
                    <input type="text" id="title_ar" name="title_ar" dir="rtl"
                           class="form-control @error('title_ar') is-invalid @enderror"
                           value="{{ old('title_ar', $filter->title_ar ?? '') }}"
                           placeholder="مثال: حجم الشاشة">
                    @error('title_ar') <span class="error-message">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="title_he" class="form-label">{{ __('messages.title_hebrew') }}</label>
                    <input type="text" id="title_he" name="title_he" dir="rtl"
                           class="form-control @error('title_he') is-invalid @enderror"
                           value="{{ old('title_he', $filter->title_he ?? '') }}"
                           placeholder="דוגמה: גודל מסך">
                    @error('title_he') <span class="error-message">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label for="slug" class="form-label">{{ __('messages.filter_slug') }}</label>
                    <input type="text" id="slug" name="slug" dir="ltr"
                           class="form-control @error('slug') is-invalid @enderror"
                           value="{{ old('slug', $filter->slug ?? '') }}"
                           placeholder="{{ __('messages.slug_placeholder') }}">
                    <small class="form-text text-muted" style="font-size: 11px; margin-top: 4px;">{{ __('messages.reserved_slugs_hint') ?? 'Avoid using: brand, status, price (reserved for built-in filters)' }}</small>
                    @error('slug') <span class="error-message">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>
    </div>

    {{-- ═══ DESCRIPTIONS ═══ --}}
    <div class="card">
        <div class="card-header">
            <h2><i class="fas fa-align-left"></i> {{ __('messages.filter_descriptions_multilang') }}</h2>
        </div>
        <div class="card-body">
            <div class="form-row">
                <div class="form-group">
                    <label for="description_en" class="form-label">{{ __('messages.description_english') }}</label>
                    <textarea id="description_en" name="description_en" dir="ltr" rows="2"
                              class="form-control @error('description_en') is-invalid @enderror"
                              placeholder="Shown under filter title in sidebar">{{ old('description_en', $filter->description_en ?? '') }}</textarea>
                    @error('description_en') <span class="error-message">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label for="description_ar" class="form-label">{{ __('messages.description_arabic') }}</label>
                    <textarea id="description_ar" name="description_ar" dir="rtl" rows="2"
                              class="form-control @error('description_ar') is-invalid @enderror">{{ old('description_ar', $filter->description_ar ?? '') }}</textarea>
                    @error('description_ar') <span class="error-message">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="description_he" class="form-label">{{ __('messages.description_hebrew') }}</label>
                    <textarea id="description_he" name="description_he" dir="rtl" rows="2"
                              class="form-control @error('description_he') is-invalid @enderror">{{ old('description_he', $filter->description_he ?? '') }}</textarea>
                    @error('description_he') <span class="error-message">{{ $message }}</span> @enderror
                </div>
                <div class="form-group"></div> {{-- spacer --}}
            </div>
        </div>
    </div>

    {{-- ═══ CONFIGURATION ═══ --}}
    <div class="card">
        <div class="card-header">
            <h2><i class="fas fa-cog"></i> {{ __('messages.filter_configuration') }}</h2>
        </div>
        <div class="card-body">
            <div class="form-row">
                <div class="form-group">
                    <label for="type" class="form-label">{{ __('messages.filter_type') }} <span class="required">*</span></label>
                    <select id="type" name="type" class="form-control @error('type') is-invalid @enderror" required>
                        <option value="checkbox" {{ old('type', $filter->type ?? 'checkbox') === 'checkbox' ? 'selected' : '' }}>{{ __('messages.type_checkbox') }}</option>
                        <option value="radio" {{ old('type', $filter->type ?? '') === 'radio' ? 'selected' : '' }}>{{ __('messages.type_radio') }}</option>
                        <option value="range" {{ old('type', $filter->type ?? '') === 'range' ? 'selected' : '' }}>{{ __('messages.type_range_slider') }}</option>
                        <option value="min_max" {{ old('type', $filter->type ?? '') === 'min_max' ? 'selected' : '' }}>{{ __('messages.type_min_max') }}</option>
                        <option value="boolean" {{ old('type', $filter->type ?? '') === 'boolean' ? 'selected' : '' }}>{{ __('messages.type_boolean') }}</option>
                    </select>
                    @error('type') <span class="error-message">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label for="sort_order" class="form-label">{{ __('messages.filter_sort_order') }}</label>
                    <input type="number" id="sort_order" name="sort_order" min="0" max="9999"
                           class="form-control @error('sort_order') is-invalid @enderror"
                           value="{{ old('sort_order', $filter->sort_order ?? 0) }}">
                    @error('sort_order') <span class="error-message">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label class="checkbox-group">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" value="1"
                               {{ old('is_active', $filter->is_active ?? true) ? 'checked' : '' }}>
                        <span>{{ __('messages.active_label') }} — {{ __('messages.active_help') }}</span>
                    </label>
                </div>
            </div>
        </div>
    </div>

    {{-- ═══ CATEGORY ASSIGNMENTS ═══ --}}
    <div class="card">
        <div class="card-header">
            <h2><i class="fas fa-folder-open"></i> {{ __('messages.filter_assignments') }}</h2>
            <p style="color: #64748b; font-size: 13px; margin-top: 4px;">{{ __('messages.select_categories') }}</p>
        </div>
        <div class="card-body">
            <div id="assignments-container">
                {{-- Existing assignments will be rendered by JS --}}
            </div>
            <button type="button" class="btn-add-assignment" onclick="addAssignment()">
                <i class="fas fa-plus"></i> {{ __('messages.select_categories') }}
            </button>
        </div>
    </div>

    {{-- ═══ FILTER OPTIONS ═══ --}}
    <div class="card" id="options-card">
        <div class="card-header">
            <h2><i class="fas fa-list-ul"></i> {{ __('messages.filter_options') }}</h2>
            <p style="color: #64748b; font-size: 13px; margin-top: 4px;">{{ __('messages.filter_options_info') }}</p>
        </div>
        <div class="card-body">
            <div id="options-not-applicable" style="display:none; text-align:center; padding:20px; color:#64748b;">
                <i class="fas fa-info-circle"></i> {{ __('messages.no_options_for_type') }}
            </div>
            <div id="options-container" class="options-container">
                {{-- Options rendered by JS --}}
            </div>
            <button type="button" class="btn-add-option" id="btn-add-option" onclick="addOption()">
                <i class="fas fa-plus"></i> {{ __('messages.add_option') }}
            </button>
        </div>
    </div>

    {{-- Submit --}}
    <div style="display: flex; justify-content: flex-end; gap: 12px; margin-top: 20px;">
        <a href="{{ route('admin.filters.index') }}" class="btn btn-secondary">{{ __('messages.cancel') ?? 'Cancel' }}</a>
        <button type="submit" class="btn btn-primary" style="padding: 12px 32px; font-size: 15px;">
            <i class="fas fa-save"></i> {{ $submitLabel }}
        </button>
    </div>
</form>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // ── Category data ──────────────────────────────────────
    const allCategories = @json($categories);
    const existingAssignments = @json($existingAssignments);
    const existingOptions = @json($existingOptions);
    const optionBasedTypes = ['checkbox', 'radio', 'boolean'];

    let assignmentIndex = 0;
    let optionIndex = 0;

    // ── Toggle options section visibility ───────────────────
    function toggleOptionsSection() {
        const type = document.getElementById('type').value;
        const isOption = optionBasedTypes.includes(type);
        document.getElementById('options-container').style.display = isOption ? '' : 'none';
        document.getElementById('btn-add-option').style.display = isOption ? '' : 'none';
        document.getElementById('options-not-applicable').style.display = isOption ? 'none' : '';
    }

    // ── Add Assignment Row ─────────────────────────────────
    window.addAssignment = function(catId = '', inherit = false) {
        const idx = assignmentIndex++;
        const container = document.getElementById('assignments-container');

        const catOptions = allCategories.map(c =>
            `<option value="${c.id}" ${c.id == catId ? 'selected' : ''}>${c.name}</option>`
        ).join('');

        const row = document.createElement('div');
        row.className = 'assignment-row';
        row.innerHTML = `
            <select name="categories[${idx}][category_id]" class="form-control" required>
                <option value="">— {{ __('messages.select_categories') }} —</option>
                ${catOptions}
            </select>
            <label class="inherit-toggle">
                <input type="hidden" name="categories[${idx}][inherit_to_children]" value="0">
                <input type="checkbox" name="categories[${idx}][inherit_to_children]" value="1" ${inherit ? 'checked' : ''}>
                {{ __('messages.inherit_to_children') }}
            </label>
            <button type="button" class="btn-remove-assignment" onclick="this.closest('.assignment-row').remove()">
                <i class="fas fa-times"></i>
            </button>
        `;
        container.appendChild(row);
    };

    // ── Add Option Row ─────────────────────────────────────
    window.addOption = function(data = null) {
        const idx = optionIndex++;
        const container = document.getElementById('options-container');

        const row = document.createElement('div');
        row.className = 'option-row';
        row.innerHTML = `
            ${data && data.id ? `<input type="hidden" name="options[${idx}][id]" value="${data.id}">` : ''}
            <div class="form-group">
                <label class="form-label">{{ __('messages.label_english') }} *</label>
                <input type="text" name="options[${idx}][label_en]" class="form-control" dir="ltr"
                       value="${data ? (data.label_en || '') : ''}" required placeholder="e.g., 15.6 inches">
            </div>
            <div class="form-group">
                <label class="form-label">{{ __('messages.label_arabic') }}</label>
                <input type="text" name="options[${idx}][label_ar]" class="form-control" dir="rtl"
                       value="${data ? (data.label_ar || '') : ''}" placeholder="مثال: 15.6 بوصة">
            </div>
            <div class="form-group">
                <label class="form-label">{{ __('messages.label_hebrew') }}</label>
                <input type="text" name="options[${idx}][label_he]" class="form-control" dir="rtl"
                       value="${data ? (data.label_he || '') : ''}" placeholder="דוגמה: 15.6 אינץ'">
            </div>
            <div class="form-group">
                <label class="form-label">{{ __('messages.filter_option_value') }} *</label>
                <input type="text" name="options[${idx}][value_slug]" class="form-control" dir="ltr"
                       value="${data ? (data.value_slug || '') : ''}" required placeholder="15-6-inches">
            </div>
            <div class="form-group">
                <label class="form-label">{{ __('messages.filter_option_color') }}</label>
                <input type="text" name="options[${idx}][color_code]" class="form-control" dir="ltr"
                       value="${data ? (data.color_code || '') : ''}" placeholder="#ff0000">
            </div>
            <div class="form-group">
                <label class="form-label">{{ __('messages.display_order') }}</label>
                <input type="number" name="options[${idx}][sort_order]" class="form-control"
                       value="${data ? (data.sort_order || idx) : idx}" min="0">
            </div>
            <button type="button" class="btn-remove-option" onclick="this.closest('.option-row').remove()" style="align-self:end;margin-bottom:3px;">
                <i class="fas fa-times"></i>
            </button>
        `;
        container.appendChild(row);
    };

    // ── Init (must come AFTER function definitions) ────────
    if (existingAssignments.length > 0) {
        existingAssignments.forEach(a => addAssignment(a.category_id, a.inherit_to_children));
    }
    if (existingOptions.length > 0) {
        existingOptions.forEach(o => addOption(o));
    }
    toggleOptionsSection();
    document.getElementById('type').addEventListener('change', toggleOptionsSection);
});
</script>
