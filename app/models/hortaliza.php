<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Hortaliza extends Model
{
    use SoftDeletes;

    protected $primaryKey = 'id';
    protected $table = 'hortalizas';
    public $incrementing = true;
    public $timestamps = false;

    const DELETED_AT = 'fecha_de_baja';

    protected $fillable = [
        'nombre', 'precio', 'stock', 'tipo', 'foto'
    ];
}

?>