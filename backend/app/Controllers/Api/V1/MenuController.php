<?php

namespace App\Controllers\Api\V1;

class MenuController extends ApiBaseController
{
    public function week()
    {
        $days = $this->db->table('menu_days')->orderBy('day_of_week', 'ASC')->get()->getResultArray();
        foreach ($days as &$day) {
            $day['items'] = $this->db->table('menu_items')->where('menu_day_id', $day['id'])->get()->getResultArray();
        }

        return $this->response->setJSON(['ok' => true, 'data' => $days]);
    }

    public function rate(int $itemId)
    {
        $ctx = $this->context();
        $rating = (int) ($this->request->getJsonVar('rating') ?? 0);
        if ($rating < 1 || $rating > 5) {
            return $this->response->setJSON(['ok' => false, 'error' => 'rating must be 1-5'])->setStatusCode(422);
        }

        $this->db->table('meal_ratings')->insert([
            'menu_item_id' => $itemId,
            'student_id'   => $ctx['student_ids'][0] ?? null,
            'rating'       => $rating,
            'created_at'   => date('Y-m-d H:i:s'),
        ]);

        return $this->response->setJSON(['ok' => true]);
    }
}
