<?php

namespace App\Controller;

use App\Entity\TrainingUnit;
use App\Entity\TrainingConfiguration;
use App\Form\TrainingConfigurationType;
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
        $user=$this->getUser();
        $configurations=$user->getTrainingConfigurations();

        return $this->render('training_configuration/index.html.twig', [
            'configurations' => $configurations,
        ]);
    }


    /**
     * @Route("/add", name="app_training_configuration_add")
     */
    public function trainingConfigurationForm(Request $request,?TrainingConfiguration $config=null)
    {
        $user=$this->getUser();
        if($config==null)
        {
            $config=new TrainingConfiguration();
            $config->setTrainer($user);
        }

        $form=$this->createForm(TrainingConfigurationType::class,$config);
        $form->handleRequest($request);
        if($form->isSubmitted()&&$form->isValid())
        {
            $em=$this->getDoctrine()->getManager();
            $em->persist($config);
            $em->flush();
            $this->addFlash('success','Dodano nową konfigurację');
            return $this->redirectToRoute('app_training_configuration_my');
        }


        return $this->render('training_configuration/form.html.twig', [
            'form' => $form->createView(),
            'config'=>$config
        ]);
    }


    /**
     * @Route("/{id}", name="app_training_configuration_preview")
     */
    public function previewConfiguration(TrainingConfiguration $config)
    {
        $user=$this->getUser();
        if($user!=$config->getTrainer())
        {
            return $this->redirectToRoute('app_training_configuration_my');
        }

        $units=$config->getTrainingUnits();

        return $this->render('training_configuration/config_preview.html.twig',[
            'config'=>$config,
            'units'=>$units
        ]);

    }

    /**
     * @Route("/{id}/unit/{sort}/{step}", name="app_training_configuration_unit_form", defaults={"step":1}, requirements={"step":"\d+"})
     * @ParamConverter("trainingUnit", options={"sort" = "sort", "trainingConfiguration" = "id"})
     */
    public function trainingUnitForm(Request $request,TrainingConfiguration $config,int $step=1,?TrainingUnit $unit=null)
    {
        $user=$this->getUser();
        if($user!=$config->getTrainer())
        {
            return $this->redirectToRoute('app_training_configuration_my');
        }
    }

}
