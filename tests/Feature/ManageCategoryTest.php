<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Inertia\Testing\AssertableInertia;
use Tests\TestCase;

class ManageCategoryTest extends TestCase
{
    use DatabaseMigrations;

    private function validParams($overrides = [])
    {
        return array_merge([
            'name' => 'Example',
            'color' => 'blue',
        ], $overrides);
    }

    /** @test */
    public function guests_cannot_view_a_list_of_categories()
    {
        $response = $this->get('/categories');

        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    /** @test */
    public function users_can_view_the_add_category_form()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/categories/create');

        $response->assertStatus(200);
        $response->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Categories/Create')
        );
    }

    /** @test */
    public function guests_cannot_view_the_add_category_form()
    {
        $response = $this->get('/categories/create');

        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    /** @test */
    public function adding_a_category()
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();
        $response = $this->actingAs($user)->post('/categories', [
            'name' => 'Example',
            'color' => 'violet',
        ]);

        tap(Category::first(), function ($category) use ($response) {
            $response->assertRedirect('/categories');

            $this->assertEquals('Example', $category->name);
            $this->assertEquals('violet', $category->color);
        });
    }

    /** @test */
    public function guests_cannot_add_new_categories()
    {
        $response = $this->post('/categories', $this->validParams());

        $response->assertStatus(302);
        $response->assertRedirect('/login');
        $this->assertEquals(0, Category::count());
    }

    /** @test */
    public function users_can_delete_categories()
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();
        $category = Category::factory()->create();

        $this->assertEquals(1, Category::count());

        $response = $this->actingAs($user)->delete("/categories/{$category->id}");

        $this->assertEquals(0, Category::count());

        $response->assertRedirect('/categories');
    }

    /** @test */
    public function guests_cannot_delete_categories()
    {
        $category = Category::factory()->create();

        $this->assertEquals(1, Category::count());

        $response = $this->delete("/categories/{$category->id}");

        $response->assertStatus(302);
        $response->assertRedirect('/login');
        $this->assertEquals(1, Category::count());
    }

    // TODO: name_is_required
    // TODO: color_is_optional
    // TODO: color_must_be_a_tailwind_default_color_name
    // TODO: users_can_edit_categories
    // TODO: users_can_view_a_list_of_categories
}
