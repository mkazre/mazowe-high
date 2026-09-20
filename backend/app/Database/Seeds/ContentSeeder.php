<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ContentSeeder extends Seeder
{
    public function run()
    {
        $now = date('Y-m-d H:i:s');

        $notices = [
            ['title' => 'Round 2 entrance assessment — Saturday 23 October', 'body' => 'Applicants sitting the second round of entrance assessments should arrive at the Assessment Hall by 07:45 on Saturday 23 October. Bring your application reference and a pen; no calculators are needed for the entrance paper.'],
            ['title' => 'Founding Open Day booking now open', 'body' => 'The Founding Open Day runs Saturday 10 October, 09:00, at the Main Quadrangle. Booking is required — spaces are limited to keep tour groups small.'],
            ['title' => 'Parent Information Evening — 5 November, online', 'body' => 'An online information evening for prospective parents runs Thursday 5 November at 18:00, covering admissions, fees and the Cambridge/ZIMSEC pathway choice.'],
        ];
        foreach ($notices as $n) {
            $this->db->table('notices')->insert([
                'title'        => $n['title'],
                'body'         => $n['body'],
                'status'       => 'published',
                'published_at' => $now,
                'created_at'   => $now,
                'updated_at'   => $now,
            ]);
        }

        $events = [
            ['title' => 'Founding Open Day', 'location' => 'Main Quadrangle', 'starts_at' => '2026-10-10 09:00:00', 'tag' => 'Booking open'],
            ['title' => 'Entrance Assessment — Round 2', 'location' => 'Assessment Hall', 'starts_at' => '2026-10-23 08:00:00', 'tag' => 'Applicants only'],
            ['title' => 'Parent Information Evening', 'location' => 'Online', 'starts_at' => '2026-11-05 18:00:00', 'tag' => 'Free'],
            ['title' => 'Valley Run & Family Picnic', 'location' => 'Top Field', 'starts_at' => '2026-11-21 07:30:00', 'tag' => 'Tickets $5'],
        ];
        foreach ($events as $e) {
            $this->db->table('events')->insert([
                'title'      => $e['title'],
                'location'   => $e['location'],
                'starts_at'  => $e['starts_at'],
                'tag'        => $e['tag'],
                'status'     => 'published',
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        $posts = [
            ['title' => 'Why we are teaching both Cambridge and ZIMSEC', 'slug' => 'why-cambridge-and-zimsec', 'category' => 'Curriculum', 'excerpt' => 'The pathway a child sits should follow their plan, not the school’s marketing.', 'body' => '<p>The pathway a child sits should follow their plan, not the school’s marketing. From Form 3, pupils at Mazowe Heights choose the examination pathway that fits their university plans — the school resources both without preference.</p>', 'published_at' => '2026-09-02 08:00:00'],
            ['title' => 'The science block, eleven months in', 'slug' => 'science-block-eleven-months-in', 'category' => 'Building', 'excerpt' => 'Eight laboratories, a prep room and the first fume cupboards installed.', 'body' => '<p>Eight laboratories, a prep room and the first fume cupboards installed. Construction remains on schedule for a January 2027 opening.</p>', 'published_at' => '2026-08-19 08:00:00'],
            ['title' => 'What our house parents are reading this term', 'slug' => 'house-parents-reading-this-term', 'category' => 'Boarding', 'excerpt' => 'Preparing adults to look after other people’s children, deliberately.', 'body' => '<p>Preparing adults to look after other people’s children, deliberately — a look inside the house-parent training programme ahead of January 2027.</p>', 'published_at' => '2026-08-04 08:00:00'],
        ];
        foreach ($posts as $p) {
            $this->db->table('posts')->insert([
                'title'        => $p['title'],
                'slug'         => $p['slug'],
                'category'     => $p['category'],
                'excerpt'      => $p['excerpt'],
                'body'         => $p['body'],
                'status'       => 'published',
                'published_at' => $p['published_at'],
                'created_at'   => $now,
                'updated_at'   => $now,
            ]);
        }

        $vacancies = [
            ['title' => 'Head of Mathematics', 'department' => 'Mathematics'],
            ['title' => 'Chemistry Teacher', 'department' => 'Sciences'],
            ['title' => 'Boarding House Parent (Nyanga House)', 'department' => 'Boarding'],
            ['title' => 'School Nurse', 'department' => 'Health & Wellbeing'],
            ['title' => 'Librarian', 'department' => 'Library'],
        ];
        foreach ($vacancies as $v) {
            $this->db->table('vacancies')->insert([
                'title'        => $v['title'],
                'department'   => $v['department'],
                'description'  => 'Founding-staff post for the January 2027 opening. See the full job description or apply via info@mazoweheights.ac.zw.',
                'status'       => 'open',
                'created_at'   => $now,
                'updated_at'   => $now,
            ]);
        }
    }
}
