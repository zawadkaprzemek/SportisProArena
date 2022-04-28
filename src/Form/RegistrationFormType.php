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
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $user=$options['data'];
        $builder
            ->add('firstName',TextType::class,['label'=>'firstName'])
            ->add('lastName',TextType::class,['label'=>'lastName'])
            ->add('birthDate',DateType::class,['label'=>'birthDate','widget' => 'single_text'])
            ->add('city',TextType::class,['label'=>'city'])
            ->add('club',EntityType::class,array(
                'placeholder'=>'select_your_club',
                'class'=>Club::class,
                'label'=>'club',
                'attr'=>array('class'=>'selectpicker')
                )
            )
            ->add('email',TextType::class,['label'=>'email'])
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 8,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
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
            
            
            
            ->add('submit',SubmitType::class,array('label'=>'Rejestracja','attr'=>['class'=>'btn-primary']))
        ;

        if($user->getUserType()==User::PLAYER_TYPE)
        {
            $builder
            ->add('position',EntityType::class,array(
                'class'=>Position::class,
                'label'=>'position',
                'multiple'=>true,
                'attr'=>array('class'=>'selectpicker')
                )
            );
        }elseif($user->getUserType()==User::MANAGER_TYPE){
            $builder
            ->add('yearbook',TextType::class,array(
                'label'=>'yearbook',
                )
            );
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
