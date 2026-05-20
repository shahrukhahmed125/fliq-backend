<?php
namespace App\Repositories\Interfaces;

interface CommentRepositoryInterface
{
    public function create(array $data);
    public function getPostComments(String $uuid);
    public function delete(String $uuid);
}