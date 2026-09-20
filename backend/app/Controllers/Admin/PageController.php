<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\PageBlockModel;
use App\Models\PageModel;

class PageController extends BaseController
{
    public function index()
    {
        $model = new PageModel();
        $pages = $model->orderBy('nav_group', 'ASC')->orderBy('id', 'ASC')->findAll();

        return view('admin/pages/index', [
            'title'  => 'Pages',
            'crumb'  => 'Content',
            'active' => 'pages',
            'pages'  => $pages,
        ]);
    }

    public function edit(int $id)
    {
        $model = new PageModel();
        $page  = $model->find($id);
        if (! $page) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $blocks = $this->db->table('page_blocks')->where('page_id', $id)->orderBy('position', 'ASC')->get()->getResultArray();
        foreach ($blocks as &$b) {
            $b['data'] = json_decode($b['data'], true) ?? [];
        }

        return view('admin/pages/edit', [
            'title'  => 'Edit — ' . $page['title'],
            'crumb'  => 'Content / Pages',
            'active' => 'pages',
            'page'   => $page,
            'blocks' => $blocks,
        ]);
    }

    public function update(int $id)
    {
        $model = new PageModel();
        $model->update($id, [
            'title'            => $this->request->getPost('title'),
            'kicker'           => $this->request->getPost('kicker'),
            'lead'             => $this->request->getPost('lead'),
            'meta_description' => $this->request->getPost('meta_description'),
        ]);

        session()->setFlashdata('success', 'Page details updated.');

        return redirect()->to('/admin/pages/' . $id . '/edit');
    }

    public function updateBlock(int $pageId, int $blockId)
    {
        $blockModel = new PageBlockModel();
        $block      = $blockModel->find($blockId);
        if (! $block || (int) $block['page_id'] !== $pageId) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $existing = json_decode($block['data'], true) ?? [];
        $posted   = $this->request->getPost('field') ?? [];
        $jsonPosted = $this->request->getPost('json_field') ?? [];

        foreach ($posted as $key => $value) {
            $existing[$key] = $value;
        }
        foreach ($jsonPosted as $key => $value) {
            $decoded = json_decode($value, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $existing[$key] = $decoded;
            } else {
                session()->setFlashdata('error', 'Could not save "' . $key . '" — invalid JSON. That field was left unchanged.');
                return redirect()->to('/admin/pages/' . $pageId . '/edit');
            }
        }

        $blockModel->update($blockId, ['data' => json_encode($existing, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)]);

        session()->setFlashdata('success', 'Block updated.');

        return redirect()->to('/admin/pages/' . $pageId . '/edit');
    }

    public function addBlock(int $pageId)
    {
        $type = $this->request->getPost('type');
        if (! $type) {
            return redirect()->to('/admin/pages/' . $pageId . '/edit');
        }

        $maxPos = $this->db->table('page_blocks')->selectMax('position')->where('page_id', $pageId)->get()->getRow();
        $nextPos = ($maxPos->position ?? -1) + 1;

        $blockModel = new PageBlockModel();
        $blockModel->insert([
            'page_id'  => $pageId,
            'type'     => $type,
            'position' => $nextPos,
            'data'     => json_encode(['title' => '', 'items' => []]),
        ]);

        session()->setFlashdata('success', 'Block added — edit its content below.');

        return redirect()->to('/admin/pages/' . $pageId . '/edit');
    }

    public function deleteBlock(int $pageId, int $blockId)
    {
        (new PageBlockModel())->delete($blockId);
        session()->setFlashdata('success', 'Block removed.');

        return redirect()->to('/admin/pages/' . $pageId . '/edit');
    }

    public function reorderBlocks(int $pageId)
    {
        $blockId   = (int) $this->request->getPost('block_id');
        $direction = $this->request->getPost('direction');

        $blocks = $this->db->table('page_blocks')->where('page_id', $pageId)->orderBy('position', 'ASC')->get()->getResultArray();
        $ids    = array_column($blocks, 'id');
        $index  = array_search($blockId, $ids, true);

        if ($index !== false) {
            $swapWith = $direction === 'up' ? $index - 1 : $index + 1;
            if ($swapWith >= 0 && $swapWith < count($ids)) {
                $blockModel = new PageBlockModel();
                $blockModel->update($ids[$index], ['position' => $swapWith]);
                $blockModel->update($ids[$swapWith], ['position' => $index]);
            }
        }

        return redirect()->to('/admin/pages/' . $pageId . '/edit');
    }
}
