<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

// ---------------- Public website ----------------
$routes->get('/', 'Web\SiteController::home');
$routes->post('/contact/submit', 'Web\SiteController::contactSubmit');
$routes->post('/admissions/apply/submit', 'Web\SiteController::applicationSubmit');

// ---------------- Admin panel ----------------
$routes->group('admin', ['namespace' => 'App\Controllers\Admin'], static function ($routes) {
    $routes->get('login', 'AuthController::login');
    $routes->post('login', 'AuthController::attemptLogin');
    $routes->get('logout', 'AuthController::logout');

    $routes->group('', ['filter' => 'adminAuth'], static function ($routes) {
        $routes->get('/', 'DashboardController::index');
        $routes->get('dashboard', 'DashboardController::index');

        $routes->get('pages', 'PageController::index');
        $routes->get('pages/(:num)/edit', 'PageController::edit/$1');
        $routes->post('pages/(:num)/edit', 'PageController::update/$1');
        $routes->post('pages/(:num)/blocks/(:num)', 'PageController::updateBlock/$1/$2');
        $routes->post('pages/(:num)/blocks/(:num)/image', 'PageController::updateBlockImage/$1/$2');
        $routes->post('pages/(:num)/blocks/(:num)/delete', 'PageController::deleteBlock/$1/$2');
        $routes->post('pages/(:num)/blocks/add', 'PageController::addBlock/$1');
        $routes->post('pages/(:num)/blocks/reorder', 'PageController::reorderBlocks/$1');

        $routes->get('media', 'MediaController::index');
        $routes->post('media/upload', 'MediaController::upload');
        $routes->post('media/(:num)/delete', 'MediaController::delete/$1');

        $routes->get('settings', 'SettingsController::index');
        $routes->post('settings', 'SettingsController::update');

        $routes->get('enquiries', 'EnquiryController::index');
        $routes->get('enquiries/(:num)', 'EnquiryController::show/$1');
        $routes->post('enquiries/(:num)/status', 'EnquiryController::updateStatus/$1');

        $routes->get('notices', 'NoticeController::index');
        $routes->get('notices/create', 'NoticeController::create');
        $routes->post('notices/create', 'NoticeController::store');
        $routes->get('notices/(:num)/edit', 'NoticeController::edit/$1');
        $routes->post('notices/(:num)/edit', 'NoticeController::update/$1');
        $routes->post('notices/(:num)/delete', 'NoticeController::delete/$1');

        $routes->get('events', 'EventController::index');
        $routes->get('events/create', 'EventController::create');
        $routes->post('events/create', 'EventController::store');
        $routes->get('events/(:num)/edit', 'EventController::edit/$1');
        $routes->post('events/(:num)/edit', 'EventController::update/$1');
        $routes->post('events/(:num)/delete', 'EventController::delete/$1');

        $routes->get('posts', 'PostController::index');
        $routes->get('posts/create', 'PostController::create');
        $routes->post('posts/create', 'PostController::store');
        $routes->get('posts/(:num)/edit', 'PostController::edit/$1');
        $routes->post('posts/(:num)/edit', 'PostController::update/$1');
        $routes->post('posts/(:num)/delete', 'PostController::delete/$1');

        $routes->get('vacancies', 'VacancyController::index');
        $routes->get('vacancies/create', 'VacancyController::create');
        $routes->post('vacancies/create', 'VacancyController::store');
        $routes->get('vacancies/(:num)/edit', 'VacancyController::edit/$1');
        $routes->post('vacancies/(:num)/edit', 'VacancyController::update/$1');
        $routes->post('vacancies/(:num)/delete', 'VacancyController::delete/$1');

        $routes->get('users', 'UserController::index');
        $routes->get('users/create', 'UserController::create');
        $routes->post('users/create', 'UserController::store');
        $routes->get('users/(:num)/edit', 'UserController::edit/$1');
        $routes->post('users/(:num)/edit', 'UserController::update/$1');
        $routes->post('users/(:num)/delete', 'UserController::delete/$1');

        // ---- Setup (reference data) ----
        $routes->get('setup/(:segment)', 'SetupController::index/$1');
        $routes->post('setup/(:segment)', 'SetupController::store/$1');
        $routes->post('setup/(:segment)/(:num)/delete', 'SetupController::delete/$1/$2');

        // ---- People ----
        $routes->get('people/students', 'PeopleController::students');
        $routes->post('people/students', 'PeopleController::storeStudent');
        $routes->post('people/students/(:num)/delete', 'PeopleController::deleteStudent/$1');
        $routes->post('people/students/(:num)/create-login', 'PeopleController::createStudentLogin/$1');
        $routes->get('people/guardians', 'PeopleController::guardians');
        $routes->post('people/guardians', 'PeopleController::storeGuardian');
        $routes->post('people/guardians/(:num)/delete', 'PeopleController::deleteGuardian/$1');
        $routes->post('people/guardians/(:num)/create-login', 'PeopleController::createGuardianLogin/$1');
        $routes->get('people/staff', 'PeopleController::staff');
        $routes->post('people/staff', 'PeopleController::storeStaff');
        $routes->post('people/staff/(:num)/delete', 'PeopleController::deleteStaff/$1');

        // ---- Timetable ----
        $routes->get('timetable', 'TimetableController::index');
        $routes->post('timetable', 'TimetableController::store');
        $routes->post('timetable/(:num)/delete', 'TimetableController::delete/$1');

        // ---- Attendance ----
        $routes->get('attendance', 'AttendanceAdminController::index');
        $routes->post('attendance', 'AttendanceAdminController::store');

        // ---- Assessment & reports ----
        $routes->get('assessments', 'AssessmentAdminController::index');
        $routes->get('assessments/(:num)', 'AssessmentAdminController::show/$1');
        $routes->post('assessments/(:num)/scores', 'AssessmentAdminController::storeScores/$1');
        $routes->post('assessments/reports/(:num)/publish', 'AssessmentAdminController::publishReport/$1');

        // ---- Finance ----
        $routes->get('finance/fee-structures', 'FinanceAdminController::feeStructures');
        $routes->post('finance/fee-structures', 'FinanceAdminController::storeFeeStructure');
        $routes->post('finance/fee-structures/(:num)/delete', 'FinanceAdminController::deleteFeeStructure/$1');
        $routes->get('finance/invoices', 'FinanceAdminController::invoices');
        $routes->post('finance/invoices', 'FinanceAdminController::storeInvoice');
        $routes->get('finance/invoices/(:num)', 'FinanceAdminController::invoice/$1');
        $routes->post('finance/invoices/(:num)/cash-payment', 'FinanceAdminController::recordCashPayment/$1');

        // ---- Boarding ----
        $routes->get('boarding/dormitories', 'BoardingController::dormitories');
        $routes->post('boarding/dormitories', 'BoardingController::storeDormitory');
        $routes->post('boarding/beds/(:num)/assign', 'BoardingController::assignBed/$1');
        $routes->get('boarding/exeats', 'BoardingController::exeats');
        $routes->post('boarding/exeats/(:num)/status', 'BoardingController::updateExeatStatus/$1');
        $routes->get('boarding/sanatorium', 'BoardingController::sanatorium');
        $routes->post('boarding/sanatorium', 'BoardingController::storeVisit');
        $routes->post('boarding/sanatorium/(:num)/resolve', 'BoardingController::resolveVisit/$1');
        $routes->get('boarding/tuck', 'BoardingController::tuck');
        $routes->post('boarding/tuck/(:num)/transaction', 'BoardingController::tuckTransaction/$1');

        // ---- Catering ----
        $routes->get('catering', 'CateringController::index');
        $routes->post('catering', 'CateringController::storeItem');
        $routes->post('catering/items/(:num)', 'CateringController::updateItem/$1');
        $routes->post('catering/items/(:num)/delete', 'CateringController::deleteItem/$1');

        // ---- Transport ----
        $routes->get('transport', 'TransportAdminController::index');
        $routes->post('transport/routes', 'TransportAdminController::storeRoute');
        $routes->post('transport/stops', 'TransportAdminController::storeStop');
        $routes->post('transport/routes/(:num)/ping', 'TransportAdminController::pingLocation/$1');

        // ---- Library ----
        $routes->get('library/catalogue', 'LibraryAdminController::catalogue');
        $routes->post('library/catalogue', 'LibraryAdminController::storeItem');
        $routes->post('library/loans', 'LibraryAdminController::issueLoan');
        $routes->post('library/loans/(:num)/return', 'LibraryAdminController::returnLoan/$1');

        // ---- Comms ----
        $routes->get('threads', 'ThreadAdminController::index');
        $routes->post('threads/(:num)/reply', 'ThreadAdminController::reply/$1');
    });
});

// ---------------- REST API (mobile app) ----------------
$routes->group('api/v1', ['namespace' => 'App\Controllers\Api\V1'], static function ($routes) {
    $routes->post('auth/login', 'AuthController::login');
    $routes->post('auth/refresh', 'AuthController::refresh');

    $routes->group('', ['filter' => 'apiAuth'], static function ($routes) {
        $routes->get('me', 'AuthController::me');

        $routes->get('students/(:num)/timetable', 'StudentController::timetable/$1');
        $routes->get('students/(:num)/attendance', 'StudentController::attendance/$1');
        $routes->get('students/(:num)/grades', 'StudentController::grades/$1');
        $routes->get('students/(:num)/reports', 'StudentController::reports/$1');
        $routes->get('students/(:num)/conduct', 'StudentController::conduct/$1');
        $routes->get('students/(:num)/homework', 'StudentController::homework/$1');
        $routes->post('homework/(:num)/submit', 'HomeworkController::submit/$1');

        $routes->get('invoices', 'FinanceController::invoices');
        $routes->get('invoices/(:num)', 'FinanceController::invoice/$1');
        $routes->post('payments/initiate', 'FinanceController::payInitiate');
        $routes->post('payments/(:num)/verify', 'FinanceController::payVerify/$1');

        $routes->get('menu/week', 'MenuController::week');
        $routes->post('menu/(:num)/rate', 'MenuController::rate/$1');

        $routes->get('exeat-requests', 'ExeatController::index');
        $routes->post('exeat-requests', 'ExeatController::store');

        $routes->get('threads', 'ThreadController::index');
        $routes->get('threads/(:num)', 'ThreadController::show/$1');
        $routes->post('threads/(:num)/messages', 'ThreadController::store/$1');

        $routes->get('library/search', 'LibraryController::search');
        $routes->get('library/loans', 'LibraryController::loans');

        $routes->get('transport/routes', 'TransportController::routes');
        $routes->get('transport/routes/(:num)/live', 'TransportController::live/$1');
        $routes->post('transport/subscribe', 'TransportController::subscribe');

        // Teacher-only
        $routes->get('teacher/classes', 'TeacherController::classes');
        $routes->get('teacher/classes/(:num)/students', 'TeacherController::classStudents/$1');
        $routes->get('teacher/classes/(:num)/assessments', 'TeacherController::classAssessments/$1');
        $routes->get('teacher/subjects', 'TeacherController::subjects');
        $routes->post('assessments', 'TeacherController::assessmentCreate');
        $routes->post('attendance/bulk', 'TeacherController::attendanceBulk');
        $routes->post('assessments/(:num)/scores', 'TeacherController::assessmentScores/$1');
        $routes->post('homework', 'TeacherController::homeworkCreate');
        $routes->post('conduct', 'TeacherController::conductCreate');
    });

    $routes->get('notices', 'ContentController::notices');
    $routes->get('events', 'ContentController::events');
    $routes->get('posts', 'ContentController::posts');
    $routes->get('pages/(:segment)/(:segment)', 'ContentController::page/$1/$2');
    $routes->post('contact', 'ContentController::contact');
    $routes->post('applications', 'ContentController::application');
});

// Generic public-page catch-all — must be LAST so it never shadows /admin or /api/v1.
$routes->get('/(:segment)/(:segment)', 'Web\SiteController::page/$1/$2');
