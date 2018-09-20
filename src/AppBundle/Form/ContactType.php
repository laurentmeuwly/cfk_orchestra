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
            ->add('firstname', TextType::class, array('attr' => array('placeholder' => 'Your firstname'),
                'constraints' => array(
                    new NotBlank(array("message" => "Please provide your firstname")),
                )
            ))
            ->add('lastname', TextType::class, array('attr' => array('placeholder' => 'Your lastname'),
            	'constraints' => array(
            			new NotBlank(array("message" => "Please provide your lastname")),
            	)
            ))
            ->add('subject', TextType::class, array('attr' => array('placeholder' => 'Subject'),
                'constraints' => array(
                    new NotBlank(array("message" => "Please give a Subject")),
                )
            ))
            ->add('email', EmailType::class, array('attr' => array('placeholder' => 'Your email address'),
                'constraints' => array(
                    new NotBlank(array("message" => "Please provide a valid email")),
                    new Email(array("message" => "Your email doesn't seems to be valid")),
                )
            ))
            ->add('message', TextareaType::class, array('attr' => array('placeholder' => 'Your message here'),
                'constraints' => array(
                    new NotBlank(array("message" => "Please provide a message here")),
                )
            ))
            ->add('newsletter', CheckboxType::class, array('label' => 'Newsletter ?', 'attr' => array('label' => 'Newsletter ?')))
        ;
    }

    public function setDefaultOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'error_bubbling' => true
        ));
    }

    public function getName()
    {
        return 'contact_form';
    }
}