<?php
namespace App\Policies;

use App\Models\User;
use App\Models\Transaction;

class TransactionPolicy
{
    public function update(User $user, Transaction $transaction)
    {
        return $user->id === $transaction->user_id;
    }

    public function delete(User $user, Transaction $transaction)
    {
        return $user->id === $transaction->user_id;
    }
}
