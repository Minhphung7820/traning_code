<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PageTable extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $fillable = ['name', 'group_id', 'page_id', 'order'];
}
