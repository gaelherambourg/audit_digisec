<?php

namespace App\Services;

use Symfony\Component\Form\Form;

class ErreursServices
{

    public function getErrorMessages(Form $form)
    {

        $errors = [];

        foreach ($form->all() as $child) {
            foreach ($child->getErrors() as $error) {
                $name = $child->getName();
                $errors[$name] = $error->getMessage();
            }
        }

        return $errors;
    }
}
