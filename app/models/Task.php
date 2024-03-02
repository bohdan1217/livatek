<?php

class Task
{
    /**
     * @param string $title
     * @param string $description
     * @param bool $has_deadline
     * @param string|null $deadline
     * @param string $author
     */
    public function __construct(
        public string      $title,
        public string      $description,
        public bool        $has_deadline,
        public string|null $deadline,
        public string      $author,
    )
    {

    }
}