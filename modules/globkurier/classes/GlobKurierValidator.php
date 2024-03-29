<?php
/*
 * 2007-2014 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
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
 *  @author PrestaShop SA <contact@prestashop.com>
 *  @copyright  2007-2014 PrestaShop SA
 *  @license	http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 *  International Registered Trademark & Property of PrestaShop SA
 */

class GlobKurierValidator {

	/**
	 * Validate is empty fields
	 *
	 * @param string $str_data;
	 * @return bool
	 */
	public static function isEmpty($str_data)
	{
		if (!$str_data || empty($str_data) || $str_data == null)
			return true;
		return false;
	}

	/**
	 * Validate is short
	 *
	 * @param string $str_data;
	 * @return bool
	 */
	public static function isShort($str_data)
	{
		if (Tools::strlen($str_data) < 6)
			return true;
		return false;
	}

	/**
	 * Validate email address
	 *
	 * @param string $str_email;
	 * @return bool
	 */
	public static function isEmail($str_email)
	{
		return filter_var($str_email, FILTER_VALIDATE_EMAIL);
	}
}