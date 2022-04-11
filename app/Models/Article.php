<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Article extends Model
{
    use HasFactory;
    use Sortable;

    public $sortable= ['id', 'title', 'description', 'type_id'];

    public function articleType() {
        return $this->belongsTo(Type::class, 'type_id', 'id');
    }
}
