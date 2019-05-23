<?php

namespace Controller;

class Game
{
    private $path, $data = null;

    public function __construct()
    {
        $id = isset_get($_POST['id']);
        if ($id) {
            $this->path = __DIR__ . "/../Data/";
            $this->saveGame($id);
        } else {
            set_error('Game not found.');
        }
    }

    function saveGame($file, $data = null)
    {
        $data = $data ?: $this->data;
        if ($this->path) {
            file_put_contents($this->path . "$file.json", json_encode(compact('data')));
        }
    }

    function startGame()
    {
        $this->saveGame(1);
        return true;
    }

    function drawCard()
    {
        return true;
    }
}