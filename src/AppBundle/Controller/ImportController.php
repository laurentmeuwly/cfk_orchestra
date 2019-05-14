<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Port\Csv\CsvReader;
use Port\Excel\ExcelReader;
use Port\Reader\ArrayReader;
use Port\Doctrine\DoctrineWriter;
use Port\Filter\ValidatorFilter;
use Port\Steps\StepAggregator;
use Port\Steps\Step\MappingStep;
use Port\Steps\Step\ValueConverterStep;
use Port\ValueConverter\MappingValueConverter;
use Port\ValueConverter\DateTimeValueConverter;

class ImportController extends Controller
{
	/**
	 * @Route("/import", name="import")
	 */
	public function importAction(Request $request)
	{
		$form_outlook = $this->createForm('AppBundle\Form\OutlookType',null,array(
				// To set the action use $this->generateUrl('route_identifier')
				'action' => $this->generateUrl('import'),
				'method' => 'POST'
		));
		$form_winbiz = $this->createForm('AppBundle\Form\WinbizType',null,array(
				// To set the action use $this->generateUrl('route_identifier')
				'action' => $this->generateUrl('import'),
				'method' => 'POST'
		));
		$form = $this->createForm('AppBundle\Form\StdFileType',null,array(
				// To set the action use $this->generateUrl('route_identifier')
				'action' => $this->generateUrl('import'),
				'method' => 'POST'
		));
		
		$form_outlook->handleRequest($request);
		if ($form_outlook->isSubmitted() && $form_outlook->isValid()) {
			$this->outlookFile($request);
		}
		$form_winbiz->handleRequest($request);
		if ($form_winbiz->isSubmitted() && $form_winbiz->isValid()) {
			$this->winbizFile($request);
		}
		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()) {
			$this->stdFile($request);
		}
		
		return $this->render('::import.html.twig', array(
            'form_outlook' => $form_outlook->createView(),
			'form_winbiz' => $form_winbiz->createView(),
			'form' => $form->createView()
        ));
	}
	
	function writer(StepAggregator $workflow, MappingStep $mappingStep, FilterStep $filterStep = NULL, ConverterStep $converterStep = NULL)
	{
		$workflow->addStep($mappingStep);
		if($converterStep!=NULL) {
			$workflow->addStep($converterStep);
		}
		if($filterStep!=NULL) {
			$workflow->addStep($filterStep);
		}
		
		// Create a writer: you need Doctrine’s EntityManager.
		$em = $this->container->get('doctrine')->getManager();
		$doctrineWriter = new DoctrineWriter($em, 'AppBundle:Contact', ['email']);
		$doctrineWriter->disableTruncate();
		
		$workflow->addWriter($doctrineWriter);
		
		// Process the workflow
		$workflow->process();
		
		return true;
	}
	
	function outlookFile(Request $request)
	{
		$uploadedFile = $request->files->get('outlook');
		$path = $uploadedFile['file']->getRealPath();
		
		$file = new \SplFileObject($path);
		$xlsReader = new ExcelReader($file);
		
		// Tell the reader that the first row in the CSV file contains column headers
		$xlsReader->setHeaderRowNumber(0);
		
		$mappingStep = new MappingStep();
		$mappingStep->map('[Titre]', '[gender]');
		$mappingStep->map('[Prénom]', '[firstname]');
		$mappingStep->map('[Nom]', '[lastname]');
		$mappingStep->map('[Adressedemessagerie]', '[email]');
		//$mappingStep->map('[Télex]', '[language]');
		
		// Create the workflow from the reader
		$workflow = new StepAggregator($xlsReader);
		
		$this->writer($workflow, $mappingStep);
		
		return true;
	}
	
	function winbizFile(Request $request)
	{
		$uploadedFile = $request->files->get('winbiz');
		$path = $uploadedFile['file']->getRealPath();
		
		$file = new \SplFileObject($path);
		$csvReader = new CsvReader($file);
		
		// Tell the reader that the first row in the CSV file contains column headers
		$csvReader->setHeaderRowNumber(0);
		
		$mappingStep = new MappingStep();
		//$mappingStep->map('[Civilité]', '[gender]');
		$mappingStep->map('[AD_PRENOM]', '[firstname]');
		$mappingStep->map('[AD_NOM]', '[lastname]');
		$mappingStep->map('[AD_EMAIL]', '[email]');
		//$mappingStep->map('[AD_LANGUE]', '[language]');
		
		/*$filterStep = new FilterStep();
		$filterStep->add( function($row) { return true; } );
		
		$filter = new ValidatorFilter($validator);
		$filter->add('AD_EMAIL', new Assert\NotBlank());*/
		
		// Create the workflow from the reader
		$workflow = new StepAggregator($csvReader);
		
		$this->writer($workflow, $mappingStep);
		
		return true;
	}
	
	function stdFile(Request $request)
	{
		$uploadedFile = $request->files->get('std_file');
		$path = $uploadedFile['file']->getRealPath();
		
		// Create and configure the reader
		//$file = new \SplFileObject($this->get('kernel')->getRootDir().'/Resources/tmp/outlook_lm.csv');
		$file = new \SplFileObject($path);
		$csvReader = new CsvReader($file);
		
		// Tell the reader that the first row in the CSV file contains column headers
		$csvReader->setHeaderRowNumber(0);
		
		$mappingStep = new MappingStep();
		$mappingStep->map('[Civilité]', '[gender]');
		$mappingStep->map('[Prénom]', '[firstname]');
		$mappingStep->map('[Nom]', '[lastname]');
		$mappingStep->map('[Email]', '[email]');
		$mappingStep->map('[Langue]', '[language]');
		$mappingStep->map('[Newsletter]', '[newsletter]');
		
		// Create the workflow from the reader
		$workflow = new StepAggregator($csvReader);
		
		$this->writer($workflow, $mappingStep);
		
		return true;
	}
	
}