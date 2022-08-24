<?php

namespace App\Controller;

use App\Entity\TrainingSeries;
use App\Entity\User;
use App\Entity\TrainingUnit;
use App\Entity\TrainingUnitThrowConfig;
use App\Form\TrainingUnitType;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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
        $oldSeries=new ArrayCollection();
        $oldThrows=new ArrayCollection();
        foreach ($unit->getTrainingSeries() as $series)
        {
            $oldSeries[]=$series;
            foreach ($series->getTrainingUnitThrowConfigs() as $throwConfig)
            {
                $oldThrows[]=$throwConfig;
            }
        }

        $form=$this->createForm(TrainingUnitType::class,$unit);
        $form->handleRequest($request);
        if($form->isSubmitted()&&$form->isValid())
        {
            $seriesNew=new ArrayCollection();
            $throwsNew=new ArrayCollection();
            $em=$this->getDoctrine()->getManager();
            foreach ($unit->getTrainingSeries() as $series)
            {
                $seriesNew[]=$series;
                foreach ($series->getTrainingUnitThrowConfigs() as $throwConfig)
                {
                    $throwsNew[]=$throwConfig;
                }
            }
            foreach ($oldThrows as $oldThrow)
            {
                if(!$throwsNew->contains($oldThrow))
                {
                    $em->remove($oldThrow);
                }
            }
            foreach ($oldSeries as $series)
            {
                if(!$seriesNew->contains($series))
                {
                    $em->remove($series);
                }
            }
            $em->persist($unit);
            $em->flush();
            $this->addFlash('success','Zapisano konfigurację');
            return $this->redirectToRoute('app_training_configuration_my',[
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
