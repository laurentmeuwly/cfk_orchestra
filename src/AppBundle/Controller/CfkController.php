<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use ReCaptcha\ReCaptcha;
use AppBundle\Entity\Contact;

class CfkController extends Controller
{
    public function contactAction(Request $request)
    {
        // Create the form according to the FormType created previously.
        // And give the proper parameters
        $form = $this->createForm('AppBundle\Form\ContactType',null,array(
            // To set the action use $this->generateUrl('route_identifier')
            'action' => $this->generateUrl('cfk_contact'),
            'method' => 'POST'
        ));

        if ($request->isMethod('POST')) {
            // Refill the fields in case the form is not valid.
            $form->handleRequest($request);

            $recaptcha = new ReCaptcha('6LeIxAcTAAAAAGG-vFI1TnRWxMZNFuojJ4WifJWe');
            $resp = $recaptcha->verify($request->request->get('g-recaptcha-response'), $request->getClientIp());
            
            if (!$resp->isSuccess()) {
            	// Do something if the submit wasn't valid ! Use the message to show something
            	//$message = "The reCAPTCHA wasn't entered correctly. Go back and try it again." . "(reCAPTCHA said: " . $resp->error . ")";
            	foreach ($resp->getErrorCodes() as $code) {
            		echo '<kbd>' , $code , '</kbd> ';
            	}
            	// An error ocurred, handle
            	var_dump($resp);
            }else{
            
	            if($form->isValid()){
	            	
	            	//Getting doctrine manager
	            	$em = $this->container->get('doctrine')->getManager();
	            	// Turning off doctrine default logs queries for saving memory
	            	$em->getConnection()->getConfiguration()->setSQLLogger(null);
	            	
	            	$formData = $form->getData();
	            	// save the contact
	            	$contact = new Contact();
	            	$contact->setEmail($formData["email"]);
	            	$contact->setFirstname($formData["firstname"]);
	            	$contact->setLastname($formData["lastname"]);
	            	$contact->setNewsletter($formData["newsletter"]==true ? 1 :0);
	            	
	            	if($contact->getNewsletter() && $contact->getEmail()!='') {
	            		$sync = $this->container->get('mailchimp.sync');
	            		$sync->newContact($contact);
	            	}
	            	
	            	$em->persist($contact);
	            	$em->flush();
	            	$em->clear();
	            	
	                // Send mail
	                if($this->sendEmail($formData)){
	                    // Everything OK, redirect to wherever you want ! :
	                    return $this->redirectToRoute('thanks');
	                }else{
	                    // An error ocurred, handle
	                    var_dump("Errooooor :(");
	                }
	            }
	            
            }
        }

        return $this->render('::contact.html.twig', array(
            'form' => $form->createView()
        ));
    }
    
    public function thanksAction(Request $request)
    {
    	return $this->render('::thanks.html.twig');
    }

    private function sendEmail($data){
        $myappContactMail = 'laurent@lmeuwly.ch';
        $myappContactPassword = 'aoR49lk!23';
        
        // In this case we'll use the Gmail mail services.
        // If your service is another, then read the following article to know which smpt code to use and which port
        // http://ourcodeworld.com/articles/read/14/swiftmailer-send-mails-from-php-easily-and-effortlessly
        $transport = \Swift_SmtpTransport::newInstance('smtp.gmail.com', 465,'ssl')
            ->setUsername($myappContactMail)
            ->setPassword($myappContactPassword);

        $mailer = \Swift_Mailer::newInstance($transport);
        
        $message = \Swift_Message::newInstance("Demande de contact: ". $data["subject"])
        ->setFrom(array($data["email"] => $data["firstname"] . ' ' . $data["lastname"]))
        ->setTo(array(
            $myappContactMail => $myappContactMail
        ))
        ->setBody($data["message"]."<br>ContactMail :".$data["email"]);
        
        return $mailer->send($message);
    }
    
}
