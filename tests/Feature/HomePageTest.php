<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class HomePageTest extends TestCase
{
    /**
     * Test that homepage does not contain raw CSS text leakage.
     *
     * @return void
     */
    public function test_homepage_does_not_contain_raw_css_text(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);

        // Assert that raw CSS selectors are not present as visible text
        $response->assertDontSee('.promo-image img {', false);
        $response->assertDontSee('.promo-badge {', false);
        $response->assertDontSee('.promo-content {', false);
        $response->assertDontSee('.promo-title {', false);
        $response->assertDontSee('.promo-price {', false);
        $response->assertDontSee('.price-row {', false);
        
        // Assert that CSS comment is not visible as text
        $response->assertDontSee('/* removed old promo section styles */', false);
        
        // Assert that the CSS properties are not rendered as text
        $response->assertDontSee('background: white;', false);
        $response->assertDontSee('border-radius: 15px;', false);
    }

    /**
     * Test that homepage renders successfully.
     *
     * @return void
     */
    public function test_homepage_renders_successfully(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('IT Center', false);
    }
}
