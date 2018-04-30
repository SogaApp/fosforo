<?php

namespace App\Controller;

use App\Util\General;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use \SoapClient;






class AppController extends Controller
{


    /**
     * @Route("/servicio/soap", name="servicioSoap")
     */

    public function soap(Request $request)
    {

        $url = "http://www.xmethods.net/sd/2001/EBayWatcherService.wsdl";
        try {
            $soap = new SoapClient($url,array('location'=>Endpoint,'trace'=>true,'exceptions'=>false));
            $result = $soap->__soapCall();
        } catch ( SoapFault $e ) {
            echo $e->getMessage();
        }

        var_dump($result);
        die();


    }

    /**
     * @Route("/servicio/rest", name="servicioRest")
     */

    public function listaUsuario(Request $request)
    {

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => 'https://reqres.in/api/users',
        ));
        $resp = json_decode(curl_exec($curl));
        curl_close($curl);
        var_dump($resp);
        die();

    }

    /**
     * @Route("/test/utils", name="test_utils")
     */
    public function testUtils()
    {
<<<<<<< HEAD

        echo General::get()->getRoot();
=======
        var_dump(General::get()->getRoot());
        echo General::get()->getEdad() . "<br>";
        var_dump(General::get()->sayHello("Jako"));
        General::get()->setEdad(30);
        echo General::get()->getEdad() . "<br>";
>>>>>>> 4c28e3f348ce6d9988ae40b231c246a9ab84530d
        exit();
    }

}
