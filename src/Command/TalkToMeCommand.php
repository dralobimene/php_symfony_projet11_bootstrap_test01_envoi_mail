<?php

// COMMENT IMPLEMENTER 1 NVELLE COMMANDE CLI PERSONNELLE
namespace App\Command;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Mailer\Exception\TransportException;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Component\Mime\Email;

// NOTE:
// a la fin de l'etape 03, les commandes st viables ms n'ont encore aucun effet
// commande 01: app: talk-to-me laurent
// ou
// commande 02: app: talk-to-me --flag

#[AsCommand(
    // etape 01: definir le nom et la description de la commande
    name: 'app:talk-to-me',
    description: 'description de la commande',
    // fin etape 01
)]
class TalkToMeCommand extends Command
{

    // etape 04B
    private $mailer;
    
    public function __construct(MailerInterface $mailer) {
        $this->mailer = $mailer;
        parent::__construct();
    }
    // fin etape 04B

    protected function configure(): void
    {
        $this
            // etape 02: definir 1 argument et le flag --help
            // ajoute l'argument à la commande
            ->addArgument('nom', InputArgument::OPTIONAL, 'Votre nom')
            // ajoute le flag visible par la commande: app: talk-to-me --help
            ->addOption('flag', null, InputOption::VALUE_NONE, 'Devrai je flagger??')
            // FIN etape 02
        ;
    }

    // methode qui definit 1 email puis l'envoie
    // methode invoquée ds le try... catch
    // il n'y a pas de parametre
    public function sendEmail01() {

        $email01 = (new Email())
            ->from('testexp@test.fr')
            ->to('testdest@test.fr')
            ->subject('envoiTEST par fct° sendEmail01()')
            ->text('1 texte ds le mail');

        $this->mailer->send($email01);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $nom = $input->getArgument('nom');
        // etape 03A
        $crier = $input->getOption('flag');
        // etape 03B
        $message = sprintf('hey %s!', $nom);

        $mailer = $this->mailer;

        if ($nom) {
            $io->note(sprintf('You passed an argument: %s', $nom));
        }
        
        // etape 03C
        if ($crier) {
            $message = strtoupper($message);
        }
        // fin etape 03C

        $io->success('Vous disposez d\'1 nvelle commande CLI personnelle, appropriez vs la... --help pr voir vos options.');
    
        // etape 04A, la commande CLI personnelle
        // symfony console app: talk-to-me execute la commande (ns pose la quest°)
        // symfony console app: talk-to-me test execute la commande
        // (ns pose la quest° et ns informe qu'on a passé 1 argument)
        // symfony console app: talk-to-me --flag execute la commande (ns pose la quest°)
        if($io->confirm('Vlez-vs envoyer 1 mail')) {

            
            // etape 04C
            //On crée le mail
            $email = (new TemplatedEmail())
                ->from('testexp@test.fr')
                ->to('testdest@test.fr')
                ->subject('envoiTEST01')
                ->text('1 texte ds le mail');

            //
            try {
                $mailer->send($email);
                $this->sendEmail01();
                $io->note('Email envoyé');
                return Command::SUCCESS;
            } catch (TransportExceptionInterface $error) {
                $io->note('PROBLEME: '.$error);
                return Command::FAILURE;
            }
            // fin etape 04C
        }
        // fin etape 04A

    }
}
