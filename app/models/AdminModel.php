<?php

// Extends to class Database
// Only Protected methods
// Only interats with 'Users/Cheat/Invites' tables

// ** Every block should be wrapped in Session::isAdmin(); check **

require_once SITE_ROOT . '/app/core/Database.php';

class Admin extends Database
{
    // Get array of all users
    // - includes hashed passwords too.
    protected function UserArray()
    {
        if (Session::isAdmin()) {
            $this->prepare('SELECT * FROM `users` ORDER BY uid ASC');
            $this->statement->execute();

            $result = $this->statement->fetchAll();
            return $result;
        }
    }


    // Get array of all invite codes
    protected function invCodeArray()
    {
        if (Session::isAdmin()) {
            $this->prepare('SELECT * FROM `license`');
            $this->statement->execute();

            $result = $this->statement->fetchAll();
            return $result;
        }
    }


    // Create invite code
    protected function invCodeGen($code, $createdBy)
    {
        if (Session::isAdmin()) {
            $this->prepare('INSERT INTO `license` (`code`, `createdBy`) VALUES (?, ?)');
            $this->statement->execute([$code, $createdBy]);
        }
    }

    // Invite wave - create invite codes and send them to users
    protected function invWave($wave)
    {
        if (Session::isAdmin()) {
            $this->prepare('SELECT * FROM `users` WHERE `wave` = ?');
            $this->statement->execute([$wave]);

            $result = $this->statement->fetchAll();

            foreach ($result as $row) {
                $code = $this->invCodeGen($row['uid'], $row['uid']);

                $this->prepare('INSERT INTO `invites` (`uid`, `code`, `wave`) VALUES (?, ?, ?)');
                $this->statement->execute([$row['uid'], $code, $wave]);
            }
        }
    }

    // Get array of all subscription codes
    protected function subCodeArray()
    {
        if (Session::isAdmin()) {
            $this->prepare('SELECT * FROM `subscription`');
            $this->statement->execute();

            $result = $this->statement->fetchAll();
            return $result;
        }
    }


    // Create subscription code
    protected function subCodeGen($code, $createdBy)
    {
        if (Session::isAdmin()) {
            $this->prepare('INSERT INTO `subscription` (`code`, `createdBy`) VALUES (?, ?)');
            $this->statement->execute([$code, $createdBy]);
        }
    }

    // Resets HWID
    protected function HWID($uid)
    {
        if (Session::isAdmin()) {
            $this->prepare('UPDATE `users` SET `hwid` = NULL WHERE `uid` = ?');
            $this->statement->execute([$uid]);
        }
    }


    // Set user ban / unban
    protected function banned($uid)
    {
        if (Session::isAdmin()) {
            $this->prepare('SELECT `banned` FROM `users` WHERE `uid` = ?');
            $this->statement->execute([$uid]);
            $result = $this->statement->fetch();

            if ((int)$result->banned === 0) {
                $this->prepare('UPDATE `users` SET `banned` = 1 WHERE `uid` = ?');
                $this->statement->execute([$uid]);
            } else {
                $this->prepare('UPDATE `users` SET `banned` = 0 WHERE `uid` = ?');
                $this->statement->execute([$uid]);
            }
        }
    }


    // Set user admin / non admin
    protected function administrator($uid)
    {
        if (Session::isAdmin()) {
            $this->prepare('SELECT `admin` FROM `users` WHERE `uid` = ?');
            $this->statement->execute([$uid]);
            $result = $this->statement->fetch();

            if ((int)$result->admin === 0) {
                $this->prepare('UPDATE `users` SET `admin` = 1 WHERE `uid` = ?');
                $this->statement->execute([$uid]);
            } else {
                $this->prepare('UPDATE `users` SET `admin` = 0 WHERE `uid` = ?');
                $this->statement->execute([$uid]);
            }
        }
    }

    // Logs
    protected function logsArray()
    {
        if (Session::isAdmin()) {
            $this->prepare('SELECT * FROM `logs` ORDER BY id DESC');
            $this->statement->execute();
            $result = $this->statement->fetchAll();

            return $result;
        }
    }

    // Purge logs
    protected function logsPurge()
    {
        if (Session::isAdmin()) {
            $this->prepare('DELETE FROM `logs`');
            $this->statement->execute();
        }
    }


    //
    protected function cheatStatus()
    {
        if (Session::isAdmin()) {
            $this->prepare('SELECT `status` FROM `cheat`');
            $this->statement->execute();
            $result = $this->statement->fetch();

            if ((int)$result->status === 0) {
                $this->prepare('UPDATE `cheat` SET `status` = 1');
                $this->statement->execute();
            } else {
                $this->prepare('UPDATE `cheat` SET `status` = 0');
                $this->statement->execute();
            }
        }
    }


    //
    protected function cheatMaint()
    {
        if (Session::isAdmin()) {
            $this->prepare('SELECT `maintenance` FROM `cheat`');
            $this->statement->execute();
            $result = $this->statement->fetch();

            if ((int)$result->maintenance === 0) {
                $this->prepare('UPDATE `cheat` SET `maintenance` = 1');
                $this->statement->execute();
            } else {
                $this->prepare('UPDATE `cheat` SET `maintenance` = 0');
                $this->statement->execute();
            }
        }
    }
    //
    protected function cheatMotd($motd)
    {
        if (Session::isAdmin()) {
            $this->prepare('UPDATE `cheat` SET `motd` = ?');
            $this->statement->execute([$motd]);
        }
    }


    //
    protected function cheatVersion($ver)
    {
        if (Session::isAdmin()) {
            $this->prepare('UPDATE `cheat` SET `version` = ?');
            $this->statement->execute([$ver]);
        }
    }
}
