<?php

namespace App\Rules;

use App\Models\User;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Contracts\Validation\Rule;

class UniqueNameInUser implements Rule
{
    private Relation $relation;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(Relation $relation)
    {
        $this->relation = $relation;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return !$this->relation->where('name', $value)->exists();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('validation.unique');
    }
}
