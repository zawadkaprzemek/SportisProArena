<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Repository\UserTypeRepository;
use Doctrine\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AdminFixture extends Fixture
{

    /**
     * @var UserPasswordHasherInterface
     */
    private $encoder;

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var UserTypeRepository
     */
    private $typeRepo;

    /**
     * UserFixtures constructor.
     * @param UserPasswordHasherInterface $encoder Password encoder instance
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(UserPasswordHasherInterface $encoder, EntityManagerInterface $entityManager) {
        $this->encoder = $encoder;
        $this->entityManager = $entityManager;
    }
    
    public function load(ObjectManager $manager): void
    {
        $admin=new User();
        $admin->setFirstName('Master')
            ->setLastName('Admin')
            ->setEmail('admin@sportisarenapro.pl')
            ->setPassword($this->encoder->hashPassword($admin, 'Sportis1234'))
            ->setBirthDate((new \DateTime('1989-06-14')))
            ->setCity('Olsztyn')
            ->setUuid($admin->generateUuid())
            ->setDataConsent(true)
            ->setUserType(User::ADMIN_MASTER)
            ->setMarketingConsent(true)
            ;
            $this->entityManager->persist($admin);
            $this->entityManager->flush();
    }
}
