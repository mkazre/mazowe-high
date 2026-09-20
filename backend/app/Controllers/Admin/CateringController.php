<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class CateringController extends BaseController
{
    public function index()
    {
        $days = $this->db->table('menu_days')->orderBy('day_of_week')->get()->getResultArray();
        foreach ($days as &$d) {
            $d['items'] = $this->db->table('menu_items')->where('menu_day_id', $d['id'])->get()->getResultArray();
            foreach ($d['items'] as &$item) {
                $ratings = $this->db->table('meal_ratings')->where('menu_item_id', $item['id'])->get()->getResultArray();
                $item['avg_rating'] = $ratings ? round(array_sum(array_column($ratings, 'rating')) / count($ratings), 1) : null;
            }
        }

        return view('admin/catering/index', ['title' => 'Menu cycle', 'crumb' => 'Boarding & catering', 'active' => 'catering', 'days' => $days]);
    }

    public function storeItem()
    {
        $this->db->table('menu_items')->insert([
            'menu_day_id' => (int) $this->request->getPost('menu_day_id'),
            'meal'        => $this->request->getPost('meal'),
            'description' => $this->request->getPost('description'),
            'tags'        => $this->request->getPost('tags'),
        ]);

        return redirect()->to('/admin/catering');
    }

    public function updateItem(int $id)
    {
        $this->db->table('menu_items')->where('id', $id)->update(['description' => $this->request->getPost('description'), 'tags' => $this->request->getPost('tags')]);

        return redirect()->to('/admin/catering');
    }

    public function deleteItem(int $id)
    {
        $this->db->table('menu_items')->where('id', $id)->delete();

        return redirect()->to('/admin/catering');
    }
}
