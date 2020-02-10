<?php

namespace AppBundle\Controller;

#use JavierEguiluz\Bundle\EasyAdminBundle\Controller\AdminController as EasyAdminController;
use AlterPHP\EasyAdminExtensionBundle\Controller\AdminController as EasyAdminController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use AppBundle\Entity\Contact;


class AdminController extends EasyAdminController
{
	/**
	 * @Route("/", name="easyadmin")
	 * @Route("/", name="admin")
	 */
	public function indexAction(Request $request)
    {
    	return parent::indexAction($request);
    }
    
    public function preUpdateCompanyEntity($entity)
    {
        //$sync = $this->container->get('mailchimp.sync');
        
        if($entity->getNewsletter()==true && $entity->getUserUnsubscription()==true) {
            $entity->setNewsletter(false);
        }
        
    }
    
    public function preUpdateContactEntity($entity)
    {        
        if($entity->getNewsletter()==true && $entity->getUserUnsubscription()==true) {
            $entity->setNewsletter(false);
        }
        
    }
    
    
    public function prePersistContactEntity($entity)
    {
    	/*if($entity->getNewsletter() && $entity->getEmail()!='') {
    		$sync = $this->container->get('mailchimp.sync');
    		$sync->newContact($entity); 
    	}*/
    }
    
    /*public function preUpdateContactEntity($entity)
    {
    	$sync = $this->container->get('mailchimp.sync');

    	if($entity->getNewsletter()==false) {
    		$sync->unsubscribeContact($entity);
    	}
    	$sync->updateContact($entity);
    }*/
    
    public function preDeleteContactEntity($entity)
    {
    	/*$sync = $this->container->get('mailchimp.sync');
    	$sync->delContact($entity);*/
    }
    
    public function createNewUserEntity()
    {
    	return $this->get('fos_user.user_manager')->createUser();
    }
    
    public function prePersistUserEntity($user)
    {
    	$this->get('fos_user.user_manager')->updateUser($user, false);
    }
    
    public function preUpdateUserEntity($user)
    {
    	$this->get('fos_user.user_manager')->updateUser($user, false);
    }
    
}