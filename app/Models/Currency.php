<?php

namespace App\Models;

use App\Services\FileService\XMLService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'NumCode',
        'CharCode',
        'Nominal',
        'Name',
        'Value',
        'VunitRate'
    ];

    protected $table;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->setTable(env('ACTUAL_CURRENCY_TABLE'));
    }

    public function newQuery()
    {
        $this->setTable(env('ACTUAL_CURRENCY_TABLE'));

        return parent::newQuery();
    }
}
