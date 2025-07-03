<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Uts220101079 extends Model
{
    protected $primaryKey = 'kode_info';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $table = "uts_220101079";
    protected $fillable = [
        'kode_info',
        'judul_info',
        'konten_info'
    ];
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {

            $last = self::orderBy('kode_info', 'desc')->first();

            if ($last) {
                $lastNumber = intval(substr($last->kode_kata, 2));
                $newNumber = $lastNumber + 1;
            } else {
                $newNumber = 1;
            }

            $model->kode_info = 'HI' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
        });
    }
}
