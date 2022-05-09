<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\TrainingSession;
use Symfony\Component\Form\AbstractType;
use App\Repository\PlayerManagerRepository;
use App\Repository\UserRepository;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class AssignPlayerToSessionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var TrainingSession $session */
        $session=$options['data'];
        $user=$session->getBuyer();
        $builder
            ->add('player',EntityType::class,array(
                'label'=>'Zawodnik',
                'class' => User::class,
                'choice_label' => 'fullName',
                'attr' => array('class' =>'selectpicker'),
                'placeholder'=>'Wybierz zawodnika którego chcesz przypisać do tej sessji',
                'query_builder' => function (UserRepository $er) use ($user) {
                    return $er->createQueryBuilder('u')
                        ->select('u')
                        ->join('u.player','pm')
                        ->andWhere('pm.manager = :user')
                        ->andWhere('pm.accepted = 1')
                        ->andWhere('pm.active = 1')
                        ->setParameter('user',$user);
                }
                )
            )
            ->add('submit',SubmitType::class,['label'=>'Zapisz'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => TrainingSession::class,
        ]);
    }
}
