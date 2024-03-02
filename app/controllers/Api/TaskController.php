<?php

require_once '../app/models/Task.php';
require_once '../config/database.php';

class TaskController
{
    /**
     * @throws JsonException
     */
    public function index(): false|string
    {
        // get all tasks
        $tasks = $this->all();

        return json_encode($tasks, JSON_THROW_ON_ERROR);
    }

    /**
     * @throws JsonException
     */
    public function create(): bool|string
    {
        $data = json_decode(file_get_contents('php://input'), true, 512, JSON_THROW_ON_ERROR);

        if (empty($data)) {
            http_response_code(400);
            return json_encode(array("message" => "Empty data"), JSON_THROW_ON_ERROR);
        }

        // Validate data
        if (!$this->validateTaskData($data)) {
            http_response_code(400);
            return json_encode(array("message" => "Invalid task data"), JSON_THROW_ON_ERROR);
        }
        // get all tasks
        $tasks = $this->all();

        $task = new Task(
            $data['title'],
            $data['description'],
            $data['has_deadline'],
            $data['has_deadline'] ? $data['deadline'] : null,
            $data['author']
        );

        $tasks[] = $task;

        $this->save($tasks);

        return json_encode(array("message" => "Task created successfully"), JSON_THROW_ON_ERROR);
    }

    /**
     * @throws JsonException
     */
    public function delete(): bool|string
    {
        $data = json_decode(file_get_contents('php://input'), true, 512, JSON_THROW_ON_ERROR);
        $taskId = $data["id"];

        $tasks = $this->all();
        // Find task by ID and remove it
        foreach ($tasks as $key => $task) {
            if ($key === $taskId) {
                unset($tasks[$key]);
                $this->save(array_values($tasks));

                return json_encode(array("message" => "Task deleted successfully"), JSON_THROW_ON_ERROR);
            }
        }

        // If task with given ID not found
        http_response_code(404); // Not Found
        return json_encode(["message" => "Task not found"], JSON_THROW_ON_ERROR);
    }


    /**
     * @throws JsonException
     */
    private function all()
    {
        if (!file_exists(DATA_FILE)) {
            return [];
        }

        $data = file_get_contents(DATA_FILE);

        return json_decode($data, false, 512, JSON_THROW_ON_ERROR);
    }

    /**
     * @throws JsonException
     */
    private function save($tasks): void
    {
        file_put_contents(DATA_FILE, json_encode($tasks, JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT));
    }

    /**
     * @param $data
     * @return bool
     */
    private function validateTaskData($data): bool
    {
        return isset($data['title']) &&
            isset($data['description']) &&
            isset($data['has_deadline']) &&
            isset($data['deadline']) &&
            isset($data['author']) &&
            !empty($data['title']) &&
            !empty($data['description']) &&
            is_bool($data['has_deadline']) &&
            (!empty($data['deadline']) || !$data['has_deadline']) &&
            !empty($data['author']);
    }

}