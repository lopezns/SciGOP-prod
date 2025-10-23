<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Payroll;
use App\Models\Employee;
use Carbon\Carbon;

class PayrollSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $employees = Employee::all();
        
        if ($employees->count() == 0) {
            $this->command->info('No employees found. Please run EmployeeSeeder first.');
            return;
        }

        // Generar nóminas para los últimos 3 meses
        $months = [
            ['start' => Carbon::now()->subMonths(2)->startOfMonth(), 'end' => Carbon::now()->subMonths(2)->endOfMonth()],
            ['start' => Carbon::now()->subMonths(1)->startOfMonth(), 'end' => Carbon::now()->subMonths(1)->endOfMonth()],
            ['start' => Carbon::now()->startOfMonth(), 'end' => Carbon::now()->endOfMonth()],
        ];

        foreach ($months as $monthIndex => $period) {
            foreach ($employees->take(15) as $employeeIndex => $employee) {
                $payrollCode = 'NOM-' . $period['start']->format('Y-m') . '-' . str_pad($employee->id, 3, '0', STR_PAD_LEFT);
                
                // Generar algunos datos variables
                $daysWorked = rand(28, 30);
                $hoursWorked = $daysWorked * 8;
                $overtimeHours = rand(0, 20);
                $bonuses = $monthIndex == 2 ? rand(0, 500000) : 0; // Bonos solo en el mes actual
                $commissions = in_array($employee->department, ['Ventas']) ? rand(100000, 800000) : 0;
                
                $payroll = Payroll::create([
                    'employee_id' => $employee->id,
                    'payroll_code' => $payrollCode,
                    'period_start' => $period['start'],
                    'period_end' => $period['end'],
                    'payment_date' => $period['end']->copy()->addDays(5),
                    'payroll_type' => 'mensual',
                    'days_worked' => $daysWorked,
                    'hours_worked' => $hoursWorked,
                    'overtime_hours' => $overtimeHours,
                    'base_salary' => $employee->base_salary,
                    'overtime_pay' => 0,
                    'bonuses' => $bonuses,
                    'commissions' => $commissions,
                    'transportation_allowance' => $employee->transportation_allowance,
                    'food_allowance' => rand(0, 200000),
                    'other_income' => 0,
                    'total_income' => 0, // Se calculará automáticamente
                    'health_contribution' => 0,
                    'pension_contribution' => 0,
                    'solidarity_fund' => 0,
                    'unemployment_fund' => 0,
                    'income_tax_withholding' => 0,
                    'other_withholdings' => 0,
                    'loan_deductions' => rand(0, 300000),
                    'other_deductions' => 0,
                    'total_deductions' => 0, // Se calculará automáticamente
                    'employer_health' => 0,
                    'employer_pension' => 0,
                    'employer_arl' => 0,
                    'employer_sena' => 0,
                    'employer_icbf' => 0,
                    'employer_compensation' => 0,
                    'total_employer_contributions' => 0,
                    'vacation_provision' => 0,
                    'bonus_provision' => 0,
                    'severance_provision' => 0,
                    'severance_interest' => 0,
                    'net_salary' => 0, // Se calculará automáticamente
                    'total_cost' => 0, // Se calculará automáticamente
                    'status' => $monthIndex < 2 ? 'paid' : ($monthIndex == 1 ? 'approved' : 'calculated'),
                    'notes' => $monthIndex < 2 ? 'Nómina pagada' : ($monthIndex == 1 ? 'Nómina aprobada pendiente de pago' : 'Nómina calculada pendiente de aprobación'),
                ]);

                // Calcular automáticamente la nómina
                try {
                    $payroll->calculatePayroll();
                    $this->command->info("Payroll calculated for {$employee->full_name} - {$period['start']->format('M Y')}");
                } catch (\Exception $e) {
                    $this->command->error("Error calculating payroll for {$employee->full_name}: " . $e->getMessage());
                }
            }
        }

        $this->command->info('PayrollSeeder completed successfully!');
    }
}
