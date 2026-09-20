<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

/** Generic CRUD for small reference-data tables (Setup module, BUILD-PLAN §5.1). */
class SetupController extends BaseController
{
    private array $entities = [
        'academic-years' => ['table' => 'academic_years', 'label' => 'Academic years', 'fields' => ['name' => 'text', 'is_current' => 'bool']],
        'terms'          => ['table' => 'terms', 'label' => 'Terms', 'fields' => ['academic_year_id' => 'text', 'name' => 'text', 'starts_on' => 'date', 'ends_on' => 'date', 'is_current' => 'bool']],
        'year-groups'    => ['table' => 'year_groups', 'label' => 'Year groups', 'fields' => ['name' => 'text', 'sort_order' => 'text']],
        'houses'         => ['table' => 'houses', 'label' => 'Houses', 'fields' => ['name' => 'text', 'colour' => 'text']],
        'subjects'       => ['table' => 'subjects', 'label' => 'Subjects', 'fields' => ['name' => 'text', 'code' => 'text', 'pathway' => 'text']],
    ];

    private function entity(string $key): array
    {
        if (! isset($this->entities[$key])) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        return $this->entities[$key];
    }

    public function index(string $key)
    {
        $e = $this->entity($key);
        $rows = $this->db->table($e['table'])->get()->getResultArray();

        return view('admin/setup/index', [
            'title' => $e['label'], 'crumb' => 'Setup', 'active' => 'setup',
            'key' => $key, 'entities' => $this->entities, 'entity' => $e, 'rows' => $rows,
        ]);
    }

    public function store(string $key)
    {
        $e = $this->entity($key);
        $data = [];
        foreach (array_keys($e['fields']) as $field) {
            $data[$field] = $this->request->getPost($field) ?: null;
        }
        $this->db->table($e['table'])->insert($data);

        return redirect()->to('/admin/setup/' . $key);
    }

    public function delete(string $key, int $id)
    {
        $e = $this->entity($key);
        $this->db->table($e['table'])->where('id', $id)->delete();

        return redirect()->to('/admin/setup/' . $key);
    }
}
