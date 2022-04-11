<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Type extends Model
{
    use HasFactory;
    use Sortable;

    public $sortable= ['id', 'title', 'description'];

    public function typeArticle() {
       
        return $this->hasMany(Article::class, 'type_id', 'id');
    }
}
