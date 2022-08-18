<?php

namespace App\Form;

use App\Entity\TrainingUnit;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class TrainingScenarioType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $unit=$options['data'];
        $builder
            ->add('seriesCount',NumberType::class,[
                'label'=>'Ilość serii',
                'data'=>($unit->getSeriesCount()?? 1),
                'html5'=>true,
                'attr'=>[
                    'step'=>1,
                    'min'=>1
                ]
            ])

            ->add('submit',SubmitType::class,[
                'label'=>'Konfiguruj serie'
            ])
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => TrainingUnit::class,
        ]);
    }
}
