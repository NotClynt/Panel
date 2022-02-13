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
            $this->prepare('SELECT * FROM `invites`');
            $this->statement->execute();

            $result = $this->statement->fetchAll();
            return $result;
        }
    }


    // Create invite code
    protected function invCodeGen($code, $createdBy)
    {
        if (Session::isAdmin()) {
            $this->prepare('INSERT INTO `invites` (`code`, `createdBy`) VALUES (?, ?)');
            $this->statement->execute([$code, $createdBy]);
        }
    }

    // Invite wave - create invite codes and send them to users
    protected function invCodeWave()
    {
        if (Session::isAdmin()) {
            $this->prepare('SELECT * FROM `users`');
            $this->statement->execute();

            $result = $this->statement->fetchAll();

            foreach ($result as $user) {
                $code = bin2hex(random_bytes(16));
                $this->invCodeGen($code, $user->username);
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

            $this->prepare('INSERT INTO `logs` (`log_user`, `log_action`, `log_time`) VALUES (?, ?, NOW())');
            $this->statement->execute([$createdBy, "Created subscription code $code"]);
        }
    }

    // Resets HWID
    protected function HWID($uid)
    {
        if (Session::isAdmin()) {
            $this->prepare('UPDATE `users` SET `hwid` = NULL WHERE `uid` = ?');
            $this->statement->execute([$uid]);

            $this->prepare('INSERT INTO `logs` (`log_user`, `log_action`, `log_time`) VALUES (?, ?, NOW())');
            $this->statement->execute([$uid, "Reset HWID"]);
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

                $this->prepare('INSERT INTO `logs` (`log_user`, `log_action`, `log_time`) VALUES (?, ?, NOW())');
                $this->statement->execute([$uid, "Banned user"]);
            } else {
                $this->prepare('UPDATE `users` SET `banned` = 0 WHERE `uid` = ?');
                $this->statement->execute([$uid]);

                $this->prepare('INSERT INTO `logs` (`log_user`, `log_action`, `log_time`) VALUES (?, ?, NOW())');
                $this->statement->execute([$uid, "Unbanned user"]);
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

                $this->prepare('INSERT INTO `logs` (`log_user`, `log_action`, `log_time`) VALUES (?, ?, NOW())');
                $this->statement->execute([$uid, "Made user admin"]);
            } else {
                $this->prepare('UPDATE `users` SET `admin` = 0 WHERE `uid` = ?');
                $this->statement->execute([$uid]);

                $this->prepare('INSERT INTO `logs` (`log_user`, `log_action`, `log_time`) VALUES (?, ?, NOW())');
                $this->statement->execute([$uid, "Removed admin from user"]);
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

            $this->prepare('INSERT INTO `logs` (`log_user`, `log_action`, `log_time`) VALUES (?, ?, NOW())');
            $this->statement->execute(['admin', "Purged logs"]);
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

                $this->prepare('INSERT INTO `logs` (`log_user`, `log_action`, `log_time`) VALUES (?, ?, NOW())');
                $this->statement->execute(['admin', "Enabled cheat"]);
            } else {
                $this->prepare('UPDATE `cheat` SET `status` = 0');
                $this->statement->execute();

                $this->prepare('INSERT INTO `logs` (`log_user`, `log_action`, `log_time`) VALUES (?, ?, NOW())');
                $this->statement->execute(['admin', "Disabled cheat"]);
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

                $this->prepare('INSERT INTO `logs` (`log_user`, `log_action`, `log_time`) VALUES (?, ?, NOW())');
                $this->statement->execute(['admin', "Enabled cheat maintenance"]);
            } else {
                $this->prepare('UPDATE `cheat` SET `maintenance` = 0');
                $this->statement->execute();

                $this->prepare('INSERT INTO `logs` (`log_user`, `log_action`, `log_time`) VALUES (?, ?, NOW())');
                $this->statement->execute(['admin', "Disabled cheat maintenance"]);
            }
        }
    }
    //
    protected function cheatMotd($motd)
    {
        if (Session::isAdmin()) {
            $this->prepare('UPDATE `cheat` SET `motd` = ?');
            $this->statement->execute([$motd]);

            $this->prepare('INSERT INTO `logs` (`log_user`, `log_action`, `log_time`) VALUES (?, ?, NOW())');
            $this->statement->execute(['admin', "Updated cheat motd"]);
        }
    }


    //
    protected function cheatVersion($ver)
    {
        if (Session::isAdmin()) {
            $this->prepare('UPDATE `cheat` SET `version` = ?');
            $this->statement->execute([$ver]);

            $this->prepare('INSERT INTO `logs` (`log_user`, `log_action`, `log_time`) VALUES (?, ?, NOW())');
            $this->statement->execute(['admin', "Updated cheat version"]);
        }
    }
}
