<?php

namespace AppBundle\Form;

use AppBundle\Entity\Title;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class SubscribeType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
		->add('firstname', TextType::class, [
			'translation_domain' => 'messages', 
			'required' => true, 
			'label' => 'form.label.firstname',
			'label_attr' => [
				'class' => 'sr-only',
			] ,
			'attr' => [
				'placeholder' => 'form.field.firstname', 
				'class' => 'form-control mb-2',
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
				'class' => 'sr-only',
			] ,
			'attr' => [
				'placeholder' => 'form.field.lastname', 
				'class' => 'form-control mb-2',
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
				'class' => 'sr-only',
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
		->add('save', SubmitType::class, [
			'label' => 'form.label.submit',
			'attr' => ['class' => 'btn btn-primary mb-2'],
		])
		;
	}

	public function setDefaultOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults(array(
				'error_bubbling' => false
		));
	}

	public function getName()
	{
		return 'subscribe_form';
	}
}