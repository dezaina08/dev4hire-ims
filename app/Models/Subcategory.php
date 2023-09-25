<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Subcategory extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'category_id',
        'name',
        'description',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'created_at_date',
        'updated_at_date',
    ];

    /**
     * Get the category.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get created at.
     */
    protected function createdAtDate(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->created_at ? $this->created_at->diffForHumans():  '',
        );
    }

    /**
     * Get updated at.
     */
    protected function updatedAtDate(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->updated_at ? $this->updated_at->diffForHumans() : '',
        );
    }
}
