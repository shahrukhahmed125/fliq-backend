<?php
namespace App\Repositories\Interfaces;

interface PostRepositoryInterface
{
    public function all();
    public function find(String $uuid);
    public function store(array $data);
    public function update(String $uuid, array $data);
    public function delete(String $uuid);
}