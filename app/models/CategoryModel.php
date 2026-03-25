<?php
namespace models;

use core\Model;

class CategoryModel extends Model{

    public function getCategories($userId){
        $categories = $this->db->prepare('SELECT * FROM categories WHERE user_id = ?');
        $categories->execute([$userId]);

        return $categories->fetchAll();
    }

    public function create($data){
        $create = $this->db->prepare('INSERT INTO categories(category_name, user_id) VALUES (?, ?)');

        return $create->execute([
            $data['category_name'], 
            $data['user_id'], 
        ]);

    }

    public function update($data){
        $update = $this->db->prepare('UPDATE categories SET category_name = ? WHERE id = ? AND user_id = ?');

        return $update->execute([
            $data['category_name'], 
            $data['category_id'], 
            $data['user_id']
        ]);
    }

    public function delete($data){
        $delete = $this->db->prepare('DELETE FROM categories WHERE id = ? AND user_id = ?');

        return $delete->execute([
            $data['category_id'], 
            $data['user_id'], 
        ]);
    }
}