<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use AppBundle\Form\ConfigurationType;

use AppBundle\Entity\Contact;



/**
 * Sendinblue controller.
 *
 */
class SendinblueController extends Controller
{
	/**
	 * @Route("/", name="sendinblue")
	 */
	public function indexAction()
	{
	    $account = $this->get('sendinblue_api.account_endpoint')->getAccount();
	    $lists = $this->get('sendinblue_api.lists_endpoint')->getLists()->getLists();
	    //var_dump($account->getAddress());
	    
	   		
	    return $this->render('::sendinblue.html.twig', [
	        'lists' => $lists,
	        'account' => $account,
	    ]);
	}
	
	

}