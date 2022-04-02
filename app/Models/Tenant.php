<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class Tenant extends Model
{
    use HasFactory;

    protected $table = "tenants";

    protected $primaryKey = "id";

    protected $fillable = [
        "name",
        "email"
    ];


    public function users()
    {
        return $this->hasMany(User::class,"tenant_id", "id");
    }

    public function register($data)
    {
        try {
            DB::beginTransaction();

            $tenant = Tenant::create([
                "name" => $data["company"],
                "email" => $data["email"]
            ]);

            $user = User::create([
                "name" => $data["name"],
                "email" => $data["email"],
                "password" => Hash::make($data["password"]),
                "tenant_id" => $tenant->id
            ]);

            DB::commit();

            return $user;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
