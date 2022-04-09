<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Subscription extends Model
{
    use HasFactory;

    protected $table = "subscriptions";

    protected $primaryKey = "id";

    protected $fillable = [
        "student_id",
        "plan_id",
        "dt_start",
        "dt_end"
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id','id');
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class, 'plan_id','id');
    }

    /**
     * Filtra automatico o ID da empresa
     * @return mixed
     */
    public function scopeTenant()
    {
        return $this->whereHas('student', function($q) {
           $q->where("tenant_id","=", Auth::user()->tenant_id);
        });
    }

    public function subscriptionExists($student_id, $plan_id)
    {
        return $this->whereHas("student", function($q) use($student_id){
            $q->where("student_id","=", $student_id);
        })->whereHas("plan", function($q) use($plan_id){
            $q->where("plan_id","=", $plan_id);
        })->exists();
    }

    public function getSubscription($id)
    {
        return Subscription::query()
            ->tenant()
            ->with(["student","plan"])
            ->where("id","=", $id)
            ->firstOrFail();
    }

    public function searchSubscription($search, $plan = null)
    {
        return Subscription::query()
            ->tenant()
            ->with(["plan","student"])
            ->whereHas('student', function ($query) use ($search) {
                $query->whereRaw("name LIKE ? ", ["%$search%"]);
            })
            ->when(!empty($plan), function($q) use ($plan) {
                $q->whereHas('plan', function ($query) use ($plan) {
                    $query->where("id",'=', $plan);
                });
            })
            ->orderBy("id","DESC")
            ->paginate(10);
    }
}
