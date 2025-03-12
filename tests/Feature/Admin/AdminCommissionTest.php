<?php

namespace Admin;

use App\Models\Category;
use App\Models\Commission;
use Tests\AdminTest;

class AdminCommissionTest extends AdminTest
{
    public function test_index_should_see_un_expired_commissions()
    {
        Commission::factory()->create();
        Commission::factory()->create(["expired_at" => now()->subHour()]);
        $res = $this->getJson(route("v1.admin.commissions.index"));
        $this->assertCount(1, $res->json());
    }

    public function test_store_should_create_commission()
    {
        $data = Commission::factory()->raw();
        $res = $this->postJson(route("v1.admin.commissions.store"), $data);
        $this->assertDatabaseHas("commissions", $data);
    }

    public function test_store_should_not_store_if_category_is_child()
    {
        $parent = Category::factory()->create();
        $child = Category::factory()->for($parent, "parent")->create();
        $data = Commission::factory()->for($child)->raw();
        $this->postJson(route("v1.admin.commissions.store"), $data)
            ->assertStatus(422);
    }

    public function test_store_should_expire_previous_commissions_if_category_exists()
    {
        $category = Category::factory()->create();
        $commission = Commission::factory()->for($category)->create();
        $data = Commission::factory()->for($category)->raw();
        $this->postJson(route("v1.admin.commissions.store"), $data);
        $this->assertEquals(now()->format("Y-m-d"), $commission->fresh()->expired_at);
    }

    public function test_destroy_should_delete_commission()
    {
        $commission = Commission::factory()->create(["expired_at" => now()->subHour()]);
        $res = $this->deleteJson(route("v1.admin.commissions.destroy", $commission));
        $this->assertDatabaseMissing("commissions", $commission->toArray());
    }

    public function test_destroy_should_not_delete_commission_if_is_not_expired()
    {
        $commission = Commission::factory()->create();
        $this->deleteJson(route("v1.admin.commissions.destroy", $commission))
            ->assertStatus(422);
        $this->assertDatabaseCount("commissions", 1);
    }
}
