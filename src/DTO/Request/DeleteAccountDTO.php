<?php

namespace App\DTO\Request;

use App\Entity\User;

class DeleteAccountDTO
{
    private $password; // On inclure d'autres propriétés nécessaires

    // Getters et setters pour les propriétés

    public function isDataValid(User $user): bool
    {
        // On peut implémentez une logique pour comparer les données fournies avec les données utilisateur.
        // Par exemple, vérifiez si le mot de passe fourni correspond au mot de passe de l'utilisateur.

        return password_verify($this->password, $user->getPassword());
    }
    
}

