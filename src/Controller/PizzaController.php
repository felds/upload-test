<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\Pizza;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/pizza")
 */
class PizzaController extends Controller
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * PizzaController constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/")
     */
    public function indexAction()
    {
        $entities = $this->em->getRepository(Pizza::class)->findBy([], ['name' => 'ASC']);

        return $this->render('Pizza/index.html.twig', [
            'entities' => $entities,
        ]);
    }
}