<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\PostModel;

class PostController extends BaseController
{
    public function index()
    {
        $items = (new PostModel())->orderBy('published_at', 'DESC')->findAll();

        return view('admin/simple_list', [
            'title' => 'Blog posts', 'crumb' => 'Communications', 'active' => 'posts',
            'headerAction' => ['label' => '+ New post', 'href' => '/admin/posts/create'],
            'items' => $items, 'entity' => 'posts',
            'columns' => ['title' => 'Title', 'category' => 'Category', 'status' => 'Status', 'published_at' => 'Published'],
        ]);
    }

    public function create()
    {
        return view('admin/posts/form', ['title' => 'New post', 'crumb' => 'Communications', 'active' => 'posts', 'item' => null]);
    }

    public function store()
    {
        (new PostModel())->insert($this->fromPost(true));
        session()->setFlashdata('success', 'Post published.');

        return redirect()->to('/admin/posts');
    }

    public function edit(int $id)
    {
        $item = (new PostModel())->find($id);

        return view('admin/posts/form', ['title' => 'Edit post', 'crumb' => 'Communications', 'active' => 'posts', 'item' => $item]);
    }

    public function update(int $id)
    {
        (new PostModel())->update($id, $this->fromPost(false));
        session()->setFlashdata('success', 'Post updated.');

        return redirect()->to('/admin/posts');
    }

    public function delete(int $id)
    {
        (new PostModel())->delete($id);

        return redirect()->to('/admin/posts');
    }

    private function fromPost(bool $isNew): array
    {
        helper('text');
        $title = $this->request->getPost('title');
        $data = [
            'title'    => $title,
            'category' => $this->request->getPost('category'),
            'excerpt'  => $this->request->getPost('excerpt'),
            'body'     => $this->request->getPost('body'),
            'status'   => $this->request->getPost('status') ?: 'published',
        ];
        if ($isNew) {
            $data['slug'] = url_title($title, '-', true) . '-' . substr(md5(microtime()), 0, 5);
            $data['published_at'] = date('Y-m-d H:i:s');
        }

        return $data;
    }
}
