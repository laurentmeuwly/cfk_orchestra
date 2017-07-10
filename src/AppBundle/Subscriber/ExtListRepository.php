<?php

namespace AppBundle\Subscriber;

use Welp\MailchimpBundle\Subscriber\ListRepository;

/**
 * Handle action on MailChimp List
 */
class ExtListRepository extends ListRepository
{
	/**
	 * Retrieve all MailChimp Lists
	 * @return array lists http://developer.mailchimp.com/documentation/mailchimp/reference/lists/#read-get_lists
	 */
	public function findAll()
	{
		$result = $this->mailchimp->get("lists");
	
		if (!$this->mailchimp->success()) {
			$this->throwMailchimpError($this->mailchimp->getLastResponse());
		}
		return $result['lists'];
	}
}