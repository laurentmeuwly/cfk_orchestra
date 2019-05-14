<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

class OutlookType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('file', FileType::class, array('label' => 'form.label.file.outlook', 'attr' => array('placeholder' => 'form.field.file.outlook', 'class' => 'form-control', 'style' => 'margin-bottom:15px'),
                'constraints' => array(
                    new NotBlank(array("message" => "Please upload a file")),
                )
            ))
            ->add('info', TextType::class, array('label' => 'form.label.info', 'attr' => array('placeholder' => 'form.field.info', 'class' => 'form-control', 'style' => 'margin-bottom:15px')
            ))
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
        return 'outlook_form';
    }
}