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
            if (file_exists($this->path . $id . ".json")) {
                $this->loadGame($id);
            }
        } else {
            set_error('Game not found.');
        }
    }

    private function saveGame($file, $data = null)
    {
        $data = $data ?: $this->data;
        if ($this->path) {
            file_put_contents($this->path . "$file.json", json_encode(compact('data')));
        }
    }

    private function loadGame($file)
    {
        if ($this->path) {
            $this->data = json_decode(file_get_contents($this->path . "$file.json"), true)['data'];
        }
    }

    function start()
    {
        $data = ['name' => ''];
        $this->saveGame(isset_get($_POST['id']), $data);
        return true;
    }

    function status()
    {
        $data = $this->data;
        return compact('data');
    }

    function drawCard()
    {
        return true;
    }
}