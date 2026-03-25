<?php

namespace models;

use core\Model;
use DateTime;

class ExpenseModel extends Model{

    private function getRecentExpenses($userId){
        $dateFrom = date('Y-m-01');
        $dateTo = date('Y-m-t');

        $recentExpenses = $this->db->prepare('SELECT * FROM expenses WHERE user_id = ? AND date BETWEEN ? AND ? ORDER BY id DESC LIMIT 5');
        $recentExpenses->execute([$userId, $dateFrom, $dateTo]);

        return $recentExpenses->fetchAll();
    }

    private function getTotalExpenses($userId){
        $dateFrom = date('Y-m-01');
        $dateTo = date('Y-m-t');

        $totalExpenses = $this->db->prepare('SELECT SUM(Amount) AS total FROM expenses WHERE user_id = ? AND date BETWEEN ? AND ?');
        $totalExpenses->execute([$userId, $dateFrom, $dateTo]);

        return $totalExpenses->fetch()['total'] ?? 0;
    }

    public function getDashboardInfo($userId){
        $budgetModel = new BudgetModel();
        $budget = $budgetModel->getBudget($userId);
        
        $recent = $this->getRecentExpenses($userId);
        $total = $this->getTotalExpenses($userId); 

        $categoryModel = new CategoryModel();
        $categories = $categoryModel->getCategories($userId);
        
        return [
            'recentExpenses' => $recent,
            'totalExpense' => $total,
            'categories' => $categories,
            'budget' => $budget,
            'balance' => ($budget['amount'] - $total),
        ];
    }

    public function getAllExpenses($userId, $page){
        $categoryModel = new CategoryModel();
        $categories = $categoryModel->getCategories($userId);

        $limit = 10;
        $offset = ($page - 1) * $limit;

        $allExpenses = $this->db->prepare(
            "SELECT * FROM expenses 
            WHERE user_id = ? 
            ORDER BY id DESC
            LIMIT $limit OFFSET $offset"
        );

        $allExpenses->execute([$userId]);

        $count = $this->db->prepare("SELECT COUNT(*) FROM expenses WHERE user_id = ?");
        $count->execute([$userId]);

        $totalRows = $count->fetchColumn();
        $totalPages = ceil($totalRows / $limit);

        return [
            'allExpenses' => $allExpenses->fetchAll(),
            'totalPages' => $totalPages,
            'totalRows' => $totalRows,
            'page' => $page,
            'categories' => $categories,
        ];
    }

    public function create($data){
        $create = $this->db->prepare(
            'INSERT INTO expenses (user_id, date, description, amount, category_id)
            VALUES (?, ?, ?, ?, ?)'
        );
        
        return $create->execute([
            $data['user_id'], 
            $data['date'], 
            $data['description'],
            $data['amount'],
            $data['category_id']
        ]);
    }

    public function update($data){
        $update = $this->db->prepare(
            'UPDATE expenses SET date = ?, description = ?, amount = ?, category_id = ? WHERE id = ?'
        );

        return $update->execute([
            $data['date'],
            $data['description'],
            $data['amount'], 
            $data['category_id'], 
            $data['id']
        ]);
    }

    public function delete($id){
        $delete = $this->db->prepare(
            'DELETE FROM expenses WHERE id = ?'
        );

        return $delete->execute([$id]);
    }

    public function getAllById($userId){
        $expenses = $this->db->prepare('SELECT * FROM expenses WHERE user_id = ?');
        $expenses->execute([$userId]);

        $categoryModel = new CategoryModel();
        $categories = $categoryModel->getCategories($userId);

        return [
            'expenses' => $expenses->fetchAll(),
            'categories' => $categories
        ];
    }
}