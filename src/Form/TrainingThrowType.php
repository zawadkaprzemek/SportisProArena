<?php

namespace App\Form;

use App\Entity\TrainingUnitThrowConfig;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class TrainingThrowType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('launcher',ChoiceType::class,[
                'label'=>'Wybór wyrzutni',
                'label_html'=>true,
                'label_attr'=>['class'=>'unit-form-input'],
                'choices'=>$this->prepareLauncherChoices(6),
                'row_attr'=>['class'=>'form-check-inline col-12 hide-inputs'],
                'attr'=>['data-show'=>'#training_unit_trainingSeries___name___trainingUnitThrowConfigs___t___power,#training_unit_trainingSeries___name___trainingUnitThrowConfigs___t___angle,#training_unit_trainingSeries___name___trainingUnitThrowConfigs___t___startPlace'],
                'expanded'=>true
            ])
            ->add('power',RangeType::class,[
                'label'=>'Siła wyrzucanej piłki',
                'attr'=>['class'=>'range','min'=>1,'max'=>10],
                'row_attr'=>[
                    'class'=>'range-wrap d-none'
                ],
                'data'=>5
            ])
            ->add('angle',RangeType::class,[
                'label'=>'Kąt pochylenia',
                'attr'=>['class'=>'range','min'=>0,'max'=>90,'data-orient'=>'vertical'],
                'row_attr'=>[
                    'class'=>'range-wrap range-vertical d-none'
                ]
            ])
            ->add('sound', CheckboxType::class, [
                'label'=>'Dźwięk',
                'row_attr' => ['class' => 'form-switch'],
                'required'=>false
            ])
            ->add('light', CheckboxType::class, [
                'label'=>'Światło',
                'row_attr' => ['class' => 'form-switch'],
                'required'=>false
            ])
            ->add('startPlace',TextType::class,[
                'label'=>'Miejsce startu ćwiczenia',
                'attr'=>['class'=>'d-none-always'],
                'row_attr'=>['class'=>'pointPickerWrapper rounded d-none']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => TrainingUnitThrowConfig::class,
        ]);
    }

    private function prepareLauncherChoices(int $max)
    {
        $choices=[];
        for($i=1;$i<=$max;$i++)
        {
            $choices['<span class="icon icon-number">'.$i.'</span>']=$i;
        }

        return $choices;
    }
}
