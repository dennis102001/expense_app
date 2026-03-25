<?php

namespace models;

use core\Model;

class BudgetModel extends Model{

    public function getBudget($userId){
        $date = date("Y-m");

        $getCurrentMonthBudget = $this->db->prepare('SELECT * FROM budgets WHERE date = ? AND user_id = ?');
        $getCurrentMonthBudget->execute([$date, $userId]);

        $budget = $getCurrentMonthBudget->fetch();

        if(!$budget){
            $insertDate = $this->db->prepare('INSERT INTO budgets (date, user_id) VALUES (?, ?)');
            $insertDate->execute([$date, $userId]);

            $getCurrentMonthBudget->execute([$date, $userId]);
            $budget = $getCurrentMonthBudget->fetch();
        }

        return $budget;
    }

    public function update($data){
        $update = $this->db->prepare('UPDATE budgets SET amount = ? WHERE id = ? AND user_id = ?');

        return $update->execute([
            $data['budget_amount'],
            $data['budget_id'],
            $data['user_id']
        ]);
    }
}