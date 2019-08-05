<?php

use Illuminate\Database\Seeder;
use App\BibSubject;
use Faker\Factory as Faker;
use App\Department;
use App\Course;

class DcsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $department = Department::create(['name' => 'General Subjects']);
        $department  = Department::create(['name' => 'College of Business Administration']);
        $course = $department->courses()->create(['name' => 'Business Communications and Critical Thinking']);
        $subject = $course->subjects()->create(['department_id' => $course->department_id, 'code' => '1112', 'name' => 'Subject CBA']);

        $department  = Department::create(['name' => 'College of Arts and Science']);
        $course = $department->courses()->create(['name' => 'Bachelor of Arts in Communication Arts']);
        $subject = $course->subjects()->create(['department_id' => $course->department_id, 'code' => '1112', 'name' => 'Subject CAS']);

        $department  = Department::create(['name' => 'College of Education']);
        $course = $department->courses()->create(['name' => 'Bachelor of Elementary Education']);
        $subject = $course->subjects()->create(['department_id' => $course->department_id, 'code' => '1112', 'name' => 'Subject COED']);

        $department  = Department::create(['name' => 'College of Technology']);
        $course = $department->courses()->create(['name' => 'Sample Course COT']);
        $subject = $course->subjects()->create(['department_id' => $course->department_id, 'code' => '1112', 'name' => 'Subject COT']);

        $department  = Department::create(['name' => 'Shool of Applied Science']);
        $course = $department->courses()->create(['name' => 'Sample Course SAC']);
        $subject = $course->subjects()->create(['department_id' => $course->department_id, 'code' => '1112', 'name' => 'Subject SAC']);
    }
}
