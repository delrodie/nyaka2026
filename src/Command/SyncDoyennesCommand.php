<?php

namespace App\Command;

use App\Entity\List\Doyenne as DoyenneList;
use App\Entity\Main\Vicariat;
use App\Entity\Main\Doyenne as DoyenneMain;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:sync-doyennes',
    description: 'Copie les doyennes de LIST vers la base de données MAIN',
)]
class SyncDoyennesCommand extends Command
{
    private ManagerRegistry $doctrine;
    public function __construct(ManagerRegistry $doctrine)
    {
        parent::__construct();
        $this->doctrine = $doctrine;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $emList = $this->doctrine->getManager('list_manager');
        $emMain = $this->doctrine->getManager('default');

        $doyenneSource = $emList->getRepository(DoyenneList::class)->findAll();
        $io->note(sprintf("Traitements de %d doyennes...", count($doyenneSource)));

        foreach ($doyenneSource as $dList) {
            $dMain = $emMain->getRepository(DoyenneMain::class)->findOneBy([
                'nom' => $dList->getNom(),
            ]);

            if (!$dMain) {
                $dMain = new DoyenneMain();
                $io->info('Ajout du Doyenné : '.$dList->getNom());
            }

            $vicariat = $emMain->getRepository(Vicariat::class)->findOneBy([
                'nom' => $dList->getVicariat()->getNom(),
            ]);

            // Mise a jour des champs
            $dMain->setNom($dList->getNom());
            $dMain->setCode($dList->getCode());
            $dMain->setVicariat($vicariat);
            $dMain->setUuid($dList->getId());

            $emMain->persist($dMain);
        }

        $emMain->flush();
        $io->success('Synchronisation effectuée avec succès!');

        return Command::SUCCESS;
    }
}
