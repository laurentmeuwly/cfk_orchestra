<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

//use Welp\MailchimpBundle\Subscriber\ListRepository;
//use Welp\MailchimpBundle\WelpMailchimpBundle;
use AppBundle\Subscriber\ExtListRepository;

use Welp\MailchimpBundle\Event\SubscriberEvent;
use Welp\MailchimpBundle\Subscriber\Subscriber;
use \DrewM\MailChimp\MailChimp;
use AppBundle\Form\ConfigurationType;

use AppBundle\Entity\Contact;

/**
 * Mailchimp controller.
 *
 */
class MailchimpController extends Controller
{
	const MAILCHIMP_API_KEY = '';
	
	/**
	 * @Route("/config", name="mailchimp")
	 */
	public function configAction()
	{
		$infos = array();
		$mailchimp = new MailChimp(self::MAILCHIMP_API_KEY);
		
		$listRepository = new ExtListRepository($mailchimp);
		$lists = $listRepository->findAll();
		
		
		//$form = $this->createForm(ConfigurationType::class);
		return $this->render('::mailchimp.html.twig', array('lists' => $lists, 'infos' => $infos));
	}
	
	/**
	 * @Route("/batchsync", name="batchsync")
	 */
	public function batchsyncAction()
	{
		$infos['syncedContact'] = 0;
		$infos['unsyncedContact'] = 0;
		$counter=0;
		$sync = $this->container->get('mailchimp.sync');
		
		$em = $this->container->get('doctrine')->getManager();
		// Turning off doctrine default logs queries for saving memory
		$em->getConnection()->getConfiguration()->setSQLLogger(null);
		
		$contacts = $em->getRepository('AppBundle:Contact')->findAll();
		
		foreach($contacts as $contact)
		{
			if($contact->getNewsletter() && $contact->getEmail()!='') {
				$sync->newContact($contact);
				$infos['syncedContact']++;
			} else {
				$infos['unsyncedContact']++;
			}
		}
		
		$mailchimp = new MailChimp(self::MAILCHIMP_API_KEY);
		
		$listRepository = new ExtListRepository($mailchimp);
		$lists = $listRepository->findAll();
		
		
		//$form = $this->createForm(ConfigurationType::class);
		return $this->render('::mailchimp.html.twig', array('lists' => $lists, 'infos' => $infos));
	}

}