<?php

namespace Tests\Unit;

use App\Services\HeroEmployeeClient;
use Illuminate\Support\Facades\Http;
use RuntimeException;
use Tests\TestCase;

class HeroEmployeeClientTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config([
            'services.hero.url' => 'http://hero.test/api',
            'services.hero.token' => 'hero-token',
        ]);
    }

    public function test_search_uses_employee_id_when_hero_returns_it()
    {
        Http::fake([
            'http://hero.test/api/employees/search' => Http::response([
                'status' => 'success',
                'data' => [[
                    'nik' => '1001',
                    'is_active' => 1,
                    'employee' => ['id' => '11111111-1111-1111-1111-111111111111', 'fullname' => 'Ani'],
                ]],
            ]),
        ]);

        $people = (new HeroEmployeeClient())->search('Ani');

        $this->assertSame([[
            'id' => '11111111-1111-1111-1111-111111111111',
            'fullname' => 'Ani',
            'nik' => '1001',
            'project' => '',
        ]], $people);

        Http::assertSent(function ($request) {
            return $request->method() === 'POST'
                && $request->url() === 'http://hero.test/api/employees/search'
                && $request->data() === ['name' => 'Ani']
                && $request->hasHeader('X-API-Key', 'hero-token');
        });
    }

    public function test_search_resolves_uuid_from_the_employee_list_when_the_payload_has_no_id()
    {
        Http::fake([
            'http://hero.test/api/employees/list' => Http::response([
                ['id' => 'aaaaaaaa-aaaa-aaaa-aaaa-aaaaaaaaaaaa', 'fullname' => 'Budi'],
                ['id' => 'bbbbbbbb-bbbb-bbbb-bbbb-bbbbbbbbbbbb', 'fullname' => 'Budi'],
            ]),
            'http://hero.test/api/employees/search' => Http::response([
                'data' => [[
                    'nik' => '2002',
                    'is_active' => 1,
                    'employee' => ['fullname' => 'Budi'],
                ]],
            ]),
            'http://hero.test/api/employees/aaaaaaaa-aaaa-aaaa-aaaa-aaaaaaaaaaaa' => Http::response([
                'data' => [['nik' => '2002', 'is_active' => 1]],
            ]),
            'http://hero.test/api/employees/bbbbbbbb-bbbb-bbbb-bbbb-bbbbbbbbbbbb' => Http::response([
                'data' => [['nik' => '3003', 'is_active' => 1]],
            ]),
        ]);

        $people = (new HeroEmployeeClient())->search('2002');

        $this->assertCount(1, $people);
        $this->assertSame('aaaaaaaa-aaaa-aaaa-aaaa-aaaaaaaaaaaa', $people[0]['id']);
        $this->assertSame('2002', $people[0]['nik']);

        Http::assertSent(function ($request) {
            return $request->method() === 'POST' && $request->data() === ['nik' => '2002'];
        });
    }

    public function test_exact_nik_match_is_none_when_hero_returns_404()
    {
        Http::fake([
            'http://hero.test/api/employees/by-nik/4040' => Http::response(['message' => 'missing'], 404),
        ]);

        $match = (new HeroEmployeeClient())->matchByExactNik('4040');

        $this->assertSame('none', $match['status']);
        $this->assertNull($match['person']);
    }

    public function test_exact_nik_match_is_ambiguous_when_two_people_hold_it()
    {
        Http::fake([
            'http://hero.test/api/employees/by-nik/5005' => Http::response([
                'data' => [[
                    'nik' => '5005',
                    'is_active' => 1,
                    'employee' => ['fullname' => 'Cici'],
                ]],
            ]),
            'http://hero.test/api/employees/list' => Http::response([
                ['id' => 'cccccccc-cccc-cccc-cccc-cccccccccccc', 'fullname' => 'Cici'],
                ['id' => 'dddddddd-dddd-dddd-dddd-dddddddddddd', 'fullname' => 'Cici'],
            ]),
            'http://hero.test/api/employees/cccccccc-cccc-cccc-cccc-cccccccccccc' => Http::response([
                'data' => [['nik' => '5005', 'is_active' => 1]],
            ]),
            'http://hero.test/api/employees/dddddddd-dddd-dddd-dddd-dddddddddddd' => Http::response([
                'data' => [['nik' => '5005', 'is_active' => 1]],
            ]),
        ]);

        $match = (new HeroEmployeeClient())->matchByExactNik('5005');

        $this->assertSame('ambiguous', $match['status']);
    }

    public function test_search_includes_project_label()
    {
        Http::fake([
            'http://hero.test/api/employees/search' => Http::response([
                'data' => [[
                    'nik' => '1001',
                    'is_active' => 1,
                    'employee' => ['id' => '11111111-1111-1111-1111-111111111111', 'fullname' => 'Ani'],
                    'project' => ['project_code' => 'PRJ', 'project_name' => 'Project One'],
                ]],
            ]),
        ]);

        $people = (new HeroEmployeeClient())->search('Ani');

        $this->assertSame('PRJ — Project One', $people[0]['project']);
    }

    public function test_find_by_id_returns_active_person()
    {
        Http::fake([
            'http://hero.test/api/employees/11111111-1111-1111-1111-111111111111' => Http::response([
                'data' => [[
                    'nik' => '1001',
                    'is_active' => 1,
                    'employee' => ['fullname' => 'Ani'],
                    'project' => ['project_code' => 'PRJ', 'project_name' => 'Project One'],
                ]],
            ]),
        ]);

        $person = (new HeroEmployeeClient())->findById('11111111-1111-1111-1111-111111111111');

        $this->assertSame([
            'id' => '11111111-1111-1111-1111-111111111111',
            'fullname' => 'Ani',
            'nik' => '1001',
            'project' => 'PRJ — Project One',
        ], $person);
    }

    public function test_missing_base_url_is_reported()
    {
        config(['services.hero.url' => '']);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('HERO_API_URL is not set.');

        (new HeroEmployeeClient())->search('Ani');
    }
}
