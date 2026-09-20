<?php

namespace App\Models;

use CodeIgniter\Model;

class PageBlockModel extends Model
{
    protected $table      = 'page_blocks';
    protected $primaryKey = 'id';
    protected $allowedFields = ['page_id', 'type', 'position', 'data'];
    protected $useTimestamps = true;
}
