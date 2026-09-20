<?php

namespace App\Models;

use CodeIgniter\Model;

class NoticeModel extends Model
{
    protected $table      = 'notices';
    protected $primaryKey = 'id';
    protected $allowedFields = ['title', 'body', 'status', 'published_at'];
    protected $useTimestamps = true;
}
