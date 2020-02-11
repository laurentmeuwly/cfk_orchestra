<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
//use ReCaptcha\ReCaptcha;
use AppBundle\Entity\Contact;
use AppBundle\Form\ContactType;

class CfkController extends Controller
{
    public function subscribeAction(Request $request)
    {
        // Create the form according to the FormType created previously.
        // And give the proper parameters
        $form = $this->createForm('AppBundle\Form\SubscribeType',null,array(
            'action' => $this->generateUrl('subscribe'),
            'method' => 'POST'
        ));

        if ($request->isMethod('POST')) {
            // Refill the fields in case the form is not valid.
            $form->handleRequest($request);
        }

        return $this->render('::subscribe.html.twig', array(
            'form' => $form->createView()
        ));
    }

    public function contactAction(Request $request)
    {
        $contact = new Contact();

        // Create the form according to the FormType created previously.
        // And give the proper parameters
        $form = $this->createForm(ContactType::class, $contact, array(
            'action' => $this->generateUrl('cfk_contact', [], UrlGeneratorInterface::ABSOLUTE_URL),
            'method' => 'POST',
            'locale' => $request->getLocale(),
        ));          

        if ($request->isMethod('POST')) {
            // Refill the fields in case the form is not valid.
            $form->handleRequest($request);

            //$recaptcha = new ReCaptcha('6LeIxAcTAAAAAGG-vFI1TnRWxMZNFuojJ4WifJWe');
            $resp = true;//$recaptcha->verify($request->request->get('g-recaptcha-response'), $request->getClientIp());
            
            //if (!$resp->isSuccess()) {
            if (!$resp) {
            	// Do something if the submit wasn't valid ! Use the message to show something
            	//$message = "The reCAPTCHA wasn't entered correctly. Go back and try it again." . "(reCAPTCHA said: " . $resp->error . ")";
            	foreach ($resp->getErrorCodes() as $code) {
            		echo '<kbd>' , $code , '</kbd> ';
            	}
            	// An error ocurred, handle
            	//var_dump($resp);
            } else {
            
	            if($form->isValid()){
	            	
	            	//Getting doctrine manager
	            	$em = $this->container->get('doctrine')->getManager();
	            	// Turning off doctrine default logs queries for saving memory
	            	$em->getConnection()->getConfiguration()->setSQLLogger(null);
	            	
	            	$formData = $form->getData();

                    // if email already exists do not save the contact
                    $contactExists = $em->getRepository('AppBundle:Contact')->findByEmail($formData["email"]);
                    if(!$contactExists) {

                        // save the contact
                        $contact = new Contact();
                        $contact->setEmail($formData["email"]);
                        $contact->setFirstname($formData["firstname"]);
                        $contact->setLastname($formData["lastname"]);
                        $contact->setNewsletter($formData["newsletter"]==true ? 1 :0);
                        $contact->setMessage($formData["message"]);
                        $contact->setLanguage($request->getLocale());
                        //$contact->setTitle($formData["title"]);
                        
                        /*if($contact->getNewsletter() && $contact->getEmail()!='') {
                            $sync = $this->container->get('mailchimp.sync');
                            $sync->newContact($contact);
                        }*/
                        
                        $em->persist($contact);
                        $em->flush();
                        $em->clear();
                    }
	            	
	                // Always send mail
	                if($this->sendEmail($formData)){
                        // Everything OK, redirect to wherever you want ! :
                        //$route = $request->getLocale() . '/thanks';
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
        $myappContactMail = 'crm@crm.kaeserberg.ch';
        $myappContactPassword = 'oUrg!161';

        //$myappDestMail = 'info@kaeserberg.ch';
        $myappDestMail = 'laurent@lmeuwly.ch';
        $myappCCMail = 'laurent@lmeuwly.ch';
        
        // In this case we'll use the Gmail mail services.
        // If your service is another, then read the following article to know which smpt code to use and which port
        // http://ourcodeworld.com/articles/read/14/swiftmailer-send-mails-from-php-easily-and-effortlessly
        /*$transport = \Swift_SmtpTransport::newInstance('smtp.gmail.com', 465,'ssl')
            ->setUsername($myappContactMail)
            ->setPassword($myappContactPassword);*/
        $transport = \Swift_SmtpTransport::newInstance('janus.kreativmedia.ch', 465,'ssl')
            ->setUsername($myappContactMail)
            ->setPassword($myappContactPassword);


        $mailer = \Swift_Mailer::newInstance($transport);
        
        $message = \Swift_Message::newInstance("Nouveau contact via site web")
        ->setFrom(array($myappContactMail => $myappContactMail))
        ->setReplyTo($data["email"])
        ->setTo(array($myappDestMail => $myappDestMail))
        ->setBcc(array($myappCCMail => $myappCCMail))
        ->setBody(
            "Voici les détails du Contact qui a été généré depuis votre site Internet:<br/><br/>" 
            . "Prénom : " . $data["firstname"] . "<br/>"
            . "Nom : " . $data["lastname"] . "<br/>"
            . "Email : " . $data["email"] . "<br/>"
            . "Newsletter : " . $data["newsletter"] . "<br/>"
            . "<br/>Message :<br/>" . $data["message"]
            
            , 'text/html');
        
        return $mailer->send($message);
    }
    
}
