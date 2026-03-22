<?php

namespace App\Command;

use App\Entity\List\Vicariat as VicariatList;
use App\Entity\Main\Vicariat as VicariatMain;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:sync-vicariats',
    description: 'Copie les Vicariats de List vers la base Main',
)]
class SyncVicariatsCommand extends Command
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

        $vicariatsSource = $emList->getRepository(VicariatList::class)->findAll();
        $io->note(sprintf('Traitements de %d vicariats...', count($vicariatsSource)));

        foreach ($vicariatsSource as $vList) {
            $vMain = $emMain->getRepository(VicariatMain::class)->findOneBy(['nom' => $vList->getNom()]);

            if (!$vMain){
                $vMain = new VicariatMain();
                $io->info('Ajout du Vicariat :'.$vList->getNom());
            }

            // Mise a jour des champs
            $vMain->setNom($vList->getNom());
            $vMain->setCode($vList->getCode());
            $vMain->setUuid($vList->getId());

            $emMain->persist($vMain);
        }

        $emMain->flush();
        $io->success('Synchronisation terminée avec succès!');

        return Command::SUCCESS;
    }
}
