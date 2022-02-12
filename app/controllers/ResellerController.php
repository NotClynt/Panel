<?php

// Extends to class Reseller
// Only Public methods

require_once SITE_ROOT . '/app/models/ResellerModel.php';

class ResellerController extends Reseller
{
    public function getResellerSubCodeGen($username)
    {
        $code = Util::randomCode(20);
        return $this->resellerSubCodeGen($code, $username);
    }

    public function getResellerSubCodeArray($createdBy)
    {
        return $this->resellerSubCodeArray($createdBy);
    }

    public function getBalance()
    {
        return $this->balance();
    }
}
