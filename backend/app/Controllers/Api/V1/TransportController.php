<?php

namespace App\Controllers\Api\V1;

class TransportController extends ApiBaseController
{
    public function routes()
    {
        $rows = $this->db->table('routes')->get()->getResultArray();

        return $this->response->setJSON(['ok' => true, 'data' => $rows]);
    }

    /** Live location is simulated — no GPS hardware is connected. Returns the most recent stored ping
     *  plus the route's stops so the app can render a "last known position" map, clearly not real-time. */
    public function live(int $id)
    {
        $route = $this->db->table('routes')->where('id', $id)->get()->getRowArray();
        if (! $route) return $this->response->setJSON(['ok' => false])->setStatusCode(404);

        $ping  = $this->db->table('vehicle_pings')->where('route_id', $id)->orderBy('recorded_at', 'DESC')->get()->getRowArray();
        $stops = $this->db->table('stops')->where('route_id', $id)->orderBy('sort_order', 'ASC')->get()->getResultArray();

        return $this->response->setJSON([
            'ok' => true,
            'simulated' => true,
            'data' => ['route' => $route, 'last_ping' => $ping, 'stops' => $stops],
        ]);
    }

    public function subscribe()
    {
        $ctx = $this->context();
        if (! $ctx['student_ids']) return $this->denied();

        $routeId = (int) $this->request->getJsonVar('route_id');
        $studentId = $ctx['student_ids'][0];
        $exists = $this->db->table('route_subscriptions')->where('route_id', $routeId)->where('student_id', $studentId)->countAllResults();
        if (! $exists) {
            $this->db->table('route_subscriptions')->insert(['route_id' => $routeId, 'student_id' => $studentId]);
        }

        return $this->response->setJSON(['ok' => true]);
    }
}
