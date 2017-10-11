<?php

namespace Tutorial\Part3;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->setAction($options['action'])
            ->setMethod($options['method'])
            ->add(
                'name',
                TextType::class,
                array(
                    'label' => 'Name',
                    'required' => true,
                )
            )
            ->add(
                'email',
                EmailType::class,
                array(
                    'label' => 'Email',
                    'required' => true,
                )
            )
            ->add(
                'save',
                SubmitType::class,
                array(
                    'label' => 'Save',
                    'attr' => array('class' => 'btn btn-default'),
                )
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => UserEntity::class,
            'method' => 'POST',
            'action' => null,
        ));
    }
}
