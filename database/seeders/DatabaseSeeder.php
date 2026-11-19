<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $admin = User::forceCreate([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'role' => 'admin',
            'password' => '12345678',
        ]);

        $instructor = User::forceCreate([
            'name' => 'Instructor',
            'email' => 'instructor@example.com',
            'role' => 'instructor',
            'password' => '12345678',
        ]);

        $instructor2 = User::forceCreate([
            'name' => 'Second Instructor',
            'email' => 'instructor2@example.com',
            'role' => 'instructor',
            'password' => '12345678',
        ]);

        $student = User::forceCreate([
            'name' => 'Student',
            'email' => 'student@example.com',
            'role' => 'student',
            'password' => '12345678',
        ]);

        $student2 = User::forceCreate([
            'name' => 'Second Student',
            'email' => 'student2@example.com',
            'role' => 'student',
            'password' => '12345678',
        ]);

        $web = Course::create([
            'name' => 'Web Development',
            'description' => 'HTML, CSS and PHP basics',
        ]);
        $web->users()->attach([$instructor->id, $student->id, $student2->id]);

        $db = Course::create([
            'name' => 'Databases',
            'description' => 'SQL and data modeling',
        ]);
        $db->users()->attach([$instructor2->id, $student->id]);
        $exam = $web->exams()->forceCreate([
            'created_by'           => $instructor->id,
            'title'                => 'Web Basics Quiz',
            'description'          => 'A short quiz on HTML and PHP.',
            'duration_minutes'     => 10,
            'opens_at'             => now()->subDay(),
            'closes_at'            => now()->addDays(30),
            'pass_mark'            => 50,
            'status'               => 'published',
            'show_correct_answers' => true,
            'randomize_questions'  => true,
        ]);

        // 1) single choice
        $q1 = $exam->questions()->create([
            'text' => 'Which tag creates a hyperlink in HTML?',
            'type' => 'single_choice',
            'points' => 2,
            'position' => 1,
        ]);
        foreach (['<a>' => true, '<p>' => false, '<div>' => false] as $text => $correct) {
            $q1->options()->create([
                'text' => $text,
                'is_correct' => $correct,
                'position' => $q1->options()->count() + 1,
            ]);
        }

        // 2) multiple choice
        $q2 = $exam->questions()->create([
            'text' => 'Which of these are PHP frameworks?',
            'type' => 'multiple_choice',
            'points' => 3,
            'position' => 2,
        ]);
        foreach (['Laravel' => true, 'Symfony' => true, 'Django' => false, 'React' => false] as $text => $correct) {
            $q2->options()->create([
                'text' => $text,
                'is_correct' => $correct,
                'position' => $q2->options()->count() + 1,
            ]);
        }

        // 3) short text
        $exam->questions()->create([
            'text' => 'What does CSS stand for? (three words)',
            'type' => 'short_text',
            'points' => 5,
            'position' => 3,
            'correct_answer' => 'Cascading Style Sheets',
        ]);
    }
}
