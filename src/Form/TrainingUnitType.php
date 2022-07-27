<?php

namespace App\Form;

use App\Entity\TrainingUnit;
use App\Entity\TrainingDictionary;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

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
                'row_attr'=>['class'=>'form-check-inline col-12 hide-inputs d-none'],
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
                'row_attr'=>['class'=>'form-check-inline col-12 hide-inputs d-none'],
                'attr'=>['data-show'=>'#training_unit_trainingGroup'],
                'expanded'=>true
            ])
            ->add('trainingGroup',ChoiceType::class,[
                'label'=>'Grupa',
                'label_html'=>true,
                'label_attr'=>['class'=>'unit-form-input no-icon'],
                'choices'=>$this->getChoicesFromDictionary('trainingGroup'),
                'row_attr'=>['class'=>'form-check-inline col-12 hide-inputs d-none'],
                'attr'=>['data-show'=>'#training_unit_trainingSubGroupsAgeCategory'],
                'expanded'=>true
            ])
            ->add('trainingSubGroupsAgeCategory',ChoiceType::class,[
                'label'=>'Kategoria wiekowa',
                'mapped'=>false,
                'label_html'=>true,
                'label_attr'=>['class'=>'unit-form-input no-icon'],
                'choices'=>$this->getChoicesFromDictionary('trainingSubGroups'),
                'row_attr'=>['class'=>'form-check-inline col-12 hide-inputs d-none'],
                'attr'=>['data-show'=>'#training_unit_trainingSubGroupsLevel'],
                'expanded'=>true,
                'multiple'=>true
            ]
            )
            ->add('trainingSubGroupsLevel',ChoiceType::class,[
                'label'=>'Poziom trudności',
                'mapped'=>false,
                'label_html'=>true,
                'label_attr'=>['class'=>'unit-form-input no-icon'],
                'choices'=>$this->getChoicesFromDictionary('trainingLevels'),
                'row_attr'=>['class'=>'form-check-inline col-12 hide-inputs d-none'],
                'expanded'=>true,
                'multiple'=>true
            ])
        ;

        /*
        $builder
            
            
            ->add('seriesCount')
            ->add('screensCount')
            ->add('screensConfiguration')
            ->add('targetType')
            ->add('targetConfiguration')
            ->add('playerTasks')
            ->add('throwsCount')
            ->add('throwsConfiguration')
            ->add('sound')
            ->add('soundVolume')
            ->add('timeConfiguration')
            ->add('breaksConfiguration')
            ->add('mainObjectives')
        ;

        */

        $builder
            ->add('submit',SubmitType::class,['label'=>'Zapisz','attr'=>['disabled'=>true]])
            ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            //'data_class' => TrainingUnit::class,
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
