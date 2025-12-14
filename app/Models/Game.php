<?php

namespace App\Models;

use App\Traits\LayoutTrait;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    use LayoutTrait;

    protected $fillable = [
        'layout',
    ];

    public function toArray(): array
    {
        return [
            'id' => $this->getKey(),
            'layout' => $this->transformLayout($this->layout),
        ];
    }

    protected function layout(): Attribute
    {
        return Attribute::make(
            get: static fn(string $value) => json_decode($value, true, 512, JSON_THROW_ON_ERROR),
            set: static fn(array|string $value) => is_array($value)
                ? json_encode($value, JSON_THROW_ON_ERROR)
                : $value,
        );
    }
}
