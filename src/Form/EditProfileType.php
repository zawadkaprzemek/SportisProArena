<?php

namespace App\Form;

use App\Entity\Club;
use App\Entity\User;
use App\Entity\Position;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class EditProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $user=$options['data'];
        $builder
            ->add('firstName',TextType::class,['label'=>'firstName'])
            ->add('lastName',TextType::class,['label'=>'lastName'])
            ->add('birthDate',DateType::class,['label'=>'birthDate','widget' => 'single_text'])
            ->add('city',TextType::class,['label'=>'city'])
            //->add('image')
            ->add('club',EntityType::class,array(
                'class'=>Club::class,
                'label'=>'club',
                'attr'=>array('class'=>'selectpicker')
                )
            )
            ->add('submit',SubmitType::class,array('label'=>'Zapisz','attr'=>['class'=>'btn-primary']))
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
