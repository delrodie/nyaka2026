<?php

namespace App\Command;

use App\Entity\List\Grade as GradeList;
use App\Entity\Main\Grade as GradeMain;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:sync-grades',
    description: 'Copie des Grade de LIST vers la BD Main',
)]
class SyncGradesCommand extends Command
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

        $gradeSource = $emList->getRepository(GradeList::class)->findAll();
        $io->note(sprintf("Traitements de %d grades...", count($gradeSource)));

        foreach ($gradeSource as $gList) {
            $gMain = $emMain->getRepository(GradeMain::class)->findOneBy([
                'nom' => $gList->getNom(),
            ]);

            if (!$gMain) {
                $gMain = new GradeMain();
                $io->info('Ajout du Doyenné : '.$gList->getNom());
            }

            $gMain->setUuid($gList->getId());
            $gMain->setNom($gList->getNom());
            $gMain->setPosition($gList->getPosition());

            $emMain->persist($gMain);
        }

        $emMain->flush();
        $io->success('Synchronisation affectuée avec succès!');

        return Command::SUCCESS;
    }
}
