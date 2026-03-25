<?php

namespace controllers;

use core\Controller;
use models\BudgetModel;

class BudgetController extends Controller {
    private $budgetModel;

    public function __construct(){
        $this->budgetModel = new BudgetModel();
    }

    public function update(){
        $data = [
            'budget_id' => $_POST['budget_id'],
            'budget_amount' => $_POST['budget_amount'],
            'user_id' => $_SESSION['user_id']
        ];
        
        $result = $this->budgetModel->update($data);

        if($result){
            
            $_SESSION['flash'] = [
                'type' => 'success',
                'message' => 'Budget successfully updated'
            ] ;
        }
        else{
            $_SESSION['flash'] = [
                'type' => 'error',
                'message' => 'Failed to update budget. Please contact the administrator'
            ];
        }

        header('Location: dashboard');
        exit;
    }
}