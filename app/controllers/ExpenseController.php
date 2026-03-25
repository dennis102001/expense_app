<?php

namespace controllers;

use core\Controller;
use models\ExpenseModel;

class ExpenseController extends Controller{
    private $expenseModel;

    public function __construct()
    {
        $this->expenseModel = new ExpenseModel();
    }

    private function mapCategory($expenses, $categories){
        $recentExpenses = [];

        foreach($expenses as $expense){

            $categoryName = '';

            foreach($categories as $category){
                if($expense['category_id'] == $category['id']){
                    $categoryName = $category['category_name'];
                    break;
                }
            }

            $expense['category_name'] = $categoryName;

            $recentExpenses[] = $expense;
        };

        return $recentExpenses;
    }

    private function validate($data, $redirect, $action){
        $errors = [];

        if(!$data['date']){
            $errors['date'] = 'Date field is required';
        }
        if(!$data['description']){
            $errors['description'] = 'Description field is required';
        }
        if(!$data['amount']){
            $errors['amount'] = 'Amount field is required';
        }

        if(!empty($errors)){
            $_SESSION[$action.'_errors'] = $errors;
            $_SESSION['expense_form_data'] = $data;
            $_SESSION['flash'] = [
                'type' => 'error',
                'message' => 'Please provide all required fields'
            ];
            header("Location: $redirect");
            exit;
        }

        return true;
    }

    public function dashboard(){
        $dashboardInfo = $this->expenseModel->getDashboardInfo($_SESSION['user_id']);

        $recentExpenses = $this->mapCategory($dashboardInfo['recentExpenses'], $dashboardInfo['categories']);

        $this->view('Dashboard', [
            'expenses' => $recentExpenses,
            'total' => $dashboardInfo['totalExpense'],
            'categories' => $dashboardInfo['categories'],
            'budget' => $dashboardInfo['budget'],
            'balance' => $dashboardInfo['balance'],
        ]);
    }

    public function addExpense(){
        $data = [
            'user_id' => $_SESSION['user_id'],
            'date' => $_POST['date'],
            'description' => $_POST['description'],
            'amount' => $_POST['amount'],
            'category_id' => $_POST['category_id'] == 0 ? null : $_POST['category_id'],
        ];

        $this->validate($data, 'dashboard', 'add_expense');
            
        $result = $this->expenseModel->create($data);
        
        if($result){
            $_SESSION['flash'] = [
                'type' => 'success',
                'message' => 'Expense successfully created'
            ] ;
        }
        else{
            $_SESSION['flash'] = [
                'type' => 'error',
                'message' => 'Failed to create expense. Please contact the administrator'
            ];
        }
        
        header('Location: dashboard');
        exit;
        
    }

    public function updateExpense(){
        $data = [
            'id' => $_POST['id'],
            'date' => $_POST['date'],
            'description' => $_POST['description'],
            'amount' => $_POST['amount'],
            'category_id' => $_POST['category_id'] == 0 ? null : $_POST['category_id']
        ];
        
        $redirect = $_POST['redirect'] ?? 'dashboard';

        if(!in_array($redirect, ['dashboard', 'all_expenses'])){
            $redirect = 'dashboard';
        }

        $this->validate($data, $redirect, 'update_expense');

        $result = $this->expenseModel->update($data);
        
        if($result){
            $_SESSION['flash'] = [
                'type' => 'success',
                'message' => 'Expense successfully updated'
            ];
        }
        else{
            $_SESSION['flash'] = [
                'type' => 'error',
                'message' => 'Failed to update expense. Please contact the administrator'
            ];
        }
        
        header("Location: $redirect");
        exit;
        
    }

    public function deleteExpense(){
        $id = $_POST['id'];

        $result = $this->expenseModel->delete($id);

        if($result){
            $_SESSION['flash'] = [
                'type' => 'success',
                'message' => 'Expense successfully deleted'
            ] ;
        }
        else{
            $_SESSION['flash'] = [
                'type' => 'error',
                'message' => 'Failed to delete expense. Please contact the administrator'
            ];
        }

        $redirect = $_POST['redirect'] ?? 'dashboard';

        header("Location: $redirect");
        exit;
    }

    public function showAllExpenses(){
        $userId = $_SESSION['user_id'];
        $page = $_GET['page'] ?? 1;
        $page = max(1, (int)$page);

        $allExpenses = $this->expenseModel->getAllExpenses($userId, $page);

        $expenses = $this->mapCategory($allExpenses['allExpenses'], $allExpenses['categories']);

        $this->view('AllExpenses', [
            'expenses' => $expenses,
            'totalPages' => $allExpenses['totalPages'],
            'totalRows' => $allExpenses['totalRows'],
            'page' => $allExpenses['page'],
            'categories' => $allExpenses['categories']
        ]);
    }

    public function print(){
        $data = $this->expenseModel->getAllById($_SESSION['user_id']);

        $expenses = [];

        $expenses = $this->mapCategory($data['expenses'], $data['categories']);

        $this->view('Print', [
            'expenses' => $expenses
        ]);
    }
}