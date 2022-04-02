<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UniqueTenant implements Rule
{

    protected string $table;
    protected string $column;
    protected mixed $value;
    protected string $messageName;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(string $table, string $column = "id", $value = null, string $messageName = '')
    {
        $this->table = $table;
        $this->column = $column;
        $this->value = $value;
        $this->messageName = $messageName;
    }

    /**
     * Valida se o tenant já tem um registro com esse valor
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {

        $tenant_id = Auth::user()->tenant_id ?? null;

        $exists = DB::table($this->table)
            ->where($attribute, "=", $value)
            ->where("tenant_id", "=", $tenant_id);

        if($this->column && $this->value) {
            $exists->where($this->column,"!=", $this->value);
        }

        return !$exists->exists();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        $placeholder = !empty($this->messageName) ? $this->messageName : ":attribute";
        return "O campo {$placeholder} já está em uso";
    }
}
