<?php

namespace App\Command;

use App\Entity\List\Section as SectionList;
use App\Entity\Main\Doyenne;
use App\Entity\Main\Section as SectionMain;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:sync-sections',
    description: 'Copie des Sections List dans la base de données Main',
)]
class SyncSectionsCommand extends Command
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

        $sectionSource = $emList->getRepository(SectionList::class)->findAll();
        $io->note(sprintf('Traitements de %d sections ...', count($sectionSource)));

        foreach ($sectionSource as $sList) {
            $sMain = $emMain->getRepository(SectionMain::class)->findOneBy([
                'nom' => $sList->getNom()
            ]);

            if (!$sMain){
                $sMain = new SectionMain();
                $io->info('Ajout de la Section : '.$sList->getNom());
            }

            $doyenne = $emMain->getRepository(Doyenne::class)->findOneBy([
                'nom' => $sList->getDoyenne()?->getNom()
            ]);

            // Mise a jour
            $sMain->setCode($sList->getCode());
            $sMain->setNom($sList->getNom());
            $sMain->setDoyenne($doyenne);
            $sMain->setUuid($sList->getId());

            $emMain->persist($sMain);
        }

        $emMain->flush();
        $io->success('Synchronisation terminée avec succès!');

        return Command::SUCCESS;
    }
}
