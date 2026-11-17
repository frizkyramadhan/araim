<?php

namespace Tests\Feature;

use App\Models\Asset;
use App\Models\Category;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Inventory;
use App\Models\Position;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Tests\TestCase;

class HeroHoldingTest extends TestCase
{
    use DatabaseTransactions;

    public function test_holdings_require_a_token_and_return_current_rows_with_history()
    {
        $holder = $this->employee('9001001', 'Holder');
        $previous = $this->employee('9001002', 'Previous');
        $heroId = (string) Str::uuid();
        $holder->hero_employee_id = $heroId;
        $holder->save();

        $asset = $this->asset();
        $mutated = $this->inventory($previous, $asset, 'INV-HERO-1', 'Mutated', 1, '2026-01-01');
        $current = $this->inventory($holder, $asset, 'INV-HERO-1', 'Available', 1, '2026-02-03');
        $this->inventory($holder, $asset, 'INV-HERO-2', 'Discarded', 1);
        $this->inventory($holder, $asset, 'INV-HERO-3', 'Available', 0);

        config(['services.api.key' => 'test-araim-key']);

        $this->getJson('/api/hero/employees/' . $heroId . '/inventories')->assertUnauthorized();

        $this->withHeader('Authorization', 'Bearer test-araim-key')
            ->getJson('/api/hero/employees/' . $heroId . '/inventories')
            ->assertUnauthorized();

        $this->withHeader('X-API-Key', 'test-araim-key')
            ->getJson('/api/hero/employees/not-a-uuid/inventories')
            ->assertStatus(422);

        $this->withHeader('X-API-Key', 'test-araim-key')
            ->getJson('/api/hero/employees/' . Str::uuid() . '/inventories')
            ->assertOk()
            ->assertExactJson(['data' => []]);

        $response = $this->withHeader('X-API-Key', 'test-araim-key')
            ->getJson('/api/hero/employees/' . $heroId . '/inventories');

        $response->assertOk();
        $response->assertJsonCount(1, 'data');
        $response->assertJsonPath('data.0.inventory_no', 'INV-HERO-1');
        $response->assertJsonPath('data.0.asset_name', 'Laptop');
        $response->assertJsonPath('data.0.quantity', 1);
        $response->assertJsonPath('data.0.inventory_status', 'Good');
        $response->assertJsonPath('data.0.transfer_status', 'Available');
        $response->assertJsonPath('data.0.date', '2026-02-03');
        $response->assertJsonPath('data.0.history.0.id', $mutated->id);
        $response->assertJsonPath('data.0.history.0.date', '2026-01-01');
        $response->assertJsonPath('data.0.history.0.holder_name', 'Previous');
        $response->assertJsonPath('data.0.history.0.transfer_status', 'Mutated');
        $response->assertJsonPath('data.0.history.0.inventory_status', 'Good');
        $response->assertJsonPath('data.0.history.1.id', $current->id);
        $response->assertJsonPath('data.0.history.1.hero_employee_id', $heroId);

        $this->withHeader('X-API-Key', 'test-araim-key')
            ->getJson('/api/hero/employees/' . $heroId . '/holdings')
            ->assertOk()
            ->assertJsonPath('data.0.inventory_no', 'INV-HERO-1');
    }

    public function test_employee_form_stores_and_clears_the_hero_id_without_changing_nik()
    {
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin-' . Str::random(8) . '@example.test',
            'password' => 'secret',
            'level' => 'admin',
        ]);
        $project = Project::create([
            'project_code' => 'HX',
            'project_name' => 'Hero bind',
            'project_status' => 1,
        ]);
        $department = Department::create([
            'dept_name' => 'Hero dept',
            'dept_status' => 1,
        ]);
        $position = Position::create([
            'position_name' => 'Hero position',
            'department_id' => $department->id,
            'position_status' => 1,
        ]);
        $heroId = 'eeeeeeee-eeee-eeee-eeee-eeeeeeeeeeee';

        $this->actingAs($admin)->get(route('employees.create'))
            ->assertOk()
            ->assertSee('Link to ARKA HERO')
            ->assertSee('Not linked');

        $this->actingAs($admin)->post(route('employees.store'), [
            'nik' => '9002001',
            'fullname' => 'Bound Person',
            'project_id' => $project->id,
            'position_id' => $position->id,
            'email' => 'bound@example.test',
            'hero_employee_id' => $heroId,
        ])->assertRedirect(route('employees.index'));

        $employee = Employee::where('nik', 9002001)->first();
        $this->assertNotNull($employee);
        $this->assertSame($heroId, $employee->hero_employee_id);
        $this->assertSame(9002001, (int) $employee->nik);

        $this->actingAs($admin)->patch(route('employees.update', $employee), [
            'nik' => '9002001',
            'fullname' => 'Bound Person',
            'project_id' => $project->id,
            'position_id' => $position->id,
            'email' => 'bound@example.test',
            'status' => 1,
            'hero_employee_id' => '',
        ])->assertRedirect(route('employees.index'));

        $this->assertNull($employee->fresh()->hero_employee_id);
        $this->assertSame(9002001, (int) $employee->fresh()->nik);
    }

    public function test_bind_command_writes_only_a_single_exact_nik_match()
    {
        config([
            'services.hero.url' => 'http://hero.test/api',
            'services.hero.token' => '',
        ]);

        $matched = $this->employee('9003001', 'Match Me');
        $missing = $this->employee('9003002', 'Missing');
        $already = $this->employee('9003003', 'Already');
        $already->hero_employee_id = 'ffffffff-ffff-ffff-ffff-ffffffffffff';
        $already->save();

        Http::fake(function ($request) {
            $url = $request->url();
            if (strpos($url, '/by-nik/9003001') !== false) {
                return Http::response([
                    'data' => [[
                        'nik' => '9003001',
                        'is_active' => 1,
                        'employee' => [
                            'id' => '12121212-1212-1212-1212-121212121212',
                            'fullname' => 'Match Me',
                        ],
                    ]],
                ]);
            }

            return Http::response(['message' => 'missing'], 404);
        });

        $this->artisan('employees:bind-hero')->assertExitCode(0);

        $this->assertSame('12121212-1212-1212-1212-121212121212', $matched->fresh()->hero_employee_id);
        $this->assertNull($missing->fresh()->hero_employee_id);
        $this->assertSame('ffffffff-ffff-ffff-ffff-ffffffffffff', $already->fresh()->hero_employee_id);
        $this->assertSame(9003001, (int) $matched->fresh()->nik);
    }

    private function employee($nik, $name)
    {
        $project = Project::create([
            'project_code' => 'P' . $nik,
            'project_name' => 'Project ' . $nik,
            'project_status' => 1,
        ]);
        $department = Department::create([
            'dept_name' => 'Dept ' . $nik,
            'dept_status' => 1,
        ]);
        $position = Position::create([
            'position_name' => 'Position ' . $nik,
            'department_id' => $department->id,
            'position_status' => 1,
        ]);

        return Employee::create([
            'nik' => $nik,
            'fullname' => $name,
            'position_id' => $position->id,
            'project_id' => $project->id,
            'status' => 1,
        ]);
    }

    private function asset()
    {
        $category = Category::create([
            'category_name' => 'Cat ' . Str::random(6),
            'category_status' => 1,
        ]);

        return Asset::create([
            'asset_name' => 'Laptop',
            'category_id' => $category->id,
            'asset_status' => 1,
        ]);
    }

    private function inventory(Employee $employee, Asset $asset, $number, $transferStatus, $active, $inputDate = null)
    {
        return Inventory::create([
            'inventory_no' => $number,
            'employee_id' => $employee->id,
            'asset_id' => $asset->id,
            'quantity' => 1,
            'transfer_status' => $transferStatus,
            'inventory_status' => 'Good',
            'is_active' => $active,
            'input_date' => $inputDate,
        ]);
    }
}
