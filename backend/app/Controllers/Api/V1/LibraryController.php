<?php

namespace App\Controllers\Api\V1;

class LibraryController extends ApiBaseController
{
    public function search()
    {
        $q = (string) ($this->request->getGet('q') ?? '');
        $builder = $this->db->table('catalogue_items');
        if ($q !== '') {
            $builder->groupStart()->like('title', $q)->orLike('author', $q)->groupEnd();
        }
        $rows = $builder->get()->getResultArray();

        return $this->response->setJSON(['ok' => true, 'data' => $rows]);
    }

    public function loans()
    {
        $ctx = $this->context();
        if (! $ctx['student_ids']) {
            return $this->response->setJSON(['ok' => true, 'data' => []]);
        }
        $rows = $this->db->table('loans l')
            ->select('l.*, c.title, c.author')
            ->join('catalogue_items c', 'c.id = l.catalogue_item_id')
            ->whereIn('l.student_id', $ctx['student_ids'])
            ->orderBy('l.borrowed_at', 'DESC')
            ->get()->getResultArray();

        return $this->response->setJSON(['ok' => true, 'data' => $rows]);
    }
}
