<?php

namespace App\Controller;

use App\Entity\TrainingSeries;
use App\Entity\User;
use App\Entity\TrainingUnit;
use App\Entity\TrainingUnitThrowConfig;
use App\Form\TrainingUnitType;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\JsonResponse;
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
        $user = $this->getUser();
        $configurations = $user->getTrainingConfigurations();

        return $this->render('training_configuration/index.html.twig', [
            'configurations' => $configurations,
        ]);
    }


    /**
     * @Route("/add", name="app_training_configuration_add")
     * @Route("/{id}/edit", name="app_training_configuration_edit")
     */
    public function trainingConfigurationForm(Request $request, ?TrainingUnit $unit = null)
    {
        /** @var User $user */
        $user = $this->getUser();
        $session=$request->getSession();
        $configToFinish=$session->get('training_configuration_to_finish');
        if ($unit == null) {
            if($configToFinish!=null)
            {
                return $this->redirectToRoute('app_training_configuration_edit',['id'=>$configToFinish]);
            }
            $configsToFinish=$user->getTrainingConfigurationsToFinish();
            if(sizeof($configsToFinish)>0)
            {
                return $this->redirectToRoute('app_training_configuration_edit',['id'=>$configsToFinish->first()->getId()]);
            }
            $unit = new TrainingUnit();
            $unit->setTrainer($user);
            $series = new TrainingSeries();
            $series->addTrainingUnitThrowConfig(new TrainingUnitThrowConfig());
            $unit->addTrainingSeries($series);
        }
        $oldSeries = new ArrayCollection();
        $oldThrows = new ArrayCollection();
        foreach ($unit->getTrainingSeries() as $series) {
            $oldSeries[] = $series;
            foreach ($series->getTrainingUnitThrowConfigs() as $throwConfig) {
                $oldThrows[] = $throwConfig;
            }
        }

        $form = $this->createForm(TrainingUnitType::class, $unit);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $seriesNew = new ArrayCollection();
            $throwsNew = new ArrayCollection();
            $em = $this->getDoctrine()->getManager();
            foreach ($unit->getTrainingSeries() as $series) {
                $seriesNew[] = $series;
                foreach ($series->getTrainingUnitThrowConfigs() as $throwConfig) {
                    $throwsNew[] = $throwConfig;
                }
            }
            foreach ($oldThrows as $oldThrow) {
                if (!$throwsNew->contains($oldThrow)) {
                    $em->remove($oldThrow);
                }
            }
            foreach ($oldSeries as $series) {
                if (!$seriesNew->contains($series)) {
                    $em->remove($series);
                }
            }
            $unit->setStatus(TrainingUnit::STATUS_FINISHED);
            if($unit->getId()===$configToFinish)
            {
                $session->remove('training_configuration_to_finish');
            }
            $em->persist($unit);
            $em->flush();
            $this->addFlash('success', 'Zapisano konfigurację');
            return $this->redirectToRoute('app_training_configuration_my');
        }


        return $this->render('training_configuration/unit_form.html.twig', [
            'form' => $form->createView(),
            'config' => $unit
        ]);
    }


    /**
     * @Route("/{id}/preview", name="app_training_configuration_preview")
     */
    public function previewConfiguration(TrainingUnit $unit)
    {
        $user = $this->getUser();
        if ($user !== $unit->getTrainer()) {
            return $this->redirectToRoute('app_training_configuration_my');
        }

        $units = [];

        return $this->render('training_configuration/config_preview.html.twig', [
            'config' => $unit,
            'units' => $units
        ]);

    }


    /**
     * @Route("/{id}/auto-save", name="app_training_configuration_auto-save", methods={"POST"})
     * @Route("/auto-save", name="app_training_configuration_auto-save_without_id", methods={"POST"})
     */
    public function autoSave(Request $request,?TrainingUnit $unit): JsonResponse
    {
        if($unit===null)
        {
            $unit=new TrainingUnit();
            $unit->setTrainer($this->getUser());
        }
        $oldSeries = new ArrayCollection();
        $oldThrows = new ArrayCollection();
        foreach ($unit->getTrainingSeries() as $series) {
            $oldSeries[] = $series;
            foreach ($series->getTrainingUnitThrowConfigs() as $throwConfig) {
                $oldThrows[] = $throwConfig;
            }
        }
        $form = $this->createForm(TrainingUnitType::class, $unit);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $seriesNew = new ArrayCollection();
            $throwsNew = new ArrayCollection();
            $em = $this->getDoctrine()->getManager();
            foreach ($unit->getTrainingSeries() as $series) {
                $seriesNew[] = $series;
                foreach ($series->getTrainingUnitThrowConfigs() as $throwConfig) {
                    $throwsNew[] = $throwConfig;
                }
            }
            foreach ($oldThrows as $oldThrow) {
                if (!$throwsNew->contains($oldThrow)) {
                    $em->remove($oldThrow);
                }
            }
            foreach ($oldSeries as $series) {
                if (!$seriesNew->contains($series)) {
                    $em->remove($series);
                }
            }
            $em->persist($unit);
            $em->flush();
            if($unit->getStatus()===TrainingUnit::STATUS_BEGIN){
                $session=$request->getSession();
                $session->set('training_configuration_to_finish',$unit->getId());
            }
            return $this->json([
                'status'=>'success',
                'unit_id'=>$unit->getId(),
                'action'=>$this->generateUrl('app_training_configuration_edit',['id'=>$unit->getId()])
            ]);
        }

        return $this->json([
           'status'=>'error'
        ]);
    }

}
