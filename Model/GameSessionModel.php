<?php
require_once PROJECT_ROOT_PATH . "/Model/Database.php";
 
class GameSessionModel extends Database
{
    public function getMemory($game_key)
    {
        return $this->select(
        "SELECT
            ma.mem_key,
            ma.mem_module,
            ma.mem_base,
            mp.mp_offset,
            ma.mem_name,
            mp.mp_pointer_order,
            ma.mem_type
        FROM memory_addrs ma
        LEFT JOIN mem_pointers mp
        ON ma.mem_game_key = mp.mp_game_key AND ma.mem_key = mp.mp_mem_key
        WHERE ma.mem_game_key = ?
        ORDER BY ma.mem_key, mp.mp_pointer_order"
        , ["i", $game_key]
        );
    }

    public function getLockedAch($game_key, $user_key)
    {
        return $this->select(
            "SELECT
                a.ach_key,
                al.al_key,
                al.al_mem_key,
                al.al_mem_bool_type,
                al.al_mem_comp,
                al.al_mem_comp_type,
                a.ach_desc,
                al.al_final_data_type,
                al.al_relation_key,
                al.al_trigger_type,
                al.al_unlock_order,
                al.al_target_cnt
            FROM achievement a
            INNER JOIN achievement_logic al
            ON a.ach_key = al.al_ach_key
            LEFT JOIN (
                SELECT *
                FROM user_ach
                WHERE ua_user_key = ?
            ) ua
            ON ua.ua_ach_key = a.ach_key
            WHERE ua.ua_ach_key IS NULL
                AND a.ach_game_key = ?
            ORDER BY ach_key, al_unlock_order;",
            ["ii", $user_key, $game_key]
        );
    }

    public function getAchProg($game_key, $user_key)
    {
        return $this->select(
            "SELECT
                al.al_ach_key,
                al.al_key,
                uap.uap_progress,
                al.al_target_cnt
            FROM achievement_logic al 
            INNER JOIN ua_ach_prog uap 
            ON al.al_ach_key = uap.uap_ach_key
                AND al.al_key = uap.uap_al_key 
            INNER JOIN achievement a
            ON al.al_ach_key = a.ach_key
            LEFT JOIN (
                    SELECT *
                    FROM user_ach
                    WHERE ua_user_key = ?
                ) ua 
            ON ua.ua_ach_key = uap.uap_ach_key 
                AND ua.ua_user_key = uap.uap_user_key
            WHERE ua.ua_user_key IS NULL
                AND a.ach_game_key = ?;",
            ["ii", $user_key, $game_key]
        );
    }

    public function unlockAch($user_key, $ach_key)
    {
        return $this->commit(
            "INSERT INTO `user_ach`(`ua_user_key`, `ua_ach_key`) VALUES (?,?);",
            ["ii", $user_key, $ach_key]
        );
    }

    public function updateProg($unlocked_flag, $progress, $user_key, $ach_key, $al_key)
    {
        return $this->commit(
            "UPDATE blip_achs.ua_ach_prog 
            SET 
                uap_unlocked_flag = ?, 
                uap_progress = ? 
            WHERE 
                uap_user_key = ? 
                AND uap_ach_key = ? 
                AND uap_al_key = ?;",
            ["iiiii", $unlocked_flag, $progress, $user_key, $ach_key, $al_key]
        );
    }

    public function startGame($game_key, $user_key)
    {
        return $this->commit(
            "INSERT INTO `user_game`(`ug_game_key`, `ug_user_key`) VALUES (?,?)",
            ["ii", $game_key, $user_key]
        );
    }
}