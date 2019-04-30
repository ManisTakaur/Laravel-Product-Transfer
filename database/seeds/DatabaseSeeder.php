<?php

use Illuminate\Database\Seeder;
use App\Employee;
class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tbl_categoryemploy')->insert(['categoryEmployId'=>5,'categoryEmployType'=>'Supplier']);
        DB::table('tbl_categoryemploy')->insert(['categoryEmployId'=>4,'categoryEmployType'=>'Company Manager']);

        $employee = new Employee();
        $employee->categoryEmployId ="5";
        $employee->employeeName ="Supplier";
        $employee->username ="supplier";
        $employee->employeePassword =md5('12345');
        $employee->save();

        $employee = new Employee();
        $employee->categoryEmployId ="4";
        $employee->employeeName ="Company Manager";
        $employee->username ="manager";
        $employee->employeePassword =md5('12345');
        $employee->save();
    }
}
