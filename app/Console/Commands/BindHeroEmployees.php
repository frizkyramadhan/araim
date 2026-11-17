<?php

namespace App\Console\Commands;

use App\Models\Employee;
use App\Services\HeroEmployeeClient;
use Illuminate\Console\Command;
use Illuminate\Database\QueryException;
use RuntimeException;

class BindHeroEmployees extends Command
{
    protected $signature = 'employees:bind-hero';

    protected $description = 'Bind unbound ARAIM employees to Hero when the NIK matches exactly one person';

    public function handle(HeroEmployeeClient $hero)
    {
        $bound = 0;
        $unmatched = 0;
        $ambiguous = 0;

        $employees = Employee::whereNull('hero_employee_id')->orderBy('nik')->get();

        foreach ($employees as $employee) {
            try {
                $match = $hero->matchByExactNik((string) $employee->nik);
            } catch (RuntimeException $exception) {
                $this->error($exception->getMessage());

                return 1;
            }

            if ($match['status'] === 'none') {
                $this->line('unmatched ' . $employee->nik . ' ' . $employee->fullname);
                $unmatched++;
                continue;
            }

            if ($match['status'] !== 'matched' || empty($match['person']['id'])) {
                $this->line('ambiguous ' . $employee->nik . ' ' . $employee->fullname);
                $ambiguous++;
                continue;
            }

            $heroId = $match['person']['id'];
            $taken = Employee::where('hero_employee_id', $heroId)
                ->where('id', '!=', $employee->id)
                ->exists();
            if ($taken) {
                $this->line('ambiguous ' . $employee->nik . ' ' . $employee->fullname . ' hero id already bound');
                $ambiguous++;
                continue;
            }

            $employee->hero_employee_id = $heroId;
            try {
                $employee->save();
            } catch (QueryException $exception) {
                $this->line('ambiguous ' . $employee->nik . ' ' . $employee->fullname . ' hero id already bound');
                $ambiguous++;
                continue;
            }

            $this->line('bound ' . $employee->nik . ' ' . $employee->fullname . ' ' . $heroId);
            $bound++;
        }

        $this->info('bound ' . $bound . ', unmatched ' . $unmatched . ', ambiguous ' . $ambiguous);

        return 0;
    }
}
