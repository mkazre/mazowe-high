<?php

namespace App\Models;

use CodeIgniter\Model;

class PageModel extends Model
{
    protected $table      = 'pages';
    protected $primaryKey = 'id';
    protected $allowedFields = ['slug', 'nav_group', 'title', 'kicker', 'lead', 'meta_description', 'status'];
    protected $useTimestamps = true;

    public function findBySlug(string $slug)
    {
        return $this->where('slug', $slug)->first();
    }

    public function withBlocks(string $slug)
    {
        $page = $this->findBySlug($slug);
        if (! $page) {
            return null;
        }

        $blocks = $this->db->table('page_blocks')
            ->where('page_id', $page['id'])
            ->orderBy('position', 'ASC')
            ->get()
            ->getResultArray();

        foreach ($blocks as &$block) {
            $block['data'] = json_decode($block['data'], true) ?? [];
        }

        $page['blocks'] = $blocks;

        return $page;
    }

    public function navGroups(): array
    {
        $groups = [
            'about'       => 'About',
            'academics'   => 'Academics',
            'admissions'  => 'Admissions',
            'boarding'    => 'Boarding',
            'life'        => 'School Life',
            'news'        => 'News',
            'community'   => 'Community',
        ];

        $rows = $this->select('slug, nav_group, title')
            ->where('nav_group !=', null)
            ->where('status', 'published')
            ->orderBy('id', 'ASC')
            ->findAll();

        $out = [];
        foreach ($groups as $key => $label) {
            $out[$key] = ['label' => $label, 'items' => []];
        }
        foreach ($rows as $row) {
            if (isset($out[$row['nav_group']])) {
                $out[$row['nav_group']]['items'][] = ['slug' => $row['slug'], 'title' => $row['title']];
            }
        }

        return $out;
    }
}
