# Online Exam Platform

A Laravel application where instructors create timed exams for their classes, students take them, and the system grades them automatically through a queue.

## Features

- Three roles: **Admin**, **Instructor**, **Student** (students sign up themselves; Admin assigns Instructor/Admin roles).
- Classes (courses) group instructors and students; membership scopes who can see and manage exams.
- Exams with duration, availability window, pass mark, draft/published status and an option to reveal correct answers after submission.
- Single choice, multiple choice and short text questions with point values.
- Server-enforced time limit, autosave, one attempt per student (enforced by a database unique constraint).
- Queued grading and queued result notifications; unsubmitted attempts are closed and graded automatically.

## Requirements

- PHP 8.3+
- Composer
- MySQL 8+
- Node.js and npm (only needed if you want to build the front-end assets)

## Setup

~~~bash
# 1. Install dependencies
composer install

# 2. Create the environment file and app key
cp .env.example .env
php artisan key:generate
~~~

3. Create an empty MySQL database (for example `exam_platform`), then edit `.env`:

~~~dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=exam_platform
DB_USERNAME=your_user
DB_PASSWORD=your_password

QUEUE_CONNECTION=database
~~~

4. Run the migrations and seed the demo users:

~~~bash
php artisan migrate
php artisan db:seed
~~~

5. Start the application:

~~~bash
php artisan serve
~~~

The app is available at <http://localhost:8000>. The login page is the home page (`/`).

## Running the queue worker (required)

Grading and result notifications run as queued jobs (`QUEUE_CONNECTION=database`). Without a worker, submitted attempts will stay at **"Waiting for result"**.

In a separate terminal:

~~~bash
php artisan queue:work
~~~

Restart the worker after changing any job code.

## Running the scheduler (required)

Attempts that are not submitted by their deadline are closed and graded by the `attempts:close-expired` command, which is scheduled to run every minute.

For local development, in another terminal:

~~~bash
php artisan schedule:work
~~~

In production, add this cron entry instead:

~~~cron
* * * * * cd /path/to/exam-platform && php artisan schedule:run >> /dev/null 2>&1
~~~

You can also run it manually at any time:

~~~bash
php artisan attempts:close-expired
~~~

## Summary: terminals to keep open

| Terminal | Command |
|---|---|
| 1 | `php artisan serve` |
| 2 | `php artisan queue:work` |
| 3 | `php artisan schedule:work` |

## Demo accounts (created by the seeder)

| Role | Email | Password |
|---|---|---|
| Admin | admin@example.com | 12345678 |
| Instructor | instructor@example.com | 12345678 |
| Student | student@example.com | 12345678 |

Change these passwords before using the app anywhere other than your local machine.

## Suggested walkthrough

1. Log in as **Admin** → *Manage Courses* → create a course and assign the instructor.
2. Log in as **Instructor** → *My Courses* → add the student (by email) to the course.
3. As Instructor, create an exam, add questions, then **Publish** it (the availability window must include the current time).
4. Log in as **Student** → *My Exams* → start the exam, answer, and submit.
5. With the queue worker running, the result appears and a notification is shown to the student.

## Design decisions

- **Architecture:** controllers are thin; validation lives in Form Requests, authorization in Policies (Form Request `authorize()` calls the Policy), business logic in Services.
- **One attempt per exam:** guaranteed by a unique index on `attempts (exam_id, student_id)`, so simultaneous requests cannot create two attempts.
- **Deadline:** `expires_at` is set when the attempt starts (capped at the exam's closing time) and checked on the server for every save and submit.
- **Correct answers:** never selected from the database while an attempt is in progress; shown on the result page only when the instructor enabled it for the exam.
- **Grading:** a question earns its full points or zero. Multiple choice requires exactly the correct set of options. Short text is compared case-insensitively after trimming whitespace.
- **Notifications:** stored in the database (`notifications` table) by a queued job and shown to the student.
- **Questions are locked** once an exam has attempts.
- **Question order:** when "Randomize question order" is enabled, each student gets a different order, stable across page reloads.