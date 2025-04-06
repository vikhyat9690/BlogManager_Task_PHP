<?php
namespace Blog;

use Blog\Blog;

use mysqli;

class BlogRepository
{
    private mysqli $conn;

    public function __construct(
        mysqli $conn
    )
    {
        $this->conn = $conn;
    }

    public function create(Blog $blog): bool
    {
        $stmt = $this->conn->prepare("INSERT INTO blogs (author_id, title, content, created_at) VALUES (?, ?, ?, NOW())");
        if(!$stmt) return false;

        $userid = $blog->getUserId();
        $title = $blog->getTitle();
        $content = $blog->getContent();

        $stmt->bind_param('iss', $userid, $title, $content);
        return $stmt->execute();
    }

    public function getAll(): array 
    {
        $stmt = $this->conn->prepare("SELECT * FROM blogs ORDER BY created_at DESC");
        $stmt->execute();
        $result = $stmt->get_result();
        $blogs = [];

        while($row = $result->fetch_assoc()) {
            $blogs[] = new Blog(
                $row['author_id'],
                $row['title'],
                $row['content'],
                $row['created_at'],
                $row['updated_at'],
                $row['id']
            );
        }
        return $blogs;
    }

    public function getById(int $id): ?Blog
    {
        $stmt = $this->conn->prepare("SELECT * FROM blogs WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();

        if($result) {
            return new Blog(
                $result['author_id'],
                $result['title'],
                $result['content'],
                $result['created_at'],
                $result['updated_at'],
                $result['id']
            );
        }

        return null;
    }

    public function getPostsByUser(int $userId): array
    {
        $stmt = $this->conn->prepare("SELECT id, title, content FROM blogs WHERE author_id = ? ORDER BY created_at DESC");
        $stmt->bind_param("i", $userId);

        $stmt->execute();
        $result = $stmt->get_result();

        $posts = [];
        while ($row = $result->fetch_assoc()) {
            $posts[] = $row;
        }

        $stmt->close();

        return $posts;
    }

    public function update(Blog $blog): bool
    {
        $stmt = $this->conn->prepare("UPDATE blogs SET title = ?, content = ?, updated_at =NOW() WHERE id = ?");
        $stmt->bind_param("ssi", $blog->getTitle(), $blog->getContent(), $blog->getId());
        return $stmt->execute();
    }

    public function delete(int $id): bool 
    {
        $stmt = $this->conn->prepare("DELETE FROM blogs WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}