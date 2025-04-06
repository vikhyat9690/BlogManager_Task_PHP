<?php
namespace Blog;

class Blog implements \JsonSerializable
{
    private $id;
    private $userid;
    private $title;
    private $content;
    private $createdAt;
    private $updatedAt;

    public function __construct(
        int $userid,
        string $title,
        string $content,
        string $createdAt = '',
        string $updatedAt = '',
        int $id = 0
    )
    {
        $this->userid = $userid;
        $this->title = $title;
        $this->content = $content;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->id = $id;
    }

    //Getters
    public function getId(): int {return $this->id;}
    public function getUserId(): int {return $this->userid;}
    public function getTitle(): string {return $this->title;}
    public function getContent(): string {return $this->content;}
    public function getCreateAt(): string {return $this->createdAt;}
    public function getUpdatedAt(): string {return $this->updatedAt;}

    //Setters
    public function setTitle(string $title): void {$this->title = $title;}
    public function setContent(string $content): void {$this->content = $content;}
    public function setUpdatedAt(string $updatedAt): void {$this->updatedAt = $updatedAt;}

    public function jsonSerialize(): mixed
    {
        return [
            'id' => $this->getId(),
            'user_id' => $this->getUserId(),
            'title' => $this->getTitle(),
            'content' => $this->getContent(),
            'created_at' => $this->getCreateAt(),
            'updated_at' => $this->getUpdatedAt(),
        ];
    }
}