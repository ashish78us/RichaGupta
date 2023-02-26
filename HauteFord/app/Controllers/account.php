<?php

namespace app\Controllers;

class Account extends Controller
{

    /**
     * Création d'un compte (account) lié à un utilisateur
     *
     * @see \app\Models\Account::create()
     * @param int $userid   id de l'utilisateur
     * @return int
     */
    public function create(int $userid): int
    {
        $account_data = [
            'userid' => $userid
        ];
        return $this->model->create($account_data);
    }
}
