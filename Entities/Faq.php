<?php

namespace Modules\Faq\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    use HasFactory;

    protected $guarded = ['_token'];

    public function category()
    {
        return $this->belongsTo(FaqCategory::class, 'faq_category_id', 'id');
    }
}
