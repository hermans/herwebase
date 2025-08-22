<?php
namespace App\Controllers;

use App\Components\Controller;
use Yiisoft\View\WebView;

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
}
