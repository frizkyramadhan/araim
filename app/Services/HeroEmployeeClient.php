<?php

namespace App\Services;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class HeroEmployeeClient
{
    /** @var array<int, array<string, mixed>>|null */
    private $list;

    /** @var array<string, array<int, string>> */
    private $activeNiksByEmployee = [];

    /**
     * People matching a name or a current NIK. Each item is id, fullname, nik, project.
     *
     * @return array<int, array{id: string, fullname: string, nik: string, project: string}>
     */
    public function search($term)
    {
        $term = trim((string) $term);
        if ($term === '') {
            return [];
        }

        $payload = preg_match('/^\d+$/', $term) ? ['nik' => $term] : ['name' => $term];
        $body = $this->post('/employees/search', $payload);
        $rows = (isset($body['data']) && is_array($body['data'])) ? $body['data'] : [];

        return $this->peopleFromAdministrations($rows);
    }

    /**
     * Active person for a known Hero employee UUID.
     *
     * @return array{id: string, fullname: string, nik: string, project: string}|null
     */
    public function findById($id)
    {
        $id = trim((string) $id);
        if ($id === '') {
            return null;
        }

        $response = $this->request()->get($this->url('/employees/' . $id));
        if ($response->status() === 404) {
            return null;
        }
        if (!$response->successful()) {
            $this->fail($response, 'employee read');
        }

        $rows = [];
        foreach ($response->json('data') ?? [] as $row) {
            if (!is_array($row)) {
                continue;
            }
            $row['employee_id'] = $id;
            if (!isset($row['employee']) || !is_array($row['employee'])) {
                $row['employee'] = [];
            }
            $row['employee']['id'] = $id;
            $rows[] = $row;
        }

        $people = $this->peopleFromAdministrations($rows);

        return $people[0] ?? null;
    }

    /**
     * Exact active-NIK match for the one-time bind.
     *
     * @return array{status: string, person: array{id: string, fullname: string, nik: string, project: string}|null}
     */
    public function matchByExactNik($nik)
    {
        $nik = trim((string) $nik);
        if ($nik === '') {
            return ['status' => 'none', 'person' => null];
        }

        $response = $this->request()->get($this->url('/employees/by-nik/' . rawurlencode($nik)));
        if ($response->status() === 404) {
            return ['status' => 'none', 'person' => null];
        }
        if (!$response->successful()) {
            $this->fail($response, 'employee lookup');
        }

        $exact = [];
        foreach ($response->json('data') ?? [] as $row) {
            if (!is_array($row) || !$this->isActive($row)) {
                continue;
            }
            if (trim((string) ($row['nik'] ?? '')) === $nik) {
                $exact[] = $row;
            }
        }

        if (count($exact) === 0) {
            return ['status' => 'none', 'person' => null];
        }
        if (count($exact) > 1) {
            return ['status' => 'ambiguous', 'person' => null];
        }

        $people = $this->peopleFromAdministrations($exact);
        if (count($people) !== 1 || (string) $people[0]['nik'] !== $nik) {
            return ['status' => 'ambiguous', 'person' => null];
        }

        return ['status' => 'matched', 'person' => $people[0]];
    }

    /**
     * @param  array<int, array<string, mixed>>  $rows
     * @return array<int, array{id: string, fullname: string, nik: string, project: string}>
     */
    private function peopleFromAdministrations(array $rows)
    {
        $people = [];

        foreach ($rows as $row) {
            if (!is_array($row) || !$this->isActive($row)) {
                continue;
            }

            $nik = trim((string) ($row['nik'] ?? ''));
            $fullname = trim((string) ($row['employee']['fullname'] ?? ''));
            if ($nik === '' || $fullname === '') {
                continue;
            }

            $id = $this->directEmployeeId($row);
            if ($id === null) {
                $id = $this->resolveEmployeeId($fullname, $nik);
            }
            if ($id === null) {
                continue;
            }

            if (!isset($people[$id])) {
                $people[$id] = [
                    'id' => $id,
                    'fullname' => $fullname,
                    'nik' => $nik,
                    'project' => $this->projectLabel($row),
                ];
            }
        }

        return array_values($people);
    }

    /**
     * @param  array<string, mixed>  $row
     */
    private function projectLabel(array $row)
    {
        $project = $row['project'] ?? null;
        if (!is_array($project)) {
            return '';
        }

        $code = trim((string) ($project['project_code'] ?? ''));
        $name = trim((string) ($project['project_name'] ?? ''));
        if ($code !== '' && $name !== '' && $code !== $name) {
            return $code . ' — ' . $name;
        }

        return $code !== '' ? $code : $name;
    }

    /**
     * @param  array<string, mixed>  $row
     */
    private function directEmployeeId(array $row)
    {
        $id = $row['employee']['id'] ?? ($row['employee_id'] ?? null);
        if (!is_string($id) || trim($id) === '') {
            return null;
        }

        return trim($id);
    }

    private function resolveEmployeeId($fullname, $nik)
    {
        $candidates = [];
        foreach ($this->employeeList() as $person) {
            if (!is_array($person) || empty($person['id'])) {
                continue;
            }
            if (trim((string) ($person['fullname'] ?? '')) === $fullname) {
                $candidates[] = (string) $person['id'];
            }
        }

        $matched = [];
        foreach (array_values(array_unique($candidates)) as $id) {
            if ($this->employeeHoldsActiveNik($id, $nik)) {
                $matched[] = $id;
            }
        }

        return count($matched) === 1 ? $matched[0] : null;
    }

    private function employeeHoldsActiveNik($id, $nik)
    {
        if (!isset($this->activeNiksByEmployee[$id])) {
            $response = $this->request()->get($this->url('/employees/' . $id));
            $niks = [];
            if ($response->successful()) {
                foreach ($response->json('data') ?? [] as $row) {
                    if (!is_array($row) || !$this->isActive($row)) {
                        continue;
                    }
                    $value = trim((string) ($row['nik'] ?? ''));
                    if ($value !== '') {
                        $niks[] = $value;
                    }
                }
            } elseif ($response->status() !== 404) {
                $this->fail($response, 'employee read');
            }
            $this->activeNiksByEmployee[$id] = $niks;
        }

        return in_array($nik, $this->activeNiksByEmployee[$id], true);
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function employeeList()
    {
        if ($this->list !== null) {
            return $this->list;
        }

        $response = $this->request()->get($this->url('/employees/list'));
        if (!$response->successful()) {
            $this->fail($response, 'employee list');
        }

        $body = $response->json();
        if (is_array($body) && isset($body['data']) && is_array($body['data'])) {
            $body = $body['data'];
        }
        if (!is_array($body)) {
            $body = [];
        }

        $this->list = $body;

        return $this->list;
    }

    /**
     * @param  array<string, mixed>  $payload
     * @return array<string, mixed>
     */
    private function post($path, array $payload)
    {
        $response = $this->request()->post($this->url($path), $payload);
        if ($response->status() === 404) {
            return ['data' => []];
        }
        if (!$response->successful()) {
            $this->fail($response, 'employee search');
        }

        $json = $response->json();

        return is_array($json) ? $json : [];
    }

    private function request()
    {
        $http = Http::timeout(20)->acceptJson();
        $token = config('services.hero.token');
        if (is_string($token) && $token !== '') {
            $http = $http->withHeaders(['X-API-Key' => $token]);
        }

        return $http;
    }

    private function url($path)
    {
        $base = rtrim((string) config('services.hero.url'), '/');
        if ($base === '') {
            throw new RuntimeException('HERO_API_URL is not set.');
        }

        return $base . '/' . ltrim($path, '/');
    }

    private function fail(Response $response, $action)
    {
        $message = $response->json('message');
        $detail = is_string($message) ? ' ' . substr($message, 0, 200) : '';

        throw new RuntimeException('Hero ' . $action . ' failed (' . $response->status() . ').' . $detail);
    }

    /**
     * @param  array<string, mixed>  $row
     */
    private function isActive(array $row)
    {
        if (!array_key_exists('is_active', $row)) {
            return false;
        }

        return $row['is_active'] === 1 || $row['is_active'] === '1' || $row['is_active'] === true;
    }
}
