<?php

namespace App\Rules;

use App\Models\Category;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Validates that a category's parent_id follows the hierarchy rules:
 * - Child categories must have a parent_id pointing to a parent category (parent_id = null)
 * - Sub-child categories must have a parent_id pointing to a child category (not a parent)
 * - Prevents creating 4th level categories (max depth is 3: parent -> child -> sub-child)
 */
class ValidCategoryParent implements ValidationRule
{
    /**
     * The ID of the category being updated (null for new categories)
     */
    protected ?int $excludeId;

    /**
     * Create a new rule instance.
     *
     * @param int|null $excludeId The ID of the category being updated (to exclude from validation)
     */
    public function __construct(?int $excludeId = null)
    {
        $this->excludeId = $excludeId;
    }

    /**
     * Run the validation rule.
     *
     * @param string $attribute
     * @param mixed $value
     * @param Closure $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // If parent_id is null, this is a parent category - always valid
        if ($value === null) {
            return;
        }

        // Find the parent category
        $parentCategory = Category::find($value);

        if (!$parentCategory) {
            $fail(__('validation.exists', ['attribute' => $attribute]));
            return;
        }

        // Check if parent category is active
        if (!$parentCategory->is_active) {
            $fail(__('validation.category_parent_inactive'));
            return;
        }

        // Determine the depth of the parent category
        $parentDepth = $this->getCategoryDepth($parentCategory);

        // If parent is at depth 2 (sub-child), we cannot create a 4th level
        if ($parentDepth >= 2) {
            $fail(__('validation.category_max_depth'));
            return;
        }

        // Prevent circular references when updating
        if ($this->excludeId !== null) {
            if ($this->wouldCreateCircularReference($this->excludeId, $value)) {
                $fail(__('validation.category_circular_reference'));
                return;
            }
        }
    }

    /**
     * Get the depth of a category in the hierarchy.
     * Parent = 0, Child = 1, Sub-child = 2
     *
     * @param Category $category
     * @return int
     */
    protected function getCategoryDepth(Category $category): int
    {
        $depth = 0;
        $current = $category;

        while ($current->parent_id !== null) {
            $depth++;
            $current = $current->parent;
            
            // Safety check to prevent infinite loops
            if ($depth > 10) {
                break;
            }
        }

        return $depth;
    }

    /**
     * Check if setting the parent_id would create a circular reference.
     *
     * @param int $categoryId The category being updated
     * @param int $newParentId The proposed new parent ID
     * @return bool
     */
    protected function wouldCreateCircularReference(int $categoryId, int $newParentId): bool
    {
        // Check if the new parent is a descendant of the category being updated
        $category = Category::find($categoryId);
        
        if (!$category) {
            return false;
        }

        // Get all descendant IDs
        $descendantIds = $this->getDescendantIds($category);

        return in_array($newParentId, $descendantIds);
    }

    /**
     * Get all descendant IDs of a category.
     *
     * @param Category $category
     * @return array
     */
    protected function getDescendantIds(Category $category): array
    {
        $ids = [];
        
        foreach ($category->children as $child) {
            $ids[] = $child->id;
            $ids = array_merge($ids, $this->getDescendantIds($child));
        }

        return $ids;
    }
}
