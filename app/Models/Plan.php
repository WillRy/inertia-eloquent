<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use phpDocumentor\Reflection\Types\This;

class Plan extends Model
{
    use HasFactory;

    protected $table = "plans";

    protected $primaryKey = "id";

    protected $fillable = [
        "name",
        "duration",
        "price",
        "tenant_id"
    ];

    protected static function boot()
    {
        parent::boot();

        /**
         * preenche automatico o id da empresa
         */
        static::creating(function($student){
            if (Auth::guard()->check() && Auth::user()->tenant_id) $student->tenant_id = Auth::user()->tenant_id;
        });
    }

    /**
     * Filtra automatico o ID da empresa
     * @return mixed
     */
    public function scopeTenant()
    {
        return $this->where("tenant_id", "=", Auth::user()->tenant_id);
    }

    public function searchPlans($filters = [])
    {
        return $this::tenant()
            ->when(!empty($filters["search"]), function ($query) use ($filters) {
                $query->where("name", 'like', "%{$filters['search']}%")->orWhere("price", 'like', "%{$filters['search']}%");
            })
            ->orderBy($filters["sortName"], $filters["sortOrder"])
            ->paginate(10);
    }

}
