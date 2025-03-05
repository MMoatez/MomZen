<?php
namespace App\Dto;

class ForumSearch
{
    private ?string $query = null;
    private string $sort = 'date_desc';

    public function getQuery(): ?string { return $this->query; }
    public function setQuery(?string $query): void { $this->query = $query; }

    public function getSort(): string { return $this->sort; }
    public function setSort(string $sort): void { $this->sort = $sort; }
}