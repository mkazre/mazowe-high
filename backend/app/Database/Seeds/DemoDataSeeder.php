<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DemoDataSeeder extends Seeder
{
    private function now(): string
    {
        return date('Y-m-d H:i:s');
    }

    private function roleId(string $slug): ?int
    {
        $row = $this->db->table('roles')->where('slug', $slug)->get()->getRowArray();

        return $row['id'] ?? null;
    }

    private function makeUser(string $name, string $email, string $roleSlug): int
    {
        $existing = $this->db->table('users')->where('email', $email)->get()->getRowArray();
        if ($existing) {
            return (int) $existing['id'];
        }
        $this->db->table('users')->insert([
            'name'       => $name,
            'email'      => $email,
            'password'   => password_hash('Demo@1234', PASSWORD_DEFAULT),
            'role_id'    => $this->roleId($roleSlug),
            'status'     => 'active',
            'created_at' => $this->now(),
            'updated_at' => $this->now(),
        ]);

        return (int) $this->db->insertID();
    }

    public function run()
    {
        $now = $this->now();

        // ---------------- Academic setup ----------------
        $this->db->table('academic_years')->insert(['name' => '2027', 'is_current' => 1]);
        $yearId = (int) $this->db->insertID();

        $this->db->table('terms')->insert(['academic_year_id' => $yearId, 'name' => 'Term 1', 'starts_on' => '2027-01-12', 'ends_on' => '2027-03-26', 'is_current' => 1]);
        $termId = (int) $this->db->insertID();
        $this->db->table('terms')->insert(['academic_year_id' => $yearId, 'name' => 'Term 2', 'starts_on' => '2027-04-27', 'ends_on' => '2027-07-09', 'is_current' => 0]);
        $this->db->table('terms')->insert(['academic_year_id' => $yearId, 'name' => 'Term 3', 'starts_on' => '2027-08-31', 'ends_on' => '2027-11-12', 'is_current' => 0]);

        $yearGroups = [];
        foreach (['Form 1', 'Form 2', 'Form 3', 'Form 4', 'Lower Sixth', 'Upper Sixth'] as $i => $name) {
            $this->db->table('year_groups')->insert(['name' => $name, 'sort_order' => $i]);
            $yearGroups[$name] = (int) $this->db->insertID();
        }

        $houses = [];
        foreach ([['Nyanga', '#2e7d32'], ['Zambezi', '#0b3d6b'], ['Matopo', '#c9a227'], ['Chimanimani', '#ec3013']] as [$name, $colour]) {
            $this->db->table('houses')->insert(['name' => $name, 'colour' => $colour]);
            $houses[$name] = (int) $this->db->insertID();
        }

        $subjects = [];
        foreach (['Mathematics', 'English', 'Combined Science', 'History', 'Geography', 'Computer Science', 'Physical Education'] as $name) {
            $this->db->table('subjects')->insert(['name' => $name, 'pathway' => 'both']);
            $subjects[$name] = (int) $this->db->insertID();
        }

        // ---------------- Staff (a demo teacher) ----------------
        $teacherUserId = $this->makeUser('Mrs. Nyasha Chirwa', 'teacher@mazoweheights.ac.zw', 'teacher');
        $this->db->table('staff')->insert(['user_id' => $teacherUserId, 'staff_number' => 'STF-001', 'department' => 'Mathematics', 'position' => 'Class Teacher']);
        $staffId = (int) $this->db->insertID();

        // ---------------- Class ----------------
        $this->db->table('classes')->insert(['year_group_id' => $yearGroups['Form 1'], 'name' => 'Form 1 Nyanga', 'house_id' => $houses['Nyanga'], 'form_teacher_id' => $staffId]);
        $classId = (int) $this->db->insertID();

        // ---------------- Demo student + guardian ----------------
        $studentUserId = $this->makeUser('Tafara Mutasa', 'student@mazoweheights.ac.zw', 'student');
        $this->db->table('students')->insert([
            'admission_number' => 'MH-2027-0001',
            'first_name' => 'Tafara', 'last_name' => 'Mutasa', 'dob' => '2014-03-11', 'gender' => 'Male',
            'year_group_id' => $yearGroups['Form 1'], 'class_id' => $classId, 'house_id' => $houses['Nyanga'],
            'day_or_boarding' => 'boarding', 'user_id' => $studentUserId, 'status' => 'active', 'created_at' => $now,
        ]);
        $studentId = (int) $this->db->insertID();

        $parentUserId = $this->makeUser('Rudo Mutasa', 'parent@mazoweheights.ac.zw', 'parent');
        $this->db->table('guardians')->insert(['name' => 'Rudo Mutasa', 'email' => 'parent@mazoweheights.ac.zw', 'phone' => '+263 77 000 0000', 'user_id' => $parentUserId]);
        $guardianId = (int) $this->db->insertID();
        $this->db->table('guardian_student')->insert(['guardian_id' => $guardianId, 'student_id' => $studentId, 'relationship' => 'Mother', 'is_primary' => 1]);

        // ---------------- Timetable ----------------
        $periods = [];
        $slots = [['Period 1', '07:30', '08:15'], ['Period 2', '08:15', '09:00'], ['Break', '09:00', '09:20'], ['Period 3', '09:20', '10:05'], ['Period 4', '10:05', '10:50']];
        foreach ($slots as $i => [$name, $start, $end]) {
            $this->db->table('timetable_periods')->insert(['name' => $name, 'start_time' => $start, 'end_time' => $end, 'sort_order' => $i]);
            $periods[] = (int) $this->db->insertID();
        }
        $subjectCycle = array_values($subjects);
        for ($day = 1; $day <= 5; $day++) {
            foreach ($periods as $i => $periodId) {
                if ($i === 2) continue; // break period, no lesson
                $this->db->table('timetable_entries')->insert([
                    'class_id' => $classId, 'subject_id' => $subjectCycle[($day + $i) % count($subjectCycle)],
                    'staff_id' => $staffId, 'period_id' => $periodId, 'day_of_week' => $day, 'room' => 'Room ' . (($i % 5) + 1),
                ]);
            }
        }

        // ---------------- Attendance (last 5 school days) ----------------
        for ($d = 5; $d >= 1; $d--) {
            $this->db->table('attendance_marks')->insert([
                'student_id' => $studentId, 'class_id' => $classId, 'mark_date' => date('Y-m-d', strtotime("-$d weekdays")),
                'status' => $d === 3 ? 'late' : 'present', 'marked_by' => $teacherUserId, 'created_at' => $now,
            ]);
        }

        // ---------------- Conduct ----------------
        $this->db->table('conduct_marks')->insert(['student_id' => $studentId, 'type' => 'merit', 'points' => 2, 'reason' => 'Excellent contribution in Mathematics', 'awarded_by' => $teacherUserId, 'created_at' => $now]);
        $this->db->table('conduct_marks')->insert(['student_id' => $studentId, 'type' => 'merit', 'points' => 1, 'reason' => 'Helped a younger pupil settle in', 'awarded_by' => $teacherUserId, 'created_at' => $now]);

        // ---------------- Homework ----------------
        $this->db->table('homework')->insert([
            'class_id' => $classId, 'subject_id' => $subjects['Mathematics'], 'staff_id' => $staffId,
            'title' => 'Algebra worksheet — Chapter 3', 'description' => 'Complete questions 1–12 and show your working.',
            'due_date' => date('Y-m-d', strtotime('+3 days')), 'created_at' => $now,
        ]);
        $homeworkId = (int) $this->db->insertID();
        $this->db->table('homework_submissions')->insert(['homework_id' => $homeworkId, 'student_id' => $studentId, 'status' => 'pending']);

        $this->db->table('homework')->insert([
            'class_id' => $classId, 'subject_id' => $subjects['English'], 'staff_id' => $staffId,
            'title' => 'Reading response — Chapter 2', 'description' => 'Write a one-page response to the chapter.',
            'due_date' => date('Y-m-d', strtotime('-1 day')), 'created_at' => $now,
        ]);
        $homework2Id = (int) $this->db->insertID();
        $this->db->table('homework_submissions')->insert(['homework_id' => $homework2Id, 'student_id' => $studentId, 'status' => 'submitted', 'submitted_at' => $now, 'notes' => 'Submitted on time.']);

        // ---------------- Assessment & report ----------------
        $this->db->table('assessments')->insert(['class_id' => $classId, 'subject_id' => $subjects['Mathematics'], 'term_id' => $termId, 'name' => 'Term 1 Test', 'max_score' => 100, 'created_at' => $now]);
        $assessMathId = (int) $this->db->insertID();
        $this->db->table('assessment_scores')->insert(['assessment_id' => $assessMathId, 'student_id' => $studentId, 'score' => 84, 'comment' => 'Strong grasp of algebra.']);

        $this->db->table('assessments')->insert(['class_id' => $classId, 'subject_id' => $subjects['English'], 'term_id' => $termId, 'name' => 'Term 1 Test', 'max_score' => 100, 'created_at' => $now]);
        $assessEngId = (int) $this->db->insertID();
        $this->db->table('assessment_scores')->insert(['assessment_id' => $assessEngId, 'student_id' => $studentId, 'score' => 76, 'comment' => 'Good progress on comprehension.']);

        $this->db->table('reports')->insert(['student_id' => $studentId, 'term_id' => $termId, 'overall_comment' => 'A settled and positive start to boarding life. Keep up the effort in Mathematics.', 'published' => 1, 'generated_at' => $now]);
        $reportId = (int) $this->db->insertID();
        $this->db->table('report_comments')->insert(['report_id' => $reportId, 'subject_id' => $subjects['Mathematics'], 'grade' => 'A', 'effort' => '1', 'comment' => 'Excellent term.']);
        $this->db->table('report_comments')->insert(['report_id' => $reportId, 'subject_id' => $subjects['English'], 'grade' => 'B', 'effort' => '2', 'comment' => 'Consistent effort.']);

        // ---------------- Finance ----------------
        $this->db->table('fee_structures')->insert(['year_group_id' => $yearGroups['Form 1'], 'term_id' => $termId, 'description' => 'Term 1 boarding fee', 'amount_cents' => 85000_00]);
        $this->db->table('invoices')->insert([
            'student_id' => $studentId, 'term_id' => $termId, 'invoice_number' => 'MH-INV-27-0431',
            'total_cents' => 85000_00, 'paid_cents' => 0, 'status' => 'unpaid', 'due_date' => '2027-01-13', 'created_at' => $now,
        ]);
        $invoiceId = (int) $this->db->insertID();
        $this->db->table('invoice_lines')->insert(['invoice_id' => $invoiceId, 'description' => 'Tuition', 'amount_cents' => 45000_00]);
        $this->db->table('invoice_lines')->insert(['invoice_id' => $invoiceId, 'description' => 'Boarding & meals', 'amount_cents' => 35000_00]);
        $this->db->table('invoice_lines')->insert(['invoice_id' => $invoiceId, 'description' => 'Activities & prep', 'amount_cents' => 5000_00]);

        // ---------------- Boarding ----------------
        $this->db->table('dormitories')->insert(['house_id' => $houses['Nyanga'], 'name' => 'Nyanga Bay 1', 'capacity' => 6]);
        $dormId = (int) $this->db->insertID();
        $this->db->table('beds')->insert(['dormitory_id' => $dormId, 'label' => 'Bed 1', 'student_id' => $studentId]);
        for ($b = 2; $b <= 6; $b++) {
            $this->db->table('beds')->insert(['dormitory_id' => $dormId, 'label' => 'Bed ' . $b, 'student_id' => null]);
        }
        $this->db->table('exeat_requests')->insert(['student_id' => $studentId, 'reason' => 'Family function', 'depart_at' => date('Y-m-d H:i:s', strtotime('+2 weeks Friday 15:00')), 'return_at' => date('Y-m-d H:i:s', strtotime('+2 weeks Sunday 18:00')), 'status' => 'pending', 'created_at' => $now]);
        $this->db->table('tuck_accounts')->insert(['student_id' => $studentId, 'balance_cents' => 1500_00]);
        $tuckId = (int) $this->db->insertID();
        $this->db->table('tuck_transactions')->insert(['tuck_account_id' => $tuckId, 'type' => 'deposit', 'amount_cents' => 2000_00, 'note' => 'Term deposit from parent', 'created_at' => $now]);
        $this->db->table('tuck_transactions')->insert(['tuck_account_id' => $tuckId, 'type' => 'purchase', 'amount_cents' => -500_00, 'note' => 'Tuck shop — snacks', 'created_at' => $now]);

        // ---------------- Catering ----------------
        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
        foreach ($days as $i => $dayName) {
            $this->db->table('menu_days')->insert(['day_of_week' => $i + 1, 'day_name' => $dayName]);
            $dayId = (int) $this->db->insertID();
            $this->db->table('menu_items')->insert(['menu_day_id' => $dayId, 'meal' => 'breakfast', 'description' => 'Bota with peanut butter, eggs, bread', 'tags' => 'V']);
            $bId = (int) $this->db->insertID();
            $this->db->table('menu_items')->insert(['menu_day_id' => $dayId, 'meal' => 'lunch', 'description' => 'Sadza, beef stew, sugar beans, muriwo', 'tags' => 'GF · H']);
            $this->db->table('menu_items')->insert(['menu_day_id' => $dayId, 'meal' => 'supper', 'description' => 'Rice, roast chicken, mixed vegetables', 'tags' => 'H']);
            if ($i === 0) {
                $this->db->table('meal_ratings')->insert(['menu_item_id' => $bId, 'student_id' => $studentId, 'rating' => 4, 'created_at' => $now]);
            }
        }

        // ---------------- Transport ----------------
        $this->db->table('routes')->insert(['name' => 'Harare North', 'description' => 'Borrowdale, Mt Pleasant, Avondale']);
        $routeId = (int) $this->db->insertID();
        foreach ([['Borrowdale Village', '06:15', -17.7620, 31.0850], ['Mt Pleasant Shops', '06:35', -17.7460, 31.0480], ['Avondale Shops', '06:55', -17.8100, 31.0330]] as $i => [$name, $eta, $lat, $lng]) {
            $this->db->table('stops')->insert(['route_id' => $routeId, 'name' => $name, 'sort_order' => $i, 'eta' => $eta, 'lat' => $lat, 'lng' => $lng]);
        }
        $this->db->table('vehicle_pings')->insert(['route_id' => $routeId, 'lat' => -17.7700, 'lng' => 31.0600, 'recorded_at' => $now]);
        $this->db->table('route_subscriptions')->insert(['route_id' => $routeId, 'student_id' => $studentId]);

        // ---------------- Library ----------------
        $books = [['Things Fall Apart', 'Chinua Achebe'], ['A Grain of Wheat', "Ngũgĩ wa Thiong'o"], ['Nervous Conditions', 'Tsitsi Dangarembga'], ['Introduction to Algorithms', 'Cormen et al.']];
        $catalogueId = null;
        foreach ($books as $i => [$title, $author]) {
            $this->db->table('catalogue_items')->insert(['title' => $title, 'author' => $author, 'copies_total' => 3]);
            $id = (int) $this->db->insertID();
            if ($i === 0) $catalogueId = $id;
        }
        $this->db->table('loans')->insert(['catalogue_item_id' => $catalogueId, 'student_id' => $studentId, 'borrowed_at' => date('Y-m-d', strtotime('-5 days')), 'due_at' => date('Y-m-d', strtotime('+9 days'))]);

        // ---------------- Comms ----------------
        $this->db->table('message_threads')->insert(['subject' => 'Tafara — settling in', 'student_id' => $studentId, 'created_at' => $now]);
        $threadId = (int) $this->db->insertID();
        $this->db->table('thread_participants')->insert(['thread_id' => $threadId, 'user_id' => $teacherUserId]);
        $this->db->table('thread_participants')->insert(['thread_id' => $threadId, 'user_id' => $parentUserId]);
        $this->db->table('messages')->insert(['thread_id' => $threadId, 'sender_user_id' => $teacherUserId, 'sender_label' => 'Mrs. Chirwa (Class Teacher)', 'body' => 'Tafara has settled in well this week and is contributing confidently in Mathematics.', 'created_at' => $now]);
        $this->db->table('messages')->insert(['thread_id' => $threadId, 'sender_user_id' => $parentUserId, 'sender_label' => 'Rudo Mutasa (Parent)', 'body' => 'Thank you for the update — glad to hear it!', 'created_at' => $now]);

        echo "Demo accounts (password for all: Demo@1234):\n";
        echo "  Parent:  parent@mazoweheights.ac.zw\n";
        echo "  Student: student@mazoweheights.ac.zw\n";
        echo "  Teacher: teacher@mazoweheights.ac.zw\n";
    }
}
