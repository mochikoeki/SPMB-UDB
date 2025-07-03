<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Seleksi extends Model
{
protected $primaryKey = 'id'; //default: id
protected $keyType = 'integer'; //default: bigInteger
protected $table ="seleksi";
protected $fillable = [
'id',
'id_periode',
'tahap',
'tanggal',
'keterangan'
];

public function periode() {
    return $this->belongsTo(Periode::class, "id_periode");
}

}