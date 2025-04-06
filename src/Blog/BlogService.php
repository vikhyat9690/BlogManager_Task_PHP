<?php
namespace Blog;

use Blog\Blog;

class BlogService 
{
    private BlogRepository $blogRepository;

    public function __construct(
        BlogRepository $blogRepository
    )
    {
        $this->blogRepository = $blogRepository;
    }
    
    public function createPost(int $userId, string $title, string $content): bool
    {
        $blog = new Blog($userId, $title, $content);
        return $this->blogRepository->create($blog);
    }

    public function getAllPosts(): array
    {
        return $this->blogRepository->getAll();
    }

    public function getPostById(int $id): ?Blog
    {
        return $this->blogRepository->getById($id);
    }

    public function getPostsByUser(int $userId): array
    {
        return $this->blogRepository->getPostsByUser($userId);
    }

    public function updatePost(int $id, string $title, string $content): bool
    {
        $existingPost = $this->blogRepository->getById($id);
        if(!$existingPost) return false;

        $existingPost->setTitle($title);
        $existingPost->setContent($content);

        return $this->blogRepository->update($existingPost);
    }

    public function deletePost(int $id): bool
    {
        $existingPost = $this->blogRepository->getById($id);
        if(!$existingPost) return false;

        return $this->blogRepository->delete($id);
    }
}