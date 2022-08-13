<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Student extends Model
{
    use HasFactory;

    protected $table = "students";

    protected $primaryKey = "id";

    protected $fillable = [
        "name",
        "email",
        "date_birth",
        "weight",
        "height",
        "gender",
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

    public function searchStudents($filters = [])
    {
        return Student::tenant()
            ->when(!empty($filters["search"]), function ($query) use ($filters) {
                $query->where("name", 'like', "%{$filters['search']}%")->orWhere("email", 'like', "%{$filters['search']}%");
            })
            ->when(!empty($filters["gender"]), function ($query) use ($filters) {
                $query->where("gender", '=', $filters['gender']);
            })
            ->orderBy("name", "desc")
            ->paginate(10);
    }

    public function totais()
    {
        $totais = [];

        $totais["qtd"] = Student::count();

        $totais["m"] = Student::where("gender","=","m")->count();

        $totais["f"] = Student::where("gender","=","f")->count();

        $totais["o"] = Student::where("gender","=","o")->count();

        return $totais;
    }

    public function search($filters = [])
    {
        return $this::tenant()
            ->when(!empty($filters["search"]), function ($query) use ($filters) {
                $query->where("name", 'like', "%{$filters['search']}%");
            })
            ->paginate(10);
    }


}
