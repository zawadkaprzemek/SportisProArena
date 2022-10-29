<?php

namespace App\Form;

use App\Entity\Sound;
use App\Entity\TrainingSeries;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class TrainingSeriesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('screensCount', ChoiceType::class, [
                'label' => 'Na ilu ekranach wyświetlane jest zadanie',
                'label_html' => true,
                'label_attr' => ['class' => 'unit-form-input'],
                'choices' => $this->prepareScreenChoices(6),
                'row_attr' => ['class' => 'form-check-inline col-12 hide-inputs'],
                'attr' => ['data-show' => '#training_unit_trainingSeries___name___mainScreen'],
                'expanded' => true
            ])
            ->add('mainScreen', ChoiceType::class, [
                'label' => 'Główny ekran',
                'label_html' => true,
                'label_attr' => ['class' => 'unit-form-input'],
                'choices' => $this->prepareScreenChoices(4),
                'row_attr' => ['class' => 'form-check-inline col-12 hide-inputs d-none'],
                'attr' => ['data-show' => '#training_unit_trainingSeries___name___targetType'],
                'expanded' => true
            ])
            //->add('screensConfiguration')
            ->add('targetType', ChoiceType::class, [
                'label' => 'Punktowanie',
                'label_html' => true,
                'label_attr' => ['class' => 'unit-form-input'],
                'choices' => [
                    '<span class="icon icon-map"></span><p>Mapa</p>' => 'map',
                    '<span class="icon icon-shield"></span><p>Tarcza</p>' => 'shield',
                ],
                'row_attr' => ['class' => 'form-check-inline col-12 hide-inputs target-inputs d-none'],
                'attr' => [
                    'data-show' => '#training_unit_trainingSeries___name___targetConfiguration,#training_unit_trainingSeries___name___targets__TARGET__',
                ],
                'expanded' => true
            ])
            //->add('targetConfiguration')
            ->add('targetsMap', TextType::class, ['label' => false,
                    'attr' => ['class' => 'd-none-always'],
                    'required' => false,
                    'row_attr' => ['class' => 'pointPickerWrapper big target-map d-none']]
            )
            ->add('targetsShield', TextType::class, ['label' => false,
                    'attr' => ['class' => 'd-none-always'],
                    'required' => false,
                    'row_attr' => ['class' => 'pointPickerWrapper big target-shield d-none']]
            )
            ->add('targetsMapPoints', TextType::class, ['label' => false,
                    'attr' => ['class' => 'd-none-always'],
                    'required' => false,
                    'row_attr' => ['class' => 'd-none']]
            )
            ->add('targetsShieldPoints', TextType::class, ['label' => false,
                    'attr' => ['class' => 'd-none-always'],
                    'required' => false,
                    'row_attr' => ['class' => 'd-none']]
            )
            ->add('disablePointsBehindGoal', CheckboxType::class, [
                'label' => 'Wyłącz punktowanie za bramką',
                'required' => false,
                'row_attr' => ['class' => 'form-switch shield']
            ])
            ->add('targetPresentation', ChoiceType::class, [
                'label' => 'Prezentacja celu',
                'label_html' => true,
                'label_attr' => ['class' => 'unit-form-input'],
                'choices' => [
                    '<span class="icon icon-shield"></span><p>Tarcza</p>' => 'shield',
                    '<span class="icon icon-point"></span><p>Punkt</p>' => 'point',
                    '<span class="icon icon-player"></span><p>Zawodnik</p>' => 'player',
                ],
                'row_attr' => ['class' => 'form-check-inline col-12 hide-inputs map'],
                'expanded' => true,
            ])
            ->add('playerTasksWhat', ChoiceType::class, [
                'label' => 'Zadanie zawodnika',
                'label_html' => true,
                'label_attr' => ['class' => 'unit-form-input'],
                'choices' => [
                    '<span class="icon icon-take-ball"></span><p>Przyjmij</p>' => 'take-ball',
                    '<span class="icon icon-first-touch"></span><p>Z pierwszej</p>' => 'first-touch',
                    '<span class="icon icon-free-choice"></span><p>Dowolnie</p>' => 'free-choice',
                ],
                'row_attr' => ['class' => 'form-check-inline col-12 hide-inputs tasks-what'],
                'attr' => ['data-show' => '#training_unit_trainingSeries___name___playerTasksHow'],
                'expanded' => true,
            ])
            ->add('playerTasksHow', ChoiceType::class, [
                'label' => false,
                'label_html' => true,
                'label_attr' => ['class' => 'unit-form-input'],
                'choices' => [
                    '<span class="icon icon-right-foot"></span><p>Prawa</p>' => 'right',
                    '<span class="icon icon-left-foot"></span><p>Lewa</p>' => 'left',
                    '<span class="icon icon-chest"></span><p>Klatka</p>' => 'chest',
                    '<span class="icon icon-free-choice"></span><p>Dowolnie</p>' => 'free-choice',
                    '<span class="icon icon-head"></span><p>Głową</p>' => 'head',
                ],
                'row_attr' => ['class' => 'form-check-inline col-12 hide-inputs d-none'],
                'attr' => ['data-show' => '#training_unit_trainingSeries___name___seriesVolume'],
                'expanded' => true,
            ])
            ->add('seriesVolume', NumberType::class, [
                'label' => 'Objętość serii',
                //'data'=>($unit->getSeriesCount()?? 1),
                'html5' => true,
                'attr' => [
                    'step' => 1,
                    'min' => 1,
                    'max' => 10,
                    'class' => 'plus-minus-input text-center'
                ],
                'row_attr' => [
                    'class' => 'input-group col-md-6 d-none'
                ]
            ])
            ->add('trainingUnitThrowConfigs', CollectionType::class, [
                'label' => false,
                'entry_type' => TrainingThrowType::class,
                'entry_options' => ['label' => false],
                'attr' => ['class' => 'throws'],
                'allow_add' => true,
                'allow_delete' => true,
                'prototype' => true,
                'prototype_name' => '__t__',
                'by_reference' => false
            ])
            ->add('surroundingSound', EntityType::class, [
                'label' => 'Dźwiek otoczenia',
                'class' => Sound::class,
                'choice_label' => 'name',
                'label_attr' => ['class' => 'unit-form-input no-icon'],
                'expanded' => true,
                'row_attr' => ['class' => 'form-check-inline hide-inputs col-12 mt-3'],
                'attr' => ['data-show' => '#training_unit_trainingSeries___name___soundVolume,#training_unit_trainingSeries___name___timeConfigurationMeaning'],
            ])
            ->add('soundVolume', RangeType::class, [
                'label' => 'Poziom głośności',
                'attr' => ['class' => 'range', 'min' => 0, 'max' => 100, 'data-show' => '#training_unit_trainingSeries___name___timeConfigurationMeaning'],
                'row_attr' => [
                    'class' => 'range-wrap d-none'
                ],
            ])
            ->add('timeConfigurationMeaning', ChoiceType::class, [
                'label' => 'Definiowanie czasu',
                'label_html' => true,
                'label_attr' => ['class' => 'unit-form-input no-icon'],
                'choices' => [
                    'Nie ma znaczenia' => 'not_relevant',
                    'Im szybciej tym lepiej' => 'the_sooned_the_better',
                    'W określonym czasie' => 'in_certain_time'
                ],
                'row_attr' => ['class' => 'form-check-inline col-12 hide-inputs d-none'],
                'attr' => ['data-show' => '#training_unit_trainingSeries___name___timeConfigurationMin,
                #training_unit_trainingSeries___name___timeConfigurationMax,
                #training_unit_trainingSeries___name___timeConfigurationPercent,#training_unit_trainingSeries___name___throwBreaks,
                #training_unit_trainingSeries___name___seriesBreaks,#training_unit_trainingSeries___name___unitBreaks,
                #training_unit_trainingSeries___name___trainingObjectives'
                ],
                'expanded' => true,
            ])
            ->add('timeConfigurationMin', RangeType::class, [
                'label' => 'Ile czasu min (w sekundach)',
                'attr' => ['class' => 'range', 'min' => 10, 'max' => 60, 'data-show' => '#training_unit_trainingSeries___name___timeConfigurationMax'],
                'row_attr' => [
                    'class' => 'range-wrap col-md-6 d-none'
                ],
                'empty_data' => 10,
            ])
            ->add('timeConfigurationMax', RangeType::class, [
                'label' => 'Ile czasu max (w sekundach)',
                'attr' => ['class' => 'range', 'min' => 10, 'max' => 60, 'data-show' => '#training_unit_trainingSeries___name___timeConfigurationPercent'],
                'row_attr' => [
                    'class' => 'range-wrap col-md-6 d-none'
                ],
                'empty_data' => 60
            ])
            ->add('timeConfigurationPercent', RangeType::class, [
                'label' => 'Ile procent na osi czasu',
                'attr' => ['class' => 'range', 'min' => 0, 'max' => 100, 'data-show' => '#training_unit_trainingSeries___name___throwBreaks'],
                'row_attr' => [
                    'class' => 'range-wrap col-md-6 d-none'
                ],
                'empty_data' => 50
            ])

            //->add('breaksConfiguration')
            ->add('throwBreaks', NumberType::class, [
                'label' => 'Przerwy między wyrzutami',
                'html5' => true,
                'empty_data'=>0,
                'attr' => [
                    'min' => 0,
                    'step' => 5,
                    'data-show' => '#training_unit_trainingSeries___name___seriesBreaks'
                ],
                'row_attr' => ['class' => 'col-md-6 d-none'],
            ])
            ->add('seriesBreaks', NumberType::class, [
                'label' => 'Przerwy między seriami',
                'html5' => true,
                'empty_data'=>0,
                'attr' => [
                    'min' => 0,
                    'step' => 5,
                    'data-show' => '#training_unit_trainingSeries___name___unitBreaks'
                ],
                'row_attr' => ['class' => 'col-md-6 d-none'],
            ])
            ->add('unitBreaks', NumberType::class, [
                'label' => 'Przerwy między jednostkami',
                'html5' => true,
                'empty_data'=>0,
                'attr' => [
                    'min' => 0,
                    'step' => 5,
                    'data-show' => '#training_unit_trainingSeries___name___trainingObjectives'
                ],
                'row_attr' => ['class' => 'col-md-6 d-none'],
            ])
            ->add('trainingObjectives', ChoiceType::class, [
                'label' => '4 główne cele',
                'label_html' => true,
                'label_attr' => ['class' => 'unit-form-input no-icon'],
                'choices' => [
                    'Przyjęcie' => 'take-ball',
                    'Odegranie' => 'playing-ball',
                    'Czas' => 'time',
                    'Precyzja' => 'precision',
                    'Percepcja' => 'perception'
                ],
                'row_attr' => ['class' => 'form-check-inline col-12 hide-inputs d-none '],
                'expanded' => true,
                'multiple' => true
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => TrainingSeries::class,
            //'inherit_data'=>true
        ]);
    }

    private function prepareScreenChoices(int $max): array
    {
        $choices = [];
        for ($i = 1; $i <= $max; $i++) {
            $choices['<span class="icon icon-number">' . $i . '</span>'] = $i;
        }

        return $choices;
    }
}
