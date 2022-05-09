<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;


class PasswordChangeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('oldPassword',PasswordType::class,array('label'=>'Obecne hasło'))
            ->add('plainPassword', RepeatedType::class, array(
                'type' => PasswordType::class,
                'first_options' => [
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Wprowadź hasło',
                        ]),
                        new Length([
                            'min' => 8,
                            'minMessage' => 'Twoje hasło musi mieć conajmniej {{ limit }} znaków',
                            // max length allowed by Symfony for security reasons
                            'max' => 4096,
                        ]),
                    ],
                    'label' => 'Nowe hasło',
                ],
                'second_options' => [
                    'label' => 'Powtórz nowe hasło',
                ],
                'invalid_message' => 'Hasła są różne',
                // Instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
            ))
            ->add('submit',SubmitType::class,array('label'=>'Zapisz'))
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
