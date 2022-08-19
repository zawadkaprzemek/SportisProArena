<?php

namespace App\Form;

use App\Entity\Club;
use App\Entity\Position;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdminUserEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $user=$options['data'];
        $builder
            ->add('fullName',TextType::class,['label'=>'Imię i nazwisko'])
            ->add('birthDate',DateType::class,['label'=>'birthDate','widget' => 'single_text'])
            ->add('city',TextType::class,['label'=>'city'])
            //->add('image')
            ->add('club',EntityType::class,array(
                    'class'=>Club::class,
                    'label'=>'club',
                    'attr'=>array('class'=>'selectpicker')
                )
            )
            ->add('submit',SubmitType::class,array('label'=>'Zapisz zmiany','attr'=>['class'=>'btn-primary']))
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
                )
                ->add('expert_manager',CheckboxType::class,[
                    'label'=>'Trener expert',
                    'mapped'=>false,
                    'required'=>false,
                    'data'=>$user->hasRole("ROLE_MANAGER_EXPERT")
                ])
            ;
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
