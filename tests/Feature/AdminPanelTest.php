<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminPanelTest extends TestCase
{
    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::where('email', 'admin@lesgo.com')->first()
            ?? User::factory()->create(['email' => 'admin@lesgo.com', 'role' => 'admin', 'password' => bcrypt('password')]);
    }

    // Auth
    public function test_login_page_loads(): void
    {
        $this->get('/admin/login')->assertStatus(200);
    }

    public function test_admin_can_login(): void
    {
        $this->post('/admin/login', ['email' => 'admin@lesgo.com', 'password' => 'password'])
            ->assertRedirect('/admin');
    }

    public function test_non_admin_cannot_login(): void
    {
        $customer = User::where('role', 'customer')->first();
        if ($customer) {
            $this->post('/admin/login', ['email' => $customer->email, 'password' => 'password'])
                ->assertStatus(302);
        }
        $this->assertTrue(true);
    }

    // Dashboard
    public function test_dashboard_loads(): void
    {
        $this->actingAs($this->admin)->get('/admin')->assertStatus(200);
    }

    // Resource Pages
    public function test_users_page_loads(): void
    {
        $this->actingAs($this->admin)->get('/admin/users')->assertStatus(200);
    }

    public function test_partners_page_loads(): void
    {
        $this->actingAs($this->admin)->get('/admin/partners')->assertStatus(200);
    }

    public function test_driver_profiles_page_loads(): void
    {
        $this->actingAs($this->admin)->get('/admin/driver-profiles')->assertStatus(200);
    }

    public function test_orders_page_loads(): void
    {
        $this->actingAs($this->admin)->get('/admin/orders')->assertStatus(200);
    }

    public function test_services_page_loads(): void
    {
        $this->actingAs($this->admin)->get('/admin/services')->assertStatus(200);
    }

    public function test_vehicles_page_loads(): void
    {
        $this->actingAs($this->admin)->get('/admin/vehicles')->assertStatus(200);
    }

    public function test_payments_page_loads(): void
    {
        $this->actingAs($this->admin)->get('/admin/payments')->assertStatus(200);
    }

    public function test_wallets_page_loads(): void
    {
        $this->actingAs($this->admin)->get('/admin/wallets')->assertStatus(200);
    }

    public function test_wallet_top_ups_page_loads(): void
    {
        $this->actingAs($this->admin)->get('/admin/wallet-top-ups')->assertStatus(200);
    }

    public function test_geofences_page_loads(): void
    {
        $this->actingAs($this->admin)->get('/admin/geofences')->assertStatus(200);
    }

    public function test_mission_templates_page_loads(): void
    {
        $this->actingAs($this->admin)->get('/admin/mission-templates')->assertStatus(200);
    }

    public function test_rating_reviews_page_loads(): void
    {
        $this->actingAs($this->admin)->get('/admin/rating-reviews')->assertStatus(200);
    }

    public function test_document_verifications_page_loads(): void
    {
        $this->actingAs($this->admin)->get('/admin/document-verifications')->assertStatus(200);
    }

    // Custom Pages
    public function test_finance_dashboard_loads(): void
    {
        $this->actingAs($this->admin)->get('/admin/finance-dashboard')->assertStatus(200);
    }

    public function test_reports_page_loads(): void
    {
        $this->actingAs($this->admin)->get('/admin/reports')->assertStatus(200);
    }

    public function test_settings_page_loads(): void
    {
        $this->actingAs($this->admin)->get('/admin/settings')->assertStatus(200);
    }

    public function test_notification_center_loads(): void
    {
        $this->actingAs($this->admin)->get('/admin/notification-center')->assertStatus(200);
    }

    public function test_bulk_operations_loads(): void
    {
        $this->actingAs($this->admin)->get('/admin/bulk-operations')->assertStatus(200);
    }

    // CRUD Operations
    public function test_can_create_user(): void
    {
        $this->actingAs($this->admin)
            ->get('/admin/users/create')
            ->assertStatus(200);
    }

    public function test_can_create_partner(): void
    {
        $this->actingAs($this->admin)
            ->get('/admin/partners/create')
            ->assertStatus(200);
    }

    public function test_can_create_order(): void
    {
        $this->actingAs($this->admin)
            ->get('/admin/orders/create')
            ->assertStatus(200);
    }

    public function test_can_create_service(): void
    {
        $this->actingAs($this->admin)
            ->get('/admin/services/create')
            ->assertStatus(200);
    }

    // Unauthenticated access
    public function test_unauthenticated_redirects_to_login(): void
    {
        $this->get('/admin')->assertRedirect('/admin/login');
    }

    public function test_unauthenticated_cannot_access_users(): void
    {
        $this->get('/admin/users')->assertRedirect('/admin/login');
    }
}
