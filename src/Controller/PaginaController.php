<?php

namespace App\Controller;

use App\Entity\Cliente;
use App\Entity\Quarto;
use App\Entity\Reserva;
use Symfony\Bridge\Monolog\Handler\SwiftMailerHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class PaginaController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index()
    {
        return $this->render('pagina/index.html.twig', [
            'controller_name' => 'PaginaController',
        ]);
    }

    /**
     * @Route("/pesquisa", name="pesquisa")
     */
    public function pesquisa(Request $request)
    {
        $quantidade = $request->get("quantidade");
        $dias = explode(" - ", $request->get("data-selecionada"));

        $dataIni = \DateTime::createFromFormat("d/m/Y", $dias[0]);
        $dataFim = \DateTime::createFromFormat("d/m/Y", $dias[1]);



        $session = $request->getSession();
        $session->set("dataIni", $dataIni);
        $session->set("dataFim", $dataFim);
        $session->set("quantidade", $quantidade);


        //$em = $this->getDoctrine()->getRepository(Reserva::class);
        //$quartos = $em->quartosOcupados($dataIni, $dataFim, $quantidade);

        $em = $this->getDoctrine()->getRepository(Quarto::class);
        $quartos = $em->findAll();


        return $this->render('pagina/pesquisa.html.twig', array("quartos" => $quartos));
    }
    
    /**
     * @Route("/contato", name="contato")
     */
    public function contato(Request $request)
    {
       // var_dump($request);
        return $this->render('pagina/contato.html.twig');
    }
    
    /**
     * @Route("/reservar/{quarto}", name="reservar")
     */
    public function reservar($quarto, Request $request)
    {
        $dataIni = $request->getSession()->get("dataIni");
        $dataFim = $request->getSession()->get("dataFim");

        $totalDias = $dataIni->diff($dataFim);
        $quarto = $this->getDoctrine()->getRepository(Quarto::class)->find($quarto);
        $totalReserva = $totalDias->days * $quarto->getDiaria();

        return $this->render('pagina/reservar.html.twig', array(
            "quarto" => $quarto,
            "total_dias" => $totalDias->days,
            "dataIni" => $dataIni,
            "dataFim" => $dataFim,
            "totalReserva" => $totalReserva
        ));
    }

    /**
     * @Route("/confirmar", name="confirmar_reserva")
     */
    public function confirmar(Request $request, \Swift_Mailer $mailer)
    {

        $nome = $request->get("firstName");
        $sobrenome = $request->get("lastName");
        $email = $request->get("email");
        $endereco = $request->get("address");
        $quarto = $request->get("quarto");

        $dataIni = $request->getSession()->get("dataIni");
        $dataFim = $request->getSession()->get("dataFim");

        $erro = false;

        if (strlen($nome) < 2)
        {
            $this->addFlash("erro",  "O campo nome é obrigatório");
            $erro = true;
        }
        if (strlen($sobrenome) < 2)
        {
            $this->addFlash("erro",  "O campo sobrenome é obrigatório");
            $erro = true;
        }
        if (strlen($email) < 2)
        {
            $this->addFlash("erro",  "O campo email é obrigatório");
            $erro = true;
        }

        if ($erro == true)
        {
            return $this->redirectToRoute("reservar", array("quarto" => $quarto));
        }

        $totalDias = $dataIni->diff($dataFim);
        $quarto = $this->getDoctrine()->getRepository(Quarto::class)->find($quarto);
        $totalReserva = $totalDias->days * $quarto->getDiaria();

        $cliente = new Cliente();
        $cliente->setEmail($email);
        $cliente->setEndereco($endereco);
        $cliente->setSobrenome($sobrenome);
        $cliente->setNome($nome);

        $reserva = new Reserva();
        $reserva->setDataEntrada($dataIni);
        $reserva->setDataSaida($dataFim);
        $reserva->setValorTotal($totalReserva);
        $reserva->setQuarto($quarto);
        $reserva->setCliente($cliente);

        $em = $this->getDoctrine()->getManager();
        $em->persist($cliente);
        $em->persist($reserva);

        $em->flush();

        $this->enviarEmail($reserva, $mailer);

        return $this->render("pagina/confirmar.html.twig", array("reserva" => $reserva));
    }


    /**
     * Envia email com a confirmação de reserva
     * @param Reserva $reserva
     */
    private function enviarEmail(Reserva $reserva, \Swift_Mailer $mailer)
    {
            $html = $this->renderView("pagina/confirmar.html.twig", array("reserva" => $reserva));

            $msg = new \Swift_Mailer();
            $msg->addTo($reserva->getCliente()->getEmail());
            $msg->setSubject("confirmação de Reserva");
            $msg->addFrom("hotel@hotel.com", "Hotel Beira Mar");
            $msg->setBody($html, 'text/html');

            $mailer->send($msg);
    }


    /**
     * 
     * @Route("/admin", name="admin")
     */
    public function admin()
    {
        return $this->render('pagina/admin.html.twig');
    }
}
