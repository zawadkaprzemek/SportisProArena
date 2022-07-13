<?php
namespace App\Service;

use App\Entity\User;
use App\Entity\Arena;
use App\Entity\TrainingSession;
use Doctrine\ORM\EntityManagerInterface;

class TrainingService{

    const DEFAULT_START_HOUR=8;
    const DEFAULT_END_HOUR=20;
    const SESSION_TIME=10;
    const BREAK_AFTER_SESSION=5;

    private EntityManagerInterface $em;


    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em=$entityManager;
    }


    public function generateFreeSlotsArray(Arena $arena,string $date)
    {
        $reserved=$this->em->getRepository(TrainingSession::class)->getReservedForDateAndArena($arena,$date);
        $slots=$this->generateAllSlots();
        $slots=$this->removeReservedSlots($slots,$reserved);
        return $slots;
    }


    private function generateAllSlots()
    {
        $slots=[];
        for($a=self::DEFAULT_START_HOUR;$a<self::DEFAULT_END_HOUR;$a++)
        {
            $minutes="00";
            if($a<10){
                $a="0".$a;
            }
            do{
                $slots[$a.":".$minutes]=$a.":".$minutes."-".$a.":".($minutes=$minutes+self::SESSION_TIME);
                $minutes=$minutes+self::BREAK_AFTER_SESSION;
            }while($minutes<60);
        }
        return $slots;
    }


    public function reserveSlots(array $data,User $user)
    {
        $arena=$this->em->getRepository(Arena::class)->find($data['arena_id']);
        $reserved=$this->em->getRepository(TrainingSession::class)->getReservedForDateAndArena($arena,$data['training_day']);
        $data['training_dates']=$this->removeReservedSlots($data['training_dates'],$reserved,false);
        $sessions=array();
        foreach($data['training_dates'] as $item)
        {
            $date=new \DateTime($data['training_day']." ".$item);
            $trainingSession=$this->newTrainingSession($date,$user,$arena);
            $this->em->persist($trainingSession);
            $sessions[]=$item;
        }

        $user->removeTrainingUnits(sizeof($sessions));
        $this->em->persist($user);
        $this->em->flush();

        return $sessions;
    }

    private function newTrainingSession($date,User $user,Arena $arena)
    {
        $session=new TrainingSession();
        $session->setArena($arena)
            ->setSessionDate($date)
            ->setBuyer($user);
        if($user->getUserType()==User::PLAYER_TYPE)
        {
            $session->setPlayer($user);
        }
        return $session;
    }

    /**
     * @param array $slots
     * @param TrainingSession[] $reserved
     */
    private function removeReservedSlots(array $slots,array $reserved,bool $by_key=true)
    {
        $hours=[];
        foreach($reserved as $item)
        {
            $hours[]=$item->getSessionDate()->format("H:i");
        }
        
        foreach($hours as $hour)
        {
            if($by_key)
            {
                if(array_key_exists($hour,$slots))
                {
                    unset($slots[$hour]);
                }
            }else{
                $key=array_search($hour,$slots);
                if($key!==false)
                {
                    unset($slots[$key]);
                }   
            }
        }
        
        return $slots;
    }

    public function getUserReservedTrainings(User $user,Arena $arena,\DateTime $start, \DateTime $end, bool $datesArray=false)
    {
        $repo=$this->em->getRepository(TrainingSession::class);

        $reserved= $repo->getUserReservedTrainings($user,$arena,$start,$end);

        if(!$datesArray)
        {
            return $reserved;
        }else{
            return $this->prepareDateArray($reserved);
        }
    }

    private function prepareDateArray(array $reserved)
    {
        $array=[];

        foreach($reserved as $item)
        {
            $array[]=$item->getSessionDate()->format('d-m-Y');
        }

        return array_unique($array);
    }
    
}