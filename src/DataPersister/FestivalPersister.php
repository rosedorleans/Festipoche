<?php


namespace App\DataPersister;


use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use App\Entity\Festival;
use Doctrine\ORM\EntityManagerInterface;

class FestivalPersister implements DataPersisterInterface {

    protected $em;

    public function __construct(EntityManagerInterface $em) {
        $this->em = $em;
    }

    public function supports($data): bool {
        // Le Persister intervient seulement si c'est un Festival
        return $data instanceof Festival;
    }

    public function persist($data) {
        // Demander à Doctrine de persister
        $this->em->persist($data);
        $this->em->flush();
    }

    public function remove($data) {
        // Demander à Doctrine de supprimer le Festival
        $this->em->remove();
        $this->em->flush();
    }
}