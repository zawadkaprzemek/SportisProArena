<?php

namespace App\Controller;

use Knp\Snappy\Pdf;
use App\Entity\User;
use App\Entity\TrainingSession;
use Endroid\QrCode\Builder\BuilderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Endroid\QrCodeBundle\Response\QrCodeResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;


class QrCodeController extends AbstractController
{
    private BuilderInterface $qrCodeBuilder;

    public function __construct(BuilderInterface $customQrCodeBuilder)
    {
        $this->qrCodeBuilder=$customQrCodeBuilder;
    }


    /**
     * @Route("/test", name="app_qr_code")
     */
    public function index(): Response
    {
        /*$result = $this->qrCodeBuilder
            ->size(400)
            ->margin(20)
            ->build();
        return new QrCodeResponse($result);*/
        return $this->render('qr_code/index.html.twig', [
            'controller_name' => 'QrCodeController',
        ]);
    }

    /**
     * @Route("/profile/identify", name="app_profile_identifier")
     * @IsGranted("ROLE_USER")
     */
    public function myIndentifier()
    {
        $user=$this->getUser();
        $url=$this->generateUrl('app_identify_user',['uuid'=>$user->getUuid()],UrlGeneratorInterface::ABSOLUTE_URL);
        $result = $this->qrCodeBuilder
            ->size(400)
            ->data($url)
            ->margin(20)
            ->build();
        return new QrCodeResponse($result); 
    }


    /**
     * @Route("/identify/user/{uuid}", name="app_identify_user")
     */
    public function identifyUser(User $user)
    {
        return new JsonResponse(
            [
                'fullName'=>$user->getFullName(),
                'club'=>$user->getClub()->getName()
            ]
        );
    }

    /**
     * @Route("/identify/training_session/{session_uuid}/{player_uuid}", name="app_identify_session")
     * @ParamConverter("session", options={"mapping": {"session_uuid": "uuid"}})
     * @ParamConverter("user", options={"mapping": {"player_uuid": "uuid"}})
     * @param User $user
     * @param TrainingSession $session
     */
    public function identifySession(User $user,TrainingSession $session)
    {
        if($user!==$session->getPlayer())
        {
            throw new BadRequestHttpException('Bad data');
        }
        return new JsonResponse(
            [
                'player'=>[
                    'fullName'=>$user->getFullName(),
                    'club'=>$user->getClub()->getName()
                ],
                'arena'=>[
                    'id'=>$session->getArena()->getId(),
                    'name'=>$session->getArena()->getName()
                ],
                'session'=>[
                    'date'=>$session->getSessionDate()->format('d/m/Y H:i'),
                    'status'=>$session->getStatus()
                ]
            ]
        );
    }

    /**
     * @Route("/identify/training/{id}/{type}", name="app_trainig_session_identifier", defaults={"type":"show"}, requirements={"type":"show|print"})
     * @IsGranted("ROLE_USER")
     * @return Response
     */
    public function trainingSessionIdentifier(TrainingSession $session,string $type,Pdf $knpSnappyPdf)
    {
        $url=$this->generateUrl('app_identify_session',[
            'session_uuid'=>$session->getUuid(),
            'player_uuid'=>$session->getPlayer()->getUuid()
        ],UrlGeneratorInterface::ABSOLUTE_URL);
        if($type=="show")
        {
            $result = $this->qrCodeBuilder
            ->size(400)
            ->data($url)
            ->margin(20)
            ->build();
            return new QrCodeResponse($result); 
        }

        $html = $this->renderView('qr_code/identifier.html.twig', array(
            'url'=>$url
        ));
        $knpSnappyPdf->setTimeout(300);
        return new PdfResponse(
            $knpSnappyPdf->getOutputFromHtml($html),
            $session->getId().'_identyfikator.pdf'
        );
    }
}
