<?php

namespace AppBundle\Command;
 
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\ProgressBar;
use \DateTime;

use AppBundle\Entity\Company;
use AppBundle\Entity\Contact;

 
class ImportCommand extends ContainerAwareCommand
{
 
    protected function configure()
    {
        // Name and description for app/console command
        $this
        ->setName('import:csv')
        ->setDescription('Import data from CSV file')
        ->addArgument('entity', InputArgument::REQUIRED, 'An entity name');
    }
    
   protected function execute(InputInterface $input, OutputInterface $output)
    {
        // Showing when the script is launched
        $now = new \DateTime();
        $output->writeln('<comment>Start : ' . $now->format('d-m-Y G:i:s') . ' ---</comment>');
        
        // Importing CSV on DB via Doctrine ORM
        $entity = $input->getArgument('entity');
        switch ($entity) {
        	case 'company':
        		$this->importCompanies($input, $output);
        		break;
        	case 'contact':
        		$this->importContacts($input, $output);
        		break;
        	case 'lead':
        		$this->importLeads($input, $output);
        		break;
        	default:
        		$output->writeln('Nothing to import');
        }
        
        // Showing when the script is over
        $now = new \DateTime();
        $output->writeln('<comment>End : ' . $now->format('d-m-Y G:i:s') . ' ---</comment>');
    }
    
    protected function importCompanies(InputInterface $input, OutputInterface $output)
    {
        // Getting php array of data from CSV
        $data = $this->get('companies.csv', $input, $output);
        
        // Getting doctrine manager
        $em = $this->getContainer()->get('doctrine')->getManager();
        // Turning off doctrine default logs queries for saving memory
        $em->getConnection()->getConfiguration()->setSQLLogger(null);
        
        // Define the size of record, the frequency for persisting the data and the current index of records
        $size = count($data);
        $batchSize = 20;
        $i = 1;
        
        // Starting progress
        $progress = new ProgressBar($output, $size);
        $progress->start();

        // Processing on each row of data
        foreach($data as $row) {

        	$company = $em->getRepository('AppBundle:Company')
                       ->findOneByZohoId($row['id']);
						 
			// If the company does not exist we create one
            if(!is_object($company)){
            	$company = new Company();
            	$company->setZohoId($row['id']);
            	
            	if($row['category']!='') {
            		$category = $em->getRepository('AppBundle:Category')
            		->findOneByName($row['category']);
            		$company->addCategory($category);
            	}
            }
            
			// Updating info
            $company->setCreatedAt(DateTime::createFromFormat('Y-m-d H:i:s', $row['created_at']));
            $company->setUpdatedAt(DateTime::createFromFormat('Y-m-d H:i:s', $row['modified_at']));
            $company->setName($row['name']);
            $company->setPhone($row['phone']);
            $company->setWebsite($row['website']);
            $company->setFax($row['fax']);
            $company->setAddress($row['address']);
            $company->setPostalcode($row['postcode']);
            $company->setCity($row['city']);
            $company->setCountrycode($row['country']);
            $company->setDescription($row['description']);
            
            if($row['prospect']=='true') {
            	$company->setIsProspect(1);
            } else {
            	$company->setIsProspect(0);
            }	
			
	
			// Persisting the current company
            $em->persist($company);
            
			// Each 20 categories persisted we flush everything
            if (($i % $batchSize) === 0) {
 
                $em->flush();
				// Detaches all objects from Doctrine for memory save
                $em->clear();
                
				// Advancing for progress display on console
                $progress->advance($batchSize);
				
                $now = new \DateTime();
                $output->writeln(' of companies imported ... | ' . $now->format('d-m-Y G:i:s'));
 
            }
 
            $i++;
 
        }
		
		// Flushing and clear data on queue
        $em->flush();
        $em->clear();
		
		// Ending the progress bar process
        $progress->finish();
    }
    
    protected function importContacts(InputInterface $input, OutputInterface $output)
    {
    	// Getting php array of data from CSV
    	$data = $this->get('contacts.csv', $input, $output);
    	
    	// Getting doctrine manager
    	$em = $this->getContainer()->get('doctrine')->getManager();
    	// Turning off doctrine default logs queries for saving memory
    	$em->getConnection()->getConfiguration()->setSQLLogger(null);
    	
    	// Define the size of record, the frequency for persisting the data and the current index of records
    	$size = count($data);
    	$batchSize = 1;
    	$i = 1;
    	
    	// Starting progress
    	$progress = new ProgressBar($output, $size);
    	$progress->start();
    	
    	// Processing on each row of data
    	foreach($data as $row) {
    		
    		$contact = $em->getRepository('AppBundle:Contact')
    		->findOneByZohoId($row['id']);
    			
    		// If the contact does not exist we create one
    		if(!is_object($contact)){
    			//sleep(1);
    			$contact = new Contact();
    			$contact->setZohoId($row['id']);
    			
    			if($row['category_cfk']!='') {
    				$category = $em->getRepository('AppBundle:Category')
    				->findOneByName($row['category_cfk']);
    				if(is_object($category)) {
    					$contact->addCategory($category);
    				}
    			}
    		}
    	
    		// Updating info
    		$contact->setCreatedAt(DateTime::createFromFormat('Y-m-d H:i:s', $row['created_at']));
    		$contact->setUpdatedAt(DateTime::createFromFormat('Y-m-d H:i:s', $row['modified_at']));
    		$contact->setFirstname($row['firstname']);
    		$contact->setLastname($row['lastname']);
    		$contact->setGender($row['gender']);
    		$contact->setAddress($row['address']);
    		$contact->setAddress2($row['address2']);
    		$contact->setPostalcode($row['postcode']);
    		$contact->setCity($row['city']);
    		$contact->setCountrycode($row['country']);
    		$contact->setPhone($row['phone']);
    		$contact->setMessage($row['message']);
    		$contact->setLanguage($row['language']);
    		
    		// email should be NULL or unique
    		/*if($row['email']!='') {
    			$contactEmailAlreadyExists = $em->getRepository('AppBundle:Contact')->findOneByEmail($row['email']);
    			if(is_object($contactEmailAlreadyExists)) {
    				$contactEmailAlreadyExists->setEmail($contactEmailAlreadyExists->getEmail() . ' (doublon 1)');
    				$em->persist($contactEmailAlreadyExists);
    				$extEmail = $row['email'] . ' (doublon 2)';
    				$contact->setEmail($extEmail);
    			} else {
    				$contact->setEmail($row['email']);
    			}
    		}*/
    		
    		if($row['newsletter old']=='true' || $row['newsletter old']=='Oui') {
    			$contact->setNewsletter(1);
    		} else {
    			$contact->setNewsletter(0);
    		}
    		
    		if($row['prospect']=='true') {
    			$contact->setIsProspect(1);
    		} else {
    			$contact->setIsProspect(0);
    		}
    		
    		if($row['source']!='') {
    			$source = $em->getRepository('AppBundle:Source')
    			->findOneByName($row['source']);
    			$contact->setSource($source);
    		}
    		
    		if($row['company']!='') {
    			$company = $em->getRepository('AppBundle:Company')
    			->findOneByZohoId($row['company']);
    			$contact->setCompany($company);
    		}
    	
    		// Persisting the current contact
    		$em->persist($contact);
    	
    		// Each 100 products persisted we flush everything
    		if (($i % $batchSize) === 0) {
    	
    			$em->flush();
    			// Detaches all objects from Doctrine for memory save
    			$em->clear();
    	
    			// Advancing for progress display on console
    			$progress->advance($batchSize);
    	
    			$now = new \DateTime();
    			$output->writeln(' of contacts imported ... | ' . $now->format('d-m-Y G:i:s'));
    	
    		}
    	
    		$i++;
    	
    	}
  	
    	// Flushing and clear data on queue
    	$em->flush();
    	$em->clear();
    	
    	// Ending the progress bar process
    	$progress->finish();
    }
    
    protected function importLeads(InputInterface $input, OutputInterface $output)
    {
    	// Getting php array of data from CSV
    	$data = $this->get('leads.csv', $input, $output);
    	
    	// Getting doctrine manager
    	$em = $this->getContainer()->get('doctrine')->getManager();
    	// Turning off doctrine default logs queries for saving memory
    	$em->getConnection()->getConfiguration()->setSQLLogger(null);
    	
    	// Define the size of record, the frequency for persisting the data and the current index of records
    	$size = count($data);
    	$batchSize = 50;
    	$i = 1;
    	$setEmail = false;
    	
    	// Starting progress
    	$progress = new ProgressBar($output, $size);
    	$progress->start();
    	
    	// Processing on each row of data
    	foreach($data as $row) {
    	
    		if($row['company']!=$row['name']) {
    			$name = $row['company'] . ' ' . $row['name'];
    			if($row['company']=='Commune') {
    				$category = $em->getRepository('AppBundle:Category')->findOneByName('Administrations communales');
    				$setEmail = true;
    			} elseif($row['company']=='Fan Club') {
    				$category = $em->getRepository('AppBundle:Category')->findOneByName('Fan Club voiture');
    				$setEmail = true;
	    		} elseif($row['company']=='office du tourisme') {
	    			$category = $em->getRepository('AppBundle:Category')->findOneByName('Organisations touristiques');
	    			$setEmail = true;
	    		} elseif($row['company']=='concours à Fribourg Centre, oct 2013') {
	    			// here we have contact, and not companies, so break the loop
	    			break;
	    			//$category = $em->getRepository('AppBundle:Category')->findOneByName('Concours');
	    		} elseif($row['firstname']!=''){
	    			// here we have a company and a contact
	    			$name = $row['company'];
	    		}
	    		
    		} else {
    			$name = $row['name'];
    		}
    		
    		$leads = $em->getRepository('AppBundle:Company')
    		->findOneByName($name);
    		
    		// If the company does not exist we create one
    		if(!is_object($leads)){
    			$leads = new Company();
    			$leads->setName($name);
    			$leads->setCreatedAt(DateTime::createFromFormat('Y-m-d H:i:s', $row['created_at']));
    			$leads->setUpdatedAt(DateTime::createFromFormat('Y-m-d H:i:s', $row['modified_at']));
    			//$leads->setZohoId($row['id']);
    			 
    			
    		}
    		
    		// Updating info
    		if(isset($category) && $category!='') {
    			$cat = $em->getRepository('AppBundle:Category')
    			->findOneByName($category);
    			if(is_object($cat)){
    				$leads->addCategory($cat);
    			}
    		}
    		$leads->setPhone($row['phone']);
    		$leads->setWebsite($row['website']);
    		$leads->setFax($row['fax']);
    		$leads->setAddress($row['address']);
    		$leads->setPostalcode($row['postcode']);
    		$leads->setCity($row['city']);
    		$leads->setCountrycode($row['country']);
    		$leads->setDescription($row['description']);
    		$leads->setIsProspect(1);
    		
    		if($setEmail && $row['email'])
    		{
    			$leads->setEmail($row['email']);
    			$leads->setNewsletter(1);
    		}
    			
    		
    		// Persisting the current company
    		$em->persist($leads);
    		
    		// Each 50 leads persisted we flush everything
    		if (($i % $batchSize) === 0) {
    		
    			$em->flush();
    			// Detaches all objects from Doctrine for memory save
    			$em->clear();
    		
    			// Advancing for progress display on console
    			$progress->advance($batchSize);
    		
    			$now = new \DateTime();
    			$output->writeln(' of leads imported ... | ' . $now->format('d-m-Y G:i:s'));
    		
    		}
    		
    		$i++;
    		
    	}
    		
    	// Flushing and clear data on queue
    	$em->flush();
    	$em->clear();
    		
    	// Ending the progress bar process
    	$progress->finish();
    }
 
    protected function get(String $file, InputInterface $input, OutputInterface $output) 
    {
        // Getting the CSV from filesystem
        $fileName = 'docs/'. $file;
        
        // Using service for converting CSV to PHP Array
        $converter = $this->getContainer()->get('import.csvtoarray');
        $data = $converter->convert($fileName, ',');
        
        return $data;
    }
    
}