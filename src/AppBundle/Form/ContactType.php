<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

class ContactType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
		->add('firstname', TextType::class, array('label' => 'form.label.firstname', 'attr' => array('placeholder' => 'form.field.firstname', 'class' => 'form-control', 'style' => 'margin-bottom:15px'),
				'constraints' => array(
						new NotBlank(array("message" => "Please provide your firstname")),
				)
		))
		->add('lastname', TextType::class, array('label' => 'form.label.lastname', 'attr' => array('placeholder' => 'form.field.lastname', 'class' => 'form-control', 'style' => 'margin-bottom:15px'),
				'constraints' => array(
						new NotBlank(array("message" => "Please provide your lastname")),
				)
		))
		->add('subject', TextType::class, array('label' => 'form.label.subject', 'attr' => array('placeholder' => 'Subject', 'class' => 'form-control', 'style' => 'margin-bottom:15px'),
				'constraints' => array(
						new NotBlank(array("message" => "Please give a Subject")),
				)
		))
		->add('email', EmailType::class, array('label' => 'form.label.email', 'attr' => array('placeholder' => 'form.field.email', 'class' => 'form-control', 'style' => 'margin-bottom:15px'),
				'constraints' => array(
						new NotBlank(array("message" => "Please provide a valid email")),
						new Email(array("message" => "Your email doesn't seems to be valid")),
				)
		))
		->add('message', TextareaType::class, array('label' => 'form.label.message', 'attr' => array('placeholder' => 'form.field.message', 'class' => 'form-control', 'style' => 'margin-bottom:15px'),
				'constraints' => array(
						new NotBlank(array("message" => "Please provide a message here")),
				)
		))
		->add('newsletter', CheckboxType::class, array('label' => 'form.label.newsletter', 'attr' => array('class' => 'form-check-input', 'style' => 'margin-bottom:15px')))

		->add('consent', CheckboxType::class, array('label' => 'form.label.consent', 'attr' => array('class' => 'form-check-input', 'style' => 'margin-bottom:15px')))
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
		return 'contact_form';
	}
}