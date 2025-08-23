<?php
namespace App\Controllers;

use App\Components\Controller;
use Psr\Http\Message\ServerRequestInterface;

class SiteController extends Controller
{
    public function index(): string
    {
        return $this->render('site/index', [
            'title' => 'Welcome',
            'message' => 'Hello from Herwebase! Powered by Slim + Yii3.',
        ]);
    }

    public function about(): string
    {
        return $this->render('site/about');
    }

    public function contact(): string
    {
        return $this->render('site/contact');
    }

    public function date():string
    {
        return 'date';
    }

    public function time():string
    {
        return 'time';
    }

    public function secure(ServerRequestInterface $request):string
    {
        
        if($request->getMethod() == 'POST'){
            return 'result from post method';
        }
        return 'secure page';
    }
}
