<?php

namespace App\Form;

use App\Entity\TrainingUnit;
use App\Entity\TrainingDictionary;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class TrainingUnitType extends AbstractType
{
    private $em;
    private $repo;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
        $this->repo=$this->em->getRepository(TrainingDictionary::class);
    }


    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        
        $unit=$options['data'];

            $builder
            ->add('name',TextType::class,[
                'label'=>'Nazwa',
                'row_attr'=>['class'=>'col-md-6 mt-3'],
                ])
            ->add('ageCategory',ChoiceType::class,[
                'label'=>'Kategoria wiekowa',
                'label_html'=>true,
                'label_attr'=>['class'=>'unit-form-input'],
                'choices'=>[
                    '<span class="icon icon-youth"></span><p>Kategoria młodzieżowa</p>'=>'youth',
                    '<span class="icon icon-open"></span><p>Kategoria open</p>'=>'open',
                ],
                'row_attr'=>['class'=>'form-check-inline col-12 hide-inputs'],
                'attr'=>['data-show'=>'#training_unit_trainingType'],
                'expanded'=>true
            ])
            ->add('trainingType',ChoiceType::class,[
                'label'=>'Typ treningu',
                'label_html'=>true,
                'label_attr'=>['class'=>'unit-form-input'],
                'choices'=>[
                    '<span class="icon icon-solo"></span><p>Trening indywidualny</p>'=>'solo',
                    '<span class="icon icon-pair"></span><p>Trening w parze</p>'=>'pair',
                ],
                'row_attr'=>['class'=>'form-check-inline col-12 hide-inputs'.($unit->getId()==null? ' d-none': '')],
                'attr'=>['data-show'=>'#training_unit_test'],
                'expanded'=>true
                ])
            ->add('test',ChoiceType::class,[
                'label'=>false,
                'label_html'=>true,
                'label_attr'=>['class'=>'unit-form-input'],
                'choices'=>[
                    '<span class="icon icon-test"></span><p>Test</p>'=>'test',
                    '<span class="icon icon-training"></span><p>Trening</p>'=>'training',
                ],
                'row_attr'=>['class'=>'form-check-inline col-12 hide-inputs'.($unit->getId()==null? ' d-none': '')],
                'attr'=>['data-show'=>'#training_unit_trainingGroup'],
                'expanded'=>true
            ])
            ->add('trainingGroup',ChoiceType::class,[
                'label'=>'Grupa',
                'label_html'=>true,
                'label_attr'=>['class'=>'unit-form-input no-icon'],
                'choices'=>$this->getChoicesFromDictionary('trainingGroup'),
                'row_attr'=>['class'=>'form-check-inline col-12 hide-inputs'.($unit->getId()==null? ' d-none': '')],
                'attr'=>['data-show'=>'#training_unit_trainingSubGroupsAgeCategories'],
                'expanded'=>true
            ])
            ->add('trainingSubGroupsAgeCategories',ChoiceType::class,[
                'label'=>'Kategoria wiekowa',
                'label_html'=>true,
                'label_attr'=>['class'=>'unit-form-input no-icon'],
                'choices'=>$this->getChoicesFromDictionary('trainingSubGroups'),
                'row_attr'=>['class'=>'form-check-inline col-12 hide-inputs'.($unit->getId()==null? ' d-none': '')],
                'attr'=>['data-show'=>'#training_unit_trainingSubGroupsLevels'],
                'expanded'=>true,
                'multiple'=>true
            ]
            )
            ->add('trainingSubGroupsLevels',ChoiceType::class,[
                'label'=>'Poziom trudności',
                'label_html'=>true,
                'label_attr'=>['class'=>'unit-form-input no-icon'],
                'choices'=>$this->getChoicesFromDictionary('trainingLevels'),
                'row_attr'=>['class'=>'form-check-inline col-12 hide-inputs'.($unit->getId()==null? ' d-none': '')],
                'expanded'=>true,
                'multiple'=>true,
                'attr'=>['data-show'=>'#training_unit_seriesCount'],
            ])

            ->add('seriesCount',NumberType::class,[
                'label'=>'Ilość serii',
                'data'=>($unit->getSeriesCount()?? 1),
                'html5'=>true,
                'attr'=>[
                    'step'=>1,
                    'min'=>1,
                    'class'=>'plus-minus-input text-center'
                ],
                'row_attr'=>[
                    'class'=>'input-group col-md-6 '.($unit->getId()==null? ' d-none': '')
                ]
            ])

            ->add('trainingSeries',CollectionType::class,[
                'label'=>false,
                'entry_type'=>TrainingSeriesType::class,
                'entry_options'=>['label'=>false],
                'allow_add'=>true,
                'row_attr'=>['class'=>'d-none'],
                'allow_delete'=>true,
                'prototype' => true,
                'by_reference' => false
            ])
        ;

        $builder
            ->add('submit',SubmitType::class,['label'=>'Zapisz','attr'=>['disabled'=>true,'class'=>'mt-5 btn btn-primary']])
            ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => TrainingUnit::class,
        ]);
    }

    private function getChoicesFromDictionary(string $type)
    {
        $data= $this->repo->findBy(['type'=>$type]);
        $result=[];
        foreach($data as $datum)
        {
            $result[$datum->getName()]=$datum->getId();
        }

        return $result;
    }
}
