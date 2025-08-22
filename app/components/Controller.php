<?php
namespace App\Components;

use Yiisoft\View\WebView;

class Controller
{
    protected WebView $view;
    protected string $layout = 'layouts/main';

    public function __construct(WebView $view)
    {
        $this->view = $view;
    }

    public function render(string $template, array $params = []): string
    {
        $content = $this->view->render($template, $params);
        return $this->view->render($this->layout, ['content' => $content]);
    }
}
