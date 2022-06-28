<?php

namespace App\Form;

use App\Entity\Club;
use App\Entity\User;
use App\Entity\Position;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $user=$options['data'];
        $builder
            ->add('userType',ChoiceType::class,[
                'label'=>false,
                'expanded'=>true,
                'multiple'=>false,
                'choices'=>[
                    'Jestem zawodnikiem'=>User::PLAYER_TYPE,
                    'Jestem managerem'=>User::MANAGER_TYPE
                ],
                'attr' => [
                    'class' => 'form-check-inline w-100 text-left',
                ],
            ])
            ->add('fullName',TextType::class,['label'=>'Imię i nazwisko','attr'=>['placeholder'=>'Imię i nazwisko']])
            ->add('birthDate',DateType::class,['label'=>'birthDate','widget' => 'single_text','placeholder'=>'DD-MM-YYYY'])
            ->add('city',TextType::class,['label'=>'Miejscowość', 'attr'=>array('placeholder'=>'Miejscowość')])
            ->add('club',EntityType::class,array(
                'placeholder'=>'Wybierz swój klub...',
                'class'=>Club::class,
                'label'=>'Klub',
                'attr'=>array('class'=>'selectpicker form-control','placeholder'=>'Wybierz klub')
                )
            )
            ->add('email',TextType::class,['label'=>'Adres e-mail','attr'=>['placeholder'=>'Adres e-mail']])
            ->add('plainPassword', RepeatedType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'type' => PasswordType::class,
                'mapped' => false,
                'options' => ['attr' => ['class' => 'password-field']],
                'first_options'  => ['label' => 'Podaj hasło','attr'=>['placeholder'=>'*****']],
                'second_options' => ['label' => 'Powtórz hasło','attr'=>['placeholder'=>'*****']],
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
            ])
            
            
            ->add('data_consent',CheckboxType::class,[
                'label'=>'Wyrażam zgodę na przetwarzanie danych',
                'constraints' => [
                    new IsTrue([
                        'message' => 'Musisz wyrazić zgodę',
                    ]),
                ],
            ])
            ->add('marketing_consent', CheckboxType::class, [
                'label'=>'Wyrażam zgodę na przetwarzanie danych w celach marketingowych',
                'constraints' => [
                    new IsTrue([
                        'message' => 'Musisz wyrazić zgodę',
                    ]),
                ],
            ])
            ->add('position',EntityType::class,array(
                'class'=>Position::class,
                'label'=>'Pozycja',
                'multiple'=>true,
                'attr'=>array('class'=>'selectpicker','placeholder'=>'Wybierz pozycję'),
                'row_attr' => [
                    'class' => 'player-field',
                ],
                )
            )
            ->add('yearbook',TextType::class,array(
                'label'=>'Rocznik',
                'attr'=>['placeholder'=>'Rocznik'],
                'disabled'=>true,
                'row_attr' => [
                    'class' => 'manager-field d-none',
                ],
                )
            )
            ->add('submit',SubmitType::class,array('label'=>'Załóż konto','attr'=>['class'=>'btn-primary']))
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
