<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Employee;
use Carbon\Carbon;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $employees = [
            [
                'employee_code' => 'EMP001',
                'first_name' => 'María',
                'last_name' => 'González Rodríguez',
                'document_type' => 'CC',
                'document_number' => '52123456',
                'email' => 'maria.gonzalez@empresa.com',
                'phone' => '3101234567',
                'address' => 'Carrera 15 #85-42, Bogotá',
                'birth_date' => '1985-03-15',
                'hire_date' => '2020-01-15',
                'position' => 'Gerente General',
                'department' => 'Administración',
                'base_salary' => 8500000,
                'contract_type' => 'indefinido',
                'salary_type' => 'fijo',
                'eps' => 'Compensar',
                'pension_fund' => 'Colpensiones',
                'arl' => 'Positiva',
                'cesantias_fund' => 'Porvenir',
                'high_risk' => false,
                'integral_salary' => true,
                'transportation_allowance' => 0,
            ],
            [
                'employee_code' => 'EMP002',
                'first_name' => 'Carlos',
                'last_name' => 'Mendoza Pérez',
                'document_type' => 'CC',
                'document_number' => '80456789',
                'email' => 'carlos.mendoza@empresa.com',
                'phone' => '3209876543',
                'address' => 'Calle 26 #68-15, Bogotá',
                'birth_date' => '1990-07-22',
                'hire_date' => '2021-03-01',
                'position' => 'Contador Público',
                'department' => 'Contabilidad',
                'base_salary' => 3500000,
                'contract_type' => 'indefinido',
                'salary_type' => 'fijo',
                'eps' => 'Sanitas',
                'pension_fund' => 'Protección',
                'arl' => 'Sura',
                'cesantias_fund' => 'Protección',
                'transportation_allowance' => 140606,
            ],
            [
                'employee_code' => 'EMP003',
                'first_name' => 'Ana',
                'last_name' => 'Martínez López',
                'document_type' => 'CC',
                'document_number' => '63789012',
                'email' => 'ana.martinez@empresa.com',
                'phone' => '3157890123',
                'address' => 'Avenida 19 #104-28, Bogotá',
                'birth_date' => '1988-11-08',
                'hire_date' => '2019-06-15',
                'position' => 'Jefe de Recursos Humanos',
                'department' => 'Recursos Humanos',
                'base_salary' => 4200000,
                'contract_type' => 'indefinido',
                'salary_type' => 'fijo',
                'eps' => 'EPS Sura',
                'pension_fund' => 'Colfondos',
                'arl' => 'Colmena',
                'cesantias_fund' => 'Colfondos',
                'transportation_allowance' => 140606,
            ],
            [
                'employee_code' => 'EMP004',
                'first_name' => 'Luis',
                'last_name' => 'Ramírez Torres',
                'document_type' => 'CC',
                'document_number' => '71234567',
                'email' => 'luis.ramirez@empresa.com',
                'phone' => '3001234567',
                'address' => 'Transversal 45 #127-50, Bogotá',
                'birth_date' => '1992-04-20',
                'hire_date' => '2022-01-10',
                'position' => 'Desarrollador Senior',
                'department' => 'Tecnología',
                'base_salary' => 5500000,
                'contract_type' => 'indefinido',
                'salary_type' => 'fijo',
                'eps' => 'Nueva EPS',
                'pension_fund' => 'Skandia',
                'arl' => 'Liberty',
                'cesantias_fund' => 'Skandia',
                'transportation_allowance' => 140606,
            ],
            [
                'employee_code' => 'EMP005',
                'first_name' => 'Sandra',
                'last_name' => 'Herrera Gómez',
                'document_type' => 'CC',
                'document_number' => '52987654',
                'email' => 'sandra.herrera@empresa.com',
                'phone' => '3128765432',
                'address' => 'Calle 140 #15-23, Bogotá',
                'birth_date' => '1995-09-12',
                'hire_date' => '2023-02-01',
                'position' => 'Coordinadora de Ventas',
                'department' => 'Ventas',
                'base_salary' => 2800000,
                'contract_type' => 'indefinido',
                'salary_type' => 'mixto',
                'eps' => 'Compensar',
                'pension_fund' => 'Porvenir',
                'arl' => 'Positiva',
                'cesantias_fund' => 'Porvenir',
                'transportation_allowance' => 140606,
            ],
        ];

        // Crear empleados adicionales de forma automática
        $departments = ['Ventas', 'Contabilidad', 'Recursos Humanos', 'Tecnología', 'Administración', 'Operaciones'];
        $positions = [
            'Ventas' => ['Ejecutivo de Ventas', 'Asesor Comercial', 'Representante de Ventas'],
            'Contabilidad' => ['Auxiliar Contable', 'Analista Financiero', 'Tesorero'],
            'Recursos Humanos' => ['Analista de RRHH', 'Coordinador de Nómina', 'Especialista en Selección'],
            'Tecnología' => ['Desarrollador Junior', 'Analista de Sistemas', 'Soporte Técnico'],
            'Administración' => ['Asistente Administrativo', 'Coordinador Logístico', 'Secretaria'],
            'Operaciones' => ['Supervisor de Operaciones', 'Auxiliar de Producción', 'Control de Calidad']
        ];

        $names = [
            ['first_name' => 'Diego', 'last_name' => 'Morales Castro', 'document_number' => '80123456', 'email' => 'diego.morales@empresa.com'],
            ['first_name' => 'Patricia', 'last_name' => 'Vargas Ruiz', 'document_number' => '52345678', 'email' => 'patricia.vargas@empresa.com'],
            ['first_name' => 'Andrés', 'last_name' => 'Jiménez Silva', 'document_number' => '71456789', 'email' => 'andres.jimenez@empresa.com'],
            ['first_name' => 'Carolina', 'last_name' => 'Ospina Mejía', 'document_number' => '63567890', 'email' => 'carolina.ospina@empresa.com'],
            ['first_name' => 'Fernando', 'last_name' => 'Castillo Díaz', 'document_number' => '79678901', 'email' => 'fernando.castillo@empresa.com'],
            ['first_name' => 'Mónica', 'last_name' => 'Restrepo Arias', 'document_number' => '52789012', 'email' => 'monica.restrepo@empresa.com'],
            ['first_name' => 'Javier', 'last_name' => 'Delgado Sánchez', 'document_number' => '80890123', 'email' => 'javier.delgado@empresa.com'],
            ['first_name' => 'Liliana', 'last_name' => 'Agudelo Ramírez', 'document_number' => '63901234', 'email' => 'liliana.agudelo@empresa.com'],
            ['first_name' => 'Ricardo', 'last_name' => 'Muñoz Cortés', 'document_number' => '71012345', 'email' => 'ricardo.munoz@empresa.com'],
            ['first_name' => 'Claudia', 'last_name' => 'Salazar Vega', 'document_number' => '52123457', 'email' => 'claudia.salazar@empresa.com'],
            ['first_name' => 'Mauricio', 'last_name' => 'Parra Álvarez', 'document_number' => '80234568', 'email' => 'mauricio.parra@empresa.com'],
            ['first_name' => 'Gloria', 'last_name' => 'Rincón Herrera', 'document_number' => '63345679', 'email' => 'gloria.rincon@empresa.com'],
            ['first_name' => 'Alejandro', 'last_name' => 'Rojas Quintero', 'document_number' => '79456780', 'email' => 'alejandro.rojas@empresa.com'],
            ['first_name' => 'Esperanza', 'last_name' => 'Cruz Moreno', 'document_number' => '52567891', 'email' => 'esperanza.cruz@empresa.com'],
            ['first_name' => 'Germán', 'last_name' => 'Ángel Peña', 'document_number' => '71678902', 'email' => 'german.angel@empresa.com'],
        ];

        // Crear los 5 empleados principales
        foreach ($employees as $employee) {
            Employee::create($employee);
        }

        // Crear 15 empleados adicionales
        foreach ($names as $index => $name) {
            $department = $departments[array_rand($departments)];
            $position = $positions[$department][array_rand($positions[$department])];
            $salary = rand(130, 450) * 10000; // Salarios entre 1.3M y 4.5M
            
            Employee::create([
                'employee_code' => 'EMP' . str_pad($index + 6, 3, '0', STR_PAD_LEFT),
                'first_name' => $name['first_name'],
                'last_name' => $name['last_name'],
                'document_type' => 'CC',
                'document_number' => $name['document_number'],
                'email' => $name['email'],
                'phone' => '31' . rand(10000000, 99999999),
                'address' => 'Dirección ' . ($index + 1) . ', Bogotá',
                'birth_date' => Carbon::now()->subYears(rand(25, 45))->subDays(rand(1, 365))->format('Y-m-d'),
                'hire_date' => Carbon::now()->subMonths(rand(6, 36))->format('Y-m-d'),
                'position' => $position,
                'department' => $department,
                'base_salary' => $salary,
                'contract_type' => 'indefinido',
                'salary_type' => 'fijo',
                'eps' => ['Compensar', 'Sanitas', 'EPS Sura', 'Nueva EPS'][array_rand(['Compensar', 'Sanitas', 'EPS Sura', 'Nueva EPS'])],
                'pension_fund' => ['Colpensiones', 'Protección', 'Colfondos', 'Skandia', 'Porvenir'][array_rand(['Colpensiones', 'Protección', 'Colfondos', 'Skandia', 'Porvenir'])],
                'arl' => ['Positiva', 'Sura', 'Colmena', 'Liberty'][array_rand(['Positiva', 'Sura', 'Colmena', 'Liberty'])],
                'cesantias_fund' => ['Porvenir', 'Protección', 'Colfondos', 'Skandia'][array_rand(['Porvenir', 'Protección', 'Colfondos', 'Skandia'])],
                'transportation_allowance' => $salary <= 2600000 ? 140606 : 0,
            ]);
        }
    }
}
