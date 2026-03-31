<?php

namespace App\Services;

use App\Entity\Main\Grade;
use App\Entity\Main\Participant;
use App\Entity\Main\Vicariat;
use Doctrine\Persistence\ManagerRegistry;

class StatistiquesServices
{
    public function __construct(
        private ManagerRegistry $doctrine,
    )
    {
    }

    public function getAspirantByAllGrade($status = null): array
    {
        $grades = $this->doctrine->getRepository(Grade::class)->findBy([],['position' => "ASC"]);

        $liste=[]; $i=0;
        foreach ($grades as $grade){
            if ($status) $aspirants = $this->doctrine->getRepository(Participant::class)->getAllByGrade($grade->getId(), $status);
            else    $aspirants = $this->doctrine->getRepository(Participant::class)->getAllByGrade($grade->getId());

            $liste[$i++] = [
                'grade' => $grade,
                'participants' => $aspirants
            ];
        }

        return $liste;
    }

    public function getFinanceTotal()
    {
        return $this->doctrine->getRepository(Participant::class)->getMontantTotal();
    }

    public function getAspirantsByVicariat($status = null): array
    {
        $vicariats = $this->doctrine->getRepository(Vicariat::class)->findBy([],['nom' => "ASC"]);

        $liste=[]; $i=0;
        foreach ($vicariats as $vicariat){
            if ($status) $aspirants = $this->doctrine->getRepository(Participant::class)->getAllByVicariat($vicariat->getId(), $status);
            else    $aspirants = $this->doctrine->getRepository(Participant::class)->getAllByVicariat($vicariat->getId());

            $liste[$i++] = [
                'vicariat' => $vicariat,
                'aspirants' => $aspirants
            ];
        }

        return $liste;
    }
}
