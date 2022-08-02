<?php
require_once PROJECT_ROOT_PATH . "/Model/Database.php";
 
class AchEditorModel extends Database
{
    public function getGameList()
    {
        return $this->select("SELECT * FROM game;");
    }

    public function getGameFromGameKey($game_key)
    {
        return $this->select("SELECT * FROM game WHERE game_key = ?", ["i", $game_key]);
    }

    public function getGameMemory($game_key)
    {
        return $this->select("SELECT * FROM memory_addrs WHERE mem_game_key = ?", ["i", $game_key]);
    }
}