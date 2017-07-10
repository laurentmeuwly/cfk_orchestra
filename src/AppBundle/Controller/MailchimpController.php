<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

//use Welp\MailchimpBundle\Subscriber\ListRepository;
use AppBundle\Subscriber\ExtListRepository;
use Welp\MailchimpBundle\Subscriber\Subscriber;
use \DrewM\MailChimp\MailChimp;
use AppBundle\Form\ConfigurationType;

/**
 * Mailchimp controller.
 *
 */
class MailchimpController extends Controller
{
	const MAILCHIMP_API_KEY = 'fdf2c4934dd3911339d8387e99beeaa5-us16';
	const LIST_ID = 'edadf194f0';
	
	/**
	 * @Route("/config", name="mailchimp")
	 */
	public function configAction()
	{
		$mailchimp = new MailChimp(self::MAILCHIMP_API_KEY);
		$this->listRepository = new ExtListRepository($mailchimp);
		
		$lists = $this->listRepository->findAll();
		
		foreach($lists as $list)
		{
			$members = $this->listRepository->getMembers($list['id']);
			$addInfos[$list['id']]['nb_members'] = sizeof($members);
		}		
		
		$form = $this->createForm(ConfigurationType::class);
		return $this->render('::mailchimp.html.twig', array('mailchimp_api_key' => self::MAILCHIMP_API_KEY, 'lists' => $lists, 'addInfos' => $addInfos));
	}
}