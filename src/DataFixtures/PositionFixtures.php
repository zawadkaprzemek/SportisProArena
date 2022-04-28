<?php

namespace App\DataFixtures;

use App\Entity\Position;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class PositionFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $positions=[
            'GK'=>'Bramkarz',
            'RB'=>'Prawy obrońca',
            'LB'=>'Lewy obrońca',
            'CB'=>'Środkowy obrońca',
            'CM'=>'Środkowy pomocnik',
            'DM'=>'Defensywny pomocnik',
            'RM'=>'Prawy pomocnik',
            'LM'=>'Lewy pomocnik',
            'OM'=>'Ofensywny pomocnik',
            'CF'=>'Napastnik'
        ];

        foreach($positions as $key=>$position)
        {
            $pos=new Position();
            $pos->setName($position)
                    ->setShortName($key);
            $manager->persist($pos);
        }

        $manager->flush();
    }
}
