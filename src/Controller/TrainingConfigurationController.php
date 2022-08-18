<?php

namespace App\Controller;

#use App\Entity\TrainingUnit;
#use App\Form\TrainingConfigurationType;
#use App\Form\TrainingUnitType;

use App\Entity\TrainingSeries;
use App\Entity\User;
use App\Entity\TrainingUnit;
use App\Entity\TrainingUnitThrowConfig;
use App\Form\TrainingScenarioType;
use App\Form\TrainingSeriesType;
use App\Form\TrainingUnitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * @Route("/training/configuration")
 * @IsGranted("ROLE_MANAGER_EXPERT")
 */
class TrainingConfigurationController extends AbstractController
{
    /**
     * @Route("/my", name="app_training_configuration_my")
     */
    public function myConfigrutations(): Response
    {
        /**@var User $user */
        $user=$this->getUser();
        $configurations=$user->getTrainingConfigurations();

        return $this->render('training_configuration/index.html.twig', [
            'configurations' => $configurations,
        ]);
    }


    /**
     * @Route("/add", name="app_training_configuration_add")
     * @Route("/{id}/edit", name="app_training_configuration_edit")
     */
    public function trainingConfigurationForm(Request $request,?TrainingUnit $unit=null)
    {
        $user=$this->getUser();
        if($unit==null)
        {
            
            $unit=new TrainingUnit();
            $unit->setTrainer($user);
            $series=new TrainingSeries();
            $series->addTrainingUnitThrowConfig(new TrainingUnitThrowConfig());
            $unit->addTrainingSeries($series);
        }

        $form=$this->createForm(TrainingUnitType::class,$unit);
        $form->handleRequest($request);
        if($form->isSubmitted()&&$form->isValid())
        {
            $em=$this->getDoctrine()->getManager();
            //$unit->nextStep();
            //dd($unit);
            $em->persist($unit);
            $em->flush();
            $this->addFlash('success','Zapisano konfigurację');
            return $this->redirectToRoute('app_training_configuration_scenario_form',[
                'id'=>$unit->getId()
            ]);
        }


        return $this->render('training_configuration/unit_form.html.twig', [
            'form' => $form->createView(),
            'config'=>$unit
        ]);
    }


    /**
     * @Route("/{id}", name="app_training_configuration_preview")
     */
    public function previewConfiguration(TrainingUnit $unit)
    {
        $user=$this->getUser();
        if($user!=$unit->getTrainer())
        {
            return $this->redirectToRoute('app_training_configuration_my');
        }

        $units=[];

        return $this->render('training_configuration/config_preview.html.twig',[
            'config'=>$unit,
            'units'=>$units
        ]);

    }

    /**
     * @Route("/{id}/scenario", name="app_training_configuration_scenario_form")
     */
    public function sessionScenarioForm(Request $request,TrainingUnit $unit)
    {
        $form=$this->createForm(TrainingScenarioType::class,$unit);
        $form->handleRequest($request);
        if($form->isSubmitted()&&$form->isValid())
        {
            $em=$this->getDoctrine()->getManager();
            $em->persist($unit);
            $em->flush();
            $this->addFlash('success','Zapisano konfigurację');
            return $this->redirectToRoute('app_training_configuration_scenario_series_list',[
                'id'=>$unit->getId()
            ]);
        }

        return $this->render('training_configuration/scenario_form.html.twig',[
            'form'=>$form->createView(),
            'unit'=>$unit
        ]);
    }

    /**
     * @Route("/{id}/scenario/series", name="app_training_configuration_scenario_series_list")
     */
    public function trainingSeriesConfigList(TrainingUnit $unit)
    {
        $series=$unit->getTrainingSeries();

        return $this->render('training_configuration/series_list.html.twig',[
            'series'=>$series,
            'unit'=>$unit
        ]);
    }

}
