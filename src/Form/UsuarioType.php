<?php

namespace App\Form;

use App\Entity\Usuario;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class UsuarioType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username')
            //->add('usernameCanonical')
            ->add('email', EmailType::class)
            //->add('emailCanonical')
            ->add('enabled')
            //->add('salt')
            ->add('password')
            //->add('lastLogin')
            //->add('confirmationToken')
            //->add('passwordRequestedAt')
            ->add('roles', ChoiceType::class, array(
                'choices'  => array(
                    'Usuario' => "ROLE_USER",
                    'Admin' => "ROLE_ADMIN",
                    'Super Admin' => "ROLE_SUPER_ADMIN",
                ),
                "expanded" => true,
                "multiple" => true,
            ))
            ->add('nome')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Usuario::class,
        ]);
    }
}
