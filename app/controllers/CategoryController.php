<?php

namespace controllers;

use core\Controller;
use models\CategoryModel;

class CategoryController extends Controller{
    private $categoryModel;

    public function __construct()
    {
        $this->categoryModel = new CategoryModel();
    }

    public function create(){

        $this->validate(trim($_POST['category_name']));

        $data = [
            'category_name' => $_POST['category_name'],
            'user_id' => $_SESSION['user_id']
        ];

        $result = $this->categoryModel->create($data);

        if($result){
            $_SESSION['flash'] = [
                'type' => 'success',
                'message' => 'Category successfully created'
            ] ;
        }
        else{
            $_SESSION['flash'] = [
                'type' => 'error',
                'message' => 'Failed to create category. Please contact the administrator'
            ];
        }
    
        header('Location: dashboard');
        exit;

    }

    public function update(){
        $this->validate(trim($_POST['category_name']));

        $data = [
            'category_id' => $_POST['category_id'],
            'category_name' => $_POST['category_name'],
            'user_id' => $_SESSION['user_id']
        ];

        $result = $this->categoryModel->update($data);

        if($result){
            $_SESSION['flash'] = [
                'type' => 'success',
                'message' => 'Category successfully updated'
            ] ;
        }
        else{
            $_SESSION['flash'] = [
                'type' => 'error',
                'message' => 'Failed to update category. Please contact the administrator'
            ];
        }
    
        header('Location: dashboard');
        exit;

    }

    public function delete(){
        $data = [
            'category_id' => $_POST['category_id'],
            'user_id' => $_SESSION['user_id']
        ];

        $result = $this->categoryModel->delete($data);

        if($result){
            $_SESSION['flash'] = [
                'type' => 'success',
                'message' => 'Category successfully deleted'
            ] ;
        }
        else{
            $_SESSION['flash'] = [
                'type' => 'error',
                'message' => 'Failed to delete category. Please contact the administrator'
            ];
        }
    
        header('Location: dashboard');
        exit;
    }

    private function validate(string $categoryName){
        if (empty($categoryName)){
            $_SESSION['flash'] = [
                'type' => 'error',
                'message' => 'Please provide name for the category'
            ];

            header('Location: dashboard');
            exit;
        }
        
        return true;
    }
}