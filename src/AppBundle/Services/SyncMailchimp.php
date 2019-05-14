<?php

namespace AppBundle\Services;

use Welp\MailchimpBundle\Event\SubscriberEvent;
use Welp\MailchimpBundle\Subscriber\Subscriber;
use AppBundle\Entity\Contact;


class SyncMailchimp
{
	private $event_dispatcher;
	private $listId;
	
	public function __construct($event_dispatcher)
	{
		$this->event_dispatcher = $event_dispatcher;
		//CFK Newsletter: d35d5000c9
		//Contacts CFK: 57b0fba342
		//Test: f1723d9027
		$this->listId = '57b0fba342';
	}
	
	public function newContact(Contact $contact)
	{
		$subscriber = new Subscriber($contact->getEmail(), [
				'FNAME' => $contact->getFirstname(),
				'LNAME' => $contact->getLastname(),
		], [
				'language' => strtolower($contact->getLanguage()),
		]);
		
		$this->event_dispatcher->dispatch(
				SubscriberEvent::EVENT_SUBSCRIBE,
				new SubscriberEvent($this->listId, $subscriber)
				);
	}
	
	public function unsubscribeContact(Contact $contact)
	{
		$subscriber = new Subscriber($contact->getEmail());
		
		$this->event_dispatcher->dispatch(
				SubscriberEvent::EVENT_UNSUBSCRIBE,
				new SubscriberEvent($this->listId, $subscriber)
				);
	}
	
	public function updateContact(Contact $contact)
	{
		$subscriber = new Subscriber($contact->getEmail(), [
				'FNAME' => $contact->getFirstname(),
				'LNAME' => $contact->getLastname(),
		], [
				'language' => strtolower($contact->getLanguage()),
		]);
	
		$this->event_dispatcher->dispatch(
				SubscriberEvent::EVENT_UPDATE,
				new SubscriberEvent($this->listId, $subscriber)
				);
	}
	
	public function delContact(Contact $contact)
	{
		$subscriber = new Subscriber($contact->getEmail());
	
		$this->event_dispatcher->dispatch(
				SubscriberEvent::EVENT_DELETE,
				new SubscriberEvent($this->listId, $subscriber)
				);
	}

}
