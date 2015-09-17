<?php
/**
 * 2007-2015 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 *
 *  @author    PrestaShop SA <contact@prestashop.com>
 *  @copyright 2007-2015 PrestaShop SA
 *  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 *  International Registered Trademark & Property of PrestaShop SA
 */

/**
 * Class SettlementFileRequest
 * Extends Request
 * @see Request
 */
class SettlementFileRequest extends Request
{
    /**
     * @var
     */
    private $start_date;
    /**
     * @var
     */
    private $end_date;
    /**
     * @var
     */
    private $param_list;

    /**
     * @return mixed
     */
    public function getStartDate()
    {
        return $this->start_date;
    }

    /**
     * @param $start_date
     */
    public function setStartDate($start_date)
    {
        $this->start_date = $start_date;
        $this->param_list['start_date'] = $start_date;
    }
    /**
     * @return mixed
     */
    public function getEndDate()
    {
        return $this->end_date;
    }

    /**
     * @param $end_date
     */
    public function setEndDate($end_date)
    {
        $this->end_date = $end_date;
        $this->param_list['end_date'] = $end_date;
    }

    /**
     * @return mixed
     */
    public function getParamList()
    {
        return $this->param_list;
    }
}
