<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\SpecTemplate;
use App\Models\SpecField;
use App\Models\ProductSpecValue;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SpecificationSystemTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected Category $category;

    protected function setUp(): void
    {
        parent::setUp();

        // Create admin user
        $this->admin = User::factory()->create([
            'role' => 'admin',
        ]);

        // Create a category
        $this->category = Category::factory()->create([
            'name_en' => 'Laptops',
            'name_ar' => 'لابتوب',
            'slug' => 'laptops',
            'is_active' => true,
        ]);
    }

    /** @test */
    public function admin_can_create_spec_template_for_category(): void
    {
        $this->actingAs($this->admin);

        $response = $this->post(route('admin.spec-templates.store'), [
            'category_id' => $this->category->id,
            'name_en' => 'Laptop Specifications',
            'name_ar' => 'مواصفات اللابتوب',
            'is_active' => true,
        ]);

        $response->assertRedirect();
        
        $this->assertDatabaseHas('spec_templates', [
            'category_id' => $this->category->id,
            'name_en' => 'Laptop Specifications',
        ]);
    }

    /** @test */
    public function category_can_only_have_one_template(): void
    {
        $this->actingAs($this->admin);

        // Create first template
        SpecTemplate::create([
            'category_id' => $this->category->id,
            'name_en' => 'First Template',
            'is_active' => true,
        ]);

        // Try to create another template for same category
        $response = $this->post(route('admin.spec-templates.store'), [
            'category_id' => $this->category->id,
            'name_en' => 'Second Template',
            'is_active' => true,
        ]);

        $response->assertSessionHasErrors('category_id');
    }

    /** @test */
    public function admin_can_add_fields_to_template(): void
    {
        $this->actingAs($this->admin);

        $template = SpecTemplate::create([
            'category_id' => $this->category->id,
            'name_en' => 'Laptop Template',
            'is_active' => true,
        ]);

        $response = $this->post(route('admin.spec-templates.fields.store', $template), [
            'spec_template_id' => $template->id,
            'label_en' => 'Processor',
            'label_ar' => 'المعالج',
            'type' => 'text',
            'is_required' => true,
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('spec_fields', [
            'spec_template_id' => $template->id,
            'label_en' => 'Processor',
            'type' => 'text',
            'is_required' => true,
        ]);
    }

    /** @test */
    public function field_key_must_be_unique_within_template(): void
    {
        $this->actingAs($this->admin);

        $template = SpecTemplate::create([
            'category_id' => $this->category->id,
            'name_en' => 'Laptop Template',
            'is_active' => true,
        ]);

        // Create first field
        SpecField::create([
            'spec_template_id' => $template->id,
            'key' => 'processor',
            'label_en' => 'Processor',
            'type' => 'text',
        ]);

        // Try to create field with same key
        $response = $this->post(route('admin.spec-templates.fields.store', $template), [
            'spec_template_id' => $template->id,
            'key' => 'processor',
            'label_en' => 'Another Processor',
            'type' => 'text',
        ]);

        $response->assertSessionHasErrors('key');
    }

    /** @test */
    public function product_can_store_spec_values(): void
    {
        // Create template with fields
        $template = SpecTemplate::create([
            'category_id' => $this->category->id,
            'name_en' => 'Laptop Template',
            'is_active' => true,
        ]);

        $processorField = SpecField::create([
            'spec_template_id' => $template->id,
            'key' => 'processor',
            'label_en' => 'Processor',
            'type' => 'text',
            'is_required' => true,
        ]);

        $ramField = SpecField::create([
            'spec_template_id' => $template->id,
            'key' => 'ram',
            'label_en' => 'RAM',
            'type' => 'number',
            'unit' => 'GB',
        ]);

        // Create product
        $product = Product::factory()->create([
            'category_id' => $this->category->id,
        ]);

        // Sync spec values
        $product->syncSpecValues([
            $processorField->id => 'Intel Core i7-13700H',
            $ramField->id => '16',
        ]);

        $this->assertDatabaseHas('product_spec_values', [
            'product_id' => $product->id,
            'spec_field_id' => $processorField->id,
            'value' => 'Intel Core i7-13700H',
        ]);

        $this->assertDatabaseHas('product_spec_values', [
            'product_id' => $product->id,
            'spec_field_id' => $ramField->id,
            'value' => '16',
        ]);
    }

    /** @test */
    public function product_spec_values_are_updated_on_category_change(): void
    {
        // Create two categories with different templates
        $category2 = Category::factory()->create([
            'name_en' => 'Phones',
            'slug' => 'phones',
        ]);

        // Template for laptops
        $laptopTemplate = SpecTemplate::create([
            'category_id' => $this->category->id,
            'name_en' => 'Laptop Template',
            'is_active' => true,
        ]);

        $laptopField = SpecField::create([
            'spec_template_id' => $laptopTemplate->id,
            'key' => 'processor',
            'label_en' => 'Processor',
            'type' => 'text',
        ]);

        // Template for phones
        $phoneTemplate = SpecTemplate::create([
            'category_id' => $category2->id,
            'name_en' => 'Phone Template',
            'is_active' => true,
        ]);

        $phoneField = SpecField::create([
            'spec_template_id' => $phoneTemplate->id,
            'key' => 'screen_size',
            'label_en' => 'Screen Size',
            'type' => 'number',
        ]);

        // Create product in laptops category
        $product = Product::factory()->create([
            'category_id' => $this->category->id,
        ]);

        // Add laptop spec value
        $product->syncSpecValues([
            $laptopField->id => 'Intel i7',
        ]);

        // Change category to phones
        $product->category_id = $category2->id;
        $product->save();

        // Sync new phone specs - this should remove old values
        $product->syncSpecValues([
            $phoneField->id => '6.7',
        ]);

        // Old laptop value should be removed
        $this->assertDatabaseMissing('product_spec_values', [
            'product_id' => $product->id,
            'spec_field_id' => $laptopField->id,
        ]);

        // New phone value should exist
        $this->assertDatabaseHas('product_spec_values', [
            'product_id' => $product->id,
            'spec_field_id' => $phoneField->id,
            'value' => '6.7',
        ]);
    }

    /** @test */
    public function deleting_spec_field_removes_product_values(): void
    {
        $template = SpecTemplate::create([
            'category_id' => $this->category->id,
            'name_en' => 'Laptop Template',
            'is_active' => true,
        ]);

        $field = SpecField::create([
            'spec_template_id' => $template->id,
            'key' => 'processor',
            'label_en' => 'Processor',
            'type' => 'text',
        ]);

        $product = Product::factory()->create([
            'category_id' => $this->category->id,
        ]);

        ProductSpecValue::create([
            'product_id' => $product->id,
            'spec_field_id' => $field->id,
            'value' => 'Intel i7',
        ]);

        // Delete the field
        $field->delete();

        // Product value should be deleted
        $this->assertDatabaseMissing('product_spec_values', [
            'product_id' => $product->id,
            'spec_field_id' => $field->id,
        ]);
    }

    /** @test */
    public function product_formatted_specifications_returns_correct_data(): void
    {
        $template = SpecTemplate::create([
            'category_id' => $this->category->id,
            'name_en' => 'Laptop Template',
            'is_active' => true,
        ]);

        $processorField = SpecField::create([
            'spec_template_id' => $template->id,
            'key' => 'processor',
            'label_en' => 'Processor',
            'label_ar' => 'المعالج',
            'type' => 'text',
            'sort_order' => 1,
        ]);

        $ramField = SpecField::create([
            'spec_template_id' => $template->id,
            'key' => 'ram',
            'label_en' => 'RAM',
            'type' => 'number',
            'unit' => 'GB',
            'sort_order' => 2,
        ]);

        $product = Product::factory()->create([
            'category_id' => $this->category->id,
        ]);

        $product->syncSpecValues([
            $processorField->id => 'Intel Core i7',
            $ramField->id => '16',
        ]);

        $product->load(['category.specTemplate.activeFields', 'specValues.field']);

        $formattedSpecs = $product->formattedSpecifications;

        $this->assertCount(2, $formattedSpecs);
        $this->assertEquals('Processor', $formattedSpecs[0]['label']);
        $this->assertEquals('Intel Core i7', $formattedSpecs[0]['value']);
        $this->assertEquals('RAM', $formattedSpecs[1]['label']);
        $this->assertStringContainsString('16', $formattedSpecs[1]['value']);
        $this->assertStringContainsString('GB', $formattedSpecs[1]['value']);
    }

    /** @test */
    public function api_returns_category_spec_fields(): void
    {
        $this->actingAs($this->admin);

        $template = SpecTemplate::create([
            'category_id' => $this->category->id,
            'name_en' => 'Laptop Template',
            'is_active' => true,
        ]);

        SpecField::create([
            'spec_template_id' => $template->id,
            'key' => 'processor',
            'label_en' => 'Processor',
            'type' => 'text',
            'is_required' => true,
            'is_active' => true,
        ]);

        SpecField::create([
            'spec_template_id' => $template->id,
            'key' => 'ram',
            'label_en' => 'RAM',
            'type' => 'number',
            'unit' => 'GB',
            'is_active' => true,
        ]);

        $response = $this->getJson(route('admin.spec-templates.category-fields', $this->category->id));

        $response->assertOk()
            ->assertJson([
                'hasTemplate' => true,
            ])
            ->assertJsonCount(2, 'fields');
    }

    /** @test */
    public function api_returns_no_template_for_category_without_template(): void
    {
        $this->actingAs($this->admin);

        $categoryWithoutTemplate = Category::factory()->create();

        $response = $this->getJson(route('admin.spec-templates.category-fields', $categoryWithoutTemplate->id));

        $response->assertOk()
            ->assertJson([
                'hasTemplate' => false,
                'fields' => [],
            ]);
    }

    /** @test */
    public function product_name_validation_respects_max_length(): void
    {
        $this->actingAs($this->admin);

        $longName = str_repeat('a', 150); // Exceeds 120 char limit

        $response = $this->post(route('admin.products.store'), [
            'name_en' => $longName,
            'name_ar' => 'اسم المنتج',
            'category_id' => $this->category->id,
            'price' => 100,
            'stock_quantity' => 10,
            'main_image' => 'https://example.com/image.jpg',
        ]);

        $response->assertSessionHasErrors('name_en');
    }

    /** @test */
    public function product_description_validation_respects_max_length(): void
    {
        $this->actingAs($this->admin);

        $longDescription = str_repeat('a', 3500); // Exceeds 3000 char limit

        $response = $this->post(route('admin.products.store'), [
            'name_en' => 'Test Product',
            'name_ar' => 'اسم المنتج',
            'category_id' => $this->category->id,
            'price' => 100,
            'stock_quantity' => 10,
            'main_image' => 'https://example.com/image.jpg',
            'description_en' => $longDescription,
        ]);

        $response->assertSessionHasErrors('description_en');
    }
}


