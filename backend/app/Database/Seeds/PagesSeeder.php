<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class PagesSeeder extends Seeder
{
    private function b(string $type, array $data): array
    {
        return ['type' => $type, 'data' => $data];
    }

    public function run()
    {
        $b = fn ($type, $data) => $this->b($type, $data);

        $pages = [

            'home' => [
                'nav_group' => null, 'title' => 'Home', 'kicker' => '', 'lead' => '',
                'meta' => 'A co-educational boarding and day school for Form 1 to A Level, teaching Cambridge and ZIMSEC on one campus in the Mazowe Valley.',
                'blocks' => [
                    $b('hero', [
                        'kicker' => 'Founding intake · January 2027',
                        'title'  => 'A school built for the Zimbabwe that is coming.',
                        'lead'   => 'Mazowe Heights College opens in January 2027 as a co-educational boarding and day school for Form 1 to A Level, teaching both the Cambridge and ZIMSEC curricula on one campus in the Mazowe Valley.',
                        'buttons' => [['label' => 'Start an application', 'href' => '/admissions/apply'], ['label' => 'Take the virtual tour', 'href' => '/about/virtual-tour']],
                    ]),
                    $b('stats', ['items' => [
                        ['n' => '420', 'l' => 'Pupils in the founding intake, Form 1 to Lower Sixth'],
                        ['n' => '1:12', 'l' => 'Teacher to pupil ratio, capped in every class'],
                        ['n' => '480', 'l' => 'Boarding beds across four houses'],
                        ['n' => '140ha', 'l' => 'Of the Mazowe Valley, with eleven playing surfaces'],
                    ]]),
                    $b('cards', ['title' => 'Two curricula, one standard', 'items' => [
                        ['title' => 'Cambridge & ZIMSEC', 'body' => 'Both pathways from Form 3, both taught by specialists, both with full laboratory and library provision.', 'cta' => 'See the pathways', 'href' => '/academics/overview'],
                        ['title' => 'A week with shape', 'body' => 'Structured prep, compulsory sport, Sunday chapel or reflection, and an exeat every half term.', 'cta' => 'Boarding life', 'href' => '/boarding/boarding-life'],
                        ['title' => 'Thirty-one societies', 'body' => 'Robotics, debate, mbira, agriculture, entrepreneurship and a school newspaper that we do not edit.', 'cta' => 'Clubs & societies', 'href' => '/school-life/clubs'],
                    ]]),
                    $b('events', ['title' => "What's on", 'items' => [
                        ['date' => 'Sat 10 Oct 2026', 'title' => 'Founding Open Day', 'where' => 'Main Quadrangle · 09:00', 'tag' => 'Booking open'],
                        ['date' => 'Fri 23 Oct 2026', 'title' => 'Entrance Assessment — Round 2', 'where' => 'Assessment Hall · 08:00', 'tag' => 'Applicants only'],
                        ['date' => 'Thu 5 Nov 2026', 'title' => 'Parent Information Evening', 'where' => 'Online · 18:00', 'tag' => 'Free'],
                        ['date' => 'Sat 21 Nov 2026', 'title' => 'Valley Run & Family Picnic', 'where' => 'Top Field · 07:30', 'tag' => 'Tickets $5'],
                    ]]),
                    $b('cta', ['title' => 'Pay in EcoCash, ZIPIT, InnBucks, card or USD — and see the receipt instantly.', 'body' => 'Termly invoices, sibling discounts, instalment plans and a live statement for every family. Diaspora parents can settle in USD through PayPal.', 'buttons' => [['label' => 'Pay school fees', 'href' => '/portals/pay-fees'], ['label' => 'Fee schedule 2027', 'href' => '/admissions/fees']]]),
                    $b('posts', ['title' => 'From the blog', 'items' => [
                        ['cat' => 'Curriculum', 'date' => '2 Sep 2026', 'title' => 'Why we are teaching both Cambridge and ZIMSEC', 'dek' => 'The pathway a child sits should follow their plan, not the school’s marketing.'],
                        ['cat' => 'Building', 'date' => '19 Aug 2026', 'title' => 'The science block, eleven months in', 'dek' => 'Eight laboratories, a prep room and the first fume cupboards installed.'],
                        ['cat' => 'Boarding', 'date' => '4 Aug 2026', 'title' => 'What our house parents are reading this term', 'dek' => 'Preparing adults to look after other people’s children, deliberately.'],
                    ]]),
                    $b('boarding_promo', [
                        'kicker' => 'Boarding',
                        'title'  => 'Four houses. One valley. A full life after the last bell.',
                        'body'   => 'Nyanga, Zambezi, Matopo and Chimanimani house 480 boarders in small dormitory bays with resident house parents, a matron on site and a tutor for every twelve pupils. Prep is structured, weekends are not wasted, and the kitchen publishes its menu a week ahead.',
                        'buttons' => [['label' => 'Boarding life', 'href' => '/boarding/boarding-life'], ['label' => "This week's menu", 'href' => '/boarding/dining']],
                    ]),
                ],
            ],

            // ---------------- ABOUT ----------------
            'about/our-story' => [
                'nav_group' => 'about', 'title' => 'Our Story', 'kicker' => 'About',
                'lead' => 'A school conceived in 2023, built on 140 hectares of the Mazowe Valley, and opening its gates to a founding intake of 420 pupils in January 2027.',
                'meta' => 'The story of Mazowe Heights College, from founding to opening day.',
                'blocks' => [
                    $b('richtext', ['html' => '<p>Mazowe Heights College began as a conversation among eleven Zimbabwean educators, parents and professionals in 2023, frustrated that families were forced to choose between a Cambridge education and a ZIMSEC one, between a boarding school and a day school, between excellent facilities and a fee that a middle-income Zimbabwean family could actually pay.</p><p>Construction on the 140-hectare Mazowe Valley site began in 2025, following Ministry registration. The school opens its gates in January 2027 to a founding intake of 420 pupils, Form 1 to Lower Sixth, with a steady state of 900 pupils planned by 2032.</p>']),
                    $b('timeline', ['title' => 'Milestones', 'items' => [
                        ['when' => '2023', 't' => 'Founding group of eleven convenes in Harare'],
                        ['when' => '2024', 't' => '140 hectares acquired in the Mazowe Valley'],
                        ['when' => '2025', 't' => 'Ministry registration granted; construction begins'],
                        ['when' => '2026', 't' => 'Cambridge International centre approval; staff appointed'],
                        ['when' => '2027', 't' => 'Gates open — 420 pupils, Form 1 to Lower Sixth'],
                        ['when' => '2032', 't' => 'Planned steady state of 900 pupils'],
                    ]]),
                ],
            ],
            'about/head-welcome' => [
                'nav_group' => 'about', 'title' => "Head's Welcome", 'kicker' => 'About',
                'lead' => 'Dr. Tendai Mukombachoto on what a new school owes the families who trust it first.',
                'meta' => "A welcome message from the Head of College.",
                'blocks' => [
                    $b('richtext', ['html' => '<p>Every founding family is taking a chance on us before there is a single set of examination results to point to. That is not lost on any member of staff here. What we owe you in return is small classes that stay small, teachers who are specialists rather than generalists, and an honest account of how your child is doing — good news and bad.</p><p>I have spent twenty-two years in Zimbabwean and South African schools, and I have never had the chance to build one from its foundations. We intend to get the fundamentals right from day one rather than retrofit them in year five.</p><p>— Dr. Tendai Mukombachoto, Head of College</p>']),
                ],
            ],
            'about/values' => [
                'nav_group' => 'about', 'title' => 'Vision, Mission & Values', 'kicker' => 'About',
                'lead' => 'Five commitments we will be measured against, published so that you can hold us to them.',
                'meta' => 'The values Mazowe Heights College holds itself to.',
                'blocks' => [
                    $b('numbered_list', ['items' => [
                        ['n' => '01', 't' => 'Small classes, kept small', 'b' => 'Twenty-four pupils is a hard cap, not a target. If a year group over-subscribes we open another section or we hold a waiting list. We do not add desks.'],
                        ['n' => '02', 't' => 'Two pathways, no second class', 'b' => 'The ZIMSEC set and the Cambridge set share the same laboratories, the same teachers and the same timetable priority. Neither is the school’s flagship.'],
                        ['n' => '03', 't' => 'Published fees, no surprise levies', 'b' => 'One termly figure covering tuition, boarding, meals, laundry, prep, house activities and internal examinations. Trips and external examination entries are quoted separately and in advance.'],
                        ['n' => '04', 't' => 'Care that has a name attached', 'b' => 'Every pupil has a tutor, a house parent and a named safeguarding lead, and every parent has their direct contact details from day one.'],
                        ['n' => '05', 't' => 'We report honestly', 'b' => 'Results, incidents, inspection findings and audited accounts are published to parents whether or not they flatter us.'],
                    ]]),
                ],
            ],
            'about/leadership' => [
                'nav_group' => 'about', 'title' => 'Leadership & Governance', 'kicker' => 'About',
                'lead' => 'The senior team, the board of trustees, and how decisions are made and disclosed.',
                'meta' => 'The senior leadership team and governance structure.',
                'blocks' => [
                    $b('cards', ['title' => 'Senior team', 'items' => [
                        ['title' => 'Dr. Tendai Mukombachoto', 'body' => 'Head of College — Twenty-two years in Zimbabwean and South African schools; previously Deputy Head at a Harare independent school.'],
                        ['title' => 'Mrs. Nyasha Chirwa', 'body' => 'Deputy Head · Academic — Cambridge examiner in Mathematics; led the IGCSE conversion of two Zimbabwean schools.'],
                        ['title' => 'Mr. Simbarashe Ndlovu', 'body' => 'Deputy Head · Pastoral — Former Housemaster and national schools rugby coach; safeguarding lead.'],
                        ['title' => 'Ms. Rutendo Mapfumo', 'body' => 'Director of Boarding — Fifteen years of boarding leadership; designed the house-parent training programme.'],
                        ['title' => 'Mr. Farai Gwatidzo', 'body' => 'Bursar — Chartered accountant; responsible for fees, payroll, procurement and the published accounts.'],
                        ['title' => 'Dr. Anesu Kadzere', 'body' => 'Director of Studies · Sixth Form — University guidance across UZ, NUST, South African and UK admissions.'],
                    ]]),
                    $b('list', ['title' => 'Governance documents', 'items' => ['Trust deed and constitution (PDF)', 'Safeguarding policy 2026 (PDF)', 'Admissions policy (PDF)', 'Fees policy and refund terms (PDF)', 'Anti-bullying policy (PDF)', 'Complaints procedure (PDF)']]),
                ],
            ],
            'about/campus' => [
                'nav_group' => 'about', 'title' => 'Campus & Facilities', 'kicker' => 'About',
                'lead' => 'Twenty-eight teaching spaces, eight laboratories, a 400-seat hall, four boarding houses and a valley to run in.',
                'meta' => 'A tour of the Mazowe Heights campus and facilities.',
                'blocks' => [
                    $b('cards', ['title' => 'Facilities', 'items' => [
                        ['title' => '28 Teaching rooms', 'body' => 'All capped at 24 places, all with projection and reliable power.'],
                        ['title' => '08 Laboratories', 'body' => 'Three biology, three chemistry, two physics, plus a shared prep room.'],
                        ['title' => '04 Boarding houses', 'body' => '120 beds each, small dormitory bays, resident house parents.'],
                        ['title' => '400-seat assembly hall', 'body' => 'Also the theatre, examination hall and concert venue.'],
                        ['title' => '22k Library volumes', 'body' => 'Two floors, a silent study level and a digital lending desk.'],
                        ['title' => '11 Playing surfaces', 'body' => 'Rugby, football, hockey, cricket ovals, netball and eight tennis courts.'],
                        ['title' => '25m Swimming pool', 'body' => 'Six lanes, solar heated, used for galas and lifesaving.'],
                        ['title' => '12ha School farm', 'body' => 'Maize, horticulture and a poultry unit run with the agriculture department.'],
                    ]]),
                ],
            ],
            'about/virtual-tour' => [
                'nav_group' => 'about', 'title' => 'Virtual Campus Tour', 'kicker' => 'About',
                'lead' => 'Walk the site before you visit it. Twelve stops, from the gate to the top field.',
                'meta' => 'A virtual tour of the campus, stop by stop.',
                'blocks' => [
                    $b('tour', ['items' => [
                        ['t' => 'The Gate and Avenue', 'b' => 'A 600-metre jacaranda avenue from the Chiweshe Road gate to the quadrangle. Security is manned around the clock and every arrival is logged against the parent record.'],
                        ['t' => 'Main Quadrangle', 'b' => 'The administrative heart: reception, the Head’s study, the bursary and the parent meeting rooms open onto a colonnaded square.'],
                        ['t' => 'Teaching Blocks A & B', 'b' => 'Twenty-eight rooms across two storeys, each capped at twenty-four places, arranged by faculty rather than by year group.'],
                        ['t' => 'Science Block', 'b' => 'Eight laboratories with a shared prep room and two fume cupboards. Every practical on both syllabuses is performed, not demonstrated on a screen.'],
                        ['t' => 'The Library', 'b' => 'Twenty-two thousand volumes over two floors, with a silent study level, sixteen study carrels and the digital lending desk.'],
                        ['t' => 'Assembly Hall', 'b' => 'Four hundred seats, a sprung stage and a lighting rig. Assemblies, examinations, drama and the termly concert all live here.'],
                        ['t' => 'Dining Hall', 'b' => 'Seats 300 in two sittings. Three cooked meals a day, allergen and halaal marked, with the week’s menu on the wall and on the parent app.'],
                        ['t' => 'Nyanga & Zambezi Houses', 'b' => 'The first two boarding houses: 120 beds each in bays of six, a common room, prep room and a resident house-parent flat.'],
                        ['t' => 'Matopo & Chimanimani Houses', 'b' => 'Completing November 2026, to the same plan, on the eastern slope with the valley view.'],
                        ['t' => 'Sanatorium', 'b' => 'Six inpatient beds, a treatment room and an isolation bay. A nurse on site around the clock and a doctor on call from Bindura.'],
                        ['t' => 'Sports Precinct', 'b' => 'Eleven surfaces, a six-lane 25m pool, a gymnasium and the athletics oval on the lower terrace.'],
                        ['t' => 'Top Field and the Valley', 'b' => 'The cross-country start, the school farm beyond it, and the reason we built here rather than in a suburb.'],
                    ]]),
                ],
            ],

            // ---------------- ACADEMICS ----------------
            'academics/overview' => [
                'nav_group' => 'academics', 'title' => 'Academic Overview', 'kicker' => 'Academics',
                'lead' => 'Small classes, specialist teachers, and two examination pathways resourced without preference.',
                'meta' => 'An overview of academics at Mazowe Heights College.',
                'blocks' => [
                    $b('cards', ['title' => 'Choosing a pathway', 'items' => [
                        ['title' => 'Planning a Zimbabwean university?', 'body' => 'ZIMSEC is the direct route and is well understood by every local admissions office. Cambridge is accepted but adds an equivalence step.'],
                        ['title' => 'Planning South Africa or the UK?', 'body' => 'Cambridge IGCSE and A Level map straight onto those systems. ZIMSEC A Level is accepted at most South African universities but needs a SAQA evaluation.'],
                        ['title' => 'Not sure yet?', 'body' => 'Choose at the end of Form 2 and review it every year. Roughly one pupil in six changes pathway and the option blocks are built so that it costs them nothing.'],
                        ['title' => 'Does it affect the fee?', 'body' => 'No. Tuition is identical. Only the external examination entry fees differ, and those are charged at cost.'],
                    ]]),
                ],
            ],
            'academics/cambridge' => [
                'nav_group' => 'academics', 'title' => 'Cambridge Pathway', 'kicker' => 'Academics',
                'lead' => 'IGCSE from Form 3 and Cambridge International AS & A Level in the Sixth Form.',
                'meta' => 'The Cambridge International curriculum pathway.',
                'blocks' => [
                    $b('list', ['title' => 'IGCSE subjects', 'items' => ['English Language', 'English Literature', 'Shona', 'Ndebele', 'Mathematics', 'Additional Mathematics', 'Biology', 'Chemistry', 'Physics', 'Combined Science', 'History', 'Geography', 'Computer Science', 'Business Studies', 'Economics', 'Accounting', 'Art & Design', 'Music', 'Design & Technology', 'Agriculture', 'Physical Education']]),
                    $b('table', ['title' => 'Examination fees (per subject)', 'rows' => [
                        ['label' => 'IGCSE, per subject', 'value' => 'US$ 68'],
                        ['label' => 'AS Level, per subject', 'value' => 'US$ 92'],
                        ['label' => 'A Level, per subject', 'value' => 'US$ 114'],
                        ['label' => 'Late entry surcharge', 'value' => 'US$ 40'],
                    ]]),
                ],
            ],
            'academics/zimsec' => [
                'nav_group' => 'academics', 'title' => 'ZIMSEC Pathway', 'kicker' => 'Academics',
                'lead' => 'Ordinary Level and Advanced Level, taught to the same standard and in the same rooms.',
                'meta' => 'The ZIMSEC curriculum pathway.',
                'blocks' => [
                    $b('list', ['title' => 'O Level subjects', 'items' => ['English Language', 'Literature in English', 'Shona', 'Ndebele', 'Mathematics', 'Biology', 'Chemistry', 'Physics', 'Combined Science', 'History', 'Geography', 'Heritage Studies', 'Computer Science', 'Commerce', 'Principles of Accounts', 'Agriculture', 'Art', 'Music', 'Food & Nutrition', 'Building Studies', 'Physical Education']]),
                    $b('list', ['title' => 'A Level subjects', 'items' => ['Mathematics', 'Further Mathematics', 'Physics', 'Chemistry', 'Biology', 'Computer Science', 'Economics', 'Business Studies', 'Accounting', 'History', 'Geography', 'Literature in English', 'Shona', 'Divinity', 'Art & Design', 'Sociology']]),
                ],
            ],
            'academics/lower-school' => [
                'nav_group' => 'academics', 'title' => 'Lower School · Form 1–4', 'kicker' => 'Academics',
                'lead' => 'A broad common curriculum in Forms 1 and 2, then option blocks that keep doors open.',
                'meta' => 'The Lower School curriculum, Form 1 to Form 4.',
                'blocks' => [
                    $b('cards', ['title' => 'Option blocks (from Form 3)', 'items' => [
                        ['title' => 'Block A · Sciences', 'body' => 'Biology, Chemistry, Physics, Combined Science, Agriculture'],
                        ['title' => 'Block B · Humanities', 'body' => 'History, Geography, Heritage Studies, Divinity, Sociology'],
                        ['title' => 'Block C · Commerce & computing', 'body' => 'Business Studies, Economics, Accounting, Computer Science, Commerce'],
                        ['title' => 'Block D · Creative & practical', 'body' => 'Art & Design, Music, Design & Technology, Food & Nutrition, Physical Education'],
                    ]]),
                ],
            ],
            'academics/sixth-form' => [
                'nav_group' => 'academics', 'title' => 'Sixth Form · AS & A Level', 'kicker' => 'Academics',
                'lead' => 'Sixteen subjects, a tutor for every eight pupils, and a university plan from day one.',
                'meta' => 'The Sixth Form programme, AS and A Level.',
                'blocks' => [
                    $b('richtext', ['html' => '<p>The Sixth Form combines Cambridge A Level and ZIMSEC A Level pupils in shared facilities, with a personal tutor for every eight pupils and a university plan that starts on arrival, not in the final term.</p>']),
                ],
            ],
            'academics/departments' => [
                'nav_group' => 'academics', 'title' => 'Subject Departments', 'kicker' => 'Academics',
                'lead' => 'Twelve departments, each with its own laboratory, studio or workshop.',
                'meta' => 'The twelve subject departments and their heads.',
                'blocks' => [
                    $b('cards', ['title' => 'Departments', 'items' => [
                        ['title' => 'Mathematics — Mrs. N. Chirwa (6 staff)', 'body' => 'Setted from Form 1, with Additional Mathematics from Form 3 and Further Mathematics in the Sixth Form.'],
                        ['title' => 'English — Mr. P. Sibanda (6 staff)', 'body' => 'Language and Literature taught separately from Form 3; the school newspaper and debating society sit here.'],
                        ['title' => 'Sciences — Dr. L. Moyo (9 staff)', 'body' => 'Eight laboratories and a technician. Every syllabus practical is performed rather than demonstrated.'],
                        ['title' => 'Humanities — Mr. T. Chigumba (5 staff)', 'body' => 'History, Geography and Heritage Studies, with fieldwork in the Mazowe catchment and at Great Zimbabwe.'],
                        ['title' => 'Languages — Mrs. R. Dube (4 staff)', 'body' => 'Shona and Ndebele to A Level, plus beginner French in the Sixth Form as an enrichment option.'],
                        ['title' => 'Commerce — Mr. K. Marufu (4 staff)', 'body' => 'Business Studies, Economics and Accounting, with the entrepreneurship society running a real trading company each year.'],
                        ['title' => 'Computer Science — Ms. C. Nyoni (3 staff)', 'body' => 'Two laboratories of 24 machines, a robotics workshop and a Form 1 computational-thinking course for everyone.'],
                        ['title' => 'Creative Arts — Mr. B. Zvobgo (4 staff)', 'body' => 'Studio art, printmaking and ceramics; the gallery on the quadrangle changes exhibition each term.'],
                        ['title' => 'Music — Mrs. S. Chikwava (3 staff)', 'body' => 'Choir, marimba and mbira ensembles, a concert band and eight practice rooms. Individual lessons are charged separately.'],
                        ['title' => 'Design & Technology — Mr. E. Tapera (3 staff)', 'body' => 'A full workshop with machine tools, plus CAD and a 3D-printing bay shared with robotics.'],
                        ['title' => 'Physical Education — Mr. S. Ndlovu (5 staff)', 'body' => 'Curricular PE for all year groups and the coaching backbone of the fourteen school sports.'],
                        ['title' => 'Agriculture — Mrs. G. Hove (3 staff)', 'body' => 'Twelve hectares of working farm — maize, horticulture and poultry — used for teaching and for the dining hall.'],
                    ]]),
                ],
            ],
            'academics/careers-guidance' => [
                'nav_group' => 'academics', 'title' => 'Careers & University Guidance', 'kicker' => 'Academics',
                'lead' => 'UZ, NUST, South Africa, the UK, or a trade. We plan the route from Form 3.',
                'meta' => 'University and careers guidance timeline.',
                'blocks' => [
                    $b('timeline', ['title' => 'Guidance timeline', 'items' => [
                        ['when' => 'Form 3', 't' => 'First career conversation; option choices mapped against three possible destinations.'],
                        ['when' => 'Form 4', 't' => 'Aptitude and interest profiling; A Level subject planning with parents present.'],
                        ['when' => 'Lower Sixth', 't' => 'University shortlisting, entrance test registration, and the first draft of a personal statement.'],
                        ['when' => 'Upper Sixth', 't' => 'Applications submitted, interview practice, scholarship and funding applications.'],
                        ['when' => 'After results', 't' => 'Placement support, appeals, clearing and gap-year planning — the office stays open to leavers for two years.'],
                    ]]),
                ],
            ],

            // ---------------- ADMISSIONS ----------------
            'admissions/how-to-apply' => [
                'nav_group' => 'admissions', 'title' => 'How to Apply', 'kicker' => 'Admissions',
                'lead' => 'Five steps, one online form, and a decision within fourteen days of assessment.',
                'meta' => 'How to apply to Mazowe Heights College.',
                'blocks' => [
                    $b('steps', ['items' => [
                        ['n' => '01', 't' => 'Enquire', 'b' => 'Register interest online or come to an open day. No obligation and no fee.'],
                        ['n' => '02', 't' => 'Apply', 'b' => 'Complete the online form, upload a report and a birth certificate, choose day or boarding.'],
                        ['n' => '03', 't' => 'Assess', 'b' => 'A Saturday morning entrance assessment, sat on campus or remotely under invigilation.'],
                        ['n' => '04', 't' => 'Decide', 'b' => 'An offer, a waiting-list place or a decline, with feedback, within fourteen days.'],
                        ['n' => '05', 't' => 'Enrol', 'b' => 'Accept with a deposit, complete the medical and boarding forms, collect uniform.'],
                    ]]),
                    $b('timeline', ['title' => 'Key dates', 'items' => [
                        ['when' => '10 Oct 2026', 't' => 'Founding Open Day'],
                        ['when' => '23 Oct 2026', 't' => 'Entrance assessment, round 2'],
                        ['when' => '6 Nov 2026', 't' => 'Round 2 decisions released'],
                        ['when' => '27 Nov 2026', 't' => 'Final assessment round'],
                        ['when' => '11 Dec 2026', 't' => 'Deposits due; places confirmed'],
                        ['when' => '12 Jan 2027', 't' => 'Boarders arrive'],
                    ]]),
                    $b('cta', ['title' => 'Ready to apply?', 'body' => 'Start the online application — you can save and come back to it.', 'buttons' => [['label' => 'Start an application', 'href' => '/admissions/apply']]]),
                ],
            ],
            'admissions/apply' => [
                'nav_group' => 'admissions', 'title' => 'Online Application', 'kicker' => 'Admissions',
                'lead' => 'Start, save and submit your application. No paperwork, no queue at the gate.',
                'meta' => 'Apply online to Mazowe Heights College.',
                'blocks' => [
                    $b('application_form', ['note' => 'Nothing is charged at this stage. You will receive a reference within a minute of submitting.']),
                ],
            ],
            'admissions/tracker' => [
                'nav_group' => 'admissions', 'title' => 'Application Tracker', 'kicker' => 'Admissions',
                'lead' => 'Enter your reference to see exactly where your application stands.',
                'meta' => 'Track the status of a submitted application.',
                'blocks' => [
                    $b('tracker_preview', ['stages' => [
                        ['t' => 'Application received', 'when' => '14 September 2026, 21:04', 'b' => 'Reference issued and confirmation emailed.'],
                        ['t' => 'Documents verified', 'when' => '16 September 2026', 'b' => 'Grade 7 statement, birth certificate and passport photograph accepted.'],
                        ['t' => 'Assessment scheduled', 'when' => '23 October 2026, 08:00', 'b' => 'Assessment Hall, main campus. Bring the reference and a pen. Allow two and a half hours.'],
                        ['t' => 'Panel decision', 'when' => 'Expected by 6 November 2026', 'b' => 'Released to the portal and by SMS to the registered parent number.'],
                    ]]),
                ],
            ],
            'admissions/fees' => [
                'nav_group' => 'admissions', 'title' => 'Fees & Payment Plans', 'kicker' => 'Admissions',
                'lead' => 'Published in full for 2027, with sibling discounts, instalments and every payment method we accept.',
                'meta' => 'Fees and payment plans for 2027.',
                'blocks' => [
                    $b('table', ['title' => 'Cambridge examination fees', 'rows' => [
                        ['label' => 'IGCSE, per subject', 'value' => 'US$ 68'],
                        ['label' => 'AS Level, per subject', 'value' => 'US$ 92'],
                        ['label' => 'A Level, per subject', 'value' => 'US$ 114'],
                        ['label' => 'Late entry surcharge', 'value' => 'US$ 40'],
                    ]]),
                    $b('cta', ['title' => 'Pay a fee now', 'body' => 'Settle an invoice in EcoCash, ZIPIT, InnBucks, card, PayPal or cash at the bursary.', 'buttons' => [['label' => 'Pay school fees', 'href' => '/portals/pay-fees']]]),
                ],
            ],
            'admissions/scholarships' => [
                'nav_group' => 'admissions', 'title' => 'Scholarships & Bursaries', 'kicker' => 'Admissions',
                'lead' => 'Twenty-five percent of our founding intake will be on some form of award.',
                'meta' => 'Scholarships and bursaries available.',
                'blocks' => [
                    $b('richtext', ['html' => '<p>Means-tested bursaries, academic scholarships and sport/arts awards are available from the founding intake. Applying for a scholarship does not affect the admissions decision — indicate interest on the application form and the panel will follow up separately.</p>']),
                ],
            ],
            'admissions/term-dates' => [
                'nav_group' => 'admissions', 'title' => 'Term Dates & School Calendar', 'kicker' => 'Admissions',
                'lead' => 'Three terms, published two years ahead so families can plan travel.',
                'meta' => 'Term dates for the school calendar.',
                'blocks' => [
                    $b('table', ['title' => '2027 term dates', 'rows' => [
                        ['label' => 'Term 1', 'value' => '12 Jan – 26 Mar 2027'],
                        ['label' => 'Term 2', 'value' => '27 Apr – 9 Jul 2027'],
                        ['label' => 'Term 3', 'value' => '31 Aug – 12 Nov 2027'],
                    ]]),
                ],
            ],
            'admissions/uniform' => [
                'nav_group' => 'admissions', 'title' => 'Uniform & Kit List', 'kicker' => 'Admissions',
                'lead' => 'What to buy, where to buy it, and what the school issues.',
                'meta' => 'Uniform and kit list for new pupils.',
                'blocks' => [
                    $b('list', ['title' => 'Day uniform', 'items' => ['Blazer and tie (school colours)', 'White shirt/blouse', 'Grey trousers or skirt', 'Black shoes', 'PE kit (issued at enrolment)']]),
                    $b('list', ['title' => 'Boarding kit', 'items' => ['Bedding and mosquito net', 'Named laundry bag', 'Toiletries and towels', 'Tuck box']]),
                ],
            ],

            // ---------------- BOARDING ----------------
            'boarding/boarding-life' => [
                'nav_group' => 'boarding', 'title' => 'Boarding Life', 'kicker' => 'Boarding',
                'lead' => 'Four houses, 480 beds, resident house parents and a week that has shape.',
                'meta' => 'Boarding life at Mazowe Heights College.',
                'blocks' => [
                    $b('richtext', ['html' => '<p>Boarders live in small dormitory bays of six, with a resident house parent, a matron on site and a tutor for every twelve pupils. Weekday evenings run structured prep; weekends mix sport, house activities, an exeat every half term and Sunday chapel or reflection.</p>']),
                ],
            ],
            'boarding/houses' => [
                'nav_group' => 'boarding', 'title' => 'The Four Houses', 'kicker' => 'Boarding',
                'lead' => 'Nyanga, Zambezi, Matopo and Chimanimani — named for the country they serve.',
                'meta' => 'The four boarding houses.',
                'blocks' => [
                    $b('cards', ['items' => [
                        ['title' => 'Nyanga House', 'body' => '120 beds, opens January 2027, house colour green.'],
                        ['title' => 'Zambezi House', 'body' => '120 beds, opens January 2027, house colour blue.'],
                        ['title' => 'Matopo House', 'body' => '120 beds, completing November 2026, house colour gold.'],
                        ['title' => 'Chimanimani House', 'body' => '120 beds, completing November 2026, house colour red.'],
                    ]]),
                ],
            ],
            'boarding/dining' => [
                'nav_group' => 'boarding', 'title' => 'Dining & Weekly Menu', 'kicker' => 'Boarding',
                'lead' => 'Three cooked meals a day, published a week ahead, with allergen and halaal marking.',
                'meta' => 'The weekly dining menu.',
                'blocks' => [
                    $b('menu_week', ['days' => [
                        ['name' => 'Monday', 'date' => '19 Oct', 'breakfast' => 'Bota with peanut butter, scrambled eggs, brown bread', 'lunch' => 'Sadza, beef stew, sugar beans, muriwo ne dovi', 'supper' => 'Rice, roast chicken, mixed vegetables, custard and jelly'],
                        ['name' => 'Tuesday', 'date' => '20 Oct', 'breakfast' => 'Maize porridge, boiled eggs, peanut butter bread', 'lunch' => 'Sadza, kapenta with tomato, covo', 'supper' => 'Macaroni cheese, beef mince, green salad, yoghurt'],
                        ['name' => 'Wednesday', 'date' => '21 Oct', 'breakfast' => 'Bota with milk, fried eggs and tomato, toast', 'lunch' => 'Rice and sadza, chicken stew, butternut, coleslaw', 'supper' => 'Samp and beans, boerewors, steamed cabbage, fruit salad'],
                        ['name' => 'Thursday', 'date' => '22 Oct', 'breakfast' => 'Maize porridge with sugar, sausages, bread', 'lunch' => 'Sadza, beef and vegetable stew, pumpkin leaves', 'supper' => 'Spaghetti bolognese, lentil sauce, garden salad, ice cream'],
                        ['name' => 'Friday', 'date' => '23 Oct', 'breakfast' => 'Bota with peanut butter, scrambled eggs, bread', 'lunch' => 'Rice, fried bream, chomolia, potato salad', 'supper' => 'Chips and grilled chicken, vegetable burger, salad bar, doughnuts'],
                        ['name' => 'Saturday', 'date' => '24 Oct', 'breakfast' => 'Cooked breakfast: eggs, beans, toast, porridge', 'lunch' => 'Braai: beef and chicken, sadza and rice, salads', 'supper' => 'Soup and rolls, chicken pie, mixed vegetables, fruit crumble'],
                        ['name' => 'Sunday', 'date' => '25 Oct', 'breakfast' => 'Pancakes and syrup, boiled eggs, bread', 'lunch' => 'Sunday roast: beef or chicken, roast potatoes and sadza, three vegetables, trifle', 'supper' => 'Toasted sandwiches, soup, fruit and yoghurt'],
                    ]]),
                ],
            ],
            'boarding/wellbeing' => [
                'nav_group' => 'boarding', 'title' => 'Health & Wellbeing', 'kicker' => 'Boarding',
                'lead' => 'A sanatorium staffed around the clock, two counsellors, and a doctor on call.',
                'meta' => 'Health and wellbeing services on campus.',
                'blocks' => [
                    $b('richtext', ['html' => '<p>The sanatorium has six inpatient beds, a treatment room and an isolation bay, staffed by a nurse around the clock with a doctor on call from Bindura. Two school counsellors are available to every pupil, boarder or day, by self-referral or staff referral.</p>']),
                ],
            ],
            'boarding/pastoral-care' => [
                'nav_group' => 'boarding', 'title' => 'Pastoral Care & Safeguarding', 'kicker' => 'Boarding',
                'lead' => 'Every pupil has a tutor, a house parent and a named safeguarding lead.',
                'meta' => 'Pastoral care and safeguarding policy summary.',
                'blocks' => [
                    $b('richtext', ['html' => '<p>Safeguarding at Mazowe Heights College is led by the Deputy Head · Pastoral, a designated safeguarding lead trained to the standard required by the Ministry. Every concern is logged, escalated and followed up; the full safeguarding policy is available from the Leadership & Governance page.</p>']),
                ],
            ],
            'boarding/transport' => [
                'nav_group' => 'boarding', 'title' => 'Bus & Transport Tracking', 'kicker' => 'Boarding',
                'lead' => 'Nine routes across Harare, Bindura and Chinhoyi, tracked live for parents.',
                'meta' => 'School transport routes.',
                'blocks' => [
                    $b('list', ['title' => 'Routes', 'items' => ['Harare North', 'Harare South', 'Harare CBD', 'Borrowdale', 'Bindura Town', 'Bindura Rural', 'Chinhoyi', 'Mazowe Local', 'Glendale']]),
                    $b('richtext', ['html' => '<p>Live route tracking for parents is part of the mobile app and requires enrolment — this page will show a live map once the transport module is switched on.</p>']),
                ],
            ],

            // ---------------- SCHOOL LIFE ----------------
            'school-life/sport' => [
                'nav_group' => 'life', 'title' => 'Sport', 'kicker' => 'School Life',
                'lead' => 'Fourteen sports, two full seasons and a policy that everyone plays something.',
                'meta' => 'Sport at Mazowe Heights College.',
                'blocks' => [
                    $b('list', ['title' => 'Sports on offer', 'items' => ['Rugby', 'Football', 'Hockey', 'Cricket', 'Netball', 'Tennis', 'Swimming', 'Athletics', 'Basketball', 'Volleyball', 'Cross-country', 'Squash', 'Table tennis', 'Chess']]),
                ],
            ],
            'school-life/fixtures' => [
                'nav_group' => 'life', 'title' => 'Fixtures & Results', 'kicker' => 'School Life',
                'lead' => 'Every team, every weekend, updated from the touchline.',
                'meta' => 'Sports fixtures and results.',
                'blocks' => [
                    $b('richtext', ['html' => '<p>Fixtures and results will populate here once the founding intake’s first season begins in 2027.</p>']),
                ],
            ],
            'school-life/clubs' => [
                'nav_group' => 'life', 'title' => 'Clubs & Societies', 'kicker' => 'School Life',
                'lead' => 'Thirty-one societies, all pupil-proposed, all pupil-led.',
                'meta' => 'Clubs and societies at Mazowe Heights.',
                'blocks' => [
                    $b('list', ['title' => 'A sample of societies', 'items' => ['Robotics', 'Debate', 'Mbira ensemble', 'Agriculture club', 'Entrepreneurship society', 'School newspaper', 'Chess club', 'Drama society', 'Environmental club', 'Coding club']]),
                ],
            ],
            'school-life/arts' => [
                'nav_group' => 'life', 'title' => 'Arts & Culture', 'kicker' => 'School Life',
                'lead' => 'Music, drama, mbira, choir, design and a gallery that changes each term.',
                'meta' => 'Arts and culture programme.',
                'blocks' => [
                    $b('richtext', ['html' => '<p>The creative arts programme spans studio art, printmaking, ceramics, choir, marimba and mbira ensembles, a concert band and a termly drama production. The student gallery on the main quadrangle changes exhibition every term.</p>']),
                ],
            ],
            'school-life/events' => [
                'nav_group' => 'life', 'title' => 'Events Calendar', 'kicker' => 'School Life',
                'lead' => 'Open days, fixtures, concerts, parent evenings and exeat weekends.',
                'meta' => 'The full events calendar.',
                'blocks' => [
                    $b('events', ['items' => [
                        ['date' => 'Sat 10 Oct 2026', 'title' => 'Founding Open Day', 'where' => 'Main Quadrangle · 09:00', 'tag' => 'Booking open'],
                        ['date' => 'Fri 23 Oct 2026', 'title' => 'Entrance Assessment — Round 2', 'where' => 'Assessment Hall · 08:00', 'tag' => 'Applicants only'],
                        ['date' => 'Thu 5 Nov 2026', 'title' => 'Parent Information Evening', 'where' => 'Online · 18:00', 'tag' => 'Free'],
                        ['date' => 'Sat 21 Nov 2026', 'title' => 'Valley Run & Family Picnic', 'where' => 'Top Field · 07:30', 'tag' => 'Tickets $5'],
                    ]]),
                ],
            ],
            'school-life/library' => [
                'nav_group' => 'life', 'title' => 'Library & Catalogue', 'kicker' => 'School Life',
                'lead' => 'Twenty-two thousand volumes, a digital lending desk and a quiet floor.',
                'meta' => 'The school library and catalogue.',
                'blocks' => [
                    $b('richtext', ['html' => '<p>The library spans two floors: a working floor with sixteen study carrels and group tables, and a silent study floor above it. The catalogue and loan system will be searchable from this page once the library module is switched on.</p>']),
                ],
            ],

            // ---------------- NEWS ----------------
            'news/blog' => [
                'nav_group' => 'news', 'title' => 'Blog & News', 'kicker' => 'News',
                'lead' => 'Writing from the staff room, the houses and the building site.',
                'meta' => 'News and blog posts from Mazowe Heights College.',
                'blocks' => [
                    $b('posts', ['items' => [
                        ['cat' => 'Curriculum', 'date' => '2 Sep 2026', 'title' => 'Why we are teaching both Cambridge and ZIMSEC', 'dek' => 'The pathway a child sits should follow their plan, not the school’s marketing.'],
                        ['cat' => 'Building', 'date' => '19 Aug 2026', 'title' => 'The science block, eleven months in', 'dek' => 'Eight laboratories, a prep room and the first fume cupboards installed.'],
                        ['cat' => 'Boarding', 'date' => '4 Aug 2026', 'title' => 'What our house parents are reading this term', 'dek' => 'Preparing adults to look after other people’s children, deliberately.'],
                    ]]),
                ],
            ],
            'news/post' => [
                'nav_group' => 'news', 'title' => 'Featured Post', 'kicker' => 'News',
                'lead' => 'From the blog.',
                'meta' => 'A featured blog post.',
                'blocks' => [
                    $b('richtext', ['html' => '<h2>Why we are teaching both Cambridge and ZIMSEC</h2><p>The pathway a child sits should follow their plan, not the school’s marketing. From Form 3, pupils at Mazowe Heights choose the examination pathway that fits their university plans — the school resources both without preference.</p>']),
                ],
            ],
            'news/notices' => [
                'nav_group' => 'news', 'title' => 'Notices to Parents', 'kicker' => 'News',
                'lead' => 'Time-sensitive announcements, newest first.',
                'meta' => 'Notices for parents.',
                'blocks' => [
                    $b('notices_feed', []),
                ],
            ],

            // ---------------- COMMUNITY ----------------
            'community/alumni' => [
                'nav_group' => 'community', 'title' => 'Alumni Network', 'kicker' => 'Community',
                'lead' => 'The Heights Association — for a founding cohort that has not yet left.',
                'meta' => 'The Mazowe Heights alumni network.',
                'blocks' => [
                    $b('richtext', ['html' => '<p>The Heights Association will formally launch once the founding cohort reaches Upper Sixth. In the meantime, register your interest to stay connected to the school’s early years.</p>']),
                ],
            ],
            'community/giving' => [
                'nav_group' => 'community', 'title' => 'Giving & Development', 'kicker' => 'Community',
                'lead' => 'The Foundation Fund: bursaries, the library and the science block.',
                'meta' => 'Giving and development at Mazowe Heights.',
                'blocks' => [
                    $b('richtext', ['html' => '<p>The Foundation Fund supports means-tested bursaries, the library collection and the completion of the science block. Gifts of any size are welcome and are acknowledged in the annual report.</p>']),
                ],
            ],
            'community/jobs' => [
                'nav_group' => 'community', 'title' => 'Work at Mazowe Heights', 'kicker' => 'Community',
                'lead' => 'We are hiring the founding staff. Twenty-two posts open.',
                'meta' => 'Vacancies at Mazowe Heights College.',
                'blocks' => [
                    $b('vacancies_feed', []),
                ],
            ],
            'community/contact' => [
                'nav_group' => 'community', 'title' => 'Contact & Visit Us', 'kicker' => 'Community',
                'lead' => 'Where we are, who to ask for, and how to book a visit.',
                'meta' => 'Contact Mazowe Heights College.',
                'blocks' => [
                    $b('cards', ['title' => 'Departments', 'items' => [
                        ['title' => 'Admissions', 'body' => 'Mrs. P. Chinamasa — admissions@mazoweheights.ac.zw · +263 242 000 001'],
                        ['title' => 'Bursary', 'body' => 'Mr. F. Gwatidzo — bursary@mazoweheights.ac.zw · +263 242 000 002'],
                        ['title' => 'Boarding', 'body' => 'Ms. R. Mapfumo — boarding@mazoweheights.ac.zw · +263 242 000 003'],
                        ['title' => 'Reception', 'body' => 'General enquiries — info@mazoweheights.ac.zw · +263 242 000 000'],
                    ]]),
                    $b('richtext', ['html' => '<p>The campus is on the Chiweshe Road, 14km north of Mazowe centre and about an hour from northern Harare. Campus visits run Tuesdays at 10:00 and Saturdays at 09:00 by appointment. Reception is open 07:30–16:30 on weekdays and 08:00–12:00 on Saturdays during term.</p>']),
                    $b('contact_form', []),
                ],
            ],

            // ---------------- PORTALS ----------------
            'portals/pay-fees' => [
                'nav_group' => null, 'title' => 'Pay School Fees', 'kicker' => 'Portals',
                'lead' => 'Settle an invoice in EcoCash, ZIPIT, InnBucks, card, PayPal or cash at the bursary.',
                'meta' => 'Ways to pay school fees.',
                'blocks' => [
                    $b('cards', ['items' => [
                        ['title' => 'EcoCash', 'body' => 'Dial *151*2*2# or use the EcoCash app. Merchant code 220147, reference your invoice number.'],
                        ['title' => 'ZIPIT / bank transfer', 'body' => 'Transfer to CBZ 021-1234567-012 or Stanbic 9140001234567, branch Bindura. Use the invoice number as reference.'],
                        ['title' => 'Card', 'body' => 'Visa or Mastercard, charged in USD via 3-D Secure.'],
                        ['title' => 'InnBucks', 'body' => 'Scan the QR code on your invoice. Settlement is instant.'],
                        ['title' => 'Cash', 'body' => 'USD notes at the bursary window, weekdays 08:00–15:30.'],
                        ['title' => 'PayPal', 'body' => 'For diaspora families paying in USD. A 3.4% processor fee applies.'],
                    ]]),
                    $b('richtext', ['html' => '<p>Real invoice lookup and payment initiation activate once a family is enrolled and a parent account exists — this page previews the payment methods available.</p>']),
                ],
            ],
            'portals/parent-portal' => [
                'nav_group' => null, 'title' => 'Parent Portal', 'kicker' => 'Portals',
                'lead' => 'A preview of what parents see once a pupil is enrolled.',
                'meta' => 'Preview of the parent portal.',
                'blocks' => [
                    $b('richtext', ['html' => '<p>Once enrolled, parents can view their child’s timetable, attendance, grades, invoices and the weekly boarding menu, and message teachers directly, from this portal and from the mobile app. Login is not yet available — sign in opens with the founding intake in January 2027.</p>']),
                ],
            ],
            'portals/results' => [
                'nav_group' => null, 'title' => 'Results & Report Cards', 'kicker' => 'Portals',
                'lead' => 'Termly reports, continuous assessment and examination results.',
                'meta' => 'Preview of results and report cards.',
                'blocks' => [
                    $b('richtext', ['html' => '<p>Termly report cards, continuous assessment marks and external examination results will be published here and in the mobile app for enrolled families. This preview shows the layout ahead of the first academic year.</p>']),
                ],
            ],
        ];

        $order = 0;
        foreach ($pages as $slug => $page) {
            $order++;
            $this->db->table('pages')->insert([
                'slug'             => $slug,
                'nav_group'        => $page['nav_group'],
                'title'            => $page['title'],
                'kicker'           => $page['kicker'],
                'lead'             => $page['lead'],
                'meta_description' => $page['meta'],
                'status'           => 'published',
                'created_at'       => date('Y-m-d H:i:s'),
                'updated_at'       => date('Y-m-d H:i:s'),
            ]);
            $pageId = $this->db->insertID();

            $pos = 0;
            foreach ($page['blocks'] as $block) {
                $this->db->table('page_blocks')->insert([
                    'page_id'    => $pageId,
                    'type'       => $block['type'],
                    'position'   => $pos++,
                    'data'       => json_encode($block['data'], JSON_UNESCAPED_SLASHES),
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
            }
        }
    }
}
