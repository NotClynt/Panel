<?php

// Extends to class Database
// Only Protected methods
// Only interats with 'Users/Cheat/Invites' tables

// ** Every block should be wrapped in Session::isReseller(); check **

require_once SITE_ROOT . '/app/core/Database.php';

class Reseller extends Database
{
    protected function resellerSubCodeArray($createdBy)
    {
        if (Session::isReseller()) {
            $this->prepare('SELECT * FROM `subscription` WHERE `createdBy` = ?');
            $this->statement->execute([$createdBy]);

            $result = $this->statement->fetchAll();
            return $result;
        }
    }

    protected function resellerSubCodeGen($code, $createdBy)
    {
        if (Session::isReseller()) {
            $this->prepare('SELECT `balance` FROM `users` WHERE `username` = ?');
            $this->statement->execute([$createdBy]);
            $result = $this->statement->fetch();
            $balance = $result['balance'];

            if ($balance >= 1) {
                $this->prepare('INSERT INTO `subscription` (`code`, `createdBy`) VALUES (?, ?)');
                $this->statement->execute([$code, $createdBy]);

                $this->prepare('UPDATE `users` SET `balance` = `balance` - 1 WHERE `username` = ?');
                $this->statement->execute([$createdBy]);

                $this->prepare('INSERT INTO `logs` (`log_user`, `log_action`, `log_time`) VALUES (?, ?, ?)');
                $this->statement->execute([$createdBy, 'Reseller created subscription code', date('Y-m-d H:i:s')]);
                return 'Successfully created subscription code';
            } else {
                return 'You do not have enough balance to create a subscription code';
            }
        }
    }

    protected function balance()
    {
        if (Session::isReseller()) {
            $this->prepare('SELECT `balance` FROM `users` WHERE `username` = ?');
            $this->statement->execute([$_SESSION['username']]);
            $result = $this->statement->fetch();
            $balance = $result['balance'];
            return $balance;
        }
    }
}
