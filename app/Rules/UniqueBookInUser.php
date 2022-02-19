<?php

namespace App\Rules;

use App\Models\User;
use Illuminate\Contracts\Validation\Rule;

class UniqueBookInUser implements Rule
{
    private User $user;
    private String $bookName;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(User $user, String $bookName)
    {
        $this->user = $user;
        $this->bookName = $bookName;
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
        return !$this->user->books()
            ->where('name', $this->bookName)
            ->where('purchase_date', $value)
            ->exists();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The validation error message.';
    }
}
