<?php declare(strict_types=1);

namespace App;

use Smarty\Smarty;

final class View
{
    private Smarty $smarty;

    public function __construct(string $templatesDir, string $compileDir, string $cacheDir)
    {
        $smarty = new Smarty();
        $smarty->setTemplateDir($templatesDir);
        $smarty->setCompileDir($compileDir);
        $smarty->setCacheDir($cacheDir);
        $smarty->setEscapeHtml(true);

        $smarty->registerPlugin('modifier', 'dump', function($v) {
            ob_start();
            var_dump($v);
            return '<pre>' . htmlspecialchars(ob_get_clean()) . '</pre>';
        });

        $this->smarty = $smarty;
    }

    public function render(string $template, array $data = []): string
    {
        $smarty = $this->smarty;

        foreach ($data as $key => $value) {
            $smarty->assign($key, $value);
        }

        return $smarty->fetch($template);
    }
}