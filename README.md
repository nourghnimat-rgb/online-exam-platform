# Exam Space

Exam Space is a Laravel application for running timed online exams. Instructors build exams for their classes, students take them inside an availability window, and the system grades them automatically through a queue.

## Features

- **Three roles:** Admin, Instructor and Student. Students sign up themselves; only an Admin can create Instructor/Admin accounts or change roles.
- **Classes:** a class groups instructors and students. Class membership decides who can see and manage exams and results.
- **Exams:** title, description, duration, availability window (opens/closes), pass mark, draft/published status, and a per-exam option to show the correct answers after submitting.
- **Questions:** single choice, multiple choice and short text, each with a point value. Questions are locked once an exam has attempts.
- **Attempts:** one attempt per student per exam, server-enforced time limit, answers saved as the student goes, and a different question order per student (optional per exam).
- **Grading:** queued job that stores the score, percentage and pass/fail. Attempts that are not submitted by their deadline are closed and graded automatically.
- **Notifications:** the student is notified (database notification, sent from a queued job) when the result is ready.

## Requirements

- PHP 8.3+
- Composer
- MySQL 8+
- Node.js is **not** required (Bootstrap is loaded from a CDN).

## Setup

1. Clone the repository and install dependencies:

~~~bash
git clone https://github.com/nourghnimat-rgb/online-exam-platform.git
cd online-exam-platform
composer install
~~~

2. Create the environment file and the app key:

~~~bash
cp .env.example .env
php artisan key:generate
~~~

3. Create an empty MySQL database (for example `exam_space`) and edit `.env`:

~~~dotenv
APP_NAME="Exam Space"
APP_TIMEZONE=Asia/Amman

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=exam_space
DB_USERNAME=your_user
DB_PASSWORD=your_password

QUEUE_CONNECTION=database
~~~

`APP_TIMEZONE` should be your local timezone: exam opening/closing times are entered and shown in it.

4. Run the migrations and seed the demo data:

~~~bash
php artisan migrate
php artisan db:seed
~~~

To reset everything later: `php artisan migrate:fresh --seed`.

5. Start the application:

~~~bash
php artisan serve
~~~

The app is available at <http://localhost:8000>. The login page is the home page (`/`).

## Running the queue worker (required)

Grading and result notifications run as queued jobs (`QUEUE_CONNECTION=database`). Without a worker, submitted attempts stay at **"Waiting for result"**.

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
* * * * * cd /path/to/project && php artisan schedule:run >> /dev/null 2>&1
~~~

You can also run it manually at any time:

~~~bash
php artisan attempts:close-expired
~~~

## Terminals to keep open

| Terminal | Command |
|---|---|
| 1 | `php artisan serve` |
| 2 | `php artisan queue:work` |
| 3 | `php artisan schedule:work` |

## Demo data (created by the seeder)

All accounts use the password `12345678`. Change it before using the app anywhere other than your local machine.

| Role | Email | Classes |
|---|---|---|
| Admin | admin@example.com | - |
| Instructor | instructor@example.com | Web Development |
| Instructor | instructor2@example.com | Databases |
| Student | student@example.com | Web Development, Databases |
| Student | student2@example.com | Web Development |

The seeder also creates a published exam, **Web Basics Quiz** (class: Web Development, 10 minutes, random question order, correct answers shown after submitting) with one question of each type: single choice, multiple choice and short text. Its availability window is relative to the moment you run the seeder (open for the next 30 days).

## Suggested walkthrough

1. Log in as **Admin** → *Manage Users* to change roles, and *Manage Courses* to create a class and assign instructors.
2. Sign up a new student from the register page, then log in as **Instructor** (`instructor@example.com`) → *My Courses* → add that student (by email) to *Web Development*.
3. As the Instructor, open the class exams, create an exam, add questions, then **Publish** it (the availability window must include the current time).
4. Log in as **Student** (`student@example.com`) → *My Exams* → start an exam, answer, and submit.
5. With the queue worker running, the result appears and a notification is shown to the student.
6. As the Instructor, open the exam's attempts list to see the results.

## Architecture and design decisions

- **Structure:** controllers are thin (receive a request, call a service, return a response). Validation lives in Form Requests, authorization in Policies (a Form Request's `authorize()` calls the Policy), and business logic in Services.
- **One attempt per exam:** guaranteed by a unique index on `attempts (exam_id, student_id)`, so simultaneous requests cannot create two attempts.
- **Deadline:** `expires_at` is set when the attempt starts (capped at the exam's closing time) and checked on the server for every save and submit. The timer in the browser is only a display and is re-synced with the server on every save.
- **Correct answers:** never selected from the database while an attempt is in progress; shown on the result page only when the instructor enabled it for the exam.
- **Grading:** a question earns its full points or zero. Multiple choice requires exactly the correct set of options. Short text is compared case-insensitively after trimming whitespace.
- **Automatic closing:** the scheduled command claims each expired attempt with a conditional update before dispatching the grading job, so an attempt is never graded twice.
- **Notifications:** stored in the `notifications` table by a queued job and shown to the student.
- **Questions are locked** once an exam has attempts.
- **Question order:** when "Randomize question order" is enabled, each student gets a different order, stable across page reloads.