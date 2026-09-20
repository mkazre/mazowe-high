<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class TransportAdminController extends BaseController
{
    public function index()
    {
        $routes = $this->db->table('routes')->get()->getResultArray();
        foreach ($routes as &$r) {
            $r['stops'] = $this->db->table('stops')->where('route_id', $r['id'])->orderBy('sort_order')->get()->getResultArray();
            $r['subscriber_count'] = $this->db->table('route_subscriptions')->where('route_id', $r['id'])->countAllResults();
        }

        return view('admin/transport/index', ['title' => 'Routes & stops', 'crumb' => 'Transport & library', 'active' => 'transport', 'routes' => $routes]);
    }

    public function storeRoute()
    {
        $this->db->table('routes')->insert(['name' => $this->request->getPost('name'), 'description' => $this->request->getPost('description')]);

        return redirect()->to('/admin/transport');
    }

    public function storeStop()
    {
        $routeId = (int) $this->request->getPost('route_id');
        $maxPos = $this->db->table('stops')->selectMax('sort_order')->where('route_id', $routeId)->get()->getRow();
        $this->db->table('stops')->insert([
            'route_id' => $routeId, 'name' => $this->request->getPost('name'),
            'eta' => $this->request->getPost('eta'), 'sort_order' => ($maxPos->sort_order ?? -1) + 1,
        ]);

        return redirect()->to('/admin/transport');
    }

    public function pingLocation(int $routeId)
    {
        $this->db->table('vehicle_pings')->insert([
            'route_id' => $routeId,
            'lat' => (float) $this->request->getPost('lat'),
            'lng' => (float) $this->request->getPost('lng'),
            'recorded_at' => date('Y-m-d H:i:s'),
        ]);
        session()->setFlashdata('success', 'Location updated — this is a manual/simulated ping, not live GPS.');

        return redirect()->to('/admin/transport');
    }
}
