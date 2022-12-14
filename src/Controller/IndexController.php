<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session;
// use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\NotifierInterface;
// use Twig\Environment;

// pr creer 1 session rapidement
use Symfony\Component\HttpFoundation\RequestStack;

class IndexController extends AbstractController
{

    // pr creer 1 session rapidement
    private $requestStack;

    // pr creer 1 session rapidement
    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    #[Route('/', name: 'app_index')]
    public function index(Request $request,
        NotifierInterface $notifier): Response
    {

        // pr creer 1 session rapidement
        $session = $this->requestStack->getSession();
        // stores an attribute in the session for later reuse
        $session->set('attribute-name', 'attribute-value');
        // gets an attribute by name
        $foo = $session->get('foo');
        // the second argument is the value returned when the attribute doesn't exist
        $filters = $session->get('filters', ['valeur par defaut qd l\'attribut n\existe pas']);

        // dd($session);

        // message 01, pas de session
        $this->addFlash('notice',
            'flashmsg01 sans session',
            ['browser']
        );

        // message 02, pas de session
        $notifier->send(new Notification(
            'flashmsg02 sans session',
            ['browser']
            )
        );

        if(isset($session)) {
            // message 03, session
            $this->addFlash('test',
            "flashmsg03 avec session",
            ['browser']);
        } else {
            echo "pas de session";
        }
        
        //
        return $this->render('index/index.html.twig', [
            'controller_name' => 'IndexController',
        ]);
    }
}
