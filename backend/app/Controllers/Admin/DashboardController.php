<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class DashboardController extends BaseController
{
    public function index()
    {
        $counts = [
            'pages'      => $this->db->table('pages')->countAllResults(),
            'enquiries'  => $this->db->table('enquiries')->where('status', 'new')->countAllResults(),
            'notices'    => $this->db->table('notices')->countAllResults(),
            'vacancies'  => $this->db->table('vacancies')->where('status', 'open')->countAllResults(),
        ];

        $recentEnquiries = $this->db->table('enquiries')->orderBy('created_at', 'DESC')->limit(5)->get()->getResultArray();

        return view('admin/dashboard', [
            'title' => 'Dashboard',
            'crumb' => 'Overview',
            'active' => 'dashboard',
            'counts' => $counts,
            'recentEnquiries' => $recentEnquiries,
        ]);
    }
}
