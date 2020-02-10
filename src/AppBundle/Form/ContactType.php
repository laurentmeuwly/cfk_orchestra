<?php

namespace AppBundle\Form;

use AppBundle\Entity\Title;
use AppBundle\Repository\TitleRepository;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;


class ContactType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$lang = $options['locale'];
		if($lang=='de') {
			$optionTitles = ['Herr', 'Frau'];
		} elseif($lang=='en') {
			$optionTitles = ['Mister', 'Madam'];
		} else {
			$optionTitles = ['Monsieur', 'Madame'];
		}

		$builder
		->add('title', EntityType::class, [
			'translation_domain' => 'messages', 
			'required' => true, 
			'label' => 'form.label.title',
			'label_attr' => [
				'class' => 'col-sm-3 col-md-2 col-form-label',
			] ,
			'class' => Title::class,
			'query_builder' => function (TitleRepository $er) use ($optionTitles) {
				return $er->createQueryBuilder('t')
					->where('t.title IN (:titles)')
					->setParameters(array('titles' => $optionTitles));
			}, 
		])
		->add('firstname', TextType::class, [
			'translation_domain' => 'messages', 
			'required' => true, 
			'label' => 'form.label.firstname',
			'label_attr' => [
				'class' => 'col-sm-3 col-md-2 col-form-label',
			] ,
			'attr' => [
				'placeholder' => 'form.field.firstname', 
				'class' => 'form-control',
			],
			'constraints' => [
				new NotBlank(["message" => "Please provide your firstname"]),
			]
		])
		->add('lastname', TextType::class, [
			'translation_domain' => 'messages', 
			'required' => true, 
			'label' => 'form.label.lastname',
			'label_attr' => [
				'class' => 'col-sm-3 col-md-2 col-form-label',
			] ,
			'attr' => [
				'placeholder' => 'form.field.lastname', 
				'class' => 'form-control',
			],
			'constraints' => [
				new NotBlank(["message" => "Please provide your lastname"]),
			]
		])
		->add('email', EmailType::class, [
			'translation_domain' => 'messages', 
			'required' => true, 
			'label' => 'form.label.email',
			'label_attr' => [
				'class' => 'col-sm-3 col-md-2 col-form-label',
			] ,
			'attr' => [
				'placeholder' => 'form.field.email', 
				'class' => 'form-control',
			],
			'constraints' => [
				new NotBlank(array("message" => "Please provide a valid email")),
				new Email(array("message" => "Your email doesn't seems to be valid")),
			]
		])
		->add('message', TextareaType::class, [
			'translation_domain' => 'messages', 
			'required' => true, 
			'label' => 'form.label.message',
			'label_attr' => [
				'class' => 'col-sm-3 col-md-2 col-form-label',
			] ,
			'attr' => [
				'placeholder' => 'form.field.message', 
				'class' => 'form-control',
			],
			'constraints' => [
				new NotBlank(["message" => "Please provide a message here"]),
			]
		])
		->add('newsletter', CheckboxType::class, [
			'translation_domain' => 'messages', 
			'required' => false, 
			'label' => 'form.label.newsletter',
			'label_attr' => [
				'class' => 'custom-control-label',
			] ,
			'attr' => [
				'class' => 'custom-control-input',
			]
		])

		//->add('consent', CheckboxType::class, array('label' => 'form.label.consent', 'attr' => array('class' => 'form-check-input', 'style' => 'margin-bottom:15px')))
		->add('save', SubmitType::class, [
			'label' => 'form.label.submit',
			'attr' => ['class' => 'big-button'],
		])
		->add('reset', ResetType::class, [
			'label' => 'form.label.reset',
			'attr' => ['class' => 'big-button secondary'],
		])
		;
	}

	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setRequired('locale');
		//$resolver->setAllowedTypes('user', array(User::class, 'int'));
	}

	public function setDefaultOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults(array(
				'error_bubbling' => false
		));
	}

	public function getName()
	{
		return 'contact_form';
	}
}