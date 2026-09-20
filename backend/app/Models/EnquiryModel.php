<?php

namespace App\Models;

use CodeIgniter\Model;

class EnquiryModel extends Model
{
    protected $table      = 'enquiries';
    protected $primaryKey = 'id';
    protected $allowedFields = ['type', 'reference', 'name', 'email', 'phone', 'subject', 'message', 'payload', 'status'];
    protected $useTimestamps = true;
}
